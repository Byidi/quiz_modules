<?php
global $wpdb;
$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

class Quiz {

    private $id;
    private $name;
    private $tag;
    private $img_path;
    private $description;
    private $author;
    private $status;
    private $created_at;

    public function selectById($id){
        global $wpdb;
        $r = $wpdb->get_row("SELECT * FROM quiz where id='".$id."'");
        $this->id = $r->id;
        $this->name = $r->name;
        $tagId = new tag();
        $tagId->selectById($r->tag_id);
        $this->tag = $tagId;
        $this->img_path = $r->img_path;
        $this->description = $r->description;
        $this->author = $r->author_id;
        $this->status = $r->status;
        $this->created_at = $r->created_at;
        return $r;
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

    public function getTag(){
        return $this->tag;
    }

    public function setTag($tag){
        $this->tag = $tag;
    }

    public function getImgPath(){
        return $this->img_path;
    }

    public function setImgPath($img_path){
        $this->img_path = $img_path;
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
    public function setAuthor($author){
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
            $wpdb->insert(
                'quiz', array(
                    "name" => stripslashes($this->name),
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
            $u = $wpdb->update(
                'quiz', array(
                    "name" => stripslashes($this->name),
                    "tag_id" => $this->tag->getId(),
                    "img_path" => $this->img_path,
                    "description" => stripslashes($this->description),
                    "author_id" => $this->author,
                    "status" => $this->status,
                    "created_at" => $this->created_at
                ), array(
                    "id" => $this->id,
                )
            );
        }
    }

    public function delete(){
        global $wpdb;
        $wpdb->delete( 'quiz', array( 'id' => $id ) );
    }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getInfos($player){

        global $wpdb;

        $quizId = $this->id;
        $questions = $wpdb->get_results( "SELECT * FROM question WHERE id_quiz='$quizId' ORDER BY rand() LIMIT 10");
        $finish = $wpdb->get_var("SELECT count(id) FROM quiz_score WHERE quiz_id='$quizId' AND user_id='$player'");

            $quiz = array(

                'id' => $this->id,
                'name' => $this->name,
                'tag_name' => $this->tag->getName(),
                'img' => $this->img_path,
                'description' => nl2br(stripslashes($this->description)),
                'player' => $player,
                'finish' => $finish,
            );

            foreach($questions as $q){

                $question = array(  

                    "id" => $q->id,
                    "id_quiz" => $q->id_quiz,
                    "content" => $q->content,
                    "img_path" => $q->img_path,
                    "url" => $q->url,
                    "points" => $q->points,
                );

                $questionId = $question['id'];
                $answers = $wpdb->get_results( "SELECT * FROM answer where id_question='$questionId'" );
                foreach($answers as $a){

                    $answer = array(
                        'id' => $a->id,
                        'id_question' => $a->id_question,
                        'content' => $a->content,
                    );

                    $question['answers'][] = $answer;

                }

                $quiz['questions'][] = $question;

            }

            $query = $wpdb->get_results("SELECT id_question, id_answer, time FROM quiz_progress WHERE id_user= '$player' AND id_quiz = '$quizId'");
            $previous = array();
            foreach($query as $q)

            {

                $answerId = $q->id_answer;
                $answer =  $wpdb->get_var("SELECT is_true FROM answer WHERE id='$answerId'");
                $previous[] = array(
                    "id_question" => $q->id_question,
                    "id_answer" => $q->id_answer,
                    "time" => $q->time,
                );


            }

            $quiz["previous"] = $previous;

            return $quiz;
        }
    }

?>
