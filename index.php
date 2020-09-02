<?php
function sessionstart() {
  if ( ! session_id() ) {
     @session_start();
  }
}

 add_action( 'init', 'sessionstart', 1 );

 require_once(__DIR__ . '/script/database.php');
 register_activation_hook( __FILE__, 'qm_install' );
/*
  Plugin Name: quiz_modules
  Description: 
  Version: 1.0
  Author: Marie Bonifacio
*/



add_shortcode( 'qm_display_last_quiz', 'qm_display_last_quiz');
add_shortcode( 'qm_display_quiz_menu', 'qm_display_quiz_menu' );
add_shortcode( 'qm_quiz_creation_1', 'qm_quiz_creation_1');
add_shortcode( 'qm_quiz_creation_2', 'qm_quiz_creation_2');
add_shortcode( 'qm_quiz_creation_3', 'qm_quiz_creation_3');

function qm_display_last_quiz(){
  echo '
    <div class=lastQ>  
      <h3>Dernier quiz</h3>
    </div>
  ';

  wp_enqueue_style( 'lastQuiz', WP_PLUGIN_URL .'/quiz_modules/css/lastQuiz.css',false,'1.1','all');
  wp_enqueue_style( 'quizPlay', WP_PLUGIN_URL .'/quiz_modules/css/quizPlay.css',false,'1.1','all');
  wp_enqueue_script('lastQuiz', WP_PLUGIN_URL .'/quiz_modules/js/quiz/lastQuiz.js', null, true);
  wp_localize_script('lastQuiz', 'myScript', array(
      'script_directory' => WP_PLUGIN_URL .'/quiz_modules/script',
      'home_url' => home_url()
  ));
}

function qm_display_quiz_menu(){
    global $wpdb;
    echo '
    <div class="quizModules">
    <h2 id="debut" class="h2">Nos quiz</h2>
        
            <!--<a class="ancreTop" href="#debut">
                <i class="fas fa-sort-up"></i>
            </a>
            <a class="ancreDown" href="#end">
                <i class="fas fa-sort-down"></i>
            </a>-->
            <div class="button-group filters-button-group">
                <button class="button" data-filter="*">tout</button>';
            
                //ajout boucle tags db
                $tags = $wpdb->get_results( "SELECT name FROM tag");
                foreach($tags as $t){
                echo '<button class="button" data-filter=".'.$t->name.'">'.$t->name.'</button>';
                }
                
            echo '</div>
            <div class="grid">
                <div id="end"></div>
            </div>
        </div>
    ';

    wp_enqueue_style( 'quizMenu', WP_PLUGIN_URL .'/quiz_modules/css/quizMenu.css',false,'1.1','all');
    wp_enqueue_style( 'quizPlay', WP_PLUGIN_URL .'/quiz_modules/css/quizPlay.css',false,'1.1','all');
    wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array('jquery'));
    wp_enqueue_script('quiz-menu', WP_PLUGIN_URL .'/quiz_modules/js/quiz/quizMenuPlay.js', null, true);
    wp_localize_script('quiz-menu', 'myScript', array(
      'script_directory' => WP_PLUGIN_URL .'/quiz_modules/script',
      'home_url' => home_url()
    ));
}

function qm_quiz_creation_1(){
    global $wpdb;
    echo '
    <div class="step1">
    <h2 class="h2">Créez votre quiz</h2>
    <h3>Étape 1: Le sujet</h3>
    <div class="steps">
        <div class="step stepInto">1</div>
        <div class="step">2</div>
        <div class="step">3</div>
        <div class="stick"></div>
    </div>';
    
    if(!empty($_SESSION["errorQuiz"])){
      echo "<p class='mess error'>".$_SESSION["errorQuiz"]."</p>";
      unset($_SESSION["errorQuiz"]);
    }
    elseif(!empty($_SESSION["quizOk"])){
      echo "<p class='mess good'>".$_SESSION["quizOk"]."</p>";
      unset($_SESSION["quizOk"]);
    }
    echo '
    <form action="'.WP_PLUGIN_URL.'/quiz_modules/script/create_quiz_1.php" method="post" enctype="multipart/form-data">
        <div class="textarea">
            <div>
                <label for="">Description :</label>
                <textarea name="description">'.$_SESSION['quizData']['quiz']['description'].'</textarea>
            </div>
        </div>
        <div class="content">
            <div>
                <label for="">Titre du quiz * :</label>
                <input type="text" name="title" value="'. $_SESSION['quizData']['quiz']['title'].'">
            </div>
            <div>
                <label for="">Thème du quiz * :</label>
                <select name="theme" id="sites">
                    <option value="">Sélectionnez un thème</option>';
                    
                        //ajout boucle tags db
                        $tags = $wpdb->get_results( "SELECT id, name FROM tag");
    
                        foreach($tags as $t){
                            echo '<option value="'.$t->name.'"';
                            if($t->id === $_SESSION['quizData']['quiz']['theme']){
                               echo 'selected';
                            }
                            echo '>'.$t->name.'</option>';
                        }
                    echo '
                </select>
                <i class="fas fa-sort-down"></i>
            </div>
            <div>
                <label for="">Module associé :</label>
                <select name ="moduleId">
                        <option value="">Aucun</option>';
                        
                            //récupération des modules
                            $modules = $wpdb->get_results("SELECT id, title FROM module");
                            foreach ($modules as $m) {
                                echo '<option value="'.$m->id.'"';
                                if($m->id === $_SESSION['quizData']['quiz']['moduleRelated']){
                                    echo 'selected';
                                }
                                echo '>'.$m->title.'</option>';
                            }
                        echo '
                </select>
                <i class="fas fa-sort-down"></i>
            </div>
            <div>
                <label for="">
                Image * :';
                
                    if( !empty($_SESSION['quizData']['quiz']['img'])){
                    echo '<img style="width : 50px; margin-left : 6px;" src="'.get_template_directory_uri().'/img/quizs/'.$_SESSION['quizData']['quiz']['img'].'">';
                    }
                echo '
                </label>
                <button type="button" disabled><p id="fakebtn">Sélectionnez une image</p></button>
                <span id="img_select">Aucune image sélectionnée.</span>
                <input id="realbtn" type="file" name="img_quiz" hidden>
            </div>
        </div>
        <input type="submit" value="Suivant">
    </form>
    </div>
    ';

    wp_enqueue_style( 'creationStep1', WP_PLUGIN_URL .'/quiz_modules/css/creationStep1.css',false,'1.1','all');
    wp_enqueue_script('quiz_step1', WP_PLUGIN_URL .'/quiz_modules/js/creation/quizStep1.js', null, true);
}

