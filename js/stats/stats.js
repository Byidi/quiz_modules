window.addEventListener('load', function () {
var url = myScript.script_directory;
var home_url = myScript.home_url;
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function () {
  if(this.readyState == 4 && this.status == 200)
  {
    var myArray = JSON.parse(this.responseText),
    quizs = myArray.quizs,
    modules = myArray.modules,
    labels = [],
    pourcentages = [],
    borderColor= [],
    backgroundColor= [];

    function buildChart(array, labels, pourcentages, backColor, borderColor){
      labels = [],
      pourcentages = [],
      borderColor= [],
      backColor= [];
      const canva = document.querySelector(".canva");
      for(let i=0; i<array.length; i++)
      {
        labels.push(array[i].titre);
        pourcentages.push(array[i].pourcentage);

        let rgb1 = Math.floor(Math.random()*Math.floor(255)),
        rgb2 = Math.floor(Math.random()*Math.floor(255)),
        rgb3 = Math.floor(Math.random()*Math.floor(255));

        backColor.push(`rgba(${rgb1},${rgb2},${rgb3}, 0.5)`);
        borderColor.push(`rgba(${rgb1},${rgb2},${rgb3}, 1)`)
      }
      let data,
          fontColor,
          options;

      data = {
        labels: labels,
        datasets: [{
          label: '% terminé',
          data: pourcentages,
          backgroundColor: backColor,
          borderColor: borderColor,
          borderWidth: 2
        }]
      };
      options = {
        legend: {
          display: false,
        },
        animation: {
          easing: 'easeInOutQuad',
          duration: 520
        },
        scales: {
          xAxes: [{
            gridLines: {
              color: 'rgba(0,0,0,0)',
            }
          }],
          yAxes: [{
            gridLines: {
              color: 'rgba(0,0,0,0)',
            },
            ticks: {
              beginAtZero: true,
              max: 100
            }
          }]
        },
        elements: {
          line: {
            tension: 0.3
          }
        },
        tooltips: {
          titleFontFamily: 'Muli',
          backgroundColor: 'rgba(0,0,0,0.3)',
          caretSize: 5,
          cornerRadius: 2,
          xPadding: 10,
          yPadding: 10
        },
      };
      canva.remove();
      const newCanva = document.createElement("canvas"),
            canvaDiv = document.querySelector(".canvaDiv");
            newCanva.classList.add("canva");
      canvaDiv.appendChild(newCanva);

      var myChart = new Chart(newCanva, {
        type: "bar",
        data: data,
        options: options
      });
      Chart.defaults.global.defaultFontColor='white';
    }

    buildChart(quizs, labels, pourcentages, backgroundColor, borderColor);
    let typeName ="Quiz",
        filterName= "Général",
        siteName = "",
        obj;

    const quiz = document.querySelector(".quizBtn"),
          module = document.querySelector(".moduleBtn"),
          gen = document.querySelector(".genBtn"),
          site = document.querySelector(".siteBtn"),
          ul = document.querySelector(".listSite"),
          divlist = document.querySelector(".listDiv"),
          lis = document.querySelectorAll(".li"),
          labelSIte = document.querySelector(".labelSite");

    function requestPOST(obj){
      var table = obj;
      dbParamPost = JSON.stringify(table);
      var xmlhttpPost = new XMLHttpRequest();
      xmlhttpPost.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200)
        {
          var myArray = JSON.parse(this.responseText);
          if(typeName == 'Quiz')
          {
            buildChart(myArray.quizs, labels, pourcentages, backgroundColor, borderColor);
          }
          else
          {
            buildChart(myArray.modules, labels, pourcentages, backgroundColor, borderColor);
          }
        }
      }
      xmlhttpPost.open("POST", url  + '/statistics.php', true);
      xmlhttpPost.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xmlhttpPost.send(dbParamPost);
    }
    
    function listenerClickBtns(btn1, btn2)
    {   
      btn1.addEventListener("click", ()=>{
        if(!btn1.classList.contains("activated"))
        {
          btn2.classList.remove("activated");
          btn1.classList.add("activated");
        }
        if(site.classList.contains("activated"))
        {
          divlist.classList.remove("hidden")
        }
        else
        {
          divlist.classList.add("hidden")
        }
        if(btn1 == site || btn1 == gen)
        {
          filterName = btn1.textContent;
        }
        else
        {
          typeName = btn1.textContent;
        }
        if(filterName == "Sites" && siteName == "")
        {
          return
        }
        else if(typeName == "Quiz" && filterName == "Général")
        {
          buildChart(quizs, labels, pourcentages, backgroundColor, borderColor);
        }
        else if(typeName == "Module" && filterName == "Général")
        {
          buildChart(modules, labels, pourcentages, backgroundColor, borderColor);
        }
        else if(typeName == "Quiz" && filterName == "Sites" && siteName != "")
        {
          obj = {
            "site" : siteName
          }
          requestPOST(obj)
        }
        else if(typeName == "Module" && filterName == "Sites" && siteName != "")
        {
          obj = {
            "site" : siteName
          }
          requestPOST(obj);
        }
      })
    }
    listenerClickBtns(quiz, module);
    listenerClickBtns(module, quiz);
    listenerClickBtns(gen, site);
    listenerClickBtns(site, gen);

    labelSIte.addEventListener("click", ()=>{
      if(ul.classList.contains("hidden"))
      {
        ul.classList.remove("hidden")
      }
      else
      {
        ul.classList.add("hidden")
      }
    })

    lis.forEach(li => {
      li.addEventListener("click", ()=>{
        siteName = li.textContent;
        labelSIte.innerHTML = siteName;
        ul.classList.add("hidden");
        obj = {
          "site" : siteName
        }
        requestPOST(obj);
      })
    });

    // extract btn
    let objUsers,
        idQM;
    const extract = document.querySelector(".extract"),
          divMod = document.querySelector(".userModule"),
          quizCross = document.querySelector(".quizCross"),
          modCross = document.querySelector(".modCross"),
          canvaDiv = document.querySelector(".canvaDiv"),
          listMod = document.querySelector(".listMod"),
          lisusers = document.querySelectorAll(".liQM"),
          spanQ = document.querySelectorAll(".spanQ"),
          spanM = document.querySelectorAll(".spanM"),
          listQuiz = document.querySelector(".listQuiz"),
          spans = document.querySelectorAll(".spanQM"),
          tbodyQ = document.querySelector(".tbodyQ"),
          tbodyM = document.querySelector(".tbodyM"),
          divQuiz = document.querySelector(".userQuiz");

    function requestListUser(obj, type){
      var tableUsers = obj;
      dbParamPostUsers = JSON.stringify(tableUsers);
      var xmlhttpPostUsers = new XMLHttpRequest();
      xmlhttpPostUsers.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200)
        {
          var myArray = JSON.parse(this.responseText);
          const spanUserQ = document.querySelector(".nbrUsersQ"),
                spanUserM = document.querySelector(".nbrUsersM");
          if(type == "Quiz")
          {
            tbodyQ.innerHTML = "";
            for(i=0; i<myArray.length; i++)
            {
              tbodyQ.innerHTML += `
                <tr>
                  <td>${myArray[i].Utilisateur}</td>
                  <td>${myArray[i].Site}</td>
                </tr>
              `
            }
            spanUserQ.innerHTML = `Nombre de personnes n'ayant pas terminé ce quiz : <span class="imp">${myArray.length}</span>`;
          }
          else
          {
            tbodyM.innerHTML = "";
            for(i=0; i<myArray.length; i++)
            {
              tbodyM.innerHTML += `
                <tr>
                  <td>${myArray[i].Utilisateur}</td>
                  <td>${myArray[i].Site}</td>
                </tr>
              `
            }
            spanUserM.innerHTML = `Nombre de personnes n'ayant pas terminé ce module : <span class="imp">${myArray.length}</span>`;
          }
        }
      }
      xmlhttpPostUsers.open("POST", url  + '/statistics_undone.php', true);
      xmlhttpPostUsers.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xmlhttpPostUsers.send(dbParamPostUsers);
    }
    extract.addEventListener("click", ()=>{
      if(typeName == "Module")
      {
        if(divMod.classList.contains("hidden"))
        {
          divMod.classList.remove("hidden");
          divQuiz.classList.add("hidden");
          canvaDiv.classList.add("hidden");
          
        }
      }
      else
      {
        if(divQuiz.classList.contains("hidden"))
        {
          divQuiz.classList.remove("hidden");
          divMod.classList.add("hidden");
          canvaDiv.classList.add("hidden");
          
        }
      }
    })
    lisusers.forEach(li => {
      li.addEventListener("click", ()=>{
        idQM = li.dataset.id;
        objUsers = {
          "type": typeName,
          "id" : idQM
        }
        if(typeName == "Quiz")
        {
          listQuiz.classList.add("hidden");
          spanQ.forEach(span => {
            span.innerHTML = li.textContent;
          });
        }
        else
        {
          listMod.classList.add("hidden");
          spanM.forEach(span => {
            span.innerHTML = li.textContent;
          });
        }
        requestListUser(objUsers, typeName);
      })
    });
    spans.forEach(span => {
      span.addEventListener("click", ()=>{
        if(typeName == "Quiz")
        {
          if(listQuiz.classList.contains("hidden"))
          {
            listQuiz.classList.remove("hidden");
          }
          else
          {
            listQuiz.classList.add("hidden");
          }
        }
        else
        {
          if(listMod.classList.contains("hidden"))
          {
            listMod.classList.remove("hidden");
          }
          else
          {
            listMod.classList.add("hidden");
          }
        }
      })
    });
    quizCross.addEventListener("click", ()=>{
      divQuiz.classList.add("hidden");
      canvaDiv.classList.remove("hidden");
    })
    modCross.addEventListener("click", ()=>{
      divMod.classList.add("hidden");
      canvaDiv.classList.remove("hidden");
    })

    // send emails
    const btnSendMail = document.querySelector(".mailSend"),
          divMess = document.querySelector(".confirmMess"),
          no = document.querySelector(".no");
    
    btnSendMail.addEventListener("click", ()=>{
      if(divMess.classList.contains("hidden"))
      {
        divMess.classList.remove("hidden");
      }
    
    })
    no.addEventListener("click", ()=>{
      divMess.classList.add("hidden");
    })

  // notification mess closing
  var notify = document.querySelector(".notify"),
  messNotify = document.querySelector(".notifyConfirm");

  if(notify !== null){
    notify.addEventListener("click", ()=>{
      messNotify.remove();
    })
    }
}
}
xmlhttp.open("GET", url  + '/statistics.php', true);
xmlhttp.send();
});