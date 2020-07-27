<?php

define('WP_USE_THEMES', false);
require('../class/tag.class.php');


$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}


$_SESSION['returnTag'] = [
    "type" => "error",
    "message" => "Veuillez remplir le champ.",
];

//ajout tag 

 if(!empty($_POST['tag'])){
     $tag = new Tag();
     $tag->setName($_POST['tag']);
     $tag->save();

     $_SESSION['returnTag'] = [
        "type" => "success",
        "message" => "Le tag a bien été créé.",
    ];
 }

 wp_redirect(home_url()."/ajouter-tag");

?>