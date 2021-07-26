<?php
    define("MANAGE", "control");
    class Route{
        static private $instance = null;
        private function __construct(){}
        static public function init(){
            if(!self::$instance){
                self::$instance = new self;
            }
            return self::$instance;
        }
        static public function getPath(){
            $controller_name = "index";
            $action_name =     "index";
            $is_manage = false;
            $is_ajax = false;
            $url = parse_url($_SERVER['REQUEST_URI']);
            if(ltrim($url['path'], '/')!==""){
                $path_arr = explode('/', ltrim($url['path'], "/"));
                $index = 0;
                if(count($path_arr) > $index && $path_arr[$index]!==''){
                    if($path_arr[$index] === MANAGE){
                        $is_manage = true;
                        $index++;
                    }
                    if(count($path_arr) > $index && $path_arr[$index]!==''){
                        $controller_name = $path_arr[$index];
                        $index++;
                    }
                    if(count($path_arr) > $index && $path_arr[$index]!==''){
                        $action_name = $path_arr[$index];
                    }
                }
            }else{
                // var_dump($url);
                // echo("<br>");
            }
            isset($url["query"]) ? parse_str($url["query"], $args_arr) : $args_arr = null;
            if(isset($args_arr["is_ajax"])){
                $is_ajax = true;
            }
            return new class($is_manage, $is_ajax, $controller_name, $action_name, $args_arr){
                public function __construct($manage, $ajax, $controller, $action, $args){
                    $this->is_manage = $manage;
                    $this->is_ajax = $ajax;
                    $this->controller_name = "controller_".$controller;
                    $this->action_name = "action_".$action;
                    $this->args_arr = $args;
                }
            };
        }
    }