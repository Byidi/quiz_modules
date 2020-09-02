<?php

define('WP_USE_THEMES', false);
require('../class/quiz.class.php');
require('../class/quiz_score.class.php');
require('../class/module.class.php');


$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}


function moduleStat($site){
    global $wpdb;

    $userTable = $wpdb->prefix.'users';
    $metaTable = $wpdb->prefix.'usermeta';

    //Pour chaque module terminé
    $module = $wpdb->get_results( "SELECT id, title FROM module");
    $stats=[];
    foreach($module as $m){

        $moduleId = $m->id;
        $nbUsers = 0;
        if($site === null){
            $nbUsers = count_users()['total_users'];
            $sql = "SELECT count(module_id) FROM module_finish WHERE module_id = '$moduleId'";
        }else{
            $nbUsers = $wpdb->get_var("SELECT count(meta_value) FROM ".$metaTable." WHERE meta_key = 'location' AND meta_value = '$site'");
            $sql="SELECT 
                count(module_finish.module_id) 
            FROM 
                module_finish
                LEFT JOIN ".$userTable." ON ".$userTable.".ID = module_finish.user_id
                LEFT JOIN ".$metaTable." ON ".$metaTable.".user_id = ".$userTable.".ID
                    AND ".$metaTable.".meta_key = 'location'
            WHERE 
                module_id = '$moduleId'
                    AND ".$metaTable.".meta_value = '$site'";
        }
        $nbrDone = $wpdb->get_var($sql);
        $pourcent = ((int)$nbUsers ===  0) ? 0 : (round(((int)$nbrDone * 100)/(int)$nbUsers));
        $stats[] = [
            "pourcentage" => $pourcent,
            "id" => $m->id,
            "titre" => $m->title,
        ];
    }
    return $stats;
}

function quizStat($site = null){
    global $wpdb;

    $userTable = $wpdb->prefix.'users';
    $metaTable = $wpdb->prefix.'usermeta';

    //Pour chaque quiz terminé 
    $quiz = $wpdb->get_results( "SELECT id, name FROM quiz");
    $stats = [];
    foreach($quiz as $q){
        $quizId = $q->id;
        if($site === null){
            //nombre d'utilisateur
            $nbUsers = count_users()['total_users'];
            $sql = "SELECT count(quiz_id) FROM quiz_score WHERE quiz_id = '$quizId'";
        }else{
            $nbUsers = $wpdb->get_var("SELECT count(meta_value) FROM ".$metaTable." WHERE meta_key = 'location' AND meta_value = '$site'");
            $sql="SELECT 
                count(quiz_score.quiz_id) 
            FROM 
                quiz_score
                LEFT JOIN ".$userTable." ON ".$userTable.".ID = quiz_score.user_id
                LEFT JOIN ".$metaTable." ON ".$metaTable.".user_id = wp_users.ID
                    AND ".$metaTable.".meta_key = 'location'
            WHERE 
                quiz_id = '$quizId'
                    AND wp_usermeta.meta_value = '$site'";
        }
        $nbrDone = $wpdb->get_var($sql);
        $pourcent = ((int)$nbUsers ===  0) ? 0 : ( round ( ( (int)$nbrDone * 100) / (int)$nbUsers) );
        $stats[] = [
            "pourcentage" => $pourcent,
            "id" => $q->id,
            "titre" => $q->name,
        ];
    }
    return $stats;
}


$str_json = file_get_contents('php://input'); //($_POST doesn't work here)
$request = json_decode($str_json, true); // decoding received JSON to array
$site = $request['site'] ?? null;
// $site = $_GET['site'];
echo htmlspecialchars_decode(json_encode([  
    "modules" => moduleStat($site),
    "quizs" => quizStat($site),
]));

?>