<?php 
$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);

include($path.'wp-load.php');

if(!checkAuthorized(true)){

    wp_redirect( home_url() );  exit;

}

$id = $_GET["id"];

$table = "tag";

// $query = $wpdb->get_var("SELECT count(quiz.id) as nbUse FROM `tag`LEFT JOIN quiz ON quiz.tag_id = tag.id LEFT JOIN module ON module.tag_id = tag.id  WHERE tag.id =".$id." GROUP BY tag.name");

$query = $wpdb->get_row("SELECT tag.id, tag.name, (select count(id) from quiz where quiz.tag_id=tag.id) as quiz, (select count(id) from module where module.tag_id=tag.id) as module  from tag WHERE tag.id = ".$id." ");

if($query->quiz == 0 && $query->module == 0){

    $wpdb->delete($table, array('id' => $id));

}

wp_redirect( home_url()."/ajouter-tag" ); 

?>

