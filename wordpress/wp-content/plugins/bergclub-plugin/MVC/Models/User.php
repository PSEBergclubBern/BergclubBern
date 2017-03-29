<?php

namespace BergclubPlugin\MVC\Models;


use BergclubPlugin\MVC\Exceptions\NotABergClubUserException;
use BergclubPlugin\MVC\Helpers;
use BergclubPlugin\MVC\MassAssignmentException;

/**
 * Represents a Berg Club address entry.
 * Extends the WP user functionality and takes care for the WP User management in this project.
 * Needs to have at least one address type role assigned.
 * The static methods for retrieving users only returns users that have a address type role assigned.
 * Allows only to add and remove custom address type or functionary roles.
 *
 * @see Role for more information about the custom roles for Berg Club Bern.
 */
class User implements IModel
{
    const LEAVING_REASON_1 = 'Ausgetreten';
    const LEAVING_REASON_2 = 'Verstorben';
    const PROGRAM_SHIPMENT_0 = 'Nein';
    const PROGRAM_SHIPMENT_1 = 'Ja';
    const GENDER_M = 'Herr';
    const GENDER_F = 'Frau';


    /**
     * @var array $main holds the main user values from WP that magic getters and setters allow to access.
     */
    private $main = [
        'ID' => null,
        'user_login' => null,
        'user_pass' => null,
    ];

    /**
     * Note: Only the fields in this array are mass assignable.
     *
     * @var array $data holds the meta data for the user that magic getters and setters allow to access. Just extend
     * this array if more custom fields are needed.
     */
    private $data = [
        'leaving_reason' => null,
        'program_shipment' => 1,
        'company' => null,
        'gender' => '',
        'first_name' => null,
        'last_name' => null,
        'address_addition' => null,
        'street' => null,
        'zip' => null,
        'location' => null,
        'phone_private' => null,
        'phone_work' => null,
        'phone_mobile' => null,
        'email' => null,
        'birthdate' => null,
        'comments' => null,
    ];

    /**
     * @var array $role holds the associated roles.
     */
    private $roles = [];

    /**
     * @var array $deletedRoles holds the removed roles (used for saving the object). The default entry ensures, that new
     * users don't have a default WP role.
     */
    private $deletedRoles = [];

    /**
     * @var User $spouse
     */
    private $spouse;

    /**
     * @var array an array that holds the history of the user.
     * The key is the role slug. It always has a valid date_from. When date_to is null, this means that the user has the
     * role at the time.
     *
     * Example:
     * <code>
     * $history = [
     *   'bcb_intressent' => [
     *     'date_from' => '2016-10-04',
     *     'date_to' => '2016-11-13',
     *   ],
     *   'bcb_aktivmitglied' => [
     *     'date_from' => '2016-11-13',
     *     'date_to' => null,
     *   ],
     *   'bcb_leiter' => [
     *      'date_from' => '2017-03-26',
     *      'date_to' => null,
     *   ],
     * ];
     * </code>
     */
    private $historie = [];

    /**
     * User constructor.
     *
     * @param array $data an array that contains the data associated with the user. Will be added to the $data field.
     */
    public function __construct(array $data = []){
        foreach($data as $key => $value){
            $this->__set($key, $value);
        }
    }

    /**
     * Returns the User object for the currently signed in user.
     *
     * @return User|null returns User object if found, null otherwise.
     */
    public static function findCurrent(){
        return self::find(get_current_user_id());
    }

    /**
     * Finds all User. uses {@link [Role::find][Role::find]} to generate the Role objects.
     *
     * @return array an array with the generated User objects. Will be an empty array if no users are found.
     */
    public static function findAll(){
        $result = get_users();
        $users = [];
        foreach($result as $item){
            $user = self::find( $item ->ID );
            if($user){
                $users[] = $user;
            }
        }

        usort($users, function($a, $b){
            return strcmp($a->last_name.' '.$a->first_name, $b->last_name.' '.$b->first_name);
        });
        return $users;
    }

