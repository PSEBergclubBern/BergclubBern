<?php

namespace BergclubPlugin\MVC\Models;


use BergclubPlugin\MVC\Exceptions\NotABergClubUserException;
use BergclubPlugin\MVC\Helpers;

class User implements IModelMultiple
{
    private $data = [
        'ID' => null,
        'user_login' => null,
        'user_pass' => null,
    ];

    private $custom = [
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
        'type' => null,
    ];

    private $roles = [];

    private $deletedRoles = [
        'subscriber'
    ];

    public function __construct(array $data = []){
        $this->fillFromArray($data);
    }

    public static function findCurrent(){
        return self::find(get_current_user_id());
    }

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

    public static function find($id){
        $item = get_user_by('ID', $id);
        if($item) {
                $user = new User((array)$item->data);
                $metadata = get_user_meta($item->ID);
                foreach ($metadata as $key => $arr) {
                    $user->$key = $arr[0];
                }
                $user->setRoles( $item->roles );

                if(!$user->hasBergClubRole()){
                    return null;
                }
                return $user;
        }
        return null;
    }

    public function save(){
        if(!$this->hasBergClubRole()){
            throw new NotABergClubUserException('The user you are trying to save needs at least one custom Bergclub role.');
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
            $user->add_role($role);
        }

        foreach( $this->deletedRoles as $role){
            $user->remove_role( $role );
        }

        $this->deletedRoles = [];

    }

    public function delete()
    {
        self::remove($this->data['ID']);
        $this->data = [];
        $this->custom = [];
        $this->roles = [];
    }

    public static function remove($id)
    {
        wp_delete_user($id);
    }

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

    public function addRole( $role ){
        $role = Helpers::ensureKeyHasPrefix($role);
        if ( $this->isBergClubRole($role) && !in_array($role, $this->roles )){
            $this->roles[] = $role;
        }
    }

    public function removeRole( $role ){
        if($this->isBergClubRole($role) && ($key = array_search($role, $this->roles)) !== false) {
            unset($this->roles[$key]);
            $this->deletedRoles[]=$role;
        }
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        foreach($roles as $role){
            $this->addRole($role);
        }
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return array
     */
    public function getFunctionaryRoles()
    {
        return $this->getRolesByType( 'functionary' );
    }

    /**
     * @return array
     */
    public function getAddressRoles(){
        return $this->getRolesByType( 'address' );
    }

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

    private function isBergClubRole( $role ){
        return $this->isAddressRole( $role ) || $this->isFunctionaryRole( $role );
    }

    private function isAddressRole( $role ){
        return $this->isRoleOfType( $role, 'functionary' );
    }

    private function isFunctionaryRole( $role ){
        return $this->isRoleOfType( $role, 'functionary' );
    }

    private function isRoleOfType( $role, $type ){
        $role = Helpers::ensureKeyHasNoPrefix($role);
        $roles = Option::find('roles');
        return !is_null($roles) && isset($roles->getValue()[$type][$role]);
    }

    private function hasBergClubRole (){
        return $this->hasAddressRole() || $this->hasFunctionaryRole();
    }

    private function hasAddressRole(){
        return $this->hasRoleOfType( 'address' );
    }

    private function hasFunctionaryRole(){
        return $this->hasRoleOfType( 'functionary' );
    }

    private function hasRoleOfType( $type ){
        foreach($this->roles as $role){
            if($this->isRoleOfType( $role, $type) ){
                return true;
            }
        }
        return false;
    }

    private function getRolesByType( $type ){
        $result = [];
        if($roles = Option::find('roles')){
            foreach($this->roles as $role){
                $role = Helpers::ensureKeyHasNoPrefix( $role );
                if(isset($roles[$type][$role])){
                    $result[] = [Helpers::ensureKeyHasPrefix($role) => $roles[$type][$role]];
                }
            }
        }
        return $result;
    }
}