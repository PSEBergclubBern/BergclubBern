<?php

namespace BergclubPlugin\MVC;

/**
 * Extend this AbstractController in your plugin sub folder.
 * You need override the `$viewDirectory` property.
 * The `$viewDirectory` and `$view` property needs to be overridden or set in at least one of the inherited methods.
 *
 * Class AbstractController
 * @package BergclubPlugin\MVC
 */
abstract class AbstractController
{
    /**
     * The full path to your views directory (common name: views)
     * e.g. __DIR__ . '/../views'
     *
     * @var string $viewDir
     */
    protected $viewDirectory;

    /**
     * The view to use (needs to be in your views directory).
     * You can also use '.' instead of '/' for subdirectories, for the OOP 'look and feel' ;)
     *
     * e.g. 'main', 'content/page', 'content.page'
     *
     * @var string $viewDir
     */
    protected $view;

    /**
     * In this array you can put your data for the view.
     * e.g. $this->data['title'] = 'MVC Demo' is available as `$title` in the view.
     *
     * @var array $data
     */
    protected $data = [];

    /**
     * Calls the following methods in this order:
     * - `first`
     * - `get` (only if it is a GET request)
     * - `post` (only if it is a POST request)
     * - `last`
     */
    public function __construct(){
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->first();
        $this->$method();
        $this->last();

        View::render($this->viewDirectory, $this->view, $this->data);
    }

    /**
     * Is called first.
     */
    abstract protected function first();

    /**
     * Is called if the request is of type GET
     */
    abstract protected function get();

    /**
     * Is called if the request is of type POST
     */
    abstract protected function post();

    /**
     * Is called last.
     */
    abstract protected function last();
}