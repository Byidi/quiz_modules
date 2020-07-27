<?php 

header('content-type:application/json');



require('../class/tag.class.php');

require('../class/module.class.php');

require('../class/module_slide.class.php');



$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);

include($path.'wp-load.php');



if(!checkAuthorized(true)){

    wp_redirect( home_url() );  exit;

}





$id = $_GET["idModule"];



$table = "module";





$wpdb->delete($table, array('id' => $id));



//delete slides 

$wpdb->delete('module_slide', array('module_id' => $id));



//delete module progress

$wpdb->delete('module_progress', array('module_id' => $id));



//delete module finish

$wpdb->delete('module_finish', array('module_id' => $id));


//delete module_quiz

$wpdb->delete('module_quiz', array('id_module' => $id));




?>