function qm_quiz_creation_2(){
    function getQuestion($i, $isNew, $p){
        $html = '
        <div class="questionPage';
        if($isNew){
            $html .= ' new';
        }
        $html .= '">
          <div>
            <label>Votre question:</label>
            <input type="text" name="question_'.$i.'" value="'.$p['question_'.$i].'">
          </div>
          <div class="answers">
            <label>Vos réponses:</label>
            <div class="abcd">
              <div class="answer">
                <label>A.</label>
                <input type="text" name="q_'.$i.'_reponse_1" value="'.$p['q_'.$i.'_reponse_1'].'">
                <label class="true" id="truea">
                  <input '.( ($p["q_".$i."_isTrue_1"] == "true")? "checked":"" ).' type="radio" value="true" name="q_'.$i.'_isTrue_1">
                  <span>
                    <i class="fas fa-check"></i>
                  </span>
                </label>
                <label class="false" id="falsea">
                  <input '.( ($p["q_".$i."_isTrue_1"] == "true")?"":"checked" ).' type="radio" value="false" name="q_'.$i.'_isTrue_1">
                  <span>
                    <i class="fas fa-times"></i>
                  </span>
                </label>
              </div>
              <div class="answer">
                <label>B.</label>
                <input type="text" name="q_'.$i.'_reponse_2" value="'.$p['q_'.$i.'_reponse_2'].'">
                <label class="true" id="trueb">
                  <input '.( ($p["q_".$i."_isTrue_2"] == "true")? "checked":"" ).' type="radio" value="true" name="q_'.$i.'_isTrue_2">
                  <span>
                    <i class="fas fa-check"></i>
                  </span>
                </label>
                <label class="false" id="falseb">
                  <input '.( ($p["q_".$i."_isTrue_2"] == "true")?"":"checked" ).' type="radio" value="false" name="q_'.$i.'_isTrue_2">
                  <span>
                    <i class="fas fa-times"></i>
                  </span>
                </label>
              </div>
              <div class="answer">
                <label>C.</label>
                <input type="text" name="q_'.$i.'_reponse_3"  value="'.$p['q_'.$i.'_reponse_3'].'">
                <label class="true" id="truec">
                  <input '.( ($p["q_".$i."_isTrue_3"] == "true")? "checked":"" ).' type="radio" value="true" name="q_'.$i.'_isTrue_3">
                  <span>
                    <i class="fas fa-check"></i>
                  </span>
                </label>
                <label class="false" id="falsec">
                  <input '.( ($p["q_".$i."_isTrue_3"] === "true")?"":"checked" ).' type="radio" value="false" name="q_'.$i.'_isTrue_3">
                  <span>
                    <i class="fas fa-times"></i>
                  </span>
                </label>
              </div>
              <div class="answer">
                <label>D.</label>
                <input type="text" name="q_'.$i.'_reponse_4"  value="'.$p['q_'.$i.'_reponse_4'].'">
                <label class="true" id="trued">
                  <input '.( ($p["q_".$i."_isTrue_4"] == "true")? "checked":"" ).' type="radio" value="true" name="q_'.$i.'_isTrue_4">
                  <span>
                    <i class="fas fa-check"></i>
                  </span>
                </label>
                <label class="false" id="falsed">
                  <input '.( ($p["q_".$i."_isTrue_4"] == "true")?"":"checked" ).' type="radio" value="false" name="q_'.$i.'_isTrue_4">
                  <span>
                    <i class="fas fa-times"></i>
                  </span>
                </label>
              </div>
            </div>
          </div>
          <div>
            <label>Le media:</label>
          </div>
          <div class="media">
            <div>
              <label>Image :';
              if(!$isNew){
                global $wpdb;
                $img = $wpdb->get_var("SELECT img_path FROM question WHERE id='".$i."'");
                if(!empty($img)){
                    $html .= '<img style="width : 50px; margin-left : 6px;" src="'.get_template_directory().'/img/quiz/'.$img.'">';
                }
              }
              $html .= '
              </label>
              <button type="button" disabled><p id="fakebtn" data-id="'.$i.'">Sélectionnez une image</p></button>
              <span id="img_select'.$i.'">Aucune image sélectionnée.</span>
              <input id="realbtn'.$i.'" type="file" name="q_'.$i.'_img" hidden>
            </div>
            <p><strong>OU</strong></p>
            <div>
              <label>Video :</label>
              <input type="text" name="q_'.$i.'_video" value="">
            </div>
          </div>
          <i class="trash'.$id.' trash fas fa-trash" data-id="'.$i.'"></i>
        </div>
        ';

        return $html;
    }

  echo '
  
  <div class="step2">
  <h2 class="h2">'.$_SESSION['quizData']['quiz']['title'].'</h2>
    <img src="'. $_SESSION['quizData']['quiz']['img'].'" alt="votre image">
    <h3>Étape 2: Les questions</h3>
    <div class="steps">
      <div class="step">1</div>
      <div class="step stepInto">2</div>
      <div class="step">3</div>
      <div class="stick"></div>
    </div>';

    $nbrQuestion = 1;
    if(!empty($_SESSION["errorQuiz"])){
      echo "<p class='mess error'>".$_SESSION["errorQuiz"]."</p>";
      unset($_SESSION["errorQuiz"]);
      /* SI ON REVIENT DU SCRIPT VALIDATION POUR CAUSE MESSAGE D'ERREUR */
      $p = $_SESSION['formQuizStep2'];
      unset($_SESSION["formQuizStep2"]);
      $nbrQuestion = $p['nbrQuestion'];
    }
    if(!empty($_SESSION['formQuizStep2'])){
      $nbrQuestion = $_SESSION['formQuizStep2']['nbrQuestion'];
      $p = $_SESSION['formQuizStep2'];
    }
    echo '
    <form action="'.WP_PLUGIN_URL.'/quiz_modules/script/create_quiz_2.php" method="post" enctype="multipart/form-data" class="formStep2">
    <input type="text" name="nbrQuestion" value="'. $nbrQuestion.'" hidden>
    <div class="nbrQuestionVisible">
        <label>Questions visibles pendant le quiz:</label>
        <div>
        <select name ="nbrQuestionVisible">';
            for ($i=3; $i <= 10; $i++) { 
              echo'
                <option value="'.$i.'">'.$i.'</option>
              ';
            }
        echo '
        </select>
        <i class="fas fa-sort-down"></i>
        </div>
      </div>';
     
      if(!empty($p)){
        foreach ($p as $key => $value) {
          preg_match('/^question_(n)?(\d+)$/', $key, $matches);
          if(count($matches) > 2){
            $id = count($matches) === 2 ? $matches[1] : $matches[1].$matches[2];
            echo getQuestion($id , $matches[1] === 'n', $p);
          }
        }
      }else{
        echo getQuestion('n1', true, null);
      }
      echo '
      <input type="submit" name ="valider" value="Valider" hidden/>
      <input type="submit" name ="brouillon" value="Enregistrer le brouillon" hidden/>
    </form>
    <i class="plus fas fa-plus"></i>
    <p class="validate">Suivant</p>
    <p class="sketching">Enregistrer le brouillon</p>
  </div>';

  wp_enqueue_style( 'creationStep2', WP_PLUGIN_URL .'/quiz_modules/css/creationStep2.css',false,'1.1','all');
  wp_enqueue_script('quiz_step2', WP_PLUGIN_URL .'/quiz_modules/js/creation/quizStep2.js', null, true);
}

