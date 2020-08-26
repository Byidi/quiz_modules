
<?php

define('WP_USE_THEMES', false);
require('../class/answer.class.php');
require('../class/question.class.php');
require('../class/quiz.class.php');
require('../class/quiz_score.class.php');
require('../class/tag.class.php'); 

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}



$id = get_current_user_id();


$tag = new Tag();
$tag->selectByName($_SESSION['quizData']['quiz']['theme']);

//recuperation quiz
$newQuiz = new Quiz();
if(!empty($_SESSION['quizData']['quiz']['id'])){
    $newQuiz->selectById($_SESSION['quizData']['quiz']['id']);
}
$newQuiz->setName($_SESSION['quizData']['quiz']['title']);

$newQuiz->setTag($tag);
$newQuiz->setAuthorById($id);
$newQuiz->setImgPath($_SESSION['quizData']['quiz']['img']);
$newQuiz->setDescription($_SESSION['quizData']['quiz']['description']);
$newQuiz->setStatus($_GET['status'] ?? 1); 
$t = $newQuiz->save();

if(!empty($_SESSION['quizData']['quiz']['id'])){
    $newQuizId = $newQuiz->getId();
}else{
    
    $newQuizId = $wpdb->insert_id;
}

$prevQuestionsList = [];
if($_SESSION['quizEdit'] === true){
    global $wpdb;
    $queryPrev = $wpdb->get_results("SELECT id FROM question WHERE id_quiz = $newQuizId");
    foreach ($queryPrev as $prev) {
        $prevQuestionsList[] = $prev->id;
    }
}

//quiz->module
global $wpdb;
$wpdb->delete('module_quiz', array("id_quiz"=> $_SESSION['quizData']['quiz']['id']));
if(!empty($_SESSION['quizData']['quiz']['module_id'])){
    global $wpdb;
    $data = array('id_quiz' => $newQuizId, 'id_module' => $_SESSION['quizData']['quiz']['module_id']);
    $wpdb->insert('module_quiz', $data);
}

//recupération questions/réponses
foreach($_SESSION['quizData']['questions'] as $q)
{
    $newQuestion = new Question();
    if(!empty($q['info']['id']) && $q['info']['id'] !== 0){
        $newQuestion->selectById($q['info']['id']);
    }
    $newQuestion->setIdQuiz($newQuizId);
    $newQuestion->setContent($q['info']['text']);
    $points = 100/sizeof($_SESSION['quizData']['questions']);
    $newQuestion->setImgPath($q['info']['img']);
    $newQuestion->setUrl($q['info']['video']);
    $newQuestion->setPoints($points);
    $newQuestion->save();

    if(!empty($q['info']['id']) && $q['info']['id'] !== 0){
        $newQuestionId = $newQuestion->getId();
        if(in_array($newQuestionId, $prevQuestionsList)){
            array_splice($prevQuestionsList, array_search($newQuestionId, $prevQuestionsList), 1);
        }
    }else{
        $newQuestionId = $wpdb->insert_id;
    }

    global $wpdb;
    $wpdb->delete('answer', array("id_question"=>$newQuestionId));

    foreach($q['answers'] as $a)
    {
        $newAnswer = new Answer();
        $newAnswer->setIdQuestion($newQuestionId);
        $newAnswer->setContent($a['text']);
        $newAnswer->setIsTrue($a['isTrue']);
        $newAnswer->save();
    }
}

foreach ($prevQuestionsList as $p) {
    global $wpdb;
    $wpdb->delete('answer', array('id_question'=>$p));
    $wpdb->delete('question', array('id'=>$p));
}

unset($_SESSION['quizData']);
unset($_SESSION['formQuizStep2']);
$_SESSION['quizEdit'] = false;
wp_redirect( home_url().'/menu-quiz' );

?>
