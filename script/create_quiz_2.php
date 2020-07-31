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

$nbrQuestion = $_POST['nbrQuestion'];
$_SESSION['errorQuiz'] = "";
$_SESSION['formQuizStep2'] = $_POST;

if(!empty($_SESSION['quizData']) && $_SESSION['quiEdit'] === false){
    $_SESSION['quizData']['questions'] = null;
}

if($nbrQuestion >= 3){
    $questions = [];
    foreach ($_POST as $key => $value) {
        preg_match('/^question_(n)?(\d+)$/', $key, $matches);
        if(count($matches) > 2){
            $id = count($matches) === 2 ? $matches[1] : $matches[1].$matches[2];
            $result = processQuestion($id, $matches[1] === 'n');
            if($result['type'] === 'error'){
                $_SESSION['errorQuiz'] = $result['content'];
            }else{
                $questions[] = $result['content'];
            }
        }
    }
    $_SESSION['quizData']['questions'] = $questions;
}else{
    $_SESSION['errorQuiz'] = "Veuillez créer au moins 3 questions.";
}

function processQuestion($id, $isNew){
    $question['info']['id'] = !$isNew ? $id : 0;
    $result = processText($id, $isNew);
    if($result['type'] === 'error'){
        return $result;
    }
    $question['info']['text'] = $result['content'];

    if(!empty($_POST['q_'.$id.'_video'])){
        $question['info']['video'] = $_POST['q_'.$id.'_video'];
    }else{
        $result = processImg($id, $isNew);
        if($result['type'] === 'error'){
            return $result;
        }
        $question['info']['img'] = $result['content'];
    }

    $result = processAnswers($id, $isNew);
    if($result['type'] === 'error'){
        return $result;
    }
    $question['answers'] = $result['content'];


    return [
        'type' => 'success',
        'content' => $question,
    ];
}

function processText($id, $isNew){
    if(!empty($_POST['question_'.$id])){
        return [
            'type' => 'success',
            'content' => $_POST['question_'.$id],
        ];
    }else{
        return [
            'type' => 'error',
            'content' => 'Veuillez remplir l\'énoncé des questions.',
        ];
    }
}

function processImg($id, $isNew){
    if($_FILES['q_'.$id.'_img']['error'] != UPLOAD_ERR_NO_FILE && !empty($_FILES['q_'.$id.'_img'])){
        $dir = md5($_SESSION['quizData']['quiz']['title']);
        $path = "../img/quizs/".$dir."/questions";
        $pathClean = $dir."/questions";

        if(!is_dir($path))
        {
            mkdir($path, 0775, true);
        }

        $content_dir =  get_template_directory()."/img/quizs/".$dir."/questions/";
        $tmp_file = $_FILES['q_'.$id.'_img']['tmp_name'];

        if(!is_uploaded_file($tmp_file))
        {
            return [
                'type' => 'error',
                'content' => 'Un des fichiers est introuvable',
            ];
        }

        $type_file = $_FILES['q_'.$id.'_img']['type'];

        if( !strpos($type_file, 'jpg') && !strpos($type_file, 'jpeg') && !strpos($type_file, 'png'))
        {
            return [
                'type' => 'error',
                'content' => 'Le format d\'un des fichiers n\'est pas pris en charge.',
            ];
        }

        $name_file = md5($tmp_file).'.'.preg_replace("#image\/#","",$type_file);
        $uploading = wp_upload_bits($name_file, null, file_get_contents($_FILES['q_'.$id.'_img']["tmp_name"]));
        if( $uploading['error'] !== false )
        {
            return [
                'type' => 'error',
                'content' => 'Impossible de copier le fichier.',
            ];
        }

        $img = $uploading['url'];

        return [
            'type' => 'success',
            'content' => $img,
        ];
    }else{
        if(!$isNew){
            global $wpdb;

            return [
                'type' => 'success',
                'content' => $wpdb->get_var("SELECT img_path FROM question WHERE id='".$id."'"),
            ];
        }
    }
}

function processAnswers($id, $isNew){
    $nbrAnswer = 0;
    $nbrTrue = 0;
    $answers= array();
    for( $r=1; $r <= 4; $r++){
        if(!empty($_POST['q_'.$id.'_reponse_'.$r])){
            $nbrAnswer += 1;
            if($_POST['q_'.$id.'_isTrue_'.$r] == "true"){
                $nbrTrue += 1;
            }

            $answers[] = array(
                'text' => $_POST['q_'.$id.'_reponse_'.$r],
                'isTrue' => $_POST['q_'.$id.'_isTrue_'.$r],
            );
        }
    }

    if($nbrTrue < 1){
        return [
            'type' => 'error',
            'content' => "Merci de renseigner au moins une bonne réponse par question.",
        ];
    }
    if($nbrAnswer < 2){
        return [
            'type' => 'error',
            'content' => "Merci de renseigner au moins deux réponses par question.",
        ];
    }

    return [
        'type' => 'success',
        'content' => $answers,
    ];
}

if($_SESSION['errorQuiz'] == ""){
    if(isset($_POST['brouillon'])){
        wp_redirect(get_template_directory_uri()."/app/create_quiz_3.php?status=0" );
    }else{
        wp_redirect( home_url().'/creationquizetape3' );
    }
}else{
    wp_redirect( home_url().'/creationquizetape2' );
}

?>
