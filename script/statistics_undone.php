<?php

define('WP_USE_THEMES', false);



$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}


function moduleStat($moduleId){
    global $wpdb;
   
    return  $wpdb->get_results( "SELECT wp_users.ID AS ID, wp_users.display_name AS Utilisateur, wp_usermeta.meta_value AS Site FROM wp_users LEFT JOIN wp_usermeta ON wp_usermeta.user_id = wp_users.ID AND wp_usermeta.meta_key = 'location' WHERE wp_users.ID NOT IN (SELECT user_id FROM module_finish WHERE module_id ='".$moduleId."')");
}

function quizStat($quizId){
    global $wpdb;

    return  $wpdb->get_results( "SELECT wp_users.ID AS ID, wp_users.display_name AS Utilisateur, wp_usermeta.meta_value AS Site FROM wp_users LEFT JOIN wp_usermeta ON wp_usermeta.user_id = wp_users.ID AND wp_usermeta.meta_key = 'location' WHERE wp_users.ID NOT IN (SELECT user_id FROM quiz_score WHERE '".$quizId."' = quiz_id)");
}

$str_json = file_get_contents('php://input'); //($_POST doesn't work here)
$request = json_decode($str_json, true); // decoding received JSON to array
$type = $request['type'];
$id = $request['id'];

if($type === "Module"){
    echo json_encode(moduleStat($id));
}else{
    echo json_encode(quizStat($id));
}
   



?>