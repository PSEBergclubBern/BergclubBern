<?php

namespace BergclubPlugin\MVC\Models;


use BergclubPlugin\MVC\Exceptions\NotABergClubUserException;

/**
 * Represents a Berg Club address entry. Takes care of the WP User functionality in this project.
 * Needs to have at least one custom Berg Club address type role assigned.
 * Allows only to add and remove custom Berg Club roles (address type or functionary roles).
 * For methods that take a role name as parameter, no <code>bcb_</code> prefix is needed, but works also if provided.
 * Check the WP Option <code>bcb_roles</code> for the custom roles (2 types: 'address' and 'functionary').
 */
class User implements IModelMultiple
{

    /**
     * @var array $data holds the main user values from WP that magic getters and setters allow to access.
     */
    private $data = [
        'ID' => null,
        'user_login' => null,
        'user_pass' => null,
    ];

    /**
     * @var array $custom holds the meta data for the user that magic getters and setters allow to access. Just extend
     * this array if more custom fields are needed.
     */
    private $custom = [
        'company' => null,
        'first_name' => null,
        'last_name' => null,
        'gender' => null,
        'street' => null,
        'zip' => null,
        'location' => null,
        'phone_private' => null,
        'phone_work' => null,
        'phone_mobile' => null,
        'email' => null,
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
     * User constructor.
     * @param array $data an array that contains the data associated with the user. Will be added to either $data,
     * $custom or $roles. For $data and $custom, just use the field name (<code>'field_name' => 'value'</code>, not
     * <code>'data' => ['field_name' => 'value']</code>. For roles use an array with key 'roles' (<code>'roles' => ['role1',
     * 'role2']</code>.
     */
    public function __construct(array $data = []){
        $this->fillFromArray($data);
    }

    /**
     * Returns the User object for the currently signed in user.
     * @return User|null returns User object if found, null otherwise.
     */
    public static function findCurrent(){
        return self::find(get_current_user_id());
    }

    /**
     * Finds all Berg Club Users. This means all users that have at least one custom Berg Club role assigned.
     * @return array an array containing the User objects, can be empty.
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
     * Finds and returns the User object for the given user id.
     * @param integer $id the user id
     * @return User|null returns the User object if found, null otherwise
     */
    public static function find($id){
        $item = get_user_by('ID', $id);
        if ($item) {
            $user = new User((array)$item->data);
            $metadata = get_user_meta($item->ID);
            foreach ($metadata as $key => $arr) {
                $user->$key = $arr[0];
            }

            foreach($item->roles as $wpRole){
                $role = Role::find($wpRole);
                if($role){
                    $user->addRole($role);
                }
            }

            if (!$user->hasBergClubRole()) {
                return null;
            }
            return $user;
        }
        return null;
    }

    /**
     * Persists the User object in the database. Adds an unique login name for the user, if not set already.
     * @throws NotABergClubUserException if no custom Berg Club address type role is assigned.
     */
    public function save(){
        if(!$this->hasAddressRole()){
            throw new NotABergClubUserException('The user you are trying to save needs at least one custom address type role.');
        }

        if(empty($this->data['user_login'])){
            $this->data['user_login'] = $this->createLogin();
        }

        if(!$this->data['ID']) {
            $this->data['ID'] = wp_insert_user($this->data);
        }

        foreach($this->custom as $key => $value){
            update_user_meta($this->data['ID'], $key, $value);
        }

        $user = get_user_by('ID', $this->data['ID'] );

        foreach( $this->roles as $role){
            /* @var Role $role */
            if(!$role->isSaved()){
                $role->save();
            }
            $user->add_role($role->getKey());
        }

        foreach( $this->deletedRoles as $role){
            $user->remove_role($role->getKey());
        }

        //ensure that user does not have WP default role
        $user->remove_role('subscriber');

        $this->deletedRoles = [];

    }

    /**
     * Removes the User object from the database
     */
    public function delete()
    {
        self::remove($this->data['ID']);
        $this->data = [];
        $this->custom = [];
        $this->roles = [];
    }

    /**
     * Removes the user with the given id from the database.
     * @param integer $id the user's id
     */
    public static function remove($id)
    {
        wp_delete_user($id);
    }

    /**
     * Adds the given role to the User but only if the given role is a custom Berg Club role.
     * Ensures that there is only custom Berg Club address type role assigned. If given a non-assigned address type role
     * it will replace the current address type role.
     * @param Role $role the role to add
     */
    public function addRole( Role $role ){
        if($role->getType() == Role::TYPE_ADDRESS && $this->getAddressRole()){
            if($role->getKey() != $this->getAddressRole()->getKey()){
                $this->removeRole($role);
            }
        }

        if ( $role->getType() != Role::TYPE_SYSTEM){
            $this->roles[$role->getKey()] = $role;
        }
    }

    /**
     * Removes the given role from the User if the role is assigned to the user.
     * @param Role $role the role to add
     */
    public function removeRole( $role ){
        if(!$role->getType() == $role::TYPE_SYSTEM && isset($this->roles[$role->getKey()])) {
            unset($this->roles[$role->getKey()]);
            $this->deletedRoles[]=$role;
        }
    }

    /**
     * Adds the given role name in the array to the User.
     * @see User::addRole() to check the conditions when a role is added and how.
     * @param array $roles an array containing Role objects.
     */
    public function setRoles( $roles )
    {
            foreach ($roles as $role) {
                $this->addRole($role);
            }
    }

    /**
     * Returns the roles assigned to the User as array. The roles will be always prefixed with <code>bcb_</code>.
     * @return array like <code>['bcb_role1', 'bcb_role2', ...]</code>
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the custom Berg Club functionary roles assigned to the User. The roles will be always prefixed with <code>bcb_</code>.
     * @return array like <code>['role1' => 'Role name 1', 'role2' => 'Role name 2', ...]</code>
     */
    public function getFunctionaryRoles()
    {
        return $this->getRolesByType( Role::TYPE_FUNCTIONARY );
    }

    /**
     * Returns the custom Berg Club address roles assigned to the User. The roles will be always prefixed with <code>bcb_</code>.
     * @return Role like <code>['role1' => 'Role name 1', 'role2' => 'Role name 2', ...]</code>
     */
    public function getAddressRole(){
        $roles = $this->getRolesByType( Role::TYPE_ADDRESS );
        if(count($roles) > 0){
            return reset($roles);
        }
        return null;
    }

    /**
     * Returns the name of the first added custom Berg Club address role.
     * @return string
     */
    public function getAddressRoleName(){
        $role = $this->getAddressRole();
        if($role){
            return $role->getName();
        }

        return "";
    }

    /**
     * Magic setter. Will set either the $data, $custom, $roles field or will do nothing, depending on the given input.
     * If $key is a key for the $data or $custom field, the value will be added to this field. If $key is 'roles' the
     * roles field will be set with the value.
     * @param string $key the key to set.
     * @param mixed $value the value to set.
     */
    public function __set($key, $value){
        if (array_key_exists($key, $this->data)) {
            $this->data[$key] = $value;
        } elseif (array_key_exists($key, $this->custom)) {
            $this->custom[$key] = $value;
        } elseif ( $key == 'roles' && is_array( $value ) ){
            $this->setRoles($value);
        }
    }

    public function __get($key){
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } elseif (array_key_exists($key, $this->custom)) {
            return $this->custom[$key];
        }
        return null;
    }


    /*
     * Private stuff from here ;)
     */

    private function fillFromArray(array $data){
        foreach($data as $key => $value){
            $this->__set($key, $value);
        }
    }

    private function createLogin(){
        $login = \URLify::filter ($this->custom['last_name'] . $this->custom['first_name'], 8,"de");
        $cnt = '';
        while(username_exists($login . $cnt)){
            $cnt++;
        }
        return $login . $cnt;
    }

    private function hasAddressRole(){
        return $this->hasRoleOfType( Role::TYPE_ADDRESS );
    }

    private function hasFunctionaryRole(){
        return $this->hasRoleOfType( Role::TYPE_FUNCTIONARY );
    }

    private function hasBergClubRole(){
        return $this->hasAddressRole() || $this->hasFunctionaryRole();
    }

    private function hasRoleOfType( $type ){
        foreach($this->roles as $role){
            /* @var Role $role */
            if($role->getType() == $type){
                return true;
            }
        }
        return false;
    }

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