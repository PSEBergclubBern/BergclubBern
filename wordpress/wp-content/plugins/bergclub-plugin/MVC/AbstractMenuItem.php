<?php

namespace BergclubPlugin\MVC;

/**
 * Represents a (main or sub) menu item in the WordPress Admin.
 *
 * Class AbstractMenuItem
 * @package BergclubPlugin\MVC
 */
abstract class AbstractMenuItem
{
    /**
     * The title for the menu item
     * @var string
     */
    protected $title;

    /**
     * The needed capability to view the page
     * @var string
     */
    protected $capability;

    /**
     * The controller class as string (including namespace)
     * @var string
     */
    protected $controller;

    /**
     * An array with assets (css and js urls)
     * @var array
     */
    protected $assets;


    /**
     * @param string $title The title for the menu item
     * @param string $capability The needed capability to view the page
     * @param string $controller The controller class as string (including namespace)
     * @param array $assets An optional array with assets (css and js urls)
     */
    public function __construct($title, $capability, $controller, $assets = []){
        $this->title = $title;
        $this->capability = $capability;
        $this->controller = $controller;
        $this->assets = $assets;
    }

    /**
     * This method is called from the page hook for the menu item.
     * Create a new instance from the given class new.
     */
    public function show(){
        new $this->controller();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getCapability()
    {
        return $this->capability;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        return $this->assets;
    }

    public function getUrl()
    {
        return get_admin_url() . '?page=' . Helpers::getSlug($this->controller);
    }
}