<?php
require('../class/answer.class.php');
require('../class/question.class.php');
require('../class/quiz.class.php');
require('../class/quiz_score.class.php');
require('../class/tag.class.php');

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(false, true)){
    wp_redirect( home_url() );  exit;
}

$str_json = file_get_contents('php://input');
$response = json_decode($str_json, true);
$id_quiz = $response['id_quiz'];
$id_user = $response['id_user'];

$query = $wpdb->get_results("SELECT id_question, id_answer, time FROM quiz_progress WHERE id_user= '$id_user' AND id_quiz = '$id_quiz'");

$result = array(
    "questions" => array(),
    "good" => 0,
    "score" => 0,
    "time" => 0,
);
$questionsGood = 0;
$maxTime = 0;


foreach($query as $q)
{
    $questionId = $q->id_question;
    $answersTrue = $wpdb->get_results("SELECT id, is_true FROM answer WHERE id_question='$questionId' and (is_true='true' or is_true=1)");
    $answersUser = json_decode($q->id_answer);

    $question = array(
        "id" => $questionId,
        "content" => $wpdb->get_var("SELECT content FROM question WHERE id='$questionId'"),
        "answers" => $wpdb->get_results("SELECT id, content, is_true FROM answer WHERE id_question='$questionId'"),
        "user_answer" => $answersUser,
        "good" => false,

    );


    if($q->time > $maxTime){
        $maxTime = $q->time;
    }

    if(count($answersUser) != count($answersTrue)){
        $result['questions'][] = $question;
        continue;
    }

    $allGood = true;
    foreach ($answersTrue as $a) {
        if(!in_array((int)$a->id, $answersUser)) {
            $allGood = false;
        }
    }

    if($allGood){
        $questionsGood++;
        $question['good'] = true;
    }

    $result['questions'][] = $question;
}

$quiz = new Quiz();
$quiz->selectById($response['id_quiz']);

$score = round(100/count($query) * $questionsGood);

$newScore = new Quiz_score();
$newScore->setUserId($id_user);
$newScore->setQuizId($quiz);
$newScore->setScore($score);
$newScore->setTime($maxTime);
$newScore->save();

$result['score'] = $score;
$result['time'] = $maxTime;
$result['good'] = $questionsGood;

echo json_encode($result);
?>
