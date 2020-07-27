<?php
global $wpdb;
$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

class Tag {
    private $id;
    private $name;


    public function selectById($id){
        global $wpdb;
        $r = $wpdb->get_row("SELECT * FROM tag WHERE id='".$id."'");
        $this->id = $r->id;
        $this->name = $r->name;
    }

    public function selectByName($name){
        global $wpdb;
        $r = $wpdb->get_row("SELECT * FROM tag WHERE name='".$name."'");
        $this->id = $r->id;
        $this->name = $r->name;
    }

    public function getId(){
        return $this->id;
    }
    
    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function save(){
        if ($this->id == null){
            global $wpdb;
            $wpdb->insert(
                'tag', array(
                    "name" => $this->name
                )
            );
            return $wpdb->insert_id;
        }else{
            global $wpdb;
            $wpdb->update(
                'tag', array(
                    "name" => $this->name
                ), array(
                    "id" => $this->id
                )
            );
        }
    }

    public function delete(){
        global $wpdb;
        $wpdb->delete( 'tag', array( 'id' => $id ) );
    }
}

?>