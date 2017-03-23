<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 23.03.2017
 * Time: 09:37
 */

namespace BergclubPlugin\MVC\Models;


use BergclubPlugin\MVC\Helpers;

class Role implements IModelMultiple
{
    const TYPE_SYSTEM = 'system';
    const TYPE_ADDRESS = 'address';
    const TYPE_FUNCTIONARY = 'functionary';

    /**
     * @var string|null $type holds the custom type of the Berg Club role
     */
    private $type;

    /**
     * @var string|null $key
     */
    private $key;

    /**
     * @var string|null $name
     */
    private $name;

    /**
     * @var array|null $capabilities
     */
    private $capabilities = [];

    /**
     * @param string $type needs to be one of the class constants
     * @param string $key
     * @param string $name
     */
    public function __construct($type, $key, $name)
    {
        if(!self::isInConstants($type)){
            throw new \UnexpectedValueException('the given type ' . $type . ' is not one of the class constants.');
        }
        $this->type = $type;
        $this->key = Helpers::ensureKeyHasPrefix($key);
        $this->name = $name;
    }

    public static function findAll()
    {
        global $wp_roles;
        $wpRoles = array_keys ($wp_roles->roles);
        $roles = [];
        foreach($wpRoles as $role){
            $role = self::find($role);
            if($role){
                $roles[] = $role;
            }
        }
        return $roles;
    }

    public static function findByType($type){
        $allRoles = self::findAll();
        $roles = [];
        foreach($allRoles as $role){
            if($role->getType() == $type){
                $roles[] = $role;
            }
        }
        return $roles;
    }

    public static function find($id)
    {
        $id = Helpers::ensureKeyHasPrefix($id);
        $role = null;
        $data = get_role($id);

        if(!$data){
            $id = Helpers::ensureKeyHasNoPrefix($id);
        }

        $data = get_role($id);

        if($data){
            $customRoles = Option::get('roles');
            $type = self::TYPE_SYSTEM;
            if(isset($customRoles[self::TYPE_ADDRESS][$id])){
                $type = self::TYPE_ADDRESS;
            }elseif(isset($customRoles[self::TYPE_FUNCTIONARY][$id])){
                $type = self::TYPE_FUNCTIONARY;
            }

            $name = $data->name;
            if($type != self::TYPE_SYSTEM){
                if(isset($customRoles[$type][$id])){
                    $name = $customRoles[$type][$id];
                }
            }


            $role = new Role($type, $id, $name);
            $role->setCapabilities($data->capabilities);
            return $role;
        }
        return null;
    }

    public function save()
    {
        add_role($this->key, $this->name, $this->capabilities);
        if($this->type != self::TYPE_SYSTEM){
            $customRoles = Option::get('roles');
            $customRoles[$this->type][$this->key] = $this->name;
            Option::set('roles', $customRoles);
        }
    }

    public function delete()
    {
        self::remove($this->key);
        $this->key = null;
        $this->name = null;
        $this->capabilites = [];
    }

    public static function remove($id)
    {
        $id = Helpers::ensureKeyHasPrefix($id);
        $role = self::find($id);
        if($role){
            $customRoles = Option::get('roles');
            if(isset( $customRoles[ $role->getType() ][ $role->getKey() ] )){
                unset( $customRoles[ $role->getType() ][ $role->getKey() ] );
                Option::set('roles', $customRoles);
            }
        }
        remove_role($id);
    }

    /**
     * @return array
     */
    public function getCapabilities()
    {
        return $this->capabilities;
    }

    /**
     * @param array $capabilities
     */
    public function setCapabilities($capabilities)
    {
        $this->capabilities = $capabilities;
    }

    /**
     * @param string $capability
     * @param boolean $grant
     */
    public function addCapability($capability, $grant){
        $capability = Helpers::ensureKeyHasPrefix($capability);
        $this->capabilities[$capability] = $grant;
    }

    /**
     * @param string $capability
     */
    public function removeCapability($capability){
        $capability = Helpers::ensureKeyHasPrefix($capability);
        unset($this->capabilities[$capability]);
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return null|string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    public function isSaved(){
        $role = Role::find($this->key);
        return $role ? true : false;
    }

    private static function isInConstants($value){
        $reflection = new \ReflectionClass(__CLASS__);
        return in_array($value, $reflection->getConstants());
    }
}