function qm_quiz_creation_3(){
  global $wpdb;
  echo '
  <div class="step3">
  <h2 class="h2">'. $_SESSION['quizData']['quiz']['title'].'</h2>
    <img class="img" src="'. $_SESSION['quizData']['quiz']['img'].'" alt="votre image">
   <h3>Étape 3: Confirmation</h3>
    <div class="steps">
      <div class="step">1</div>
      <div class="step">2</div>
      <div class="step stepInto">3</div>
      <div class="stick"></div>
    </div>
    <div class="recap">';

      $num = 0;
      if(!empty($_SESSION['quizData']['questions'])){
        echo '<div class="questions"><p class="introP"><span class="numQ">Description</span>'.nl2br(stripslashes($_SESSION['quizData']['quiz']['description'])).'</p></div>';
      }
      foreach($_SESSION['quizData']['questions'] as $q){
        $num ++;
        echo '
        <div class="questions">';
          if(!empty($q['info']['img'])){
            echo '
            <div class="medias">
              <img src="'.$q['info']['img'].'" alt="votre image">
            </div>
            ';
          }elseif (!empty($q['info']['video'])) {
            $regex = "ton regex";
            preg_match("/^.*v=(.*)$/", $q['info']['video'], $keywords);
            if(isset($keywords)){
              echo '
              <iframe width="500" height="300" src="https://www.youtube.com/embed/'.$keywords[1].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              ';
            }else{
              echo '
              <div class="img">
                <a href="'.$q['info']['video'].'>Voir la vidéo</a>
              </div>';
            }
          }
          echo '
          <span class="numQ">'.$num.'</span>
          <div class="question">
            <p>'.stripslashes($q['info']['text']).'</p>
          </div>
          <div class="answers">';
            $lettre = array("A", "B", "C", "D");
            foreach($q['answers'] as $k =>  $a){
              echo'
              <div>
                <div class="spanP">
                  <span>'.$lettre[$k].'.</span>
                  <p>'.stripslashes($a['text']).'</p>
                </div>
              ';
              if($a['isTrue'] == "true"){
                echo'
                <div>
                  <i class="fas fa-check good"></i>
                  <i class="fas fa-times"></i>
                </div>';
              }else{
                echo '
                <div>
                  <i class="fas fa-check"></i>
                  <i class="fas fa-times error"></i>
                </div>';
              }
              echo '</div>';
            }
          echo '
          </div>
        </div>';
      };
  echo '
    </div>
  <a href="'.WP_PLUGIN_URL.'/quiz_modules/script/create_quiz_3.php">Confirmez la création de votre quiz</a>
  <a href="'.WP_PLUGIN_URL.'/quiz_modules/script/create_quiz_3.php?status=0">Enregistrer le brouillon</a>
  </div>';

  wp_enqueue_style( 'creationStep3', WP_PLUGIN_URL .'/quiz_modules/css/creationStep3.css',false,'1.1','all');

}


///////////////////////modules//////////////////////////////////////////////


add_shortcode( 'qm_display_module_menu', 'qm_display_module_menu' );
add_shortcode( 'qm_module_creation_1', 'qm_module_creation_1');
add_shortcode( 'qm_module_creation_2', 'qm_module_creation_2');
add_shortcode( 'qm_module_creation_3', 'qm_module_creation_3');

function qm_display_module_menu(){
  global $wpdb;
  echo '  
  
  <div class="quizModules">
  <h2 id="debut" class="h2">Nos modules</h2>
    <!--<a class="ancreTop" href="#debut">
      <i class="fas fa-sort-up"></i>
    </a>
    <a class="ancreDown" href="#end">
      <i class="fas fa-sort-down"></i>
    </a>-->
    <div class="button-group filters-button-group">
    <button class="button" data-filter="*">tout</button>';

     //ajout boucle tags db

      $tags = $wpdb->get_results( "SELECT name FROM tag");

      foreach($tags as $t){

        echo '<button class="button" data-filter=".'.$t->name.'">'.$t->name.'</button>';

      }

      echo '
      </div>
      <div class="grid">
        <div id="end"></div>
      </div>
    </div>';

    wp_enqueue_style( 'moduleMenu', WP_PLUGIN_URL .'/quiz_modules/css/moduleMenu.css',false,'1.1','all');
    wp_enqueue_style( 'modulePlay', WP_PLUGIN_URL .'/quiz_modules/css/modulePlay.css',false,'1.1','all');
    wp_enqueue_style( 'quizPlay', WP_PLUGIN_URL .'/quiz_modules/css/quizPlay.css',false,'1.1','all');
    wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array('jquery'));
    wp_enqueue_script('module-menu', WP_PLUGIN_URL .'/quiz_modules/js/modules/modulesMenuPlay.js', null, true);
    wp_localize_script('module-menu', 'myScript', array(
        'script_directory' => WP_PLUGIN_URL .'/quiz_modules/script',
        'home_url' => home_url()
    ));

}

function qm_module_creation_1(){
  global $wpdb;
  echo '
  
  <div class="step1">
  <h2 class="h2">Créez votre module</h2>
    <h3>Étape 1: Le sujet</h3>
    <div class="steps">
        <div class="step stepInto">1</div>
        <div class="step">2</div>
        <div class="step">3</div>
        <div class="stick"></div>
    </div>';

    if(!empty($_SESSION["errorModule"])){
        echo "<p class='mess error'>".$_SESSION["errorModule"]."</p>";
        unset($_SESSION["errorModule"]);
    }
    elseif(!empty($_SESSION["moduleOk"])){
        echo "<p class='mess good'>".$_SESSION["moduleOk"]."</p>";
        unset($_SESSION["moduleOk"]);
    }
    
    echo '
    <form action="'.WP_PLUGIN_URL.'/quiz_modules/script/create_module_1.php" method="post" enctype="multipart/form-data">
      <div class="textarea">
        <div>
          <label for="">Description :</label>
          <textarea name="description">'. $_SESSION['moduleData']['module']['description'].'</textarea>
        </div>
      </div>
      <div class="content">
        <div>
          <label for="">Titre du module * :</label>
          <input type="text" name="title" value="'.$_SESSION['moduleData']['module']['title'].'">
        </div>
        <div>
          <label for="" class="moduleThemeLabel">Thème du module * :</label>
          <select name="theme" id="sites">
            <option value="">Thème de votre module</option>';

            //ajout boucle tags db
            $tags = $wpdb->get_results( "SELECT id, name FROM tag");

            foreach($tags as $t){
              echo '<option value="'.$t->name.'"';
              if($_SESSION['moduleData']['module']['theme'] === $t->id){
                echo 'selected';
              }
              echo '>'.$t->name.'</option>';
            }
            echo '
          </select>
          <i class="fas fa-sort-down"></i>
        </div>
        <div>
          <label for="">Image :';
            if( !empty($_SESSION['moduleData']['module']['img'])){
              echo '<img style="width : 50px; margin-left : 6px;" src="'.get_template_directory_uri().'/img/modules/'.$_SESSION['moduleData']['module']['img'].'">';
            }
          echo '
          </label>
          <button type="button" disabled><p id="fakebtn">Séléctionnez une image</p></button>
          <span id="img_select">Aucune image sélectionnée.</span>
          <input id="realbtn" type="file" name="img_module" hidden>
        </div>
      </div>
      <input type="submit" value="Suivant">
    </form>
  </div>';

  wp_enqueue_style( 'creationStep1', WP_PLUGIN_URL .'/quiz_modules/css/creationStep1.css',false,'1.1','all');

  wp_enqueue_script('module_step1', WP_PLUGIN_URL .'/quiz_modules/js/creation/moduleStep1.js', null, true);
}

