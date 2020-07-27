<?php
define('WP_USE_THEMES', false);

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}

function createCampaign($post){
    if(empty($post['name'])){
        $_SESSION['campaignError'] = "Veuillez saisir un nom de campagne.";
        return 0;
    }
    if(empty($post['dateStart'])){
        $_SESSION['campaignError'] = "Veuillez saisir une date de début.";
        return 0;
    }
    if(empty($post['dateEnd'])){
        $_SESSION['campaignError'] = "Veuillez saisir une date de fin.";
        return 0;
    }
   
    global $wpdb;

    $name = $post['name'];
    $dateStart = $post['dateStart'];
    $dateEnd = $post['dateEnd'];

    if($dateStart > $dateEnd){
        $_SESSION['campaignError'] = "La date de début doit être inférieure à la date de fin.";
        return 0;
    }
    
    $wpdb->insert("campaign", array('name'=>$name, 'start'=> $dateStart, 'end'=> $dateEnd));
    $_SESSION['campaignSuccess'] = "La campagne a bien été créée.";
}

$post = $_POST;
createCampaign($post);
wp_redirect(home_url()."/nouvelle-campagne");
?>