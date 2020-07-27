<?php
define('WP_USE_THEMES', false);

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');

if(!checkAuthorized(true)){
    wp_redirect( home_url() );  exit;
}



//Récupérer les created at etc des quizs et des modules
function quizStatsByCampaign($campaignStart, $campaignEnd, $site, $nbQuiz, $nbUsers){
    global $wpdb;
    $quizInfo = $wpdb->get_row("
        SELECT 
            count(quiz_score.id) AS nb, AVG(quiz_score.score) AS moyenne, AVG(quiz_score.time) AS temps
        FROM 
            quiz_score 
        LEFT JOIN wp_usermeta ON quiz_score.user_id = wp_usermeta.user_id 
                AND wp_usermeta.meta_key = 'location' 
        WHERE 
            wp_usermeta.meta_value = '$site' 
        AND 
            quiz_score.created_at BETWEEN '$campaignStart' AND '$campaignEnd'"
    );

    $nbQuizDone = $quizInfo->nb;
    $moyenne = $quizInfo->moyenne ?? 0;
    $temps = $quizInfo->temps ?? 0;

    $pourcent = ((int)$nbUsers === 0 || (int)$nbQuiz === 0) ? 0 : (round(((int)$nbQuizDone * 100)/((int)$nbQuiz*(int)$nbUsers)));
    return [
        "participationQuiz" => $pourcent,
        "moyenneQuiz" => $moyenne,
        "tempsQuiz" => $temps
    ];
}

function moduleStatsByCampaign($campaignStart, $campaignEnd, $site, $nbModule, $nbUsers){
    global $wpdb;
    $nbModuleDone = $wpdb->get_var("
        SELECT
            count(module_finish.id) 
        FROM
            module_finish
        LEFT JOIN  wp_usermeta ON module_finish.user_id = wp_usermeta.user_id 
            AND wp_usermeta.meta_key = 'location' 
        WHERE
            wp_usermeta.meta_value = '$site' 
        AND
            module_finish.created_at BETWEEN '$campaignStart' AND '$campaignEnd'"
    );

    return ((int)$nbUsers === 0 || (int)$nbModule === 0) ? 0 : (round(((int)$nbModuleDone * 100)/((int)$nbModule*(int)$nbUsers)));
}

global $wpdb;
$str_json = file_get_contents('php://input'); //($_POST doesn't work here)
$request = json_decode($str_json, true); // decoding received JSON to array

$campaignId = $request['id'];
// $campaignId = 4;
//récupérer les scores, taux de participation etc par ville 
$campaign = $wpdb->get_row("SELECT * FROM campaign WHERE id = $campaignId");
$sites = $wpdb->get_results("SELECT distinct(meta_value) FROM wp_usermeta WHERE meta_key = 'location' ORDER BY meta_value");
$campaignStart = $campaign->start;
$campaignEnd = $campaign->end;
$nbQuiz = $wpdb->get_var("
        SELECT
            count(quiz.id)
        FROM
            quiz
        WHERE  
            quiz.created_at BETWEEN '$campaignStart' AND '$campaignEnd'"
    );
$nbModule = $wpdb->get_var("
    SELECT
        count(module.id)
    FROM
        module
    WHERE
        module.created_at BETWEEN '$campaignStart' AND '$campaignEnd'"
);

$stats = [
    "nbQuiz" => $nbQuiz,
    "nbModule" => $nbModule,
    "sites" => [],
];

$total = [
    "participationQuiz" => 0,
    "moyenneQuiz" => 0,
    "tempsQuiz" => 0,
    "participationModule" => 0,
];
foreach($sites as $site){
    $nbUsers = $wpdb->get_var("
        SELECT count(umeta_id) FROM wp_usermeta WHERE wp_usermeta.meta_key = 'location' AND  wp_usermeta.meta_value = '$site->meta_value'
    ");
    
    $stats['sites'][$site->meta_value] = quizStatsByCampaign($campaign->start, $campaign->end, $site->meta_value, $nbQuiz, $nbUsers);
    $stats['sites'][$site->meta_value]["participationModule"] = moduleStatsByCampaign($campaign->start, $campaign->end, $site->meta_value, $nbModule, $nbUsers);

    $total["participationQuiz"] += (int)$stats['sites'][$site->meta_value]["participationQuiz"];
    $total["moyenneQuiz"] += (int)$stats['sites'][$site->meta_value]["moyenneQuiz"];
    $total["tempsQuiz"] += (int)$stats['sites'][$site->meta_value]["tempsQuiz"];
    $total["participationModule"] += (int)$stats['sites'][$site->meta_value]["participationModule"];
}

foreach($total as $k=>$v){
    $total[$k] = ceil($v/count($sites));
}

$stats['total'] = $total;


echo json_encode($stats);
?>