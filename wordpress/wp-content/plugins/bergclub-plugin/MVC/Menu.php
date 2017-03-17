<?php

namespace BergclubPlugin\MVC;


use BergclubPlugin\AssetHelper;

/**
 * Represents a main menu entry in WordPress Admin
 *
 * Class Menu
 * @package BergclubPlugin\MVC
 */
class Menu extends AbstractMenuItem
{
    private $subMenus = [];
    private $icon;

    /**
     * Represents a main menu item in WordPress Admin
     *
     * @param string $title The title for the menu item
     * @param string $capability The needed capability to view the page
     * @param string $controller The controller class as string (including namespace)
     * @param array $assets An optional array with assets (css and js urls)
     * @param string $icon The WordPress Dashicon to use.
     *               See {@link https://developer.wordpress.org/resource/dashicons/ Dashicons}.
     *               Click on the icon you want to use, click on copy HTML und just copy the part that begins with
     *               `dashicons-` (e.g. `dashicons-admin-media`).
     */
    public function __construct($title, $capability, $controller, $assets, $icon = ''){
        parent::__construct($title, $capability, $controller, $assets);

        $this->icon = $icon;
        add_action('admin_menu', [$this, 'run']);
    }

    /**
     * Adds a sub menu entry
     * @param SubMenu $subMenu
     */
    public function addSubMenu(SubMenu $subMenu){
        $this->subMenus[] = $subMenu;
    }

    /**
     * Is called from WordPress when generating the admin menu.
     */
    public function run(){
        $parent_slug = Helpers::getSlug($this->controller);
        add_menu_page($this->getTitle(), $this->getTitle(), $this->getCapability(), $parent_slug, [$this, 'show'], $this->getIcon());

        foreach($this->getAssets() as $url){
            AssetHelper::addAsset($parent_slug, $url);
        }

        foreach ($this->subMenus as $subMenu) {
            /* @var \BergclubPlugin\MVC\SubMenu $subMenu */
            $slug = Helpers::getSlug($subMenu->controller);
            add_submenu_page($parent_slug, $subMenu->getTitle(), $subMenu->getTitle(), $subMenu->getCapability(), $slug, [$subMenu, 'show']);
            foreach($subMenu->getAssets() as $url){
                AssetHelper::addAsset($slug, $url);
            }
        }
    }


    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }
}