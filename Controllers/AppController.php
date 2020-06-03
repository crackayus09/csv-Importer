<?php
require_once(__DIR__.'/ControllerInterface.php');

/**
 * Class AppController
 */
class AppController implements ControllerInterface
{
    public function __construct()
    {
        session_start();
        require_once("includes/config.php");
    }

    public function index()
    {
    }

    /**
     * Used to render a view
     *
     * @param string $view
     * @param array $params
     */
    public function render($view, $params = [])
    {
        ob_start();
        extract($params);
        require_once(__DIR__.'/../Views/'.$view.'.php');
        $str = ob_get_contents();
        ob_end_clean();
        echo $str;
    }

    /**
     * Used to extract a key from an array
     *
     * @param array $arr
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function arrExtract($arr, $key, $default = "")
    {
        return (isset($arr[$key]) && $arr[$key]) ? $arr[$key] : $default;
    }

    /**
     * Used to get POST data
     *
     * @return array
     */
    public function postData()
    {
        if (empty($_POST)) {
            $post_data = file_get_contents('php://input');
            return json_decode($post_data, true);
        }
        return $_POST;
    }
}
