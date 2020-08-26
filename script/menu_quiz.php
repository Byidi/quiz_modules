<?php 



header('content-type:application/json');



require('../class/answer.class.php');

require('../class/question.class.php');

require('../class/quiz.class.php');

require('../class/quiz_score.class.php');

require('../class/tag.class.php');

require('../class/module.class.php');



$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);

include($path.'wp-load.php');


if(!checkAuthorized(false, true)){

    wp_redirect( home_url() );  exit;

}

//JSON encode 

$where = '';
if(!isset($_GET['all']) ){
    $where = " WHERE status=1";
}

$quizs = $wpdb->get_results( "SELECT id, name, tag_id, img_path FROM quiz".$where );

$quizArray = [];

foreach ($quizs as $q){

    $score = null;

    $userId = get_current_user_id();

    $score = $wpdb->get_row( "SELECT score, time FROM quiz_score where quiz_id = ".$q->id." AND user_id = ".$userId." ");



    $quizTmp = new Quiz();

    $quizTmp->selectById($q->id);

    

    $quiz = array(

        "id" => $quizTmp->getId(),

        "name" => $quizTmp->getName(),

        "tag_id" => $quizTmp->getTag()->getId(),

        "tag_name" => $quizTmp->getTag()->getName(),

        "img" => $quizTmp->getImgPath(),

        "user_score" => $score->score,

        "user_time" => $score->time,

        "status" => $quizTmp->getStatus(),

    );

    $moduleInfo = array();

    $moduleQuery = $wpdb->get_results("SELECT id_module FROM module_quiz WHERE id_quiz=".$q->id." ");

    foreach($moduleQuery as $m){

        $moduleRelated = new Module();

        $moduleRelated->selectById($m->id_module);

        $moduleInfo[] = array(

            "id" => $moduleRelated->getId(),

            "title" => $moduleRelated->getTitle(),

            "tag" => $moduleRelated->getTag()->getName(),

            "img"=> $moduleRelated->getImgPath(),

        );

    }

    $quiz['moduleRelated'] = $moduleInfo;

    $quizArray['quiz'][] = $quiz;

}

echo json_encode($quizArray);


?>



