<?php
require('../class/module.class.php');
require('../class/module_slide.class.php');
require('../class/tag.class.php');
require('../class/quiz.class.php');


$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(false, true)){
    wp_redirect( home_url() );  exit;
}

//JSON encode
$module = new Module();
$module->selectById($_GET['id']);

echo json_encode($module->getInfos(get_current_user_id()));

?>
