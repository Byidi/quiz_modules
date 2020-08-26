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

/* SAVE TO BDD  */
$tag = new Tag();
$tag->selectByName($_SESSION['moduleData']['module']['theme']);

$newModule = new Module();
if(!empty($_SESSION['moduleData']['module']['id'])){
    $newModule->selectById($_SESSION['moduleData']['module']['id']);
}
//recuperation module
$newModule->setTitle($_SESSION['moduleData']['module']['title']);
$newModule->setTag($tag);
$newModule->setAuthor(get_current_user_id());
$newModule->setImgPath($_SESSION['moduleData']['module']['img']);
$newModule->setDescription($_SESSION['moduleData']['module']['description']);
$newModule->setStatus($_GET['status'] ?? 1); 
$newModule->save();

if(!empty($_SESSION['moduleData']['module']['id'])){
    $newModuleId = $newModule->getId();
}else{
    $newModuleId = $wpdb->insert_id;
        echo 'a-'.$newModuleId.'*<br/>';
}

$prevSlidesList = [];
if($_SESSION['moduleEdit'] === true){
    global $wpdb;
    $queryPrev = $wpdb->get_results("SELECT id FROM module_slide WHERE module_id = $newModuleId");
    foreach ($queryPrev as $prev) {
        $prevSlidesList[] = $prev->id;
    }
}

//recupération slides
foreach($_SESSION['moduleData']['pages'] as $m)
{
    $newSlide = new ModuleSlide();
    if($m['info']['id'] !== 0){
        $newSlide->selectById($m['info']['id']);
    }
    $newSlide->setModuleId($newModuleId);
    $newSlide->setContent($m['info']['content']);
    $newSlide->setTitle($m['info']['title']);
    $newSlide->setOrder($m['info']['order']);
    $newSlide->setImgPath($m['info']['img']);
    $newSlide->setUrl($m['info']['url']);
    $newSlide->save();
    if($m['info']['id'] !== 0){
        $newSlideId = $newSlide->getId();
        if(in_array($newSlideId, $prevSlidesList)){
            array_splice($prevSlidesList, array_search($newSlideId, $prevSlidesList), 1);
        }
    }else{
        $newSlideId = $wpdb->insert_id;
    }
}

foreach ($prevSlidesList as $p) {
    global $wpdb;
    $wpdb->delete('module_slide', array('id'=>$p));
}

unset($_SESSION['moduleData']);
unset($_SESSION['formModuleStep2']);
$_SESSION['moduleEdit'] = false;
wp_redirect( home_url().'/menu-module' );

?>