    /**
     * Finds and WP User with the given id and converts it to our custom User.
     *
     * @param integer $id the user id
     * @return User|null returns the User object if found and if a address type role is assigned, null otherwise
     */
    public static function find($id){
        $item = get_user_by('ID', $id);
        if ($item) {
            $user = new User((array) $item->data);
            $metadata = get_user_meta($item->ID);
            foreach ($metadata as $key => $arr) {
                if($key == 'history') {
                    $arr[0] = unserialize($arr[0]);
                }
                $user->__set($key, $arr[0]);
            }

            foreach($item->roles as $wpRole){
                $role = Role::find($wpRole);
                if($role){
                    $user->addRole($role, false);
                }
            }

            if (!$user->hasAddressRole()) {
                return null;
            }
            return $user;
        }
        return null;
    }

    /**
     * Saves the current User object.
     * Adds an unique login name for the user, if not set already.
     *
     * @throws NotABergClubUserException if no address type role is assigned.
     */
    public function save(){
        if(!$this->hasAddressRole()){
            throw new NotABergClubUserException('The user you are trying to save needs at least one custom address type role.');
        }

        if(empty($this->main['user_login'])){
            $this->main['user_login'] = $this->createLogin();
        }

        if(!$this->main['ID']) {
            $this->main['ID'] = wp_insert_user($this->main);
        }

        foreach($this->data as $key => $value){
            update_user_meta($this->main['ID'], $key, $value);
        }

        update_user_meta($this->main['ID'], 'history', $this->historie);

        $user = get_user_by('ID', $this->main['ID'] );

        foreach( $this->roles as $role){
            /* @var Role $role */
            $role->save(); //ensure the role is saved (exists in WP) before added to the WP user.
            $user->add_role($role->getKey());
        }

        foreach( $this->deletedRoles as $role){
            $user->remove_role($role->getKey());
        }

        //ensure that user does not have the WP default role
        $user->remove_role('subscriber');

        $this->deletedRoles = [];

    }

    /**
     * Wrapper for the static {@link [User::remove][User::remove]} method, so it can directly called on the object
     * without passing its id.
     * Resets the object.
     */
    public function delete()
    {
        self::remove($this->main['ID']);
        $this->main = [];
        $this->data = [];
        $this->roles = [];
    }

    /**
     * Removes the user with the given id from the WP database.
     *
     * @param integer $id the user's id
     */
    public static function remove($id)
    {
        wp_delete_user($id);
    }

    /**
     * Adds the given role to the User but only if the given role is a custom role (address or functionary type).
     * Ensures that there is only one address type role assigned. If given an address type role that is not currently
     * assigned it will replace the current address type role.
     *
     * @param Role $role the role to add
     */
    public function addRole( Role $role , $updateHistory = true){
        if($role->getType() == Role::TYPE_ADDRESS && $this->hasAddressRole()){
            if($role->getKey() != $this->getAddressRole()->getKey()){
                $this->removeRole($this->getAddressRole());
            }
        }

        if ( !isset($this->roles[$role->getKey()]) && $role->getType() != Role::TYPE_SYSTEM){
            $this->roles[$role->getKey()] = $role;
            if($updateHistory) {
                $this->openHistory($role);
            }
        }
    }

    /**
     * Removes the given role from the User if the role is currently assigned to the user.
     *
     * @param Role $role the role to add
     */
    public function removeRole( $role , $updateHistory = true){
        if(isset($this->roles[$role->getKey()])) {
            unset($this->roles[$role->getKey()]);
            $this->deletedRoles[]=$role;
            if($updateHistory) {
                $this->closeHistory($role);
            }
        }
    }

    public function hasCapability($key){
        foreach($this->getRoles() as $role){
            /* @var Role $role */
            if($role->hasCapability($key)){
                return true;
            }
        }
        return false;
    }

