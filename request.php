 
<?php
    class Request
    {
        public $url;

        public function __construct()
        {
            $request = $_SERVER['REQUEST_URI'];
            $router_path = $_SERVER["PHP_SELF"];
            $query_str = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "";

            $actual_router = str_replace("/index.php", "", $router_path);
            $route = str_replace($actual_router . "/", "", $request);
            if (!empty($query_str)) {
                $route = str_replace("?".$query_str, "", $route);
            }
            $this->url = $route;
        }
    }

?>