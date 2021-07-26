<?php
    class controller_index{
        public function action_index($arr = null){
            return $this->action_view_material_list($arr);
        }
        public function action_create_new_category($args = null){
            $tag = new Row("group_1");
            $tag->load($_POST);
            $tag->INSERT();
            return $this->action_create_category();
        }
        public function action_create_new_tag($args = null){
            $tag = new Row("tags_list");
            $tag->load($_POST);
            $tag->INSERT();
            return $this->action_create_tag();
        }
        public function action_delete_material($args = null){

            $id = $args['id'];
            $material = new Row("materials");
            $material->query(
                "
                DELETE FROM materials WHERE id = '$id';
                DELETE FROM link WHERE id_material = '$id';
                DELETE FROM authors WHERE id_material = '$id';
                DELETE FROM tags WHERE id_material = '$id';
                "
            );
            return $this->action_view_material_list($args);
        }
        public function action_find_material($arr = null){
            $look_for = $_GET["look_for"];
            $model = new Row("materials");
            $list = $model->query("
                select 
                    m.id, 
                    m.title as title, 
                    GROUP_CONCAT(DISTINCT p.name SEPARATOR ', ') as authors,  
                    t.title as type, 
                    g.title as section, 
                    concat(m.title, concat(t.title,concat(g.title,GROUP_CONCAT(DISTINCT p.name SEPARATOR ', ')))) as search_str
                from materials as m 
                left join authors as a on  m.id=a.id_material 
                left join person as p on a.id_person=p.id 
                left join type_list as t on m.type=t.id 
                left join group_1 as g on m.section=g.id 
                group by m.id 
                HAVING search_str LIKE '%$look_for%';
            ");
            return new class($list, $look_for){
                public function __construct($list, $look_for){
                    $this->page_name = "list-materials";
                    $this->list = $list;
                    $this->look_for = $look_for;
                    $this->tmp = "BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND ";
                }
            };

        }
        public function action_view_material_list($arr = null){
            $model = new Row("materials");
            $list = $model->query("
                select 
                    m.id, 
                    m.title as title, 
                    GROUP_CONCAT(DISTINCT p.name SEPARATOR ', ') as authors,  
                    t.title as type, 
                    g.title as section 
                from materials as m 
                left join authors as a on  m.id=a.id_material 
                left join person as p on a.id_person=p.id 
                left join type_list as t on m.type=t.id 
                left join group_1 as g on m.section=g.id 
                group by m.id  
            ");
            return new class($list){
                public function __construct($list){
                    $this->page_name = "list-materials";
                    $this->list = $list;
                    $this->tmp = "BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND ";
                }
            };
        }
        public function action_add_link(){
            $link = new Row("link");
            $link->load($_POST);
            $id = $link->INSERT();
            header("Location: /control/index/view_material?id=".$link->get()["id_material"]["VALUE"]);
        }
        public function action_remove_link($id = null){
            $link = new Row('link');
            $link->read_by_id($_GET['id']);
            $is_ok = $link->DELETE();
            //$id = $tag->UPDATE(6);
            header("Location: /control/index/view_material?id=".$link->get()["id_material"]["VALUE"]);
        }
        public function action_add_tag(){
            $tag = new Row('tags');
            $tag->load($_POST);
            $id = $tag->INSERT();
            header("Location: /control/index/view_material?id=".$tag->get()["id_material"]["VALUE"]);
        }
        public function action_remove_tag($args = null){
            $tag = new Row('tags');
            $tag->load($_GET);
            $tag->DELETE();
            header("Location: /control/index/view_material?id=".$tag->get()["id_material"]["VALUE"]);
        }
        public function action_view_material($args = null){
            if(isset($args["id"])){
                $item = new Row("materials");
                $info = $item->query("
                    select m.id, m.title as title, GROUP_CONCAT(DISTINCT p.name SEPARATOR ', ') as authors, t.title as type, g.title as section, m.description from materials as m
                    left join authors as a on  m.id=a.id_material 
                    left join person as p on a.id_person=p.id 
                    left join type_list as t on m.type=t.id 
                    left join group_1 as g on m.section=g.id 
                    where m.id=".$args['id']."
                    group by m.id  
                ");
                if($info==null){  header("Location: not_found"); }
                $info = $info[0];
                $tags_list =$item->query("select * from tags_list");
                $tags =$item->query("SELECT t.id_material, l.id, l.title FROM tags as t left join tags_list as l on t.id_tag=l.id where t.id_material=".$args['id'].";");
                $links =$item->query("SELECT * FROM test_9.link WHERE id_material=".$args['id'].";");
                return new class($info, $tags_list, $tags, $links){
                public function __construct($info, $tags_list, $tags, $links){
                    $this->page_name = "view-material";
                    $this->info = $info;
                    $this->tags_list = $tags_list;
                    $this->tags = $tags;
                    $this->links = $links;
                    $this->tmp = "BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND ";
                }
            };
            }else{
                header("Location: not_found");
            }
        }
        public function action_create_category(){
            return new class(){
                public function __construct(){
                    $this->page_name = "create-category";
                }
            };
        }
        public function action_create_tag(){
            return new class(){
                public function __construct(){
                    $this->page_name = "create-tag";
                }
            };
        }
        public function action_create_material(){
            $type_row = new Row("type_list");
            $type_list = $type_row->query("SELECT * FROM type_list");
            $group_list = $type_row->query("SELECT * FROM group_1");
            return new class($group_list, $type_list){
                public function __construct($group_list, $type_list){
                    $this->page_name = "create-material";
                    $this->types = $type_list;
                    $this->groups = $group_list;
                }
            };
        }
        public function action_save_material(){
            $material = new Row('materials');
            $material->load($_POST);
            $id_material = $material->INSERT();
            $authors = $_POST["authors"];
            $authors_arr = explode(",",$authors);
            $person = new Row("person");
            foreach($authors_arr as $author){
                $author = trim($author);
                $exist = $person->query("SELECT * FROM person WHERE `name` LIKE '%".$author."%';");
                if($exist == NULL){
                    $person->load_param('name', $author);
                    $id_person = $person->INSERT();
                }else{
                    $id_person = $exist[0]['id'];
                }
                $author_table = new Row('authors');
                $author_table->load_param("id_material", $id_material);
                $author_table->load_param("id_person", $id_person);
                $author_table->INSERT();
                $material->query("INSERT INTO materials (id_material, id_person) VALUES ($id_material, $id_person)");
            }
            header("Location: /control/");
        }
        public function action_not_found(){
            return new class(){
                public function __construct(){
                    $this->page_name = "not-found";
                }
            };
        }
    }