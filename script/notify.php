<?php

define('WP_USE_THEMES', false);

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}

function checkNotify(){
    global $wpdb;
    $checkDate = $wpdb->get_results("SELECT * from notify_date WHERE date > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    var_dump($checkDate);
    if(count($checkDate) != 0){
        $_SESSION['notify'] = "Des mails ont déjà été envoyés aujourd'hui.";
        // wp_redirect(home_url()."/statistiques");
        exit;
    }
}


function notify(){
    global $wpdb;
    $users = $wpdb->get_results( "SELECT * FROM wp_users");
    $quizs = $wpdb->get_results("SELECT id, name FROM quiz");
    $modules = $wpdb->get_results("SELECT id, title FROM module");

    foreach($users as $user){
        $userId = $user->ID;

        $quizUndone = $wpdb->get_results("SELECT id, name FROM quiz WHERE id NOT IN (SELECT quiz_id FROM quiz_score WHERE user_id = '.$userId.')");
        $moduleUndone = $wpdb->get_results("SELECT id, title FROM module WHERE id NOT IN (SELECT module_id FROM module_finish WHERE user_id = '.$userId.')");

        if(count($quizUndone) === 0 && count($moduleUndone) === 0 ){
            continue;
        }
     
        $to = get_userdata($userId)->get("user_email");
        $subject = "Quizs et modules à faire";

        $message = "Merci de bien vouloir effectuer ce(s) "; 
        
        if(count($quizUndone) === 0){
            $message.="module(s)";
        }elseif(count($moduleUndone) === 0){
            $message.="quiz(s)";
        }else{
            $message.="quiz(s) et ce(s) module(s)";
        }
        
        $message.=" sur le portail Soda CyberDéfense. \n ";
        if(count($moduleUndone) != 0){
            $message.= "\n Modules : ";
        }
        foreach($moduleUndone as $module){
            $message.="\n \t- ".htmlspecialchars_decode($module->title);
        }
        if(count($quizUndone) != 0){
            $message.= "\n Quiz : ";
        }
        foreach($quizUndone as $quiz){
            $message.="\n \t- ".htmlspecialchars_decode($quiz->name);
        }

        $mail = wp_mail($to, $subject, $message);

    }
    $wpdb->insert("notify_date", ["date"=>(new DateTime())->format('Y-m-d H:i:s'), "author_id"=> get_current_user_id()]);
    $_SESSION['notify'] = "Les mails ont été envoyés.";
    // wp_redirect(home_url()."/statistiques");
}

checkNotify();
notify();



?>