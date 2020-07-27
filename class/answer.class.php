<?php
global $wpdb;
$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

class Answer {

    private $id;
    private $id_question;
    private $content;
    private $is_true;

    public function selectById($id){
        global $wpdb;
        $r = $wpdb->get_row("SELECT * FROM 'answer' where id=".$id."");
        $this->id = $r->id;
        $this->id_question = (new Question())->selectById($r->id);;
        $this->content = $r->content;
        $this->is_true = $r->is_true;
    }

    public function getId(){
        return $this->id;
    }

    public function getIdQuestion(){
        return $this->id_question;
    }
    public function setIdQuestion($id_question){
        $this->id_question = $id_question;
    }

    //Set id_question with id of question
    public function setIdQuestionById(int $quizId){
        $this->id_question = new Question();
        $this->id_question->selectById($quizId);
    }

    public function getContent(){
        return $this->content;
    }
    public function setContent($content){
        $this->content = $content;
    }

    public function getIsTrue(){
        return $this->is_true;
    }
    public function setIsTrue($is_true){
        $this->is_true = $is_true;
    }


    public function save(){
        if ($this->id == null){
            global $wpdb;
            $wpdb->insert(
                'answer', array(
                    "id_question" => $this->id_question,
                    "content" => stripslashes($this->content),
                    "is_true" => $this->is_true,
                    )
                );
                return $wpdb->insert_id;
        }else{
            global $wpdb;
            $wpdb->update(
                'answer', array(
                    "id_question" => $this->id_question,
                    "content" => stripslashes($this->content),
                    "is_true" => $this->is_true,
                ), array(
                    "id"  => $this->id,
                )
            );  
        }
    }

    public function delete(){
        global $wpdb;
        $wpdb->delete( 'answer', array( 'id' => $id ) );
    }
}

?>