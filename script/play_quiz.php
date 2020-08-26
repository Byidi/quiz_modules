<?php

require('../class/answer.class.php');

require('../class/question.class.php');

require('../class/quiz.class.php');

require('../class/quiz_score.class.php');

require('../class/tag.class.php');



$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);

include($path.'wp-load.php');





if(!checkAuthorized(false, true)){

    wp_redirect( home_url() );  exit;

}



//JSON encode 

$quiz = new Quiz();

$quiz->selectById($_GET['id']);

echo json_encode($quiz->getInfos(get_current_user_id()));

?>