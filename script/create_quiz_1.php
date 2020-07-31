<?php
define('WP_USE_THEMES', false);
require('../class/answer.class.php');
require('../class/question.class.php');
require('../class/quiz.class.php');
require('../class/quiz_score.class.php');

//J'initialise la variable session
if(!empty($_SESSION['quizData']) && $_SESSION['quizEdit'] !== true){
    unset($_SESSION['quizData']);
}

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}

//1st Step creation de quiz / thème + image
$error_quiz = "";
if(!empty($_POST['title']) && !empty($_POST['theme']))
{
    $img_path = $_SESSION['quizData']['quiz']['img'];
    if((!isset($_FILES['img_quiz']) || $_FILES['img_quiz']['error'] == UPLOAD_ERR_NO_FILE) && $_SESSION['quizData']['quiz']['img'] === "")
    {
        $error_quiz = "Veuillez selectionner une image en format jpg ou png.";
    }else if($_FILES['img_quiz']['type'] !== ""){
        $dir = md5($_POST['title']);
        mkdir("../img/quizs/".$dir, 0755, true);
        $content_dir =  get_template_directory()."/img/quizs/".$dir."/";
        $tmp_file = $_FILES['img_quiz']['tmp_name'];

        if(!is_uploaded_file($tmp_file))
        {
            $error_quiz="Le fichier est introuvable";
        }
        $type_file = $_FILES['img_quiz']['type'];

        if( !strpos($type_file, 'jpg') && !strpos($type_file, 'jpeg') && !strpos($type_file, 'png'))
        {
            $error_quiz = "Le format du fichier n'est pas pris en charge";
        }
            // on copie le fichier dans le dossier de destination
        $name_file = md5($_POST['title']).'.'.preg_replace("#image\/#","",$type_file);
        $img = $name_file;

        $uploading = wp_upload_bits($name_file, null, file_get_contents($_FILES["img_quiz"]["tmp_name"]));

        if( $uploading['error'] !== false )
        {
            $error_module = "Impossible de copier le fichier $name_file dans $content_dir";
        }
        $img = $uploading['url'];
    }
    //enregistrement des POST en SESSION pour passer à la seconde étape sans enregistrer en base de données en cas d'abandon
    $title = htmlspecialchars($_POST['title']);
    $description = $_POST['description'];
    $theme = $_POST['theme'];
////

    if(isset($_POST['moduleId'])){
        $moduleRelated = $_POST['moduleId'];
    }else{
        $moduleRelated = null;
    }
 ////

    $_SESSION['quizData']['quiz']['title'] = $title;
    $_SESSION['quizData']['quiz']['theme'] = $theme;
    $_SESSION['quizData']['quiz']['img'] = $img_path;
    $_SESSION['quizData']['quiz']['description'] = $description;
    $_SESSION['quizData']['quiz']['module_id']  = $moduleRelated;

}else{
    $error_quiz = "Veuillez remplir tous les champs.";
}



if($error_quiz !== ""){
    $_SESSION["errorQuiz"] = $error_quiz;
    wp_redirect( home_url().'/creationquizetape1' );
}else{
    $_SESSION["quizOk"] = $quiz_ok;
    wp_redirect( home_url().'/creationquizetape2' );
}

?>
