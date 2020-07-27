<?php
global $wpdb;
$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

class Question {

    private $id;
    private $id_quiz;
    private $content;
    private $img_path;
    private $url;
    private $points;

    public function selectById($id){
        global $wpdb;
        $r = $wpdb->get_row("SELECT * FROM question where id=".$id."");
        $this->id = $r->id;
        $this->id_quiz = (new Quiz())->selectById($r->id);
        $this->content = $r->content;
        $this->img_path = $r->img_path;
        $this->url = $r->url;
        $this->points = $r->points;
    }

    public function getId(){
        return $this->id;
    }

    public function getIdQuiz(){
        return $this->id_quiz;
    }
    public function setIdQuiz($id_quiz){
        $this->id_quiz = $id_quiz;
    }

    //Set id_Quiz with id of quiz
    public function setIdQuizById(int $idQuiz){
        $this->id_quiz = new quiz();
        $this->id_quiz->selectById($idQuiz);
    }

    public function getContent(){
        return $this->content;
    }
    public function setContent($content){
        $this->content = $content;
    }

    public function getImgPath(){
        return $this->img_path;
    }
    public function setImgPath($img_path){
        $this->img_path = $img_path;
    }

    public function getUrl(){
        return $this->url;
    }
    public function setUrl($url){
        $this->url = $url;
    }

    public function getPoints(){
        return $this->points;
    }
    public function setPoints($points){
        $this->points = $points;
    }


    public function save(){
        if ($this->id === null){
            global $wpdb;
            $wpdb->insert(
                'question', array(
                    "id" => $this->id,
                    "id_quiz" => $this->id_quiz,
                    "content" => stripslashes($this->content),
                    "img_path" => $this->img_path,
                    "url" => $this->url,
                    "points" => $this->points,
                    )
                );
                return $wpdb->insert_id;
        }else{
            global $wpdb;
            $u = $wpdb->update(
                'question', array(
                    "id_quiz" => $this->id_quiz,
                    "content" => stripslashes($this->content),
                    "img_path" => $this->img_path,
                    "url" => $this->url,
                    "points" => $this->points,
                ), array(
                    "id" => $this->id,
                )
            );
        }
    }

    public function delete(){
        global $wpdb;
        $wpdb->delete( 'question', array( 'id' => $id ) );
    }
}

?>
