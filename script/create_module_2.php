<?php
define('WP_USE_THEMES', false);
require('../class/module.class.php');
require('../class/module_slide.class.php');
require('../class/tag.class.php');


$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');


function processPage($id, $isNew){
    $page['info']['id'] = !$isNew ? $id : 0;
    $result = processTitle($id, $isNew);
    if($result['type'] === 'error'){
        return $result;
    }
    $page['info']['title'] = $result['content'];

    $result = processText($id, $isNew);
    if($result['type'] === 'error'){
        return $result;
    }
    $page['info']['content'] = $result['content'];

    if(!empty($_POST['content_'.$id.'_video'])){
        $page['info']['url'] = $_POST['content_'.$id.'_video'];
    }else{
        $result = processImg($id, $isNew);
        if($result['type'] === 'error'){
            return $result;
        }
        $page['info']['img'] = $result['content'];
    }

    $page['info']['order'] = 0;

    return [
        'type' => 'success',
        'content' => $page,
    ];
}

function processTitle($id, $isNew){
    if(!empty($_POST['content_'.$id.'_title'])){
        return [
            'type' => 'success',
            'content' => $_POST['content_'.$id.'_title'],
        ];
    }else{
        return [
            'type' => 'error',
            'content' => 'Veuillez remplir le titre des pages.',
        ];
    }
}

function processText($id, $isNew){
    $content = "";
    if(!empty($_POST['content_'.$id])){
        $content = $_POST['content_'.$id];
    }
        return [
            'type' => 'success',
            'content' => $content,
        ];
    // }else{
    //     return [
    //         'type' => 'error',
    //         'content' => 'Veuillez remplir le contenu des pages.',
    //     ];
    // }
}

function processImg($id, $isNew){
    if( $_FILES['content_'.$id.'_img']['error'] != UPLOAD_ERR_NO_FILE && !empty($_FILES['content_'.$id.'_img']) ){
        $dir = md5($_SESSION['moduleData']['module']['title']);
        $path = "../img/modules/".$dir."/pages";
        $pathClean = $dir."/pages";;

        if(!is_dir($path))
        {
            mkdir($path, 0775, true);
        }

        $content_dir =  get_template_directory()."/img/modules/".$dir."/pages/";
        $tmp_file = $_FILES['content_'.$id.'_img']['tmp_name'];

        if(!is_uploaded_file($tmp_file))
        {
            return [
                'type' => 'error',
                'content' => 'Un des fichiers est introuvable',
            ];
        }

        $type_file = $_FILES['content_'.$id.'_img']['type'];

        if( !strpos($type_file, 'jpg') && !strpos($type_file, 'jpeg') && !strpos($type_file, 'png'))
        {
            return [
                'type' => 'error',
                'content' => 'Le format d\'un des fichiers n\'est pas pris en charge.',
            ];
        }

        $name_file = md5($tmp_file).'.'.preg_replace("#image\/#","",$type_file);
        $uploading = wp_upload_bits($_FILES['content_'.$id.'_img']['name'], null, file_get_contents($_FILES['content_'.$id.'_img']["tmp_name"]));
        if( $uploading['error'] !== false )
        {
            return [
                'type' => 'error',
                'content' => 'Impossible de copier le fichier.',
            ];
        }

        $img = $name_file;

        return [
            'type' => 'success',
            'content' => $uploading['url'],
        ];
    }else{
        if(!$isNew){
            global $wpdb;

            return [
                'type' => 'success',
                'content' => $wpdb->get_var("SELECT img_path FROM module_slide WHERE id='".$id."'"),
            ];
        }
    }
}

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}

$nbrPage = $_POST['nbrPage'];
$_SESSION['errorModule'] = "";
$_SESSION['formModuleStep2'] = $_POST;

if(!empty($_SESSION['moduleData']) && $_SESSION['moduleEdit'] !== true){
    $_SESSION['moduleData']['module']['pages'] = null;
}

if($nbrPage >= 1){
    $pages = [];

    foreach ($_POST as $key => $value) {
        preg_match('/^content_(n)?(\d+)_title$/', $key, $matches);
        if(count($matches) > 2){
            $id = count($matches) === 2 ? $matches[1] : $matches[1].$matches[2];
            $result = processPage($id, $matches[1] === 'n');
            if($result['type'] === 'error'){
                $_SESSION['errorModule'] = $result['content'];
            }else{
                $page = $result['content'];
                $page['info']['order'] = count($pages);
                $pages[] = $page;
            }
        }
    }
    $_SESSION['moduleData']['pages'] = $pages;
}else{
    $_SESSION['errorQuiz'] = "Veuillez créer au moins 1 page.";
}

if($_SESSION['errorModule'] == "")
{
    if(isset($_POST['brouillon'])){
        wp_redirect(WP_PLUGIN_URL."/quiz_modules/script/create_module_3.php?status=0" );
    }else{
        wp_redirect( home_url().'/creationmoduleetape3' );
    }
}else{
    wp_redirect( home_url().'/creationmoduleetape2' );
}

?>