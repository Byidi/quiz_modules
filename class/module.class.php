<?php

global $wpdb;

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);

include($path.'wp-load.php');



class Module {

    private $id;
    private $title;
    private $content;
    private $tag;
    private $img_path;
    private $description;
    private $author;
    private $status;
    private $created_at;

    public function selectById($id){
        global $wpdb;
        $r = $wpdb->get_row("SELECT * FROM module where id='".$id."'");
        $this->id = $r->id;
        $this->title = $r->title;
        $this->content = $r->content;
        $tagId = new tag();
        $tagId->selectById($r->tag_id);
        $this->tag = $tagId;
        $this->img_path = $r->img_path;
        $this->description = $r->description;
        $this->author = $r->author_id;
        $this->status = $r->status;
        $this->created_at = $r->created_at;
    }

    public function getId(){
        return $this->id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function getContent(){
        return $this->content;
    }

    public function setContent($content){
        $this->content = $content;
    }

    public function getTag(){
        return $this->tag;
    }

    public function setTag($tag){
        $this->tag = $tag;
    }

    public function getImgPath(){
        return $this->img_path;
    }

    public function setImgPath($imgPath){
        $this->img_path = $imgPath;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getAuthor(){
        return $this->author;
    }

     //Set author with instance of author

     public function setAuthor(int $author){
        $this->author = $author;
    }

    //Set author with id of author

    public function setAuthorById(int $authorId){
        $this->author = $authorId;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function getCreatedAt(){
        return $this->created_at;
    }

    public function setCreatedAt($created_at){
        $this->created_at = $created_at;
    }





    public function save(){

        if ($this->id == null){

            $timeZone = get_option("timezone_string");
            date_default_timezone_set($timeZone); 
            global $wpdb;
            $this->created_at = (new DateTime())->format('Y-m-d H:i:s');

            $r = $wpdb->insert(
                'module', array(
                    "title" => stripslashes($this->title),
                    "content" => '123',
                    "tag_id" => $this->tag->getId(),
                    "img_path" => $this->img_path,
                    "description" => stripslashes($this->description),
                    "author_id" => $this->author,
                    "status" => $this->status,
                    "created_at" => $this->created_at
                    )
                );
                return $wpdb->insert_id;
        }else{

            global $wpdb;
            $wpdb->update(
                'module', array(   
                    "title" => stripslashes($this->title),
                    "content" => $this->content,
                    "tag_id" => $this->tag->getId(),
                    "img_path" => $this->img_path,
                    "description" => stripslashes($this->description),
                    "author_id" => $this->author,
                    "status" => $this->status,
                    "created_at" => $this->created_at,
                ), array(
                    "id"  => $this->id,
                )
            );
            return $r;
        }
        return 'fail';
    }

    public static function delete(){
        global $wpdb;
        $wpdb->delete( 'user', array( 'id' => $id ) );
    }

    public function printIndex(){
        return '
            <div>
                <h2>'.$this->title.'</h2>
            </div>
        ';
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function getInfos($player){
    global $wpdb;
    
    $moduleId = $this->getId();
    $finish = $wpdb->get_var("SELECT count(id) FROM module_finish WHERE module_id='$moduleId' AND user_id='$player'");

    $module = array(
        'id' => $this->getId(),
        'title' => $this->getTitle(),
        'tag_name' => $this->getTag()->getName(),
        'img' => $this->getImgPath(),
        'description' => nl2br(stripslashes($this->getDescription())),
        'player' => $player,
        'finish' => $finish,
    );

    
    $pages = $wpdb->get_results( "SELECT * FROM module_slide WHERE module_id='$moduleId' ORDER BY 'order' DESC");
    foreach($pages as $p){
        $page = array(
            "id" => $p->id,
            "title" => $p->title,
            "module_id" => $p->module_id,
            "content" => nl2br($p->content),
            "img_path" => $p->img_path,
            "video" => $p->url,
            "order" => $p->order,
        );
        $module['slides'][] = $page;
    }
    /////
    $quizQuery = $wpdb->get_results("SELECT id_quiz FROM module_quiz WHERE id_module='$moduleId'");
    $quizInfo = array();
    foreach($quizQuery as $q){
        $quizRelated = new Quiz();
        $quizRelated->selectById($q->id_quiz);
        $quizInfo[] = $quizRelated->getInfos($_SESSION['userConnected']);
    }
    $module["quizs"] = $quizInfo;
    /////
    $userId = $player;
    $query = $wpdb->get_results("SELECT slide_id FROM module_progress WHERE user_id= '$userId' AND module_id = '$moduleId'");
    $previous = array();
    foreach($query as $q)
    {
        $slideId = $q->slide_id;
        $order = $wpdb->get_var("SELECT `order` FROM module_slide WHERE id='$slideId'");
        $previous[] = array(
            "id_module" => $moduleId,
            "id_slide" => $slideId,
            "order" => $order,
        );
    }
    usort($previous, function ($a, $b) {
        return $a['order'] <=> $b['order'];
    });
    $module["previous"] = $previous;
    
    return $module;

}





}



?>