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
    public function render($view, $params = [])
    {
        ob_start();
        extract($params);
        require_once(__DIR__.'/../Views/'.$view.'.php');
        $str = ob_get_contents();
        ob_end_clean();
        echo $str;
    }

    public function index()
    {
    }

    public function arr_extract($arr, $key, $default = "")
    {
        return (isset($arr[$key]) && $arr[$key]) ? $arr[$key] : $default;
    }
    public function post_data()
    {
        if (empty($_POST)) {
            $post_data = file_get_contents('php://input');
            return json_decode($post_data, true);
        }
        return $_POST;
    }
}