function qm_module_creation_2(){
  global $wpdb;
  echo '
  
  <div class="step2">
  <h2 class="h2">'. $_SESSION['moduleData']['module']['title'].'</h2>
    <img src="'. $_SESSION['moduleData']['module']['img'].'" alt="votre image">
    <h3>Étape 2: Les pages</h3>
    <div class="steps">
      <div class="step">1</div>
      <div class="step stepInto">2</div>
      <div class="step">3</div>
      <div class="stick"></div>
    </div>';


    $nbrPage = 1;
    if(!empty($_SESSION["errorModule"])){
      echo "<p class='mess error'>".$_SESSION["errorModule"]."</p>";
      unset($_SESSION["errorModule"]);
      /* SI ON REVIENT DU SCRIPT VALIDATION POUR CAUSE MESSAGE D'ERREUR */
      $p = $_SESSION['formModuleStep2'];
      // unset($_SESSION["formModuleStep2"]);
      $nbrPage = $p['nbrPage'];
    }
    if(!empty($_SESSION['formModuleStep2'])){
      $nbrPage = $_SESSION['formModuleStep2']['nbrPage'];
      $p = $_SESSION['formModuleStep2'];
    }
   
    echo '
    <form action="'.WP_PLUGIN_URL.'/quiz_modules/script/create_module_2.php" method="post" enctype="multipart/form-data" class="formStep2">
      <input type="text" name="nbrPage" value="'. $nbrPage.'" hidden>';

      function getPage($i, $isNew, $p){
        $html = '
          <div class="questionPage '.($isNew ? 'new':'').'">
            <div>
              <label>Titre de la page :</label>
              <input type="text" name="content_'.$i.'_title" value="'.$p['content_'.$i.'_title'].'">
            </div>
            <div class="legend">
              <label>Stylisation du texte :</label>
              <ul class="display">
                <li><span>{{</span> Texte sur la gauche<br> ( ex : {{votre texte{{ ) *</li>
                <li><span>}}</span> Texte sur la droite<br> ( ex : }}votre texte}} ) *</li>
                <li><span>||</span> Texte centré<br> ( ex : ||votre texte|| ) *</li>
                <li><span>~~</span> Texte justifié<br> ( ex : ~~votre texte~~ ) *</li>
                <li><span>**</span> Texte en <b>gras</b><br> ( ex: **votre texte** )</li>
                <li><span>//</span> Texte en <elem style="font-style: italic">italique</elem> <br>( ex: //votre texte// )</li>
                <li><span>__</span> Texte <elem style="text-decoration: underline">souligné</elem> <br>( ex: __votre texte__ )</li>
              </ul>
              <br>
              <p class="display">* pour la justification du texte, il y a une particularité a respecter: chaque bloc doit être entouré du symbole en question. <br>
              <elem style="text-decoration: underline">ex</elem>: <br>{{Ma phrase d\'introduction
                <br>
                mon paragraphe}} -> cet exemple ne marchera pas
                <br><br>
                {{Ma phrase d\'introduction}}
                  <br>
                {{mon paragraphe}} -> il faudra suivre cet exemple </p>
            </div>
            <div>
              <label>Contenu de la page :</label>
              <textarea name="content_'.$i.'">'.$p['content_'.$i.''].'</textarea>
            </div>
            <div class="media">
              <div>
                <label>Image :';
                if(!$isNew){
                  global $wpdb;
                  $img = $wpdb->get_var("SELECT img_path FROM module_slide WHERE id='".$i."'");
                  if(!empty($img)){
                    $html .= '<img style="width : 50px; margin-left : 6px;" src="'.get_template_directory_uri().'/img/modules/'.$img.'">';
                  }
                }
                $html .= '
                  </label>
                  <button type="button" disabled><p id="fakebtn" data-id="'.$i.'">Séléctionnez une image</p></button>
                  <span id="img_select'.$i.'">Aucune image sélectionnée.</span>
                  <input id="realbtn'.$i.'" type="file" name="content_'.$i.'_img" hidden>
              </div>
              <p><strong>OU</strong></p>
              <div>
                <label>Video :</label>
                <input type="text" name="content_'.$i.'_video" value="'.$p['content_'.$i.'_video'].'">
              </div>
            </div>
            <i class="trash'.$id.' trash fas fa-trash" data-id="'.$id.'"></i>
          </div>
        ';

        return $html;
      }

      if(!empty($p)){
        foreach ($p as $key => $value) {
        preg_match('/^content_(n)?(\d+)$/', $key, $matches);
        if(count($matches) > 2){
          $id = count($matches) === 2 ? $matches[1] : $matches[1].$matches[2];
          echo getPage($id , $matches[1] === 'n', $p);
        }
      }
    }else{
      echo getPage('n1', true, null);
    }
    echo '
      <input type="submit" name="valider" value="Valider" hidden/>
      <input type="submit" name="brouillon" value="Enregistrer le brouillon" hidden/>
    </form>
    <i class="plus fas fa-plus"></i>
    <p class="validate">Suivant</p>
    <p class="sketching">Enregistrer en brouillon</p>
  </div>';

  wp_enqueue_style( 'creationStep2', WP_PLUGIN_URL .'/quiz_modules/css/creationStep2.css',false,'1.1','all');
  wp_enqueue_script('module_step2', WP_PLUGIN_URL .'/quiz_modules/js/creation/moduleStep2.js', null, true);
}

function qm_module_creation_3(){
  global $wpdb;
  echo '
  
  <div class="step3">
  <h2 class="h2">'. $_SESSION['moduleData']['module']['title'].'</h2>
    <img class="img" src="'.$_SESSION['moduleData']['module']['img'].'" alt="votre image">
    <h3>Étape 3: Confirmation</h3>
    <div class="steps">
      <div class="step">1</div>
      <div class="step">2</div>
      <div class="step stepInto">3</div>
      <div class="stick"></div>
    </div>
    <div class="recap">';

    $num = 0;
    if(!empty($_SESSION['moduleData']['pages']))
    {
      echo '<div class="questions"><p class="introP"><span class="numQ">Description</span>'.nl2br(stripslashes($_SESSION['moduleData']['module']['description'])).'</p></div>';
    }
    foreach($_SESSION['moduleData']['pages'] as $q)
    {
      $num ++;
      echo '
      <div class="pages">
      ';
      if($q['info']['img'])
      {
        if(!empty($q['info']['content']))
        {
          echo '
          <div class="medias">
            <img src="'.$q['info']['img'].'" alt="votre image">
          </div>
          <span class="numP">'.$num.'</span>
          <div class="content">
            <h2>'.stripslashes($q['info']['title']).'</h2>
            <p class=textContent>'.nl2br(stripslashes($q['info']['content'])).'</p>
          </div>
          ';
        }
        else
        {
          echo '
          <div class="medias full">
            <img src="'.$q['info']['img'].'" alt="votre image">
          </div>
          <span class="numP">'.$num.'</span>
          ';
        }
      }
      elseif (!empty($q['info']['url'])){
        preg_match("/^.*v=(.*)$/", $q['info']['url'], $keywords);
        if(isset($keywords)){
          if(!empty($q['info']['content']))
          {
            echo '
            <iframe width="500" height="300" src="https://www.youtube.com/embed/'.$keywords[1].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <span class="numP">'.$num.'</span>
            <div class="content">
            <h2>'.stripslashes($q['info']['title']).'</h2>
            <p class=textContent>'.nl2br(stripslashes($q['info']['content'])).'</p>
            </div>
            ';
          }
          else
          {
            echo '
            <iframe width="500" height="300" src="https://www.youtube.com/embed/'.$keywords[1].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <span class="numP">'.$num.'</span>
            ';
          }
        }else{
        echo '
          <div class="img">
            <a href="'.$q['info']['video'].'>Voir la vidéo</a>
          </div>';
        }
      }
      else
      {
        echo '
        <span class="numP">'.$num.'</span>
        <div class="contentFull content">
        <h2>'.stripslashes($q['info']['title']).'</h2>
        <p class=textContent>'.nl2br(stripslashes($q['info']['content'])).'</p>
        </div>
        ';
      }
    echo '</div>';
    };
    echo '
    </div>
    <a href="'. WP_PLUGIN_URL.'/quiz_modules/script/create_module_3.php">Confirmez la création de votre module</a>
    <a href="'. WP_PLUGIN_URL.'/quiz_modules/script/create_module_3.php?status=0">Enregistrer le brouillon</a>
  </div>';

  wp_enqueue_style( 'creationStep3', WP_PLUGIN_URL .'/quiz_modules/css/creationStep3.css',false,'1.1','all');
  wp_enqueue_script('markdowns', WP_PLUGIN_URL .'/quiz_modules/js/creation/markdowns.js', null, true);
}



//// Liste modules /////
add_shortcode( 'qm_display_module_list', 'qm_display_module_list' );

function qm_display_module_list(){
  global $wpdb;

  echo '
  
  
  
  <div class="modulesL">
    <h2 class="h2"> Liste Modules </h2>
  
    <div class="listModules">
  
      <table>
  
        <thead>
  
          <tr>
  
            <th>Nom</th>
  
            <th>Tag</th>
  
            <th>Status</th>
  
            <th>Action</th>
  
          </tr>
  
        </thead>
  
        <tbody class="list"></tbody>
  
      </table>
  
    </div>
  
  </div>
  ';

  wp_enqueue_style( 'moduleList', WP_PLUGIN_URL .'/quiz_modules/css/moduleList.css',false,'1.1','all');
  wp_enqueue_script('list_module', WP_PLUGIN_URL.'/quiz_modules/js/modules/listModule.js', null, true);
        wp_localize_script('list_module', 'myScript', array(
            'script_directory' => WP_PLUGIN_URL.'/quiz_modules/script',
            'home_url' => home_url()
        ));
}

//// Liste Quiz /////
add_shortcode( 'qm_display_quiz_list', 'qm_display_quiz_list' );

function qm_display_quiz_list(){
  global $wpdb;
  echo '
  <div class="quizsL">

    <h2 class="h2"> Liste des Quizs </h2>
    
    <div class="listQuiz">
  
      <table>
  
        <thead>
  
          <tr>
  
            <th>Nom</th>
  
            <th>Tag</th>
  
            <th>Status</th>
  
            <th>Action</th>
  
          </tr>
  
        </thead>
  
        <tbody class="list"></tbody>
  
      </table>
  
    </div>
    
  </div>
    
    ';

  wp_enqueue_style( 'quizList', WP_PLUGIN_URL .'/quiz_modules/css/quizList.css',false,'1.1','all');
  wp_enqueue_script('list_quiz', WP_PLUGIN_URL.'/quiz_modules/js/quiz/listQuiz.js', null, true);
  wp_localize_script('list_quiz', 'myScript', array(
      'script_directory' => WP_PLUGIN_URL.'/quiz_modules/script',
      'home_url' => home_url()
  ));
}

///////// Tags /////
add_shortcode( 'qm_display_tag_list', 'qm_display_tag_list' );

function qm_display_tag_list(){
  global $wpdb;
  echo '
  
  <div class="add_tag">
    <h2 id="debut" class="h2">Gestion des tags</h2>
    <div class="contentTag">
        <div class="listTag">
            <h3>Tags existants</h3>
            <ul>';
                    //ajout boucle tags db

                    $tags = $wpdb->get_results( "SELECT tag.id AS tId, tag.name AS tName, (select count(id) from quiz where quiz.tag_id=tag.id) as quiz, (select count(id) from module where module.tag_id=tag.id) as module  from tag");

                    foreach($tags as $t){

                        echo '<li>'.$t->tName;

                        if($t->quiz == 0 && $t->module == 0){
                            echo ' 
                            <div data-id="'.$t->tId.'" class="deleteBtn">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="confirm hidden" id="confirm'.$t->tId.'">
                                <p>Êtes-vous sûr de vouloir supprimer ce tag ?</p>
                                <a class = "deleteTag'.$t->tId.'" href ="'.WP_PLUGIN_URL.'/quiz_modules/script/deleteTag.php?id='.$t->tId.'">Oui</a>
                                <span id="no'.$t->tId.'">Non</span>
                            </div>
                            ';
                        }
                        echo '</li>';
                    }
                    echo '
            </ul>
        </div>
        <form action="'.WP_PLUGIN_URL.'/quiz_modules/script/add_tag.php" method="post" enctype="multipart/form-data">
            <h3>Ajoutez un tag</h3>
            <div>
                <label for="">Nom du tag :</label>
                <input type = "text" name="tag"></input>
            </div>
            <input type="submit" value="Ajouter">
        </form>
    </div>
  </div>';

  wp_enqueue_style( 'tagList', WP_PLUGIN_URL .'/quiz_modules/css/tagList.css',false,'1.1','all');
  wp_enqueue_script('gestion_tag', WP_PLUGIN_URL.'/quiz_modules/js/tag/tags.js', null, true);
}
///// Classements quiz admin ///// 
add_shortcode( 'qm_display_classement_admin', 'qm_display_classement_admin' );

function qm_display_classement_admin(){
  global $wpdb;
  echo '
  <div class="board">
    <h2 class="h2 h2board">Classements</h2>
    <div class=legendSpans>
      <div class="legend">
        <p>Légende <i class="dropIcon fas fa-sort-down"></i></p>
        <div class="square_legend dropLegend">
          <div class="gold">
            <label>1er:</label>
            <div></div>
          </div>
          <div class="silver">
            <label>2ème:</label>
            <div></div>
          </div>
          <div class="bronze">
            <label>3ème:</label>
            <div></div>
          </div>
        </div>
      </div>
      <div class="spans">
        <span class="span_above"></span>
        <span class="span_under"></span>
      </div>
    </div>
    <div class="btns">
      <div class="type"> 
        <label>Type :</label>
        <button class="glob">Global</button>
        <button class="quizz">Quiz</button>
        <button class="cat">Catégorie</button>
      </div>
      <div class="filter">
        <label>Filtre :</label>
        <button class="gen">Général</button>
        <button class="sites">Sites</button>
      </div>
      <div class="select">
        <div class="labelSpan">
          <label class="labelList"></label>
          <p class="selected">Votre choix</p>
        </div>
        <ul class="listQuizCat listCat">';
        $tags = $wpdb->get_results( "SELECT tag.id AS tId, tag.name AS tName from tag");
        foreach($tags as $t){
          echo '<li class="tagLi" data-id="'.$t->tId.'">'.$t->tName.'</li>';
        }
        echo '
        </ul>
        <ul class="listQuizCat listQuiz">';
        $quiz = $wpdb->get_results( "SELECT quiz.id AS qId, quiz.name AS qName from quiz");
        foreach($quiz as $t){
          echo '<li class="quizLi" data-id="'.$t->qId.'">'.$t->qName.'</li>';
        }
        echo '
        </ul>
      </div>
    </div>
    <div class="leadboard">
      <table>
        <thead></thead>
        <tbody></tbody>
      </table>
    </div>
  </div>';

  wp_enqueue_style( 'ranking', WP_PLUGIN_URL .'/quiz_modules/css/ranking.css',false,'1.1','all');
  wp_enqueue_script('rankings', WP_PLUGIN_URL.'/quiz_modules/js/stats/rankings.js', null, true);
  wp_localize_script('rankings', 'myScript', array(
      'script_directory' => WP_PLUGIN_URL.'/quiz_modules/script'
  ));

}

///////// Statistiques quiz/module //////
add_shortcode( 'qm_display_stats_admin', 'qm_display_stats_admin' );

function qm_display_stats_admin(){
  global $wpdb;
  echo '
  <div class="stats">
    <h2 id="debut" class="h2">Statistiques</h2>
    <div class="btns">
      <div>
        <label>Type :</label>
        <button class="quizBtn activated" data-id="quiz">Quiz</button>
        <button class="moduleBtn" data-id="module">Module</button>
      </div>
      <div>
        <label>Filtre :</label>
        <button class="genBtn activated" data-id="quiz">Général</button>
        <button class="siteBtn" data-id="module">Sites</button>
      </div>
      <div class="listDiv hidden">
        <label>Votre site : <span class="labelSite">Choix de votre site</span></label>
        <ul class="listSite hidden">';
        
          $sites = array('Auxerre', 'Bielsko-Biala', 'Bordeaux', 'Boulogne-Sur-Mer', 'Caen', 'Calais', 'Caldas da Rainha', 'Châteauroux', 'Cracovie', 'Guimarães', 'Île de France', 'Lisbonne', 'Nevers', 'Poitiers', 'Porto', 'Porto Ferreira Dias', 'Stalowa Wola', 'Tauxigny', 'Tunis', 'Varsovie', "Villeneuve d'Ascq");
          for($i=0; $i<count($sites); $i++){
              echo '<li class="li">'.$sites[$i].'</li>';
          }
        echo '
        </ul>
      </div>
    </div>
    <button class="extract">Tableaux utilisateurs</button>
    <p class="mailSend">Notifier par mail</p>';

    if(isset($_SESSION['notify'])){
      echo "<span class='notifyConfirm'>".$_SESSION['notify']."<i class='fas fa-times notify'></i></span>";
      unset($_SESSION['notify']);
    } 
    echo '
    <div class="confirmMess hidden">
      <p>Vous êtes sur le point d\'envoyer un mail de rappel à tous les utilisateurs n\'ayant pas terminé leur module ou quiz.</br> Confirmez-vous l\'envoi de ces mails ?</p>
      <div class="yesNo">
        <a href="'.WP_PLUGIN_URL .'/quiz_modules/script/notify.php">Oui</a>
        <p class="no">Non</p>
      </div>
    </div>
    <div class="users userQuiz hidden">
      <i class="fas fa-times quizCross"></i>
      <div class="select">
        <label>Votre quiz: <span class="spanQM spanQ">Choisissez votre quiz</span></label>
        <ul class="listModQuiz listQuiz hidden">';
  
        $quizs = $wpdb->get_results( "SELECT quiz.id AS qId, quiz.name AS qName from quiz");
        foreach($quizs as $q){
          echo '<li class="liQM" data-id="'.$q->qId.'">'.$q->qName.'</li>';
        }
        echo '
        </ul>
      </div>
      <p>Liste des utilisateurs n\'ayant pas terminé le quiz "<span class="spanQ imp"></span>"</p>
      <span class="nbrUsers nbrUsersQ">Nombre de personnes n\'ayant pas terminé ce quiz :</span>
      <div class="table">
        <table>
          <thead>
            <tr>
              <th>Utilisateur</th>
              <th>Site</th>
            </tr>
          </thead>
          <tbody class="tbodyQ">
  
          </tbody>
        </table>
      </div>
    </div>
    <div class="users userModule hidden">
      <i class="fas fa-times modCross"></i>
      <div class="select">
        <label>Votre module: <span class="spanQM spanM">Choisissez votre module</span></label>
        <ul class="listModQuiz listMod hidden">';
          $mods = $wpdb->get_results( "SELECT module.id AS mId, module.title AS mName from module");
        foreach($mods as $m){
          echo '<li class="liQM" data-id="'.$m->mId.'">'.$m->mName.'</li>';
        }
        echo '
        </ul>
      </div>
      <p>Liste des utilisateurs n\'ayant pas terminé le module "<span class="spanM imp"></span>"</p>
      <span class="nbrUsers nbrUsersM">Nombre de personnes n\'ayant pas terminé ce module :</span>
      <div class="table">
        <table>
          <thead>
            <tr>
              <th>Utilisateur</th>
              <th>Site</th>
            </tr>
          </thead>
          <tbody class="tbodyM">
  
          </tbody>
        </table>
      </div>
    </div>
    <div class="canvaDiv">
      <canvas class="canva"></canvas>
    </div>
  </div>  
  ';

  wp_enqueue_style( 'statistics', WP_PLUGIN_URL .'/quiz_modules/css/statistics.css',false,'1.1','all');
  wp_enqueue_script('charts', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js');
  wp_enqueue_script('stats-admin', WP_PLUGIN_URL.'/quiz_modules/js/stats/stats.js', null, true);
  wp_localize_script('stats-admin', 'myScript', array(
      'script_directory' =>  WP_PLUGIN_URL.'/quiz_modules/script',
      'home_url' => home_url()
  ));
}

///// campagnes /////
add_shortcode( 'qm_display_creation_campagne', 'qm_display_creation_campagne' );

add_shortcode( 'qm_display_campagne_stats', 'qm_display_campagne_stats' );

function qm_display_creation_campagne(){

  global $wpdb;
  echo '
  <div class="new_camp">
    <h2 class="h2"> Nouvelle Campagne </h2>
    <div class=createContainer>
      <div class="confirm hidden">
        <p>Êtes-vous sur de vouloir supprimer cette campagne "<span class="nameCamp"></span>"?</p>
        <div>
          <a class="yes">Oui</a>
          <span class="no close">Non</span>
        </div>
      </div>
      <div class="modifyDiv hidden">
        <h3>Modifiez votre campagne</h3>
        <div>
          <label>Nom de la campagne:</label>
          <input type="text" class="name">
        </div>
        <div>
          <label>Début de la campagne:</label>
          <input type="date" class="start">
        </div>
        <div>
          <label>Fin de la campagne:</label>
          <input type="date" class="end">
        </div>
        <button class="confirmMod">Valider</button>
        <i class="fas fa-times close"></i>
      </div>
      <form action="'.WP_PLUGIN_URL.'/quiz_modules/script/create_campaign.php" method="POST">
        <h3>Créez votre campagne</h3>';
          if(!empty($_SESSION["campaignSuccess"])){
            echo "<p class='mess good'>".$_SESSION["campaignSuccess"]."</p>";
            unset($_SESSION["campaignSuccess"]);
          }
          elseif(!empty($_SESSION["campaignError"])){
            echo "<p class='mess error'>".$_SESSION["campaignError"]."</p>";
            unset($_SESSION["campaignError"]);
          }
        echo '
        <div>
          <label>Nom de la campagne:</label>
          <input type="text" name="name">
        </div>
        <div>
          <label> Début de la campagne:</label>
          <input type="date" name="dateStart">
        </div> 
        <div>
          <label>Fin de la campagne:</label>
          <input type="date" name="dateEnd">
        </div>
        <input type="submit" value="Valider">
      </form>
      <div class="listCamp">
        <h3>Liste des Campagnes</h3>
        <ul class="camps">
        </ul>
      </div>
    </div>
  </div>
  ';

  wp_enqueue_style( 'campaignCreate', WP_PLUGIN_URL .'/quiz_modules/css/campaignCreate.css',false,'1.1','all');
  wp_enqueue_script('createcamp', WP_PLUGIN_URL . '/quiz_modules/js/campaigns/campaigns.js', null, true);
  wp_localize_script('createcamp', 'myScript', array(
      'script_directory' => WP_PLUGIN_URL.'/quiz_modules/script',
      'home_url' => home_url()
  ));
}

function qm_display_campagne_stats(){
  global $wpdb;
  echo '
  <div class="stats_camp">
    <h2 class="h2"> Statistiques des Campagnes </h2>
    <div class="select">
      <label>Votre campagne: <span class="camp_name name_camp">votre choix</span></label>
      <ul class="listCamp hidden">';
      $campaigns = $wpdb->get_results( "SELECT campaign.id AS cId, campaign.name AS cName from campaign");
        foreach($campaigns as $c){
          echo '<li class="liC" data-id="'.$c->cId.'">'.$c->cName.'</li>';
        }
      echo '
      </ul>
    </div>
    <div class="selectC">
      <label>comparée à : <span class="compare_name compare_camp">votre choix</span></label>
      <ul class="listCampC hidden">';
      $campaigns = $wpdb->get_results( "SELECT campaign.id AS cId, campaign.name AS cName from campaign");
        foreach($campaigns as $c){
          echo '<li class="liComp" data-id="'.$c->cId.'">'.$c->cName.'</li>';
        }
      echo '
      </ul>
    </div>
    <p>Tableau des statisitques de la campagne <span class="camp_name"></span> comparée à <span class="compare_name"></span></p>
    <div class="container">
      <div class="tableContainer">
        <div>
          <h3><span class="nbrQuiz"></span> Quiz</h3>
          <table class="quizTable">
            <thead>
              <tr>
                <th>Sites</th>
                <th>Taux de participation (%)</th>
                <th>Moyenne (pts.)</th>
                <th>Temps en moyenne (sec.)</th>
              </tr>
            </thead>
            <tbody class="bodyQ">
            </tbody>
          </table>
        </div>
        <div>
          <h3><span class="nbrMod"></span> Modules</h3>
          <table class="modTable">
            <thead>
              <tr>
                <th>Sites</th>
                <th>Taux de participation (%)</th>
              </tr>
            </thead>
            <tbody class="bodyM">
        
            </tbody>
          </table>
        </div>
        <div class="totalDiv">
          <h3>Total</h3>
          <table class="totalTable">
            <thead>
              <tr>
                <th>Taux de participation Quiz (%)</th>
                <th>Taux de participation Modules (%)</th>
                <th>Moyenne des quiz (pts.)</th>
                <th>Temps des quiz (sec.)</th>
              </tr>
            </thead>
            <tbody class="total">
        
            </tbody>
          </table>
        </div>
      </div>
      <div class="tableContainer">
        <div>
          <h3><span class="nbrQuizCompare"></span> Quiz</h3>
          <table class="quizTable">
            <thead>
              <tr>
                <th>Sites</th>
                <th>Taux de participation (%)</th>
                <th>Moyenne (pts.)</th>
                <th>Temps en moyenne (sec.)</th>
              </tr>
            </thead>
            <tbody class="compareQ">
            </tbody>
          </table>
        </div>
        <div>
          <h3><span class="nbrModCompare"></span> Modules</h3>
          <table class="modTable">
            <thead>
              <tr>
                <th>Sites</th>
                <th>Taux de participation (%)</th>
              </tr>
            </thead>
            <tbody class="compareM">
        
            </tbody>
          </table>
        </div>
        <div class="totalDiv">
          <h3>Total</h3>
          <table class="totalTable">
            <thead>
              <tr>
                <th>Taux de participation Quiz (%)</th>
                <th>Taux de participation Modules (%)</th>
                <th>Moyenne des quiz (pts.)</th>
                <th>Temps des quiz (sec.)</th>
              </tr>
            </thead>
            <tbody class="totalCompare">
        
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  ';

  wp_enqueue_style( 'statsCampaign', WP_PLUGIN_URL .'/quiz_modules/css/statsCampaign.css',false,'1.1','all');
  wp_enqueue_script('statscamp', WP_PLUGIN_URL . '/quiz_modules/js/campaigns/statsCampaigns.js', null, true);
  wp_localize_script('statscamp', 'myScript', array(
      'script_directory' => WP_PLUGIN_URL.'/quiz_modules/script',
      'home_url' => home_url()
  ));
}

//////////////////////////////////Accueil//////////////////////////////////////////////////////////

add_shortcode( 'qm_display_classement_acceuil', 'qm_display_classement_acceuil' );
add_shortcode( 'qm_display_stats_acceuil', 'qm_display_stats_acceuil');

function qm_display_stats_acceuil(){
  
  echo "
  <canvas id=myChart></canvas>
  ";

  wp_enqueue_script('charts', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js');
  wp_enqueue_script('graphicTemplate', WP_PLUGIN_URL . '/quiz_modules/js/stats/graphicTemplate.js', null, true);
  wp_localize_script('graphicTemplate', 'myScript', array(
    'script_directory' => WP_PLUGIN_URL .'/quiz_modules/script',
    'home_url' => home_url()
));
}

function qm_display_classement_acceuil(){
  echo "
  <div class=containerLeadboard>
    <h3>Classement</h3>
    <div class=btnsBoard>
      <button class=gen>Général</button>
      <button class=town>Votre site</button>
    </div>
    <div class=leadboard>
      <table>
      <thead>
          <tr>
              <th colspan=1>Pos</th>
              <th colspan=1>Joueur</th>
              <th colspan=1>Site</th>
              <th colspan=1>Moyenne</th>
          </tr>
      </thead>
      <tbody class=tbody>
      </tbody>
    </table>
    </div>
  </div>
  ";

  wp_enqueue_style( 'rankingTemplate', WP_PLUGIN_URL .'/quiz_modules/css/rankingTemplate.css',false,'1.1','all');
  wp_enqueue_script('rankingTemplate', WP_PLUGIN_URL . '/quiz_modules/js/stats/rankingTemplate.js', null, true);
  wp_localize_script('rankingTemplate', 'myScript', array(
      'script_directory' => WP_PLUGIN_URL.'/quiz_modules/script',
  ));
}


function checkAuthorized($needAdmin = false, $needLog = true){
  if($needLog){
      if(get_current_user_id() == 0){
          return false;
      }
      if($needAdmin){
          $currentUser = wp_get_current_user();
          if(!user_can($currentUser,"administrator") ){
              return false;
          }
      }
  }
  return true;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//on dit à wordpress d'éxecuter la fonction siteMetaBridge, dès qu'un utilisateur se log

add_action('wp_login', 'siteMetaBridge', 10, 2);
function siteMetaBridge($user_login, $user){
    global $wpdb;
    update_user_meta($user->ID, 'test01', 'test01');
    update_user_meta($user->ID, 'test02', 'test02');

    if(function_exists('bp_loggedin_user_id')){
        $bp_user_id = bp_loggedin_user_id();
    }
    update_user_meta($user->ID, 'test03', 'test03');

    $wp_user_id = get_current_user_id();
    update_user_meta($user->ID, 'test04', 'test04');

    if($bp_user_id !== $wp_user_id){
        $user_id = $bp_user_id;
    }else{
        $user_id = $wp_user_id;
    }
    update_user_meta($user->ID, 'test05', 'test05');

    if(function_exists('xprofile_get_field_data')){
        $site = xprofile_get_field_data('Site', $user_id);
        update_user_meta($user_id, 'location', $site);
    }
    
    update_user_meta($user->ID, 'test06', 'test06');

    if(shortcode_exists('username')){
        $displayName = do_shortcode('[username]');
    }else{
        $displayName = $user->first_name.' '.$user->last_name; 
    }

    update_user_meta($user->ID, 'qm_display_name', $displayName);
    update_user_meta($user->ID, 'test08', 'test08');
}

/// LEADERBOARD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getUserClassement($userId = null, $ville=null, $limit=null){
  global $wpdb;

  $userTable = $wpdb->prefix.'users';
  $metaTable = $wpdb->prefix.'usermeta';

  $sql = "SELECT quiz_score.user_id, avg(quiz_score.score) AS moyenne,  sum(quiz_score.time) AS time, count(quiz_score.id) AS count,  ".$metaTable.".meta_value, meta2.meta_value  AS display_name ";
  $sql .= "FROM quiz_score LEFT JOIN ".$userTable." ON ".$userTable.".ID = quiz_score.user_id LEFT JOIN ".$metaTable." ON ".$metaTable.".user_id = ".$userTable.".ID AND ".$metaTable.".meta_key = 'location' LEFT JOIN ".$metaTable." meta2 ON meta2.user_id = ".$userTable.".ID AND meta2.meta_key = 'qm_display_name' ";

  if($ville !== null){
      $sql .= "WHERE ".$metaTable.".meta_value='".$ville."'";
  }

  $sql .= "group by quiz_score.user_id ORDER BY avg(quiz_score.score) DESC, sum(quiz_score.time) ASC, count(quiz_score.id) DESC ";

  if($limit != null){
      $sql .= "LIMIT ".$limit;
  }

  $q = $wpdb->get_results($sql);
  $userQuery = $wpdb->get_row("SELECT ".$metaTable.".qm_display_name AS name,  ".$metaTable.".meta_value as city FROM quiz_score LEFT JOIN ".$userTable." ON ".$userTable.".ID = quiz_score.user_id LEFT JOIN ".$metaTable." ON ".$metaTable.".user_id = ".$userTable.".ID AND ".$metaTable.".meta_key = 'location' WHERE ".$userTable.".ID='.$userId.'");
  $place = null;
  $userStat = null;

  if (array_search($userId, array_column($q,'user_id')) !== false){
      $place = array_search($userId, array_column($q,'user_id')) + 1;
      $userStat = $q[array_search($userId, array_column($q,'user_id'))];
  }

  return array(
      "classement" => array_slice($q, 0, 30),
      "userPlace" => $place,
      "userStat" => $userStat,
  );
}


function getCityClassement($quizId = null){
  global $wpdb;

  $userTable = $wpdb->prefix.'users';
  $metaTable = $wpdb->prefix.'usermeta';
  
  //Select la moyenne du score de la table quiz_score comme "moyenne" + la somme du temps dans la table quiz_score comme "temps" 
  //+ le nombre d'idi dans quiz_score comme "compteur de quizs" + le nom de la ville (meta value) dans la table wp_usermeta comme "ville"
  $sql = "SELECT avg(quiz_score.score) AS moyenne,  sum(quiz_score.time) AS time, count(quiz_score.id) AS quizCount, wp_usermeta.meta_value AS city ";
  $sql .= "FROM quiz_score LEFT JOIN ".$userTable." ON ".$userTable.".ID = quiz_score.user_id LEFT JOIN ".$metaTable." ON ".$metaTable.".user_id = ".$userTable.".ID AND ".$metaTable.".meta_key = 'location' ";

  if($quizId !== null){
      $sql .= "WHERE quiz_score.quiz_id='".$quizId."'";
  }
  $sql .= "group by ".$metaTable.".meta_value order by avg(quiz_score.score) DESC, sum(quiz_score.time) ASC, count(quiz_score.id) DESC";
  return $wpdb->get_results($sql);
}

// Tous les résultats de l'user
function getUserResults($userId){
  global $wpdb;

  $userTable = $wpdb->prefix.'users';
  $metaTable = $wpdb->prefix.'usermeta';

  return $wpdb->get_results( "SELECT quiz.name, quiz_score.score, quiz_score.time FROM quiz_score left join quiz ON quiz_score.quiz_id = quiz.id WHERE quiz_score.user_id=$userId" );

}


/// CREATION PAGE A L'INSTALL//////////////////////////////////////////////////////////////////////////////////////////////

function add_my_custom_page() {
  // Create post object
  $menuModule = array(
    'post_title'    => wp_strip_all_tags( 'menu module' ),
    'post_content'  => '[qm_display_module_menu]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $menuModule );

  // Create post object
  $menuQuiz = array(
    'post_title'    => wp_strip_all_tags( 'menu quiz' ),
    'post_content'  => '[qm_display_quiz_menu]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $menuQuiz );

  
  // Create post object
  $createM1 = array(
    'post_title'    => wp_strip_all_tags( 'create module 1' ),
    'post_content'  => '[qm_module_creation_1]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $createM1 );

// Create post object
  $createM2 = array(
    'post_title'    => wp_strip_all_tags( 'create module 2' ),
    'post_content'  => '[qm_module_creation_2]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $createM2 );

  // Create post object
  $createM3 = array(
    'post_title'    => wp_strip_all_tags( 'create module 3' ),
    'post_content'  => '[qm_module_creation_3]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $createM3 );


  // Create post object
  $createQ1 = array(
    'post_title'    => wp_strip_all_tags( 'create quiz 1' ),
    'post_content'  => '[qm_quiz_creation_1]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $createQ1 );

  // Create post object
  $createQ2 = array(
    'post_title'    => wp_strip_all_tags( 'create quiz 2' ),
    'post_content'  => '[qm_quiz_creation_2]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $createQ2  );

    // Create post object
  $createQ3= array(
    'post_title'    => wp_strip_all_tags( 'create quiz 3' ),
    'post_content'  => '[qm_quiz_creation_3]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );
  
  // Insert the post into the database
  wp_insert_post(  $createQ3 );
  
  

  // Create post object
  $listQuiz = array(
    'post_title'    => wp_strip_all_tags( 'liste quizs' ),
    'post_content'  => '[qm_display_quiz_list]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $listQuiz );


    // Create post object
  $listModule = array(
    'post_title'    => wp_strip_all_tags( 'liste modules' ),
    'post_content'  => '[qm_display_module_list]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );
  
  // Insert the post into the database
  wp_insert_post( $listModule );
  

   // Create post object
  $statistics = array(
    'post_title'    => wp_strip_all_tags( 'statistics' ),
    'post_content'  => '[qm_display_stats_admin]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $statistics );
  
  
   // Create post object
   $classements = array(
    'post_title'    => wp_strip_all_tags( 'classements' ),
    'post_content'  => '[qm_display_classement_admin]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $classements );


   // Create post object
  $tagList = array(
    'post_title'    => wp_strip_all_tags( 'ajouter tag' ),
    'post_content'  => '[qm_display_tag_list]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );

  // Insert the post into the database
  wp_insert_post( $tagList );


     // Create post object
  $newCamp = array(
    'post_title'    => wp_strip_all_tags( 'nouvelle campagne' ),
    'post_content'  => '[qm_display_creation_campagne]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );
  
    // Insert the post into the database
    wp_insert_post( $newCamp );


  // Create post object
  $statsCamp = array(
    'post_title'    => wp_strip_all_tags( 'stats campagnes' ),
    'post_content'  => '[qm_display_campagne_stats]',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'page',
  );
      
    // Insert the post into the database
    wp_insert_post( $statsCamp );
  
}

register_activation_hook(__FILE__, 'add_my_custom_page');

?>

