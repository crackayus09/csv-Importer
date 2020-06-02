<?php
require_once(__DIR__.'/ControllerInterface.php');

class AppController implements ControllerIntreface
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
}
