<?php
define('WP_USE_THEMES', false);

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}



function campaignList(){
    global $wpdb;

    return $wpdb->get_results( "SELECT id AS Id, name AS Nom, start AS Début, end AS Fin FROM campaign");
}

function updateCampaign($id, $name, $dateStart, $dateEnd){
    global $wpdb;

    return $wpdb->update("campaign", array('name'=>$name, 'start'=> $dateStart, 'end'=> $dateEnd), array('id'=>$id));
}

function deleteCampaign($id){
    global $wpdb;

    return $wpdb->delete("campaign", array("id"=>$id));
}



$str_json = file_get_contents('php://input'); //($_POST doesn't work here)
$request = json_decode($str_json, true); // decoding received JSON to array

$id = $request['id'] ?? null;
$name = $request['name'] ?? null;
$dateStart = $request['dateStart'] ?? null;
$dateEnd = $request['dateEnd'] ?? null;

if(!empty($id) && empty($name) && empty($dateStart) && empty($dateEnd)){
    echo json_encode(deleteCampaign($id));
}elseif(!empty($id) && !empty($name) && !empty($dateStart) && !empty($dateEnd)){
    echo json_encode(updateCampaign($id, $name, $dateStart, $dateEnd));
}else{
    echo json_encode(campaignList());
}

// wp_redirect(home_url()."/nouvelle-campagne");
?>