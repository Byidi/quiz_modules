<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

class Quiz_score {
    private $id;
    private $user;
    private $quiz;
    private $score;
    private $time;
    private $created_at;

    public function selectById($id){
        global $wpdb;
        $r = $wpdb->get_row("SELECT * FROM 'quiz_score' where id=".$id."");
        $this->id = $r->id;
        $this->user = $r->user_id;
        $quiz = new Quiz();
        $quiz->selectById($r->quiz_id);
        $this->quiz = $quiz;
        $this->score = $r->score;
        $this->time = $r->time;
        $this->created_at = $r->created_at;
    }

    public function getId(){
        return $this->id;
    }

    public function getUser(){
        return $this->user;
    }
    public function setUserId($user){
        $this->user = $user;
    }

    public function getQuiz(){
        return $this->quiz;
    }
    public function setQuizId($quiz){
        $this->quiz = $quiz;
    }

    //Set quiz_id with id of quiz
    public function setQuizIdById(int $quizId){
        $this->quiz = new Quiz();
        $this->quiz->selectById($quizId);
    }


    public function getScore(){
        return $this->score;
    }
    public function setScore($score){
        $this->score = $score;
    }

    public function getTime(){
        return $this->time;
    }
    public function setTime($time){
        $this->time = $time;
    }

    public function getTimeInMinutes($seconds){
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;
        return "$hours:$minutes:$seconds";
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
                'quiz_score', array(
                    "user_id" => $this->user,
                    "quiz_id" => $this->quiz->getId(),
                    "score" => $this->score,
                    "time" => $this->time,
                    "created_at" => $this->created_at
            )
        );
        return $wpdb->insert_id;
        }else{
            global $wpdb;
            $wpdb->update(
                'quiz_score', array(
                    "user_id" => $this->user,
                    "quiz_id" => $this->quiz->getId(),
                    "score" => $this->score,
                    "time" => $this->time,
                    "created_at" => $this->created_at
            ), array(
                "id" => $this->id,
            ));
            
        }
    }

    public function delete(){
        global $wpdb;
        $wpdb->delete( 'quiz_score', array( 'id' => $this->id ) );
    }

    //moyenne globale de toutes les notes tous utilisateurs compris
    public function globalAverage(){
        global $wpdb;

        return $wpdb->get_var( "SELECT AVG(`score`) from quiz_score" );
    }
    
    //Moyenne de l'utilisateur sur tous les quizs
    public function userAverage($id_user){
        global $wpdb;

        return $wpdb->get_var( "SELECT AVG(`score`) from quiz_score  where user_id =".$user_id."" );
    }
 
    //Moyenne des utilisateurs sur un quiz
    public function quizAverage($id_quiz){
        global $wpdb;

        return $wpdb->get_var( "SELECT AVG(`score`) from quiz_score  where quiz_id =".$quiz_id."" );
    }


    //Moyenne par locatiçon sur tous les quizs
    public function locationAverage($location){
        global $wpdb;

        return $wpdb->get_var( "SELECT AVG(`score`) from quiz_score  inner join user on quiz_score.user_id = user.id where `location` =".$location."" );
    }


    //Classement par score global
    public function orderByGlobalScore(){
        global $wpdb;

        $wpdb->get_result( "SELECT * from quiz_score order by score");
    }

    //Classement sur un quiz
    public function orderByQuizScore($quizId){
        global $wpdb;

        $wpdb->get_result( "SELECT * from quiz_score where quiz_id =".$quiz_id." order by score");
    }

    //Classement par lieux    
    public function orderByLocationScore($location){
        global $wpdb;

        $wpdb->get_result(" SELECT * from quiz_score inner join user on quiz_score.user_id = user.id where `location` =".$location." order by `score`");
    }
}


//faire moyennes globales et par quiz

?>