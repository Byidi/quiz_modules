<?php
define('WP_USE_THEMES', false);
require('../class/module.class.php');
require('../class/module_slide.class.php');
require('../class/tag.class.php');

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}


//J'initialise la variable session
if(!empty($_SESSION['moduleData'] && $_SESSION['moduleEdit'] !== true)){
    unset($_SESSION['moduleData']);
}

$error_module = '';
if(!empty($_POST['title']) && !empty($_POST['theme']))
{
    $dir = md5($_POST['title']);
    $img =  $_SESSION['moduleData']['module']['img'] === '' ? '' :  $_SESSION['moduleData']['module']['img'];
    if((!isset($_FILES['img_module']) || $_FILES['img_module']['error'] == UPLOAD_ERR_NO_FILE) && $_SESSION['moduleData']['module']['img'] === '')
    {
        $error_module = "Veuillez selectionner une image en format jpg ou png.";

    }else if($_FILES['img_module']['type'] !== ''){
        $dir = md5($_POST['title']);
        mkdir("../img/modules/".$dir, 0755, true);
        $content_dir =  get_template_directory()."/img/modules/".$dir."/";
        $tmp_file = $_FILES['img_module']['tmp_name'];

        if(!is_uploaded_file($tmp_file))
        {
            $error_module="Le fichier est introuvable";
        }

        $type_file = $_FILES['img_module']['type'];

        if( !strpos($type_file, 'jpg') && !strpos($type_file, 'jpeg') && !strpos($type_file, 'png'))
        {
            $error_module = "Le format du fichier n'est pas pris en charge";
        }
            // on copie le fichier dans le dossier de destination
        $name_file = md5($_POST['title']).'.'.preg_replace("#image\/#","",$type_file);
        $img = $name_file;

        if( !move_uploaded_file($tmp_file, $content_dir . $name_file) )
        {
            $error_module = "Impossible de copier le fichier $name_file dans $content_dir";
        }
        $img = $dir.'/'.$img;
    }
     //enregistrement des POST en SESSION pour passer à la seconde étape sans enregistrer en base de données en cas d'abandon
     $title = htmlspecialchars($_POST['title']);
     $theme = $_POST['theme'];
     $description = $_POST['description'];

    $_SESSION['moduleData']['module']['title'] = $title;
    $_SESSION['moduleData']['module']['theme'] = $theme;
    $_SESSION['moduleData']['module']['img'] = $img;
    $_SESSION['moduleData']['module']['description'] = $description;
}else{
    $error_module = "Veuillez remplir tous les champs.";
}

if($error_module !== ""){
    $_SESSION["errorModule"] = $error_quiz;
    wp_redirect( home_url().'/creationmoduleetape1' );
}else{
    $_SESSION["moduleOk"] = $module_ok;
    wp_redirect( home_url().'/creationmoduleetape2' );
}

?>
