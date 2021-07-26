<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
require_once("paths.php");
require_once(PATH["core"]."Route.php");
require_once(PATH["core"]."functions_common.php");
$path = Route::getPath();
$prefix = "";
switch($path->is_manage){
    case true:
            $prefix .= PATH["backend"];
        break;
    case false:
            $prefix .= PATH["frontend"];
        break;
}
spl_autoload_register(function($className){
    global $prefix;
    if(file_exists(PATH["core"].$className.".php")){
        require_once PATH["core"].$className.".php";
    }elseif(file_exists(require_once $prefix.PATH["model"].$className.".php")){
        require_once $prefix.PATH["model"].$className.".php";
    }elseif(file_exists(require_once $prefix.PATH["model"].$className.".php")){
        require_once $prefix.PATH["model"].$className.".php";
    }
});
$app = new Application();
$db = $app->db;
//$fc = $app->fc;

switch($path->is_ajax){
    case true:
        // request handle
        break;
    case false:
            if(file_exists($prefix.PATH["controller"].$path->controller_name.".php")){
                require_once($prefix.PATH["controller"].$path->controller_name.".php");
                $controller = new $path->controller_name;
                $action_name = $path->action_name;
                if(method_exists($controller, $action_name)){
                    $view_data = $controller->$action_name($path->args_arr);
                    //var_dump($view_data);
                    require($prefix.PATH["view"].$view_data->page_name.".php");
                    // HTML::assign("data", $data);
                    // HTML::display($data["page_name"], $data);
                }else{
                    throw new Exception("action does't exist");
                }
            }else{
                // show error !!!
                var_dump($path);
                echo("<br>");
                var_dump("Unknown controller");
                echo("<br>");
                die;
            }
        break;
}