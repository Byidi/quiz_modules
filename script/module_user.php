<?php

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(false, true)){
    wp_redirect( home_url() );  exit;
}

$str_json = file_get_contents('php://input'); //($_POST doesn't work here)
$response = json_decode($str_json, true); // decoding received JSON to array

$wpdb->delete(
    'module_progress',
    array(
        'user_id' => $_SESSION['userConnected'],
        'module_id' => $response['module_id'],
        'slide_id' => $response['slide_id'],
    )
);

$wpdb->insert(
    'module_progress',
    array(
        'user_id' => $_SESSION['userConnected'],
        'module_id' => $response['module_id'],
        'slide_id' => $response['slide_id'],
    )
);

?>
