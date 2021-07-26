<?php
class Application{
    public function __construct(){
        //echo(__CLASS__);
        session_start();
        $this->db = DBManager::instance();
        //$this->fc = new FaceControl($this->db);

    }
}