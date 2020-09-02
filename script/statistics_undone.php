<?php

define('WP_USE_THEMES', false);



$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}


function moduleStat($moduleId){
    global $wpdb;

    $userTable = $wpdb->prefix.'users';
    $metaTable = $wpdb->prefix.'usermeta';
   
    return  $wpdb->get_results( "SELECT ".$userTable.".ID AS ID, ".$userTable.".qm_display_name AS Utilisateur, ".$metaTable.".meta_value AS Site FROM ".$userTable." LEFT JOIN ".$metaTable." ON ".$metaTable.".user_id = ".$userTable.".ID AND ".$metaTable.".meta_key = 'location' WHERE ".$userTable.".ID NOT IN (SELECT user_id FROM module_finish WHERE module_id ='".$moduleId."')");
}

function quizStat($quizId){
    global $wpdb;

    $userTable = $wpdb->prefix.'users';
    $metaTable = $wpdb->prefix.'usermeta';

    return  $wpdb->get_results( "SELECT ".$userTable.".ID AS ID, ".$userTable.".qm_display_name AS Utilisateur, ".$metaTable.".meta_value AS Site FROM ".$userTable." LEFT JOIN ".$metaTable." ON ".$metaTable.".user_id = ".$userTable.".ID AND ".$metaTable.".meta_key = 'location' WHERE ".$userTable.".ID NOT IN (SELECT user_id FROM quiz_score WHERE '".$quizId."' = quiz_id)");
}

$str_json = file_get_contents('php://input'); //($_POST doesn't work here)
$request = json_decode($str_json, true); // decoding received JSON to array
$type = $request['type'];
$id = $request['id'];

if($type === "Module"){
    echo json_encode(moduleStat($id));
}else{
    echo json_encode(quizStat($id));
}
   



?>