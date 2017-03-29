<?php

namespace BergclubPlugin\MVC\Models;


use BergclubPlugin\MVC\Helpers;

/**
 * Extends the functionality of WP Role needed for Berg Club Bern.
 *
 * Every role has a key (slug), name and an array of capabilities.
 *
 * Additionally a type is added to the role:
 * - system: a role that is not defined by the Berg Club Bern plugin
 * - address: a role that defines the main role of a Berg Club Bern user.
 *            Every user must have exactly one address role
 * - functionary: a role that defines an additional function that a Berg Club Bern user can take on.
 *                Every user can have have zero or multiple functionary roles.
 *
 * The additional information is hold in WP Option 'bcb_roles' which is managed by this class.
 *
 * @see User for how the roles are added, removed and managed.
 * @see ../Adressverwaltung/activate.php for the generated roles and capabilities for Berg Club Bern.
 */
class Role implements IModel
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
     * @param string $key the key (slug) for the role. Will be prefixed with "bcb_" if it isn't already.
     * @param string $name the name for the role.
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

    /**
     * Finds all defined roles, uses {@link [Role::find][Role::find]} to generate the Role objects.
     *
     * @return array an array with the generated Role objects. Will be an empty array if no roles are found.
     */
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

    /**
     * Finds all roles with the given type, filters the result of {@link [Role::findAll][Role::findAll]}.
     *
     * @param string $type the type to filter for. Should be one of the class constants.
     * @return array an array with the generated Role objects. Will be an empty array if no roles with the given type
     * are found.
     */
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

    /**
     * Finds a WP Role with the given key (slug) and converts it to our custom Role.
     * First it will look if a role without the 'bcb_' prefix is found. If not it will ensure that the key has the 'bcb_'
     *
     * @param string $key the key (slug) for the role to find.
     * prefix and search for a custom Berg Club Bern role.
     * @return Role|null the Role associated with the given key or null if not found.
     */
    public static function find($key)
    {
        $id = Helpers::ensureKeyHasNoPrefix($key);
        $role = null;
        $data = get_role($key);

        if(!$data){
            $key = Helpers::ensureKeyHasPrefix($key);
        }

        $data = get_role($key);

        if($data){
            $customRoles = Option::get('roles');
            $type = self::TYPE_SYSTEM;
            if(isset($customRoles[self::TYPE_ADDRESS][$key])){
                $type = self::TYPE_ADDRESS;
            }elseif(isset($customRoles[self::TYPE_FUNCTIONARY][$key])){
                $type = self::TYPE_FUNCTIONARY;
            }

            $name = $data->name;
            if($type != self::TYPE_SYSTEM){
                if(isset($customRoles[$type][$key])){
                    $name = $customRoles[$type][$key];
                }
            }


            $role = new Role($type, $key, $name);
            $role->setCapabilities($data->capabilities);
            return $role;
        }
        return null;
    }

    /**
     * Saves the current Role object.
     *
     * Will create or update the WP Role object and also adds or updates the role in the WP Option 'bcb_roles' where we store our additional information
     * regarding the roles.
     */
    public function save()
    {
        if($this->key=="bcb_administrator"){
            $this->key="administrator";
        }
        $wpRole = get_role($this->key);
        if(!$wpRole){
            add_role($this->key, $this->name, $this->capabilities);
        }else{
            foreach($this->getCapabilities() as $capability => $grant){
                $wpRole->add_cap($capability, $grant);
            }
        }


        if($this->type != self::TYPE_SYSTEM){
            $customRoles = Option::get('roles');
            $customRoles[$this->type][$this->key] = $this->name;
            Option::set('roles', $customRoles);
        }

    }

    /**
     * Wrapper for the static {@link [Role::remove][Role::remove]} method, so it can directly called on the object
     * without passing its key.
     *
     * Resets the object.
     */
    public function delete()
    {
        self::remove($this->key);
        $this->key = null;
        $this->name = null;
        $this->capabilites = [];
    }

    /**
     * Removes the role with the given key from WP Option 'bcb_roles` where we store our additional information.
     * Also removes the role from WP.
     *
     * @param string $key the key (slug) for the role to remove. See {@link [Role::find][Role::find]} for more information.
     */
    public static function remove($key)
    {
        $role = self::find($key);
        if($role){
            $customRoles = Option::get('roles');
            if(isset( $customRoles[ $role->getType() ][ $role->getKey() ] )){
                unset( $customRoles[ $role->getType() ][ $role->getKey() ] );
                Option::set('roles', $customRoles);
            }
        }
        remove_role($key);
    }

    /**
     * Returns an array of all capabilities that are assigned to this role.
     * The array is a hashmap, where the key (slug) of the capability is the key and the value is a boolean indicating
     * to explicitly grant the capability (true) or to explicitly prohibit the capability (false).
     *
     * Example:
     * <code>
     * ['edit_something' => true, 'edit_something_else' => false]
     * </code>
     *
     * @return array an array with the assigned capabilities.
     */
    public function getCapabilities()
    {
        return $this->capabilities;
    }

    /**
     * Sets the capabilities assigned to the current role.
     * See {@link [Role::getCapabilities][Role::getCapabilities]} for a format description.
     *
     * @param array $capabilities
     */
    public function setCapabilities($capabilities)
    {
        $this->capabilities = $capabilities;
    }

    /**
     * Adds a capability to the role.
     * First it checks if WP has already a capability without 'bcb_' prefix with the given name. If yes, this capability
     * name is used. Otherwise a capability name with 'bcb_' prefix is used.
     *
     * @param string $capability the key (slug) for the capability. Does not have to exist already.
     *
     * @param boolean $grant indicates whether to explicitly grant the capability (true) or to explicitly prohibit the
     * capability (false).
     */
    public function addCapability($capability, $grant){
        $capability = Helpers::ensureKeyHasNoPrefix($capability);
        if(!$this->capabilityExists($capability)){
            $capability = Helpers::ensureKeyHasPrefix($capability);
        }
        $this->capabilities[$capability] = $grant;
    }

    /**
     * Removes a capability from the role.
     * First it checks if WP has already a capability without 'bcb_' prefix with the given name. If yes, this capability
     * name is used. Otherwise a capability name with 'bcb_' prefix is used.
     *
     * @param string $capability the key (slug) for the capability to remove.
     */
    public function removeCapability($capability){
        $capability = Helpers::ensureKeyHasNoPrefix($capability);
        if(!$this->capabilityExists($capability)){
            $capability = Helpers::ensureKeyHasPrefix($capability);
        }
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

    public function hasCapability($key){
        return isset($this->capabilities[$key]) && $this->capabilities[$key];
    }

    /**
     * Checks if the given value is one of the class constants.
     *
     * @param mixed $value the value to search for in the class constants.
     * @return bool true if found in class constants, false otherwise.
     */
    private static function isInConstants($value){
        $reflection = new \ReflectionClass(__CLASS__);
        return in_array($value, $reflection->getConstants());
    }

    /**
     * Loads all currently used capabilities in WP and checks if the given key is already existing capability.
     *
     * @param string $key the name (slug) of the capability.
     * @return bool true if the given capability name already exists in WP, false otherwise.
     */
    private function capabilityExists($key){
        global $wp_roles;
        $capabilities = [];
        foreach($wp_roles->roles as $role){
            $capabilities = array_merge($capabilities, $role['capabilities']);
        }
        return isset($capabilities[$key]);
    }
}