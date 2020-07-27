<?php

header('content-type:application/json');

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');


if(!checkAuthorized(true, true)){
    wp_redirect( home_url() );  exit;
}

function getQuizGeneralClassement($idQuiz){
    global $wpdb;

    $sql = "
    SELECT 
        wp_users.display_name AS Joueur,  
        wp_usermeta.meta_value AS Site,
        quiz_score.score AS Score, 
        quiz_score.time AS Temps
    FROM 
        quiz_score 
        LEFT JOIN wp_users ON wp_users.ID = quiz_score.user_id 
        LEFT JOIN wp_usermeta ON wp_usermeta.user_id = wp_users.ID AND wp_usermeta.meta_key = 'location' 
    WHERE 
        quiz_id = ".$idQuiz."
    ORDER BY 
        quiz_score.score DESC, 
        quiz_score.time ASC";

    return $wpdb->get_results($sql);
}

function getQuizSiteClassement($idQuiz){
    global $wpdb;

    $sql = "
    SELECT 
        wp_usermeta.meta_value AS Site, 
        avg(quiz_score.score) AS Moyenne,  
        sum(quiz_score.time) AS Temps, 
        count(quiz_score.id) AS Nombre 
    FROM 
        quiz_score 
        LEFT JOIN wp_users ON wp_users.ID = quiz_score.user_id 
        LEFT JOIN wp_usermeta ON wp_usermeta.user_id = wp_users.ID 
            AND wp_usermeta.meta_key = 'location'
    WHERE
        quiz_score.quiz_id='".$idQuiz."'
    GROUP BY
        wp_usermeta.meta_value 
    ORDER BY
        avg(quiz_score.score) DESC, 
        sum(quiz_score.time) ASC, 
        count(quiz_score.id) DESC
    ";

    return $wpdb->get_results($sql);
}

function getTagGeneralClassement($idTag){
    global $wpdb;

    $sql = "
    SELECT 
        wp_users.display_name AS Joueur, 
        wp_usermeta.meta_value AS Site, 
        avg(quiz_score.score) AS Moyenne,  
        sum(quiz_score.time) AS Temps, 
        count(quiz_score.id) AS Nombre 
    FROM 
        quiz_score 
        LEFT JOIN wp_users ON wp_users.ID = quiz_score.user_id 
        LEFT JOIN wp_usermeta ON wp_usermeta.user_id = wp_users.ID AND wp_usermeta.meta_key = 'location' 
        LEFT JOIN quiz ON quiz.id = quiz_score.quiz_id
        LEFT JOIN tag ON quiz.tag_id = tag.id
    WHERE 
        quiz.tag_id = ".$idTag."
    GROUP BY
        wp_users.ID
    ORDER BY
        avg(quiz_score.score) DESC, 
        sum(quiz_score.time) ASC, 
        count(quiz_score.id) DESC
    ";

    return $wpdb->get_results($sql);
}

function getTagSiteClassement($idTag){
    global $wpdb;

    $sql = "
    SELECT 
        wp_usermeta.meta_value AS Site, 
        avg(quiz_score.score) AS Moyenne,  
        sum(quiz_score.time) AS Temps, 
        count(quiz_score.id) AS Nombre 
    FROM 
        quiz_score 
        LEFT JOIN wp_users ON wp_users.ID = quiz_score.user_id 
        LEFT JOIN wp_usermeta ON wp_usermeta.user_id = wp_users.ID AND wp_usermeta.meta_key = 'location' 
        LEFT JOIN quiz ON quiz.id = quiz_score.quiz_id
        LEFT JOIN tag ON quiz.tag_id = tag.id
    WHERE 
        tag_id = ".$idTag."
    GROUP BY
        wp_usermeta.meta_value 
    ORDER BY
        avg(quiz_score.score) DESC, 
        sum(quiz_score.time) ASC, 
        count(quiz_score.id) DESC
    ";

    return $wpdb->get_results($sql);
}

$userId = $_SESSION['userConnected'];
$ville = $wpdb->get_var("SELECT meta_value FROM wp_usermeta WHERE meta_key='location' AND user_id='".$userId."'");

$str_json = file_get_contents('php://input'); //($_POST doesn't work here)
$request = json_decode($str_json, true); // decoding received JSON to array

if($request == null){
    global $wpdb;
    $liste['quizs'] = $wpdb->get_results("SELECT id, name from quiz");
    $liste['tags'] = $wpdb->get_results("SELECT id, name from tag");

    echo json_encode($liste);
}else{
    if($request['type'] == "global"){
        if($request['filtre'] == "general"){
            echo json_encode(getUserClassement());
        }else{
            echo json_encode(getCityClassement());
        }
    }
    
    if($request['type'] == "quiz"){
        $idQuiz = $request['id'];
        if($request['filtre'] == "general"){
            echo json_encode(getQuizGeneralClassement($idQuiz));
        }else{
            echo json_encode(getQuizSiteClassement($idQuiz));
        }
    }
    
    if($request['type'] == "tag"){
        $idTag = $request['id'];
        if($request['filtre'] == "general"){
            echo json_encode(getTagGeneralClassement($idTag));
        }else{
            echo json_encode(getTagSiteClassement($idTag));
        }
    }
}


//dashboard admin

// $response['classementVilleGeneral'] = getCityClassement();

// $response['classementUser'] = getUserClassement();

// $response['classementUserByQuiz'] = getUserByQuizClassement();

// $response['classementUserByTag'] = getUserByTagClassement();
