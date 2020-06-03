<?php
require_once(__DIR__.'/ControllerInterface.php');

class AppController implements ControllerInterface
{
    public function __construct()
    {
        session_start();
        require_once("includes/config.php");
    }

    // we will look at this in the view
    public function render($view)
    {
        include_once(__DIR__.'/../Views/'.$view.'.html');
    }

    public function index()
    {
    }

    public function arr_extract($arr, $key, $default = "")
    {
        return (isset($arr[$key]) && $arr[$key]) ? $arr[$key] : $default;
    }
}
