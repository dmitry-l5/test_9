<?php
class DBManager{
    static $_instance = null;
    public $pdo = null;

    private function __construct(){
        $config = include(PATH["presets"]."/db.php");
        $this->db_name = $config['dbname'];
        $this->pdo = new PDO($config['dsn'], $config['user'], $config['password']);
        //echo(__CLASS__);
    }
    static public function instance(){
        if(!self::$_instance){
            self::$_instance = new DBManager();
        }
        return self::$_instance;
    }
}