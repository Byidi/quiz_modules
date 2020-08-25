<?php

$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path.'wp-load.php');




function qm_install () {

   createTableAnswer();
   createTableCampaign();
   createTableModule();
   createTableModuleFinish();
   createTableModuleProgress();
   createTableModuleQuiz();
   createTableModuleSlide();
   createTableNotifyDate();
   createTableQuestion();
   createTableQuiz();
   createTableQuizProgress();
   createTableQuizScore();
   createTableTag();

   
}

function createTableAnswer(){

    global $wpdb;

    $table_name = 'answer';

    $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        id_question int(11) NOT NULL,
        content varchar(255) NOT NULL,
        is_true varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

}

function createTableCampaign(){

    global $wpdb;

    $table_name = 'campaign';

    $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        start datetime NOT NULL,
        end datetime NOT NULL,
        PRIMARY KEY  (id)
    );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

}

function createTableModule(){

    global $wpdb;

    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'module';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            content text NULL,
            tag_id int(11) NULL,
            img_path varchar(255) NULL,
            description text NULL,
            author_id int(11) NULL,
            status int(11) DEFAULT '1' NOT NULL,
            created_at timestamp NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
}

function createTableModuleFinish(){

    global $wpdb;

    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'module_finish';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL,
            module_id int(11) NOT NULL,
            created_at datetime NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

}

function createTableModuleProgress(){

    global $wpdb;

    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'module_progress';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL,
            module_id int(11) NOT NULL,
            slide_id int(11) NOT NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

}

function createTableModuleQuiz(){

    global $wpdb;

    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'module_quiz';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            id_quiz int(11) NOT NULL,
            id_module int(11) NOT NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

}

function createTableModuleSlide(){

    global $wpdb;

    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'module_slide';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            module_id int(11) NOT NULL,
            title varchar(255) NOT NULL,
            img_path varchar(255) NULL,
            url varchar(255) NULL,
            content text NULL,
            order int(11) NOT NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

}

function createTableNotifyDate(){

    global $wpdb;

    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'notify_date';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            date datetime NOT NULL,
            author_id int(11) NOT NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
}

function createTableQuestion(){

    global $wpdb;

    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'question';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            id_quiz int(11) NOT NULL,
            content varchar(255) NOT NULL,
            img_path varchar(255) NULL,
            url varchar(255) NULL,
            points float NOT NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

}

function createTableQuiz(){

    global $wpdb;

    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'quiz';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            tag_id int(11) NULL,
            author_id int(11) NULL,
            img_path varchar(255) NULL,
            description text NULL,
            status int(11) DEFAULT '1' NOT NULL,
            created_at datetime NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

}


function createTableQuizProgress(){

    global $wpdb;

    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'quiz_progress';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            id_quiz int(11) NOT NULL,
            id_question int(11) NOT NULL,
            id_answer int(11) NOT NULL,
            id_user int(11) NOT NULL,
            time int(11) NOT NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

}

function createTableQuizScore(){

    global $wpdb;


    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'quiz_score';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL,
            quiz_id int(11) NOT NULL,
            score int(11) NOT NULL,
            time int(11) NOT NULL,
            created_at date NOT NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );


}

function createTableTag(){

    global $wpdb;


    if ( $installed_ver != $qm_db_version ) {

        $table_name = 'tag';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );


}




?>