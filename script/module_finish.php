<?php

require('../class/module.class.php');

require('../class/tag.class.php');

require('../class/module_slide.class.php');



$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);

include($path.'wp-load.php');



if(!checkAuthorized(false, true)){

    wp_redirect( home_url() );  exit;

}



$str_json = file_get_contents('php://input'); //($_POST doesn't work here)

$response = json_decode($str_json, true); // decoding received JSON to array



$module = new Module();

$module->selectById($response['module_id']);

$userId = get_current_user_id();



$wpdb->delete( 'module_progress' ,

    array(

        'user_id' => $userId,

        'module_id' => $module->getId(),

    )

);



$wpdb->delete( 'module_finish' ,

    array(

        'user_id' => $userId,

        'module_id' => $module->getId(),

    )

);



$wpdb->insert("module_finish", array(

    "user_id" => $userId,

    "module_id" => $module->getId(),

));





?>