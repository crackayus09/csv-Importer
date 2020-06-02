 
<?php

class Router
{
    public static function parse($url, $request)
    {
        $url = trim($url);

        if ($url == "") {
            $request->controller = "CSVUpload";
            $request->action = "index";
            $request->params = [];
        } else {
            $explode_url = explode('/', $url);
            $request->controller = $explode_url[0];
            $request->action = isset($explode_url[1]) ? $explode_url[1] : "index";
            $request->params = array_slice($explode_url, 2);
        }
    }
}
?>