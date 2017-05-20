<?php
namespace BergclubPlugin;


class AssetHelper
{
    private static $assets = [];

    /**
     * @param string $page the page (menu slug) for which the asset needs to be added.
     * @param string $url the url to the asset (css or js, other will be ignored).
     */
    public static function addAsset($page, $url)
    {
        self::$assets[$page][] = $url;
    }

    /**
     * Called on admin_enqueue_scripts hook.
     */
    public static function getAssets()
    {
        $page = self::getPage();
        if ($page && isset(self::$assets[$page])) {
            foreach (self::$assets[$page] as $key => $url) {
                if (substr($url, -4) == ".css") {
                    wp_enqueue_style($page . "-" . $key, $url);
                } elseif (substr($url, -3) == ".js") {
                    wp_enqueue_script($page . "-" . $key, $url);
                }
            }
        }
    }

    private static function getPage()
    {
        if (!isset($_GET['page'])) {
            return null;
        }

        return $_GET['page'];
    }
}