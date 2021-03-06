<?php

namespace BergclubPlugin\MVC;

use duncan3dc\Laravel\BladeInstance;

/**
 * Outputs a rendered blade template.
 *
 * @package BergclubPlugin\MVC
 */
class View
{
    /**
     * Adds the given data to the view and renders the view.
     *
     * @param string $viewDirectory the path to the blade views directory
     * @param string $view the view to use (subdirectories can be denoted by '.' or '/')
     * @param array $data will be added to the view e.g. ['key' => 'value'] means you can access it in the view with `$key`.
     */
    public static function render($viewDirectory, $view, $data = [])
    {
        if (!file_exists(__DIR__ . '/cache')) {
            mkdir(__DIR__ . '/cache');
        }

        $blade = new BladeInstance($viewDirectory, __DIR__ . '/cache');
        return $blade->render($view, $data);
    }
}