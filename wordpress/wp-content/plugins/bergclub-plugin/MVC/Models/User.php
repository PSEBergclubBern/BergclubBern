<?php

namespace BergclubPlugin\MVC\Models;


use BergclubPlugin\MVC\Exceptions\NotABergClubUserException;
use BergclubPlugin\MVC\Helpers;

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
        'user_email' => null,
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
        'main_address' => null,
        'mail_sent' => null,
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
     * @var int $spouse
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
     * @var array all role slugs that are considered as 'Vorstand'.
     */
    private static $vorstandRoles = [
        'bcb_praesident',
        'bcb_tourenchef',
        'bcb_tourenchef_jugend',
        'kasse',
        'mutationen',
        'redaktion',
        'sekretariat',
    ];

    /**
     * @var array all role slugs that are considered as 'Mitglieder'.
     */
    private static $mitgliederRoles = [
        'bcb_aktivmitglied',
        'bcb_aktivmitglied_jugend',
        'bcb_ehrenmitglied',
        'bcb_freimitglied',
    ];

    /**
     * @var array all role slugs that are considered as 'Erweiterter Vorstand'.
     */
    private static $erweiterterVorstandRoles = [
        'bcb_materialchef',
        'bcb_materialchef_jugend',
        'bcb_js_coach',
        'bcb_versand',
    ];

    /**
     * @var array all role slugs that are considered as 'Leiter'.
     */
    private static $leiter =[
        'bcb_tourenchef',
        'bcb_leiter',
    ];

    /**
     * @var array all role slugs that are considered as 'Leiter Jugend'.
     */
    private static $leiterJugend =[
        'bcb_tourenchef_jugend',
        'bcb_leiter_jugend',
    ];

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
        return self::find(get_current_user_id(), true);
    }

    /**
     * @return array An array with all User objects except for spouses that are not set as main address.
     */
    public static function findAllWithoutSpouse(){
        $users = User::findAll();
        foreach($users as $key => $user){
            /* @var User user */
            if($user->spouse && !$user->main_address){
                unset($users[$key]);
            }
        }

        return array_values($users);
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
     * @param string $login The username of the user to find.
     * @return User|null The user with the given username, null if not found.
     */
    public static function findByLogin($login){
        $wpUser = get_user_by('login', $login);
        if(!$wpUser){
            return null;
        }
        return self::find($wpUser->ID);
    }

    /**
     * @return array An array with all User objects that have assigned one of the 'Mitglieder' roles except for spouses that are not set as main address.
     */
    public static function findMitgliederWithoutSpouse(){
        $users = User::findMitglieder();
        foreach($users as $key => $user){
            /* @var User user */
            if($user->spouse && !$user->main_address){
                unset($users[$key]);
            }
        }

        return array_values($users);
    }

    /**
     * @return array An array with all User objects that have assigned one of the 'Mitglieder' roles.
     */
    public static function findMitglieder(){
        return self::findByRoles(self::$mitgliederRoles);
    }

    /**
     * @return array An array with user data from the 'Vorstand' as array ('name', 'address', 'phone_private', 'phone_work', 'phone_mobile', 'email')
     */
    public static function findVorstand(){
        return self::findUsersAndRoleByArray(self::$vorstandRoles);
    }

    /**
     * @return array An array with user data from the 'Erweiterter Vorstand' as array ('name', 'address', 'phone_private', 'phone_work', 'phone_mobile', 'email')
     */
    public static function findErweiterterVorstand(){
        return self::findUsersAndRoleByArray(self::$erweiterterVorstandRoles);
    }

    /**
     * @return array An array with user data from the 'Leiter' as array ('name', 'address', 'phone_private', 'phone_work', 'phone_mobile', 'email')
     */
    public static function findLeiter(){
        return self::findUsersAndRoleByArray(self::$leiter);
    }

    /**
     * @return array An array with user data from the 'Leiter Jugend' as array ('name', 'address', 'phone_private', 'phone_work', 'phone_mobile', 'email')
     */
    public static function findLeiterJugend(){
        return self::findUsersAndRoleByArray(self::$leiterJugend);
    }

    /**
     * @param array $roleList a list of role slugs
     * @return array An array with User objects that have one of the given roles assigned.
     */
    public static function findByRoles(array $roleList){
        $result = [];
        $membersByRole = [];
        foreach($roleList as $itemRole){
            $role = Role::find($itemRole);
            if($role){
                $item = ['title' => $role->getName(), 'users' => []];
                $users = self::findByRole($itemRole);
                foreach($users as $user){
                    /* @var User $user */
                    $result[$user->last_name . ' ' . $user->first_name . ' ' . $user->ID] = $user;
                }
            }
        }
        ksort($result);
        return array_values($result);
    }

    /**
     * @param String $role A role slug.
     * @return array An array with User objects that have the given role assigned.
     */
    public static function findByRole($role){
        $role = Helpers::ensureKeyHasPrefix($role);
        $result = get_users(['role' => $role]);
        $users = [];
        foreach($result as $item){
            $user = self::find($item->ID);
            if ($user) {
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
    public static function find($id, $allowWpUsers = false){
        $item = get_user_by('ID', $id);
        if ($item) {
            $user = new User((array) $item->data);
            $metadata = get_user_meta($item->ID);

            foreach ($metadata as $key => $arr) {
                if($key == 'history') {
                    $arr[0] = unserialize($arr[0]);
                }
                if($key == 'spouse'){
                    $user->__set('spouseId', $arr[0]);
                }else {
                    $user->__set($key, $arr[0]);
                }
            }

            foreach($item->roles as $wpRole){
                $role = Role::find($wpRole);
                if($role){
                    $user->addRole($role, false, $allowWpUsers);
                }
            }

            if (!$user->hasAddressRole() && !$allowWpUsers) {
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
        //users need to have at least one address role assigned.
        if(!$this->hasAddressRole()){
            throw new NotABergClubUserException('The user you are trying to save needs at least one custom address type role.');
        }

        //if username is empty, create and set one
        if(empty($this->main['user_login'])){
            $this->main['user_login'] = $this->createLogin();
        }

        //if it is a newly created user, we need to insert it to the wp table
        if(!$this->main['ID']) {
            $main = $this->main;
            if($this->hasFunctionaryRole()) {
                $main['user_email'] = $this->data['email'];
            }
            foreach($main as $key => $value){
                $main[$key] = sanitize_text_field($value);
            }
            $this->main['ID'] = wp_insert_user($main);
        }

        //mark user as 'not informed' when no funtionary role is assigned
        if(!$this->hasFunctionaryRole()){
            $this->data['mail_sent'] = false;
        }

        //persist the users meta data
        foreach($this->data as $key => $value){
            if($key == 'email'){
                $value = sanitize_email($value);
                //if the user has at least one functionary role assigned, we also want to set his user_email (pass reset possible)
                //otherwise we set the user_email to null (pass reset not possible)
                if($this->hasFunctionaryRole()) {
                    wp_update_user(['ID' => $this->main['ID'], 'user_email' => $value]);
                    $this->main['user_email'] = $value;
                }else{
                    wp_update_user(['ID' => $this->main['ID'], 'user_email' => null]);
                    $this->main['user_email'] = null;
                }
            }else{
                $value = sanitize_text_field($value);
            }
            update_user_meta($this->main['ID'], $key, $value);
        }

        //update the meta data of the user if a spouse is assigned
        if($this->spouse) {
            update_user_meta($this->main['ID'], 'spouse', $this->spouse);
        }

        update_user_meta($this->main['ID'], 'history', $this->historie);

        $user = get_user_by('ID', $this->main['ID'] );

        if($user) {
            //add roles to the user
            foreach ($this->roles as $role) {
                /* @var Role $role */
                $role->save(); //ensure the role is saved (exists in WP) before added to the WP user.
                $user->add_role($role->getKey());
            }

            //remove roles from user
            foreach ($this->deletedRoles as $role) {
                $user->remove_role($role->getKey());
            }

            //ensure that user does not have the WP default role
            $user->remove_role('subscriber');
        }
        $this->deletedRoles = [];

        //send an email with the password reset link to the user if not already done and if the user has at least one
        //functionary role assigned.
        if(!$this->data['mail_sent'] && $this->hasFunctionaryRole()){
            Helpers::sendPassResetMail($this);
            $this->data['mail_sent'] = true;
            update_user_meta($this->main['ID'], 'mail_sent', $this->data['mail_sent']);
        }
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
        $this->spouse = null;
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
    public function addRole( Role $role , $updateHistory = true, $allowSystemRoles = false){
        if($role->getType() == Role::TYPE_ADDRESS && $this->hasAddressRole()){
            if($role->getKey() != $this->getAddressRole()->getKey()){
                $this->removeRole($this->getAddressRole());
            }
        }

        if ( !isset($this->roles[$role->getKey()]) && ($allowSystemRoles || $role->getType() != Role::TYPE_SYSTEM)){
            $this->roles[$role->getKey()] = $role;
            if($updateHistory && $role->getType() != Role::TYPE_SYSTEM) {
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
        if($role) {
            if (isset($this->roles[$role->getKey()])) {
                unset($this->roles[$role->getKey()]);
                $this->deletedRoles[] = $role;
                if ($updateHistory && $role->getType() != Role::TYPE_SYSTEM) {
                    $this->closeHistory($role);
                }
            }
        }
    }

    /**
     * @param string $key a capability slug
     * @return bool returns true if the user has the the given capability, false otherwise
     */
    public function hasCapability($key){
        foreach($this->getRoles() as $role){
            /* @var Role $role */
            if($role->hasCapability($key)){
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $roleSlug a role slug
     * @return bool returns true if the user has the given role assigned, false otherwise
     */
    public function hasRole($roleSlug){
        $roleSlug = Helpers::ensureKeyHasNoPrefix($roleSlug);
        $roleSlugPrefix = Helpers::ensureKeyHasPrefix($roleSlug);

        foreach($this->roles as $role){
            /* @var Role $role */
            if($role->getKey() == $roleSlug || $role->getKey() == $roleSlugPrefix){
                return true;
            }
        }
        return false;
    }

    /**
     * Removes the spouse from the user and persists this information immediately
     */
    public function unsetSpouse(){
        $this->spouse = null;
        $this->data['main_address'] = null;
        update_user_meta($this->main['ID'], 'spouse', $this->spouse);
    }

    /**
     * Magic setter. Will set either the $data, $custom, $roles field or will do nothing, depending on the given key.
     * If a 'set[key]' method exists, this method will be called. Otherwise if $key is a key for the $data or $custom
     * field, the value will be added to this field.
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

    /**
     * Magic getter. Will get either the $data, $custom, $roles field or will do nothing, depending on the given key.
     * If a 'get[key]' method exists, this method will be called. Otherwise if $key is a key for the $data or $custom
     * field, the value will be retrieved from this field.
     * @param string $key the key to get.
     */
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
     * @param array $roleList an array of role slugs
     * @return array returns an array of user data arrays ('name', 'address', 'phone_private', 'phone_work', 'phone_mobile',
     * 'email') from users that have one of the given roles assigned
     */
    private static function findUsersAndRoleByArray(array $roleList){
        $membersByRole = [];
        foreach($roleList as $itemRole){
            $role = Role::find($itemRole);
            if($role){
                $item = ['title' => $role->getName(), 'users' => []];
                $users = self::findByRole($itemRole);
                foreach($users as $user){
                    $data = [
                        'name' => trim($user->last_name . ' ' . $user->first_name),
                        'address' => $user->address,
                        'phone_private' => $user->phone_private,
                        'phone_work' => $user->phone_work,
                        'phone_mobile' => $user->phone_mobile,
                        'email' => $user->email,
                    ];
                    $item['users'][] = (object) $data;
                }
                if(count($item['users']) > 0) {
                    $membersByRole[] = $item;
                }
            }
        }
        return $membersByRole;
    }

    /**
     * Opens/overwrites a new history entry for the given role. 'date_from' will be set to the actual date, 'date_to' will
     * be set to null.
     * @param Role $role
     */
    private function openHistory(Role $role){
        if(!isset($this->historie[$role->getKey()])) {
            $this->historie[$role->getKey()] = ['date_from' => date('Y-m-d'), 'date_to' => null];
        }
    }

    /**
     * Closes the history entry for the given role by setting 'date_to' to the actual date
     * @param Role $role
     */
    private function closeHistory(Role $role){
        $this->historie[$role->getKey()]['date_to'] = date('Y-m-d');
    }

    /**
     * Sets the history array for the user.
     * Use $user->history = $history to call this from outside the class class over the magic set method.
     * The array needs to have entries in the following format:
     * ['role_slug' => ['date_from' => 'date_as_string', 'date_to' => 'date_as_string']
     * @param mixed $history an array with the user history.
     */
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

    /**
     * Gets the history array for the user. Role slug will be translated to role name and date will be converted
     * to d.m.Y format.
     * Use $user->history to call this from outside the class class over the magic get method.
     * @return array the history array of the user,
     */
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
        if(!empty($this->spouse)){
            $spouse = User::find($this->spouse);
            if($spouse){
                return $spouse;
            }
        }

        return null;
    }

    /**
     * Returns the last and first name of the spouse assigned to this user.
     * Use $user->spouse_name to call this from outside the class over the magic get method.
     *
     * @return string the name of the spouse of this user, empty if not assigned
     */
    private function getSpouseName()
    {
        $spouse = $this->getSpouse();
        if($spouse) {
            return $spouse->displayName;
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
            $this->spouse = $spouse->ID;
        }
    }

    /**
     * Sets the spouse assigned to this user.
     * Use $user->spouseId = $spouseId to call this from outside the class class over the magic set method.
     * @param int $spouseId the id of the spouse.
     */
    private function setSpouseId($spouseId)
    {
        if(is_numeric($spouseId)) {
            $this->spouse = $spouseId;
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
     * Returns the display name of the user.
     * Use $user->displayName to call this from outside the class over the magic get method.
     * @return string a string containing the lastname and firstname of the user, or only the lastname or firstname, or
     * an empty string depending on which values are set.
     */
    private function getDisplayName(){
        return trim($this->data['last_name'] . ' ' . $this->data['first_name']);
    }

    /**
     * Returns an non-associative array containing the address fields.
     * Use $user->address to call this from outside the class over the magic get method.
     * @return array an array containing the address fields.
     */
    private function getAddress(){
        $address = [];
        if(!empty($this->data['address_addition'])) {
            $address[] = $this->data['address_addition'];
        }
        if(!empty($this->data['street'])) {
            $address[] = $this->data['street'];
        }
        if(!empty($this->data['location'])) {
            $address[] = trim($this->data['zip'] . ' ' . $this->data['location']);
        }
        return $address;
    }

    /**
     * Returns the birthdate in the format d.m.Y.
     * Use $user->birthdate to call this from outside the class over the magic get method.
     *
     * @return string the birthdate in the format d.m.Y
     */
    private function getBirthdate(){
        if($this->data['birthdate']) {
            return date("d.m.Y", strtotime($this->data['birthdate']));
        }else{
            return null;
        }
    }

    /**
     * Converts the given value into a unix date stamp and saves it in the format Y-m-d.
     * Use $user->birthdate = 'birthdate' to call this from outside the class over the magic set method.
     *
     * @param string $value a string representing a date.
     */
    private function setBirthdate($value){
        if(empty(trim($value))){
            $this->data['birthdate'] = null;
        }else {
            $this->data['birthdate'] = date("Y-m-d", strtotime($value));
        }
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
        if(!empty($variant) || is_numeric($variant)) {
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