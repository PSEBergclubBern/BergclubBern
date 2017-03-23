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

    public function __construct($key, $name)
    {
        $this->key = Helpers::ensureKeyHasPrefix($key);
        $this->name = $name;
    }

    public static function findAll()
    {
        // TODO: Implement findAll() method.
    }

    public function save()
    {
        add_role($this->key, $this->name, $this->capabilities);
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
        remove_role($id);
    }

    public static function find($id)
    {
        $id = Helpers::ensureKeyHasPrefix($id);
        $role = null;
        $data = get_role($id);
        if($data){
            $role = new Role($id, $data->name);
            $role->setCapabilities($data->capabilities);
        }
        return $role;
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
        $this->capabilities[$capability] = $grant;
    }

    /**
     * @param string $capability
     */
    public function removeCapability($capability){
        unset($this->capabilities[$capability]);
    }

}