    private function openHistory(Role $role){
        if(!isset($this->historie[$role->getKey()])) {
            $this->historie[$role->getKey()] = ['date_from' => date('Y-m-d'), 'date_to' => null];
        }
    }

    private function closeHistory(Role $role){
        $this->historie[$role->getKey()]['date_to'] = date('Y-m-d');
    }

    private function setHistory($history){
        if(is_array($history)) {
            $newHistory = [];
            foreach($history as $key => $item){
                if(array_key_exists('date_from', $item)){
                    $newHistory[$key]['date_from'] = date('Y-m-d', strtotime($item['date_from']));
                    $newHistory[$key]['date_to'] = null;
                    if(array_key_exists('date_to', $item) && !empty($item['date_to'])){
                        $newHistory[$key]['date_to'] = date('Y-m-d', strtotime($item['date_to']));
                    }
                }
            }
            $this->historie = $newHistory;
        }
    }

    private function getHistory(){
        $history = [];
        foreach($this->historie as $key => $item){
            $role = Role::find($key);
            if($role) {
                $date_to = $item['date_to'];
                if($date_to){
                    $date_to = date("d.m.Y", strtotime($item['date_to']));
                }
                $history[$key] = [
                    'name' => $role->getName(),
                    'date_from' => date("d.m.Y", strtotime($item['date_from'])),
                    'date_to' => $date_to,
                ];
            }
        }
        return $history;
    }

    /**
     * Magic setter. Will set either the $data, $custom, $roles field or will do nothing, depending on the given input.
     * If $key is a key for the $data or $custom field, the value will be added to this field. If $key is 'roles' the
     * roles field will be set with the value.
     * @param string $key the key to set.
     * @param mixed $value the value to set.
     */
    public function __set($key, $value){
        $method = "set" . Helpers::snakeToCamelCase($key);
        if(method_exists($this, $method)){
            return $this->$method($value);
        } elseif (array_key_exists($key, $this->main)) {
            $this->main[$key] = $value;
        } elseif (array_key_exists($key, $this->data)) {
            $this->data[$key] = $value;
        }

        return null;
    }

