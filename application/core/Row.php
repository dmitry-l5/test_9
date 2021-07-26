<?php

class Row{
    public function __construct($table_name){
        global $app;
        $this->db = $app->db;
        $this->id = null;
        $this->name = $table_name;
        $this->collumns = array();
        $db_name = $this->db->db_name;
        $response = $this->db->pdo->query(
            "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$table_name';"
        );
        if(!$response->rowCount()){
            throw new Exception("-----Table::construct('$table_name')-----");
        }
        foreach($response as $row){
            $this->collumns[$row['COLUMN_NAME']] = array(    "COLUMN_NAME"=>$row['COLUMN_NAME'],
                                                            "COLUMN_KEY"=>$row['COLUMN_KEY'],
                                                            "DATA_TYPE"=>$row['DATA_TYPE'],
                                                            "IS_NULLABLE"=>$row['IS_NULLABLE'],
                                                            "VALUE"=>null
                                                        );
        }
        $response->closeCursor();
    }

    public function id(){
        return $this->id;
    }
    public function query($request_str){
        $stmt = $this->db->pdo->prepare($request_str);
        if($stmt->execute()){
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($result){
                //$stmt->close();
                return $result;
            }
        }
        return null;
    }

    public function DELETE(){
        $where_arr = array();
        foreach($this->collumns as $attr){
            if($attr["VALUE"]!==null){
                array_push($where_arr, $attr["COLUMN_NAME"]."='".$attr["VALUE"]."'");
            }
        }
        $where  = "WHERE ".implode(" AND ", $where_arr);
        $query_str = "DELETE FROM $this->name ".$where.";";
        $stmt = $this->db->pdo->prepare($query_str);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    public function read_by_id($id = null){
        $_id = $id;
        $stmt = $this->db->pdo->prepare("SELECT * FROM $this->name WHERE `id`=:id");
        $stmt->bindParam(":id", $_id, PDO::PARAM_INT);
        if($stmt->execute()){
            
            $this->id = $id;
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result){
                foreach($result as $key=>$value){
                    $this->load_param($key, $value);
                }
            }
        }
    }
    public function UPDATE($id = null){
        if($id == null){
            $id = $this->find_id();
            if($id == null){
                return null;
            }
        }
        $set_arr  = array();
        foreach($this->collumns as $key=>$value){
            if($value["VALUE"]!==NULL){
                array_push($set_arr,  $value["COLUMN_NAME"]." = ".$value["VALUE"]);
            }
        }
        if(count($set_arr) > 0){
            $query_str = "UPDATE ".$this->name." SET ".implode(", ", $set_arr)." WHERE id = ".$id.";";
            $this->db->pdo->query($query_str);
            return $id;
        }else{
            return NULL;
        }
    }
    public function INSERT(){
        $id = $this->find_id();
        if($id == null){
            $names_arr  = array();
            $values_arr  = array();
            foreach($this->collumns as $key=>$value){
                if($value["VALUE"]!==NULL){
                        array_push($names_arr,  $value["COLUMN_NAME"]);
                        array_push($values_arr,  $value["VALUE"]);
                }
            }
            if(count($values_arr) > 0){
                $names_str   = "( ".implode(", ", $names_arr)." )";
                $values_str  = "( '".implode("', '", $values_arr)."' )";
                $query_str = "INSERT INTO ".$this->name.$names_str." VALUES ".$values_str.";";
                // var_dump($query_str);
                // die;
                $this->db->pdo->query($query_str);
                $id = $this->find_id();
                $this->id = $id;
                return $id;
            }else{
                return NULL;
            }
        }else{
            return $id;
        }

    }
    public function write_uncorrect(){
        $select_arr  = array();
        $value_arr  = array();
        $unique_arr  = array();
        if($this->id){
            $id = $this->id; // $this->collumns["id"]["VALUE"];
            foreach($this->collumns as $key=>$value){
                if(isset($value["COLUMN_NAME"])){
                    $val = ($value["VALUE"])?"'".$value["VALUE"]."'":NULL;
                    if($val){
                        array_push($value_arr,  "`".$value["COLUMN_NAME"]."`"."=".$val);
                    }
                }
            }
            $value_str = implode(", ", $value_arr);
            $query_str = "UPDATE `$this->name` SET $value_str WHERE `id` = $id;";
            // echo("<br>");
            // var_dump($query_str);
            // echo("<br>");
            $this->db->pdo->query($query_str);
        }else{
            foreach($this->collumns as $key=>$value){
                if(isset($value["COLUMN_NAME"]) && $value["COLUMN_NAME"]!="id" ){
                    array_push($select_arr, "`".$value["COLUMN_NAME"]."`");
                    $val = ($value["VALUE"])?"'".$value["VALUE"]."'":"NULL";
                    array_push($value_arr, $val);
                    if($value["COLUMN_KEY"] == "UNI"){
                        array_push($unique_arr, "`".$value["COLUMN_NAME"]."`"." = ".$val);
                    }
                }
            }
            $select_str = implode(", ", $select_arr);
            $value_str = implode(", ", $value_arr);
            $unique_str = implode(" OR ", $unique_arr);
            $query_str = "INSERT INTO `new_blog`.`users` (".$select_str.") SELECT ".$value_str." FROM `new_blog`.`users` as `U` WHERE NOT EXISTS (SELECT * FROM `new_blog`.`users` WHERE ".$unique_str.") limit 1;";
            // echo("<br>");
            // echo("<br>");
            // echo("<br>");
            // echo("<br>");
            // var_dump($select_str);
            // echo("<br>");
            // echo("<br>");
            // echo("<br>");
            // echo("<br>");
            // var_dump($query_str);
            // echo("<br>");
            // echo("<br>");
            // echo("<br>");
            // echo("<br>");
            // var_dump($unique_str);
            // echo("<br>");
            // echo("<br>");
            die;
            $this->db->pdo->query($query_str);
        }
    }

    public function find_id(){
        $where_arr = array();
        foreach($this->collumns as $attr){
            // if($attr["VALUE"]!==null&&($attr["COLUMN_KEY"]=="PRI"||$attr["COLUMN_KEY"]=="UNI")){
            //     array_push($where_arr, $attr["COLUMN_NAME"]."=".$attr["VALUE"]);
            // }
            if($attr["VALUE"]!==null&&(true)){
                array_push($where_arr, $attr["COLUMN_NAME"]."='".$attr["VALUE"]."'");
            }
        }
        if(count($where_arr) > 0){
            $where  = "WHERE ".implode(" AND ", $where_arr);
            $query_str = "select id FROM $this->name ".$where." LIMIT 1";
            // echo("<br>");
            // echo("<br>");
            // var_dump($query_str);
            // die;
            // echo("<br>");
            // echo("<br>");
            $stmt = $this->db->pdo->prepare($query_str);
            if($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if($result!==null&&isset($result['id'])){
                    return $result['id'];
                }else{
                    return null;
                }
            }else{
                return null;
            }
        }else{
            return null;
        }
    }
    public function load($input_arr){
        foreach($input_arr as $key=>$value){
            $this->load_param($key, $value);
        }
        if(!array_key_exists("id", $input_arr) ){
            $id = $this->find_id();
            if($id){
                $this->id = $id;
            }
        }
    }
    public function load_param($name_str, $value){
        if($name_str == 'id'){
            $this->id = $value;
        }
        if(array_key_exists($name_str, $this->collumns)){
            $this->collumns[$name_str]["VALUE"] = $value;
            return true;
        }
        return false;
    }
    public function get(){
        return $this->collumns;
    }
}