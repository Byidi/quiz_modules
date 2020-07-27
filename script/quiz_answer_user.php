<?php

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(false, true)){
    wp_redirect( home_url() );  exit;
}

$str_json = file_get_contents('php://input'); //($_POST doesn't work here)
$response = json_decode($str_json, true); // decoding received JSON to array
$wpdb->insert(
    'quiz_progress',
    array(
        'id_quiz' => $response['id_quiz'],
        'id_user' => $_SESSION['userConnected'],
        'id_question' => $response['questions'],
        'id_answer' => json_encode($response['answers']),
        'time' => $response['time'],
    )
);

?>
