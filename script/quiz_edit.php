<?php
define('WP_USE_THEMES', false);
$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

require('../class/quiz.class.php');
require('../class/question.class.php');
require('../class/answer.class.php');
require('../class/tag.class.php');

if(isset($_GET['id'])){
    global $wpdb;
    $quizId = $_GET['id'];
    unset($_SESSION['quizData']);
    unset($_SESSION['formQuizStep2']);
    $_SESSION['quizEdit'] = true;

    $quiz = new Quiz();
    $quiz->selectById($quizId);

    $moduleRelated =  $wpdb->get_var("SELECT id_module FROM module_quiz WHERE id_quiz ='$quizId'");

    $_SESSION['quizData']['quiz'] = array (
        'id' => $quiz->getId(),
        'title'=> $quiz->getName(),
        'theme'=> $quiz->getTag()->getId(),
        'img'=> $quiz->getImgPath(),
        'description'=> $quiz->getDescription(),
        'moduleRelated' => $moduleRelated,
    );

    $questions = $wpdb->get_results("SELECT * FROM question WHERE id_quiz='$quizId' ");
    $_SESSION['formQuizStep2']['nbrQuestion'] = count($questions);

    foreach($questions as $key => $question){
        $qst['id'] = $question->id;
        $qst['text'] = $question->content;
        $qst['url'] = $question->url;
        $qst['img'] = $question->img_path;
        $qst['points'] = $question->points;

        $_SESSION['quizData']['questions'][$key]['info'] = $qst;


        $i = $key + 1;
        $_SESSION['formQuizStep2']['question_'.$question->id] = $question->content;

        $answers = $wpdb->get_results("SELECT * FROM answer WHERE id_question='.$question->id.' ");
        foreach($answers as $k => $answer){
            $_SESSION['quizData']['questions'][$key]['answers'][] = array(
                'text' => $answer->content,
                'isTrue' => $answer->is_true,
            );

            $j = $k + 1;
            $_SESSION['formQuizStep2']['q_'.$question->id.'_reponse_'.$j] = $answer->content;
            $_SESSION['formQuizStep2']["q_".$question->id."_isTrue_".$j] = $answer->is_true;
        }

        $_SESSION['formQuizStep2']['q_'.$question->id.'_video'] = $question->url;

    }
    wp_redirect(home_url().'/creationquizetape1/');
}else{
    wp_redirect(home_url());
}
?>
