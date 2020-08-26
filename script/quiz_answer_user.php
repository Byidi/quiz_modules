<?php

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(false, true)){
    wp_redirect( home_url() );  exit;
}

$str_json = file_get_contents('php://input'); //($_POST doesn't work here)
$response = json_decode($str_json, true); // decoding received JSON to array
$id_user = get_current_user_id();
$query_result = $wpdb->insert(
    'quiz_progress',
    array(
        'id_quiz' => $response['id_quiz'],
        'id_user' => $id_user,
        'id_question' => $response['questions'],
        'id_answer' => json_encode($response['answers']),
        'time' => $response['time'],
    )
);

echo json_encode($query_result);

?>
