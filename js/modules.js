window.addEventListener('load', function () {
  var url = myScript.script_directory;
  var home_url = myScript.home_url;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
  if(this.readyState == 4 && this.status == 200)
  {
    var myArray = JSON.parse(this.responseText);
    const grid = document.querySelector(".grid");
    for (let i = 0; i < myArray.length; i++) {

      const gridElement = document.createElement("div");
      gridElement.classList.add(`element-item` , `${myArray[i].tag_name}`);
      gridElement.setAttribute('category', `${myArray[i].tag_name}`);
      //---------------------------------------------------------
      let moduleContent = `
      <span class="tag">${myArray[i].tag_name}</span>
      <h3>${myArray[i].title}</h3>
      <span class="score">`;
      if( myArray[i].user_prog != null){
        moduleContent += ``+myArray[i].user_prog+``;
      }else{
        moduleContent += `0`;
      }

      if(myArray[i].img == null)
      {
        moduleContent +=` %</span>
        <div class="imgQ">
          <img src="${ url + `/img/imgModuleDefault.jpg}`}" alt="photo du module"/>
          <div class="filter"></div>
        </div>
      `;
      }
      else
      {
        moduleContent +=` %</span>
        <div class="imgQ">
          <img src="${ url + `/img/modules/${myArray[i].img}`}" alt="photo du module"/>
          <div class="filter"></div>
        </div>
      `;
      }

      if( myArray[i].user_prog == 0){
        moduleContent += `<p class="btnModule" data-id="${myArray[i].id}">Commencez</p>`;
      }
      else if( myArray[i].user_prog == 100 )
      {
        moduleContent += `<p class="btnModule" data-id="${myArray[i].id}">Relire</p>`;
      }
      else
      {
        moduleContent += `<p class="btnModule" data-id="${myArray[i].id}">Continuez</p>`;
      }

      gridElement.innerHTML = moduleContent;
      grid.appendChild(gridElement);
    }
    let btnModules = document.querySelectorAll(".btnModule");
    btnModules.forEach(btn => {
      btn.addEventListener("click", (e)=>{
        const id = e.target.dataset.id;
        var urlScript = url + '/play_module.php/?id=' + id;
        var xmlhttp2 = new XMLHttpRequest();
        xmlhttp2.onreadystatechange = function () {
          if(this.readyState == 4 && this.status == 200)
          {
            var myModule = JSON.parse(this.responseText);
            var previous = myModule.previous;
            const divModule = document.createElement("div");
            divModule.classList.add("modulePlay");
            document.body.appendChild(divModule);
            divModule.innerHTML += `
            <div class="module" id="module">
            </div>
            `
            if(myModule.quizs.length != 0 && myModule.finish == "1")
            {
              divModule.innerHTML += `
              <div class="btns">
              <button id="previous">Page précédente</button>
              <button id="next">Page suivante</button>
              <button id="submit">Terminer le module</button>
              <button id="see">Voir les quiz</button>
            </div>`
            }
            else
            {
              divModule.innerHTML += `
              <div class="btns">
              <button id="previous">Page précédente</button>
              <button id="next">Page suivante</button>
              <button id="submit">Terminer le module</button>
              </div>
              `
            }
            divModule.innerHTML +=`
            <div class="progress">
              <div class="progressDone" data-done=""><span class="percentage"></span></div>
            </div>
            `;

            let currentSlide = 0,
            myPages = myModule.slides,
            actualpercent = 0;
            const moduleContainer = document.getElementById('module'),
            submitButton = document.getElementById('submit'),
            progress = document.querySelector('.progressDone'),
            percentage = document.querySelector('.percentage');
            // intro ici avec un if tout simple
            const divIntro = document.createElement("div");
            divIntro.classList.add("intro");
            // let totalSeconds = 0;
            if(myModule.description != "")
            {
              document.body.appendChild(divIntro);
              divIntro.innerHTML = `
                <p class="introP">${myModule.description}</p>
                <button class="begin">Commencer</button>
              `
              const btnIntro = document.querySelector(".begin");
              btnIntro.addEventListener("click", ()=>{
                // var setInt = setInterval(setTime, 1000);
                divIntro.remove();
              })
            }
            // else
            // {
            //  var setInt = setInterval(setTime, 1000);
            // }
            if(previous.length > 0)
            {
                currentSlide = parseInt(previous[previous.length -1].order) + 1;
                divIntro.remove();
                // if(myModule.description == "")
                // {
                //   var setInt = setInterval(setTime, 1000);
                // }
            }

            // function setTime() {
            //   ++totalSeconds;
            // }

            function progressBar()
            {
             actualpercent = ((currentSlide + 1) / myPages.length) * 100;
              progress.dataset.done = Math.ceil(actualpercent);
              progress.style.width = progress.getAttribute('data-done')+ '%';
              progress.style.opacity = 1;
              percentage.innerHTML = `${Math.ceil(actualpercent)} %`;
            }

            function buildModule(){

              progressBar();
              // variable to store the HTML output
              const output = [];

              let numPage = 0;
                // for each page...
                myPages.forEach(
                  (currentPage, pageNumber) => {

                    numPage += 1;
                    // add this page and its content to the output
                    if(currentPage.img_path != null)
                    {
                      if(currentPage.content == "")
                      {
                        output.push(
                          `<div class="slide" id="slide_${pageNumber}">
                            <span>
                              ${numPage}/${myPages.length}
                            </span>
                            <div class="content">
                              <h3 class="absoluteh3">${currentPage.title}</h3>
                              <div class="medias mediaFull">
                                <img src="${ url + `/img/modules/${currentPage.img_path}`}" alt="photo de la page"/>
                              </div>
                            </div>
                          </div>`
                        );
                      }
                      else
                      {
                        output.push(
                          `<div class="slide" id="slide_${pageNumber}">
                            <span>
                              ${numPage}/${myPages.length}
                            </span>
                            <div class="content">
                              <div class="medias">
                                <img src="${ url + `/img/modules/${currentPage.img_path}`}" alt="photo de la page"/>
                              </div>
                              <div class="para">
                                <h3>${currentPage.title}</h3>
                                <p>${currentPage.content}</p>
                              </div>
                            </div>
                          </div>`
                        );
                      }
                    }else if(currentPage.video !== null){
                      let youtubeHash = currentPage.video.match(/^.*v=(.*)$/);
                      var video = ' <iframe src="https://www.youtube.com/embed/'+youtubeHash[1]+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> ';
                      if(currentPage.video.match(/^.*(youtube).*/) == null  ){
                        video = ' <a href="'+currentPage.video+'">Voir la vidéo</a> ';
                      }
                      if(currentPage.content == "")
                      {
                        output.push(
                          `<div class="slide" id="slide_${pageNumber}">
                            <span>
                              ${numPage}/${myPages.length}
                            </span>
                            <div class="content">
                              <h3 class="absoluteh3">${currentPage.title}</h3>
                              <div class="medias mediaFull">
                                ${video}
                              </div>
                            </div>
                          </div>`
                        );
                      }
                      else
                      {
                        output.push(
                          `<div class="slide" id="slide_${pageNumber}">
                            <span>
                              ${numPage}/${myPages.length}
                            </span>
                            <div class="content">
                              <div class="medias">
                                ${video}
                              </div>
                              <div class="para">
                                <h3>${currentPage.title}</h3>
                                <p>${currentPage.content}</p>
                              </div>
                            </div>
                          </div>`
                        );
                      }
                    }
                    else
                    {
                      output.push(
                        `<div class="slide" id="slide_${pageNumber}">
                          <span>
                            ${numPage}/${myPages.length}
                          </span>
                          <div class="content">
                            <div class="para paraFull">
                              <h3>${currentPage.title}</h3>
                              <p>${currentPage.content}</p>
                              <div class="shadow"></div>
                            </div>
                          </div>
                        </div>`
                      );
                    }
                  }
                );
                // finally combine our output list into one string of HTML and put it on the page
                moduleContainer.innerHTML = output.join('');
            }

            function endModule(){
              var obj = {
                "module_id" : myModule.id,
              };

              dbParam = JSON.stringify(obj);
              xmlhttp = new XMLHttpRequest();
              xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                  const div = document.createElement("div"),
                  divPlay = document.querySelector(".modulePlay");
                  div.classList.add("recapModule");
                  divPlay.appendChild(div);
                  if(myModule.quizs.length != 0)
                  {
                    div.innerHTML =`
                      <i class="fas fa-times endRecap"></i>
                      <div class="contentRecap">
                        <p>Bravo, vous venez de terminer le module "${myModule.title}" </br> Vous pouvez désormais suivre un autre module ou faire le(s) quiz(s) associé(s) à ce dernier :</p>
                        <ul class="listQuizMod">
                        </ul>
                      </div>
                    `
                    const liste = document.querySelector(".listQuizMod");
                    for (let i = 0; i < myModule.quizs.length; i++) {
                      liste.innerHTML += `
                        <li>
                          <div class="divQuiz" data-id="${i}">
                            <div class="contentQuiz">
                              <span>${myModule.quizs[i].name}</span>
                              <span class="tag">${myModule.quizs[i].tag_name}</span>
                              <div class="img">
                                <img src="${ url + `/img/quizs/${myModule.quizs[i].img}`}" alt="photo du quiz"/>
                              </div>
                            </div>
                          </div>
                        </li>
                      `
                    }
                    const quizs = myModule.quizs;
                    let myQuizs = document.querySelectorAll(".divQuiz");
                    myQuizs.forEach(quiz => {
                      quiz.addEventListener("click", (e)=>{
                        const id = quiz.dataset.id;
                        const myQuizz = quizs[id];
                        if(myQuizz.finish == "1"){
                          const quizFinished = document.createElement("div");
                          quizFinished.classList.add("quizFinished");
                          document.body.appendChild(quizFinished);
                          quizFinished.innerHTML = "<p>Vous avez déjà terminé ce quiz.</p><i class='closeDivMess fas fa-times'></i>";
                          const close = document.querySelectorAll(".closeDivMess");
                          const quizMess = document.querySelectorAll(".quizFinished");
                          close.forEach(cross => {
                            cross.addEventListener("click", ()=>{
                              for (let i = 0; i < quizMess.length; i++) {
                                quizMess[i].remove();
                              }
                            })
                          });
                        }
                        else{
                          const quizMess = document.querySelectorAll(".quizFinished");
                          for (let i = 0; i < quizMess.length; i++) 
                          {
                            quizMess[i].remove();
                          }
                          var previousQuiz = myQuizz.previous;
                          const divQuizz = document.createElement("div");
                          divQuizz.classList.add("quizPlay");
                          document.body.appendChild(divQuizz);
                          divQuizz.innerHTML = `
                          <div class="quiz" id="quiz"></div>
                          <div class="btns btnQuiz">
                          <button id="nextQuiz">Prochaine question</button>
                          <button id="submitQuiz">Terminer le quiz</button>
                          </div>
                          <div id="results">
                          </div>
                          <div class="timer">
                          <label id="minutes">00</label>:<label id="seconds">00</label>
                          </div>
                          <div class="progress progressQuiz">
                            <div class="progressDone progressDoneQuiz" data-done=""><span class="percentage percentageQuiz"></span></div>
                          </div>
                          `;
                          
                          let currentSlideQuiz = 0;
                          let myQuestions = myQuizz.questions;
                          let actualpercent = 0;
                            const quizContainer = document.getElementById('quiz');
                            const resultsContainer = document.getElementById('results');
                            const submitButtonQuiz = document.getElementById('submitQuiz');
                            const btnsQuiz = document.querySelector('.btnQuiz');
                            const progressQuiz = document.querySelector('.progressDoneQuiz');
                            const percentageQuiz = document.querySelector('.percentageQuiz');
                            const timer = document.querySelector('.timer');
                            var minutesLabel = document.getElementById("minutes");
                            var secondsLabel = document.getElementById("seconds");
                            if(previousQuiz.length > 0)
                            {
                              var totalSeconds = previousQuiz[previousQuiz.length -1].time;
                            }
                            else
                            {
                              var totalSeconds = 0;
                            }
                            var setInt = setInterval(setTime, 1000);
              
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
                            if(previousQuiz.length > 0)
                            {
                              var tableLostQuestions = [];
                              for (let i = 0; i < previousQuiz.length; i++)
                              {
                                for (let f = 0; f < myQuestions.length; f++)
                                {
                                  if(myQuestions[f].id == previousQuiz[i].id_question)
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
                            let percent = (currentSlideQuiz + 1 / myQuestions.length) * 100;
              
                            function progressBarQuiz()
                            {
                              actualpercent += parseFloat(percent);
                              progressQuiz.dataset.done = Math.ceil(actualpercent);
                              progressQuiz.style.width = progressQuiz.getAttribute('data-done')+ '%';
                              progressQuiz.style.opacity = 1;
                              percentageQuiz.innerHTML = `${Math.ceil(actualpercent)} %`;
                            }
              
                            function buildQuiz(){
              
                            progressBarQuiz();
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
                                        `<div class="slide slideQuiz">
                                          <span class="span">
                                            ${numQuestion}/${myQuestions.length}
                                          </span>
                                          <div class="question"><span>${numQuestion}.</span> ${currentQuestion.content} </div>
                                          <div class="answers">${answers.join('')}</div>
                                        </div>`
                                      );
                                    }else{
                                      if( currentQuestion.url.match(/^.*(youtube).*/) == null ){
                                        output.push(
                                          `<div class="slide slideQuiz">
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
                                        `<div class="slide slideQuiz">
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
                                      `<div class="slide slideQuiz">
                                        <span class="span">
                                          ${numQuestion}/${myQuestions.length}
                                        </span>
                                        <div class="img">
                                          <img src="${ url + `/img/quizs/${currentQuestion.img_path}`}" alt="photo de la question"/>
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
              
                            const nextButtonQuiz = document.getElementById("nextQuiz");
                            const slidesQuiz = document.querySelectorAll(".slideQuiz");
              
                            function showSlideQuiz(n) {
                              slidesQuiz[currentSlideQuiz].classList.remove('active-slideQuiz');
                              slidesQuiz[n].classList.add('active-slideQuiz');
                              currentSlideQuiz = n;
                              if(currentSlideQuiz === slidesQuiz.length-1){
                                nextButtonQuiz.style.display = 'none';
                                submitButtonQuiz.style.display = 'inline-block';
                              }
                              else{
                                nextButtonQuiz.style.display = 'inline-block';
                                submitButtonQuiz.style.display = 'none';
                              }
                            }
              
                            var id_question;
                            var id_answer;
                            var is_True;
              
                            function recupIds(){
                              id_question = myQuestions[currentSlideQuiz].id;
                              id_answer = null;
                              is_True = "false";
              
                              let answerChecked = [];
                              for (let i = 0; i < myQuestions[currentSlideQuiz].answers.length; i++) {
                                let input = document.querySelector(`.input${currentSlideQuiz}${i}`);
                                if(input.checked)
                                {
                                  answerChecked.push(parseInt(input.id));
                                }
                              }
                              return answerChecked;
                            }
              
                            // Show the first slide
                            showSlideQuiz(currentSlideQuiz);
              
                            function showNextSlideQuiz(finish) {
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
                                      showSlideQuiz(currentSlideQuiz + 1);
                                      progressBarQuiz();
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
                              btnsQuiz.remove();
                          }
                
                          // Event listeners
                          nextButtonQuiz.addEventListener("click", function(){
                              showNextSlideQuiz(false);
                          });
            
                          // on submit, show results
                          submitButtonQuiz.addEventListener('click', function(){
                              showNextSlideQuiz(true);
                          });
                        }
                      })
                    });
                  }
                  else
                  {
                    div.innerHTML =`
                      <i class="fas fa-times endRecap"></i>
                      <div class="contentRecap">
                        <p>Bravo, vous venez de terminer le module "${myModule.title}" </br> Il n'y a aucun quiz associé à ce module, vous pouvez donc retourner au menu pour commencer un autre module ou refaire ce dernier.</p>
                      </div>
                    `
                  }
                  const cross = document.querySelector(".endRecap");

                  cross.addEventListener("click", ()=>{
                    window.location.reload();
                  })
                }
              };
              xmlhttp.open("POST", url + "/module_finish.php", true);
              xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              xmlhttp.send(dbParam);
            }

            buildModule();

            const nextButton = document.getElementById("next");
            const previousButton = document.getElementById("previous");
            const slides = document.querySelectorAll(".slide");

            function showSlide(n) {
              slides[currentSlide].classList.remove('active-slide');
              document.querySelectorAll(".slide").forEach( curslide => curslide.classList.remove('active-slide'));
              document.querySelector("#slide_" + n).classList.add('active-slide');
              currentSlide = n;
              if(currentSlide === 0){
                previousButton.style.display = 'none';
              }
              else{
                previousButton.style.display = 'inline-block';
              }
              if(currentSlide === slides.length-1){
                nextButton.style.display = 'none';
                submitButton.style.display = 'inline-block';
              }
              else{
                nextButton.style.display = 'inline-block';
                submitButton.style.display = 'none';
              }
            }
            showSlide(currentSlide);

            function showNextSlide() {
              var id_page,

              id_page = myPages[currentSlide].id;

              var obj = {
                "slide_id": id_page,
                "module_id" : myModule.id,
              };

              dbParam = JSON.stringify(obj);
              xmlhttp = new XMLHttpRequest();
              xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                  // actualpercent += parseFloat(percent);
                  showSlide(currentSlide + 1);
                  progressBar();
                }
              };
              xmlhttp.open("POST", url + "/module_user.php/", true);
              xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              xmlhttp.send(dbParam);
            }

            function showPreviousSlide() {
              showSlide(currentSlide - 1);
              // actualpercent -= parseFloat(percent);
              progressBar();
            }

            if(myModule.quizs.length != 0 && myModule.finish == "1")
            {
              const see = document.querySelector("#see");
              see.addEventListener("click", endModule);
            }
            
            nextButton.addEventListener("click", showNextSlide);

            previousButton.addEventListener("click", showPreviousSlide);

            submitButton.addEventListener('click', endModule);


            moduleContainer.innerHTML += '<i class="fas fa-times endModule"></i>';

            const crossEnd = document.querySelector(".endModule");

            crossEnd.addEventListener("click", ()=>{
              window.location.reload();
            })
          }
        };

        // url a trouver
        xmlhttp2.open("GET", urlScript , true);
        xmlhttp2.send();
      })
    });
      //isotope initialized (with jquery)
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
  }
  };

  // url a trouver
  xmlhttp.open("GET", url  + '/menu_modules.php', true);
  xmlhttp.send();
});
