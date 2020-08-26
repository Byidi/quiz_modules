<?php 

header('content-type:application/json');



require('../class/tag.class.php');

require('../class/module.class.php');

require('../class/module_slide.class.php');



$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);

include($path.'wp-load.php');



if(!checkAuthorized(false, true)){

    wp_redirect( home_url() );  exit;

}



//JSON encode 
$where = '';

if(!isset($_GET['all']) ){
    $where = " WHERE status=1";
}

$modules = $wpdb->get_results( "SELECT * FROM module".$where );

$moduleArray = [];

        foreach ($modules as $m){

            $moduleProg = 0;

            $moduleTmp = new Module();

            $moduleTmp->selectById($m->id);



            $userId = get_current_user_id();

            $moduleId = $m->id;

            $query = $wpdb->get_var("SELECT id FROM module_finish WHERE user_id = ".$userId." AND module_id = ".$moduleId." ");

            if($query > 0){

                $moduleProg = 100;

            }else{

                $userProgress = $wpdb->get_var("SELECT count(*) FROM module_progress WHERE user_id = ".$userId." AND module_id = ".$moduleId." ");

                $moduleNbSlides = $wpdb->get_var("SELECT count(*) FROM module_slide WHERE module_id = ".$moduleId." ");

                $moduleNbSlides = $moduleNbSlides == 0 ? 1 : $moduleNbSlides;

                $moduleProg = ceil(($userProgress * 100)/$moduleNbSlides);

            }

            $module = array(

                "id" => $moduleTmp->getId(),

                "title" => $moduleTmp->getTitle(),

                "content" => $moduleTmp->getContent(),

                "tag_id" => $moduleTmp->getTag()->getId(),

                "tag_name" => $moduleTmp->getTag()->getName(),

                "img" => $moduleTmp->getImgPath(),

                "author_id" => $moduleTmp->getAuthor(),

                "author_name" => get_user_meta($moduleTmp->getAuthor(), 'first_name', true) . ' ' . get_user_meta($moduleTmp->getAuthor(), 'last_name', true),

                "user_prog" => $moduleProg,

                "status" => $moduleTmp->getStatus(),

            );

 $moduleArray[] = $module;

}

echo json_encode($moduleArray);

?>

