window.addEventListener('load', function () {
  var url = myScript.script_directory;
  var home_url = myScript.home_url;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
  if(this.readyState == 4 && this.status == 200)
  {
    var myArray = JSON.parse(this.responseText);
    const grid = document.querySelector(".grid");
    for(i = 0; i<myArray.quiz.length; i ++)
    {
      const gridElement = document.createElement("div");
      gridElement.classList.add(`element-item` , `${myArray.quiz[i].tag_name}`);
      gridElement.setAttribute('category', `${myArray.quiz[i].tag_name}`);
      //---------------------------------------------------------
      let quizContent = `
      <span class="tag">${myArray.quiz[i].tag_name}</span>
      <span class="mod">Module: <span class="spanQuiz" id="mod${myArray.quiz[i].id}"></span></span>
      <h3>${myArray.quiz[i].name}</h3>
      <span class="score">`;
      if( myArray.quiz[i].user_score != null){
        quizContent += ``+myArray.quiz[i].user_score+``;
      }else{
        quizContent += `0`;
      }
      if(myArray.quiz[i].img === null)
      {
        quizContent +=` pts</span>
        <div class="imgQ">
          <img src="${ url + `/img/imgQuizDefault.jpg}`}" alt="photo du quiz"/>
          <div class="filter"></div>
          </div>
      `;
      }
      else
      {
        quizContent +=` pts</span>
        <div class="imgQ">
          <img src="${myArray.quiz[i].img}" alt="photo du quiz"/>
          <div class="filter"></div>
          </div>
      `;
      }
      if( myArray.quiz[i].user_score == null){
        quizContent += `<p class="btnQuiz" data-id="${myArray.quiz[i].id}">Jouer</p>`;
      }
    //-----------------------------------------------------------------------------
      gridElement.innerHTML = quizContent;
      grid.appendChild(gridElement);
      if(myArray.quiz[i].moduleRelated.length > 0)
      {
        const moduleRelated = myArray.quiz[i].moduleRelated;
        const span = document.querySelector(`#mod${myArray.quiz[i].id}`);
        if(moduleRelated.length == 1)
        {
          for (let f = 0; f < moduleRelated.length; f++) {   
            span.innerHTML += `
              <a href="${home_url}/menu-module" target="_blank">${moduleRelated[f].title}</a>
            `;
          }
        }
        else
        {
          const list = document.createElement("ul");
          list.classList.add("list");
          span.appendChild(list);
          for (let f = 0; f < 8; f++) {
            list.innerHTML += `
            <li><a href="${home_url}/menu-module" target="_blank">${moduleRelated[0].title}</a></li>
            `;
          }
          span.innerHTML +=`<p class="btnlist" data-id="list${myArray.quiz[i].id}">Voir plus</p>`;
          const listQuiz = document.querySelector(".list"),
          btnlist = document.querySelector(".btnlist");
  
          btnlist.addEventListener("click", ()=>{
            if(listQuiz.classList.contains("listAppear"))
            {
              listQuiz.classList.remove("listAppear");
            }
            else
            {
              listQuiz.classList.add("listAppear");
            }
          })
        }
      }
      else
      {
        const span = document.querySelector(`#mod${myArray.quiz[i].id}`);
        span.innerHTML += "aucun";
      }
    }
    let btnQuizs = document.querySelectorAll(".btnQuiz");
    btnQuizs.forEach(btn => {
      btn.addEventListener("click", (e)=>{
        const id = e.target.dataset.id;
        var urlScript = url + '/play_quiz.php/?id=' + id;
        var xmlhttp2 = new XMLHttpRequest();
        xmlhttp2.onreadystatechange = function () {
          if(this.readyState == 4 && this.status == 200)
          {
            var myQuizz = JSON.parse(this.responseText);
            var previous = myQuizz.previous;
            const divQuizz = document.createElement("div");
            divQuizz.classList.add("quizPlay");
            document.body.appendChild(divQuizz);
            divQuizz.innerHTML = `
            <div class="quiz" id="quiz"></div>
            <div class="btnsQuiz">
            <button id="next">Prochaine question</button>
            <button id="submit">Terminer le quiz</button>
            </div>
            <div id="results">
            </div>
            <div class="timer">
            <label id="minutes">00</label>:<label id="seconds">00</label>
            </div>
            <div class="progress">
              <div class="progressDone" data-done=""><span class="percentage"></span></div>
            </div>
            `;

            let currentSlide = 0;
            let myQuestions = myQuizz.questions;
            let actualpercent = 0;
              const quizContainer = document.getElementById('quiz');
              const resultsContainer = document.getElementById('results');
              const submitButton = document.getElementById('submit');
              const btns = document.querySelector('.btnsQuiz');
              const progress = document.querySelector('.progressDone');
              const percentage = document.querySelector('.percentage');
              const timer = document.querySelector('.timer');
              var minutesLabel = document.getElementById("minutes");
              var secondsLabel = document.getElementById("seconds");

              const divIntro = document.createElement("div");
              divIntro.classList.add("intro");
              if(myQuizz.description != "")
              {
                document.body.appendChild(divIntro);
                divIntro.innerHTML = `
                  <p class="introP">${myQuizz.description}</p>
                  <button class="begin">Commencer</button>
                `
                const btnIntro = document.querySelector(".begin");
                btnIntro.addEventListener("click", ()=>{
                  divIntro.remove();
                  var setInt = setInterval(setTime, 1000);
                })
              }
              else 
              {
                var setInt = setInterval(setTime, 1000);
              }

              if(previous.length > 0)
              {
                var totalSeconds = previous[previous.length -1].time;
                divIntro.remove();
                if(myQuizz.description != "")
                {
                  var setInt = setInterval(setTime, 1000);
                }
              }
              else
              {
                var totalSeconds = 0;
              }

              function setTime() {
                ++totalSeconds;
                secondsLabel.innerHTML = pad(totalSeconds % 60);
                minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
              }

              function pad(val) {
                var valString = val + "";
                if (valString.length < 2) {
                  return "0" + valString;
                } else {
                  return valString;
                }
              }

              function shuffle(array)
              {
                array.sort(()=> Math.random()-0.5);
              }

              shuffle(myQuestions);
              if(previous.length > 0)
              {
                var tableLostQuestions = [];
                for (let i = 0; i < previous.length; i++)
                {
                  for (let f = 0; f < myQuestions.length; f++)
                  {
                    if(myQuestions[f].id == previous[i].id_question)
                    {
                      var idCurrentQuestion = myQuestions.indexOf(myQuestions[f]);
                      if(idCurrentQuestion > -1)
                      {
                        tableLostQuestions.splice(0, 0, myQuestions[f]);
                        myQuestions.splice(idCurrentQuestion, 1);
                      }
                    }
                  }
                }
              }
              let percent = (currentSlide + 1 / myQuestions.length) * 100;

              function progressBar()
              {
                actualpercent += parseFloat(percent);
                progress.dataset.done = Math.ceil(actualpercent);
                progress.style.width = progress.getAttribute('data-done')+ '%';
                progress.style.opacity = 1;
                percentage.innerHTML = `${Math.ceil(actualpercent)} %`;
              }

              function buildQuiz(){

              progressBar();
              // variable to store the HTML output
              const output = [];

              let numQuestion = 0;
                // for each question...
                myQuestions.forEach(
                  (currentQuestion, questionNumber) => {

                    // variable to store the list of possible answers
                    const currentAnswers = currentQuestion.answers;

                    const answers = [];
                    numQuestion += 1;
                    // and for each available answer...
                    shuffle(currentAnswers);
                    for(i = 0; i<currentAnswers.length ; i++){
                      const letters = ['A', 'B' , 'C', 'D'];
                      // ...add an HTML radio button
                      answers.push(
                        `<label>
                          <input id="${currentAnswers[i].id}" class="input${myQuestions.indexOf(currentQuestion)}${[i]}" type="checkbox" name="question${questionNumber}" data-answer="${letters[i]}" value="${currentAnswers[i].is_true}">
                          <p class="answer"><span>${letters[i]}.</span> ${currentAnswers[i].content}</p>
                        </label>`
                      );
                    }
                    // add this question and its answers to the output
                    
                    if(currentQuestion.img_path === null)
                    {
                      if(currentQuestion.url === null)
                      {
                        output.push(
                          `<div class="slide">
                            <span class="span">
                              ${numQuestion}/${myQuestions.length}
                            </span>
                            <div class="question">${numQuestion}. ${currentQuestion.content} </div>
                            <div class="answers">${answers.join('')}</div>
                          </div>`
                        );
                      }else{
                        if( currentQuestion.url.match(/^.*(youtube).*/) == null ){
                          output.push(
                            `<div class="slide">
                              <span class="span">
                                ${numQuestion}/${myQuestions.length}
                              </span>
                              <div class="img">
                                <a href="`+currentQuestion.url+`">Voir la vidéo</a>
                              </div>
                              <div class="question"><span>${numQuestion}.</span> ${currentQuestion.content} </div>
                              <div class="answers">${answers.join('')}</div>
                            </div>`
                          );
                        }
                        let youtubeHash = currentQuestion.url.match(/^.*v=(.*)$/);
                        output.push(
                          `<div class="slide">
                            <span class="span">
                              ${numQuestion}/${myQuestions.length}
                            </span>
                            <div class="img">
                            <iframe src="https://www.youtube.com/embed/`+youtubeHash[1]+`" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                            <div class="question"><span>${numQuestion}.</span> ${currentQuestion.content} </div>
                            <div class="answers">${answers.join('')}</div>
                          </div>`
                        );
                      }
                    }
                    else
                    {
                      output.push(
                        `<div class="slide">
                          <span class="span">
                            ${numQuestion}/${myQuestions.length}
                          </span>
                          <div class="img">
                            <img src="${currentQuestion.img_path}" alt="photo de la question"/>
                          </div>
                          <div class="question"><span>${numQuestion}.</span> ${currentQuestion.content} </div>
                          <div class="answers">${answers.join('')}</div>
                        </div>`
                      );
                    }
                  }
                );
                // finally combine our output list into one string of HTML and put it on the page
                quizContainer.innerHTML = output.join('');
              }

              // display quiz right away
              buildQuiz();

              const nextButton = document.getElementById("next");
              const slides = document.querySelectorAll(".slide");

              function showSlide(n) {
                slides[currentSlide].classList.remove('active-slide');
                slides[n].classList.add('active-slide');
                currentSlide = n;
                if(currentSlide === slides.length-1){
                  nextButton.style.display = 'none';
                  submitButton.style.display = 'inline-block';
                }
                else{
                  nextButton.style.display = 'inline-block';
                  submitButton.style.display = 'none';
                }
              }

              var id_question;
              var id_answer;
              var is_True;

              function recupIds(){
                id_question = myQuestions[currentSlide].id;
                id_answer = null;
                is_True = "false";

                let answerChecked = [];
                for (let i = 0; i < myQuestions[currentSlide].answers.length; i++) {
                  let input = document.querySelector(`.input${currentSlide}${i}`);
                  if(input.checked)
                  {
                    answerChecked.push(parseInt(input.id));
                  }
                }
                return answerChecked;
              }

              // Show the first slide
              showSlide(currentSlide);

              function showNextSlide(finish) {
                let answerChecked = recupIds();

                var obj = {
                  "questions": id_question,
                  "answers": answerChecked,
                  "time": totalSeconds,
                  "id_quiz" : myQuizz.id,
                };

                dbParam = JSON.stringify(obj);
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                    if (!finish){
                        showSlide(currentSlide + 1);
                        progressBar();
                    }else{
                        getResults();
                    }
                  }
                };
                xmlhttp.open("POST", url + "/quiz_answer_user.php/", true);
                xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlhttp.send(dbParam);
              }

            function getResults(){
                var obj = {
                  "id_user": myQuizz.player,
                  "id_quiz" : myQuizz.id,
                };
                dbParam = JSON.stringify(obj);
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        let result = JSON.parse(this.responseText);
                        console.log(result);
                        showFinish(result);
                    }
                };
                xmlhttp.open("POST", url + "/quiz_result.php/", true);
                xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlhttp.send(dbParam);
            }

            function showFinish(result){
                // show number of correct answers out of total
                resultsContainer.style.opacity = "1";
                resultsContainer.innerHTML = `
                <p>${result.good} correct(s) sur ${result.questions.length}</p>
                <p>Vous avez obtenu ${result.score}/100pts en ${result.time} secondes!</p>
                <i class="btnBackMenu fas fa-times"></i>
                <div class="recap">
                </div>
                `;

                const recap = document.querySelector('.recap');

                result.questions.forEach((question, i) => {
                    numQuestion = i+1;

                    const questionDiv = document.createElement("div");
                    questionDiv.classList.add("question");
                    recap.appendChild(questionDiv);

                    const p = document.createElement("p");
                    p.classList.add(`questionRecap`);
                    questionDiv.appendChild(p);
                    p.innerHTML =`<span>${numQuestion}.</span> ${question.content}`;

                    const divAnswer = document.createElement("div");
                    divAnswer.classList.add(`answerRecap${[i]}`, "answerRecap");
                    questionDiv.appendChild(divAnswer);

                    question.answers.forEach((answer, j) => {
                        const letters = ['A', 'B' , 'C', 'D'];

                        const pAnswerDiv = document.createElement("div");
                        pAnswerDiv.classList.add(`answerTF${[j]}${[i]}`, 'answerTF');
                        divAnswer.appendChild(pAnswerDiv);
                        pAnswerDiv.innerHTML = `<span>${letters[j]}:</span> ${answer.content}`;
                        if(answer.is_true == "1" || answer.is_true == "true"){
                            pAnswerDiv.style.color = "#3AD29F";
                        }else{
                            pAnswerDiv.style.color = "red";
                        }
                    });
                });

                const btnBackMenu = document.querySelectorAll(".btnBackMenu");

                btnBackMenu.forEach(btn => {
                  btn.addEventListener("click", ()=>{
                    location.reload();
                  })
                });

                timer.remove();
                quizContainer.remove();
                btns.remove();
            }

            // Event listeners
            nextButton.addEventListener("click", function(){
                showNextSlide(false);
            });

            // on submit, show results
            submitButton.addEventListener('click', function(){
                showNextSlide(true);
            });
          }
        };

        // url a trouver
        xmlhttp2.open("GET", urlScript , true);
        xmlhttp2.send();
      })
    });

    //isotope initialized (with jquery)
    jQuery(document).ready(function($) {
      var $grid = $('.grid').isotope({
        itemSelector: '.element-item',
        masonry: {
          columnWidth: 120,
          isFitWidth: true
          }
      });
      // bind filter button click
      $('.filters-button-group').on( 'click', 'button', function() {
        var filterValue = $( this ).attr('data-filter');
        // use filterFn if matches value
        $grid.isotope({ filter: filterValue });
      });
      // change is-checked class on buttons
      $('.button-group').each( function( i, buttonGroup ) {
        var $buttonGroup = $( buttonGroup );
        $buttonGroup.on( 'click', 'button', function() {
          $buttonGroup.find('.is-checked').removeClass('is-checked');
          $( this ).addClass('is-checked');
        });
      });
    });
  }
  else
  {
  }
  };

  // url a trouver
  xmlhttp.open("GET", url  + '/menu_quiz.php', true);
  xmlhttp.send();
});
