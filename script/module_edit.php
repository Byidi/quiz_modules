<?php
define('WP_USE_THEMES', false);
$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

require('../class/module.class.php');
require('../class/tag.class.php');

//http://localhost/SODA/wp-content/themes/SODA-Portail-Cyber-Securite/app/module_edit.php?id=XX
if(isset($_GET['id'])){
    global $wpdb;
    $moduleId = $_GET['id'];
    unset($_SESSION['moduleData']);
    unset($_SESSION['formModuleStep2']);
    $_SESSION['moduleEdit'] = true;

    $module = new Module();
    $module->selectById($moduleId);

    $_SESSION['moduleData']['module'] = array (
        'id' => $module->getId(),
        'title'=> $module->getTitle(),
        'theme'=> $module->getTag()->getId(),
        'img'=> $module->getImgPath(),
        'description'=> $module->getDescription(),
    );

    $slides = $wpdb->get_results("SELECT * FROM module_slide WHERE module_id=".$moduleId." ORDER BY `order`");
    $_SESSION['formModuleStep2']['nbrPage'] = count($slides);
    foreach($slides as $key => $slide){
        $page['info']['id'] = $slide->id;
        $page['info']['title'] = $slide->title;
        $page['info']['content'] = $slide->content;
        $page['info']['url'] = $slide->url;
        $page['info']['img'] = $slide->img_path;
        $page['info']['order'] = $slide->order;

        $i = $key + 1;
        $_SESSION['formModuleStep2']['content_'.$slide->id.'_title'] = $slide->title;
        $_SESSION['formModuleStep2']['content_'.$slide->id] = $slide->content;
        $_SESSION['formModuleStep2']['content_'.$slide->id.'_video'] = $slide->url;

        $_SESSION['moduleData']['pages'][] = $page;
    }


    wp_redirect(home_url().'/creationmoduleetape1/');
}else{
    wp_redirect(home_url());
}
?>
