<?php
function load_data(){
    global $id;
    if($id){
        $options = HTML::get_options();
        if( 
            isset($options["components_data"])&&
            isset($options["components_data"][$id])
        ){
        }else{
            echo("component_data don't exist<br>");
        }
        echo("++++++++++++++<br>");
    }else{
        echo("--------------");
    }
    return array();
}
function get_controller($name){
     if(file_exists("app/controllers/".$name.".php")){
        require_once("app/controllers/".$name.".php");
    }else{
        require_once("app/controllers/controller_index.php");
    }
    $result = new $name();
    return $result;
}