    public function __get($key){
        $method = "get" . Helpers::snakeToCamelCase($key);
        if(method_exists($this, $method)){
            return $this->$method();
        } elseif (array_key_exists($key, $this->main)) {
            return $this->main[$key];
        } elseif (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }


    /**
     * Returns the roles assigned to the user.
     * Use $user->roles to call this from outside the class over the magic getter.
     *
     * @return array an array containing the assigned roles, can be empty.
     */
    private function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the functionary roles assigned to the user.
     * Use $user->functionary_roles to call this from outside the class over the magic get method.
     *
     * @return array an array containing the assigned functionary roles.
     */
    private function getFunctionaryRoles()
    {
        return $this->getRolesByType( Role::TYPE_FUNCTIONARY );
    }

    /**
     * Returns the address role assigned to the user.
     * Use $user->address_role to call this from outside the class over the magic get method.
     *
     * @return Role|null the currently assigned address role, null if non is assigned.
     */
    private function getAddressRole(){
        $roles = $this->getRolesByType( Role::TYPE_ADDRESS );
        if(count($roles) > 0){
            return reset($roles);
        }
        return null;
    }

    /**
     * Returns the name of the address role assigned to the user.
     * Use $user->address_role_name to call this from outside the class over the magic get method.
     *
     * @return string
     */
    private function getAddressRoleName(){
        $role = $this->getAddressRole();
        if($role){
            return $role->getName();
        }

        return null;
    }

    /**
     * Returns the key of the address role assigned to the user.
     * Use $user->address_role_key to call this from outside the class over the magic get method.
     *
     * @return string
     */
    private function getAddressRoleKey(){
        $role = $this->getAddressRole();
        if($role){
            return $role->getKey();
        }

        return null;
    }

    /**
     * Returns the spouse assigned to this user.
     * Use $user->spouse to call this from outside the class over the magic get method.
     *
     * @return User|null the user object of the spouse of this user, null if not assigned.
     */
    private function getSpouse()
    {
        return $this->spouse;
    }

    /**
     * Returns the last and first name of the spouse assigned to this user.
     * Use $user->spouse_name to call this from outside the class over the magic get method.
     *
     * @return string the name of the spouse of this user, empty if not assigned
     */
    private function getSpouseName()
    {
        if($this->spouse) {
            return $this->spouse->last_name . ' ' . $this->spouse->first_name;
        }

        return '';
    }

    /**
     * Sets the spouse assigned to this user.
     * Use $user->spouse = $spouse to call this from outside the class class over the magic set method.
     * If the give User object is the same object as the user it will not be assigned.
     *
     * @param User $spouse the user object which you want to assign as spouse
     */
    private function setSpouse(User $spouse)
    {
        if($this != $spouse) {
            $this->spouse = $spouse;
        }
    }

    /**
     * Returns the leaving reason according to the constant value (LEAVING_REASON_X, where X is the saved value for
     * leaving reason).
     * Use $user->leaving_reason to call this from outside the class over the magic get method.
     *
     * @return string the value of the corresponding constant value (LEAVING_REASON_*) or null if no corresponding
     * constant exists.
     */
    private function getLeavingReason(){
        return $this->_getConstant('leaving_reason', $this->data['leaving_reason']);
    }

    /**
     * Sets the leaving reason.
     * The given value has to complete the constant name (LEAVING_REASON_X, where X is the given value).
     * This means it has to be a defined constant.
     * Use $user->leaving_reason = 'leaving_reason' to call this from outside the class over the magic set method.
     *
     * @param string $value the value that completes the constant name (LEAVING_REASON_*)
     * @throws \UnexpectedValueException if the constant does not exists.
     */
    private function setLeavingReason($value){
        $this->_setByConstant('leaving_reason', $value);
    }

    /**
     * Returns the program shipment according to the constant value (PROGRAM_SHIPMENT_X, where X is the saved value for
     * program shipment).
     * Use $user->program_shipment to call this from outside the class over the magic get method.
     *
     * @return string the value of the corresponding constant value (PROGRAM_SHIPMENT_*) or null if no corresponding
     * constant exists.
     */
    private function getProgramShipment(){
        return $this->_getConstant('program_shipment', $this->data['program_shipment']);
    }

    /**
     * Sets the program shipment.
     * The given value has to complete the constant name (PROGRAM_SHIPMENT_X, where X is the given value).
     * This means it has to be a defined constant.
     * Use $user->program_shipment = 'program_shipment' to call this from outside the class over the magic set method.
     *
     * @param string $value the value that completes the constant name (PROGRAM_SHIPMENT_*)
     * @throws \UnexpectedValueException if the constant does not exists.
     */
    private function setProgramShipment($value){
        $this->_setByConstant('program_shipment', $value);
    }

    /**
     * Returns the gender according to the constant value (GENDER_X, where X is the saved value for gender).
     * Use $user->gender to call this from outside the class over the magic get method.
     *
     * @return string the value of the corresponding constant value (GENDER_*) or null if no corresponding
     * constant exists.
     */
    private function getGender(){
        return $this->_getConstant('gender', $this->data['gender']);
    }

    /**
     * Sets the gender.
     * The given value has to complete the constant name (GENDER_X, where X is the given value).
     * This means it has to be a defined constant.
     * Use $user->gender = 'gender' to call this from outside the class over the magic set method.
     *
     * @param string $value the value that completes the constant name (GENDER_*)
     * @throws \UnexpectedValueException if the constant does not exists.
     */
    private function setGender($value){
        $this->_setByConstant('gender', $value);
    }

    /**
     * Returns the birthdate in the format d.m.Y.
     * Use $user->birthdate to call this from outside the class over the magic get method.
     *
     * @return string the birthdate in the format d.m.Y
     */
    private function getBirthdate(){
        return date("d.m.Y", strtotime($this->data['birthdate']));
    }

    /**
     * Converts the given value into a unix date stamp and saves it in the format Y-m-d.
     * Use $user->birthdate = 'birthdate' to call this from outside the class over the magic set method.
     *
     * @param string $value a string representing a date.
     */
    private function setBirthdate($value){
        $this->data['birthdate'] = date("Y-m-d", strtotime($value));
    }

    /**
     * Checks if the given value is a constant of the current object.
     *
     * @param string $value the name of the constant to check.
     * @return bool returns true if the given value is a constant, false otherwise
     */
    private function isAConstant($value){
        $reflection = new \ReflectionClass($this);
        return isset($reflection->getConstants()[$value]);
    }

    /**
     * Returns the value of a constant which is concatenated from the given key and variant (KEY_VARIANT).
     * The given values will be transformed to uppercase and pre- or succeeding underlines will be removed.
     *
     * @param string $key the main part of the constant.
     * @param string $variant the last part of the constant.
     * @return mixed|null the value of the constant or null if the constant does not exist.
     */
    private function _getConstant($key, $variant){
        if(!empty($variant)) {
            return constant('self::' . strtoupper(trim($key, '_') . '_' . trim($variant, '_')));
        }
        return null;
    }

    /**
     * Sets the value of the data field with the given key to the given value, after checking, that a constant with
     * concatenated and uppercase key and value exists (KEY_VALUE).
     *
     * @param string $key the key represents the main part of the constant and the key for the data field array.
     * @param mixed $value the value represents the second part of the constant and the value that will be set in the data
     * field array.
     */
    private function _setByConstant($key, $value){
        if(!empty($value)) {
            if (!$this->isAConstant(strtoupper($key) . '_' . strtoupper($value))) {
                throw new \UnexpectedValueException('the given value "' . $value . '" for ' . $key . ' is not correct.');
            }
        }

        $this->data[$key] = $value;
    }


    /**
     * URLifies the concatenated last and first name of the user and shorts it to 8 chars if longer.
     * If the last or first name of the user is not set it will use 'user' as login name.
     * Is another existing user found, it will add an increasing number to the login name until the generated login name
     * does not exist in the WP database.
     *
     * @return string the generated login name.
     */
    private function createLogin(){
        $name = $this->data['last_name'] . $this->data['first_name'];
        if(empty($name)){
            $name = "user";
        }

        $login = \URLify::filter ($name, 8,"de");
        $cnt = '';
        while(username_exists($login . $cnt)){
            $cnt++;
        }
        return $login . $cnt;
    }

    /**
     * @return bool returns true if the user has an address role assigned, false otherwise.
     */
    private function hasAddressRole(){
        return $this->hasRoleOfType( Role::TYPE_ADDRESS );
    }

    /**
     * @return bool returns true if the user has a functionary role assigned, false otherwise.
     */
    private function hasFunctionaryRole(){
        return $this->hasRoleOfType( Role::TYPE_FUNCTIONARY );
    }

    /**
     * Checks if the user has at least one role of the given type assigned.
     *
     * @param string $type the type to check for.
     * @return bool returns true if the user has a role of the given type assigned.
     */
    private function hasRoleOfType( $type ){
        foreach($this->roles as $role){
            /* @var Role $role */
            if($role->getType() == $type){
                return true;
            }
        }
        return false;
    }

    /**
     * Returns all roles of the given type that are assigned to this user.
     *
     * @param string $type the type of the roles to return.
     * @return array an array containing all roles of the given type assigned to this user, can be empty.
     */
    private function getRolesByType( $type ){
        $result = [];
        foreach($this->roles as $role){
            /* @var Role $role */
            if($role->getType() == $type){
                $result[$role->getKey()] = $role;
            }
        }
        return $result;
    }
}