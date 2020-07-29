window.addEventListener('load', function () {
  var admin = myScript.admin;
  var editor = myScript.editor;
  const bp = document.querySelector(".bpas-post-form-wrapper");

  if(admin || editor)
  {
    bp.style.display = "block";
  }
  else
  {
    bp.style.display = "none";
  }
  
  var url = myScript.script_directory;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
  if(this.readyState == 4 && this.status == 200)
  {
    var myArray = JSON.parse(this.responseText),
    lastQuiz = myArray.lastQuiz,
    userResults = myArray.userResults,
    leadTown = myArray.top30UserVille,
    leadGen = myArray.top30User,
    top30Gen = leadGen.classement,
    top30Town = leadTown.classement,
    userStat = leadGen.userStat,
    gen = document.querySelector(".gen"),
    lastQ = document.querySelector(".lastQ"),
    town = document.querySelector(".town"),
    actu = document.querySelector(".actu"),
    results = document.querySelector(".results"),
    news = document.querySelector(".news"),
    quiz = document.querySelector(".quiz"),
    tbody = document.querySelector(".tbody"),
    leaderboard = document.querySelector(".leaderboard");
    let tableContent,
    isPresent = false;
    // condition for user whithout any user stats
    if(userResults.length != 0)
    {
      // loop for leaderboard's making
      for (i = 0; i < top30Gen.length; i++) 
      {
          pos = i + 1;
          // condition in order to know if user is in top 10 or not
          if(parseInt(userStat.user_id) == parseInt(top30Gen[i].user_id))
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="imp">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 1)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="gold">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 2)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="silver">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 3)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="bronze">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else
          {
            tbody.innerHTML += `
            <tr>
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
      }
      if(isPresent != true)
      {
        tbody.innerHTML += `
        <tr class="imp">
          <td>${leadGen.userPlace}</td>
          <td>${userStat.display_name}</td> 
          <td>${userStat.meta_value}</td>
          <td>${parseInt(userStat.moyenne)}</td>
        </tr>
        `
      }
      gen.addEventListener("click", ()=>{
        town.classList.remove("activated");
        gen.classList.add("activated");
        isPresent= false;
        tbody.innerHTML ='';
        for (i = 0; i < top30Gen.length; i++) 
        {
          pos = i + 1;
          if(parseInt(userStat.user_id) == parseInt(top30Gen[i].user_id))
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="imp">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td> 
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 1)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="gold">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 2)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="silver">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 3)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="bronze">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else
          {
            tbody.innerHTML += `
            <tr>
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
        }
        if(isPresent != true)
        {
          tbody.innerHTML += `
          <tr class="imp">
            <td>${leadGen.userPlace}</td>
            <td>${userStat.display_name}</td>    
            <td>${userStat.meta_value}</td>
            <td>${parseInt(userStat.moyenne)}</td>
          </tr>
          `
        }
      })
      town.addEventListener("click", ()=>{
        gen.classList.remove("activated");
        town.classList.add("activated");
        tbody.innerHTML ='';
        isPresent = false;
        for (i = 0; i < top30Town.length; i++) 
        {
          pos = i + 1;
          if(parseInt(userStat.user_id) == parseInt(top30Town[i].user_id))
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="imp">
              <td>${pos}</td>
              <td>${top30Town[i].display_name}</td> 
              <td>${top30Town[i].meta_value}</td>
              <td>${parseInt(top30Town[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 1)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="gold">
              <td>${pos}</td>
              <td>${top30Town[i].display_name}</td>  
              <td>${top30Town[i].meta_value}</td>
              <td>${parseInt(top30Town[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 2)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="silver">
              <td>${pos}</td>
              <td>${top30Town[i].display_name}</td>  
              <td>${top30Town[i].meta_value}</td>
              <td>${parseInt(top30Town[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 3)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="bronze">
              <td>${pos}</td>
              <td>${top30Town[i].display_name}</td>  
              <td>${top30Town[i].meta_value}</td>
              <td>${parseInt(top30Town[i].moyenne)}</td>
            </tr>
            `
          }
          else
          {
            tbody.innerHTML += `
            <tr>
              <td>${pos}</td>
              <td>${top30Town[i].display_name}</td>  
              <td>${top30Town[i].meta_value}</td>
              <td>${parseInt(top30Town[i].moyenne)}</td>
            </tr>
            `
          }
        }
        if(isPresent != true)
        {
          tbody.innerHTML += `
          <tr class="imp">
            <td>${leadGen.userPlace}</td>
            <td>${userStat.display_name}</td>  
            <td>${userStat.meta_value}</td>
            <td>${parseInt(userStat.moyenne)}</td>
          </tr>
          `
        }
      })
    }
    else
    {
      for (i = 0; i < top30Gen.length; i++) 
      {
        pos = i + 1;
          if (pos == 1)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="gold">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 2)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="silver">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 3)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="bronze">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else
          {
            tbody.innerHTML += `
              <tr>
                <td>${pos}</td>
                <td>${top30Gen[i].display_name}</td>  
                <td>${top30Gen[i].meta_value}</td>
                <td>${parseInt(top30Gen[i].moyenne)}</td>
              </tr>
            `
          }
      }
      gen.addEventListener("click", ()=>{
        town.classList.remove("activated");
        gen.classList.add("activated");
        tbody.innerHTML ='';
        for (i = 0; i < top30Gen.length; i++) 
        {
          pos = i + 1;
          if (pos == 1)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="gold">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 2)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="silver">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 3)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="bronze">
              <td>${pos}</td>
              <td>${top30Gen[i].display_name}</td>  
              <td>${top30Gen[i].meta_value}</td>
              <td>${parseInt(top30Gen[i].moyenne)}</td>
            </tr>
            `
          }
          else
          {
            tbody.innerHTML += `
              <tr>
                <td>${pos}</td>
                <td>${top30Gen[i].display_name}</td>  
                <td>${top30Gen[i].meta_value}</td>
                <td>${parseInt(top30Gen[i].moyenne)}</td>
              </tr>
            `
          }
        }
      })
      town.addEventListener("click", ()=>{
        gen.classList.remove("activated");
        town.classList.add("activated");
        tbody.innerHTML ='';
        for (i = 0; i < top30Town.length; i++) 
        {
          pos = i + 1;
          if (pos == 1)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="gold">
              <td>${pos}</td>
              <td>${top30Town[i].display_name}</td>  
              <td>${top30Town[i].meta_value}</td>
              <td>${parseInt(top30Town[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 2)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="silver">
              <td>${pos}</td>
              <td>${top30Town[i].display_name}</td>  
              <td>${top30Town[i].meta_value}</td>
              <td>${parseInt(top30Town[i].moyenne)}</td>
            </tr>
            `
          }
          else if (pos == 3)
          {
            isPresent = true;
            tbody.innerHTML += `
            <tr class="bronze">
              <td>${pos}</td>
              <td>${top30Town[i].display_name}</td>  
              <td>${top30Town[i].meta_value}</td>
              <td>${parseInt(top30Town[i].moyenne)}</td>
            </tr>
            `
          }
          else
          {
            tbody.innerHTML += `
              <tr>
                <td>${pos}</td>
                <td>${top30Town[i].display_name}</td>  
                <td>${top30Town[i].meta_value}</td>
                <td>${parseInt(top30Town[i].moyenne)}</td>
              </tr>
            `
          }
        }
      })
    }
    
    // creation of last quiz ancer
    const elementQuiz = document.createElement("div");
    elementQuiz.classList.add("contentQ");
    elementQuiz.innerHTML = `
      <a href="${myScriptDir.home_url}/menu-quiz/">
      <div class="filter"></div>
      <img src="${ url + `/img/quizs/${lastQuiz.img}`}" alt="photo du quiz"/>
      <h2>${lastQuiz.name}</h2>
      <p>${lastQuiz.tag_name}</p>
      </a>
    `;
    lastQ.appendChild(elementQuiz);

    let lastResults,
    labels = [],
    points = [];
    if(userResults.length > 10)
    {
      lastResults = userResults.slice(Math.max(userResults.length - 10, 1));
    }
    else
    {
      lastResults = userResults;
    }

    for ( i = 0; i < lastResults.length; i++) {
      labels.push(lastResults[i].name);
      points.push(parseInt(lastResults[i].score));
    }


    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels : labels,
        datasets: [{
          label: 'score',
          data: points,
          pointBackgroundColor: '#E2B34A',
          borderWidth: 3,
          borderColor: 'rgba(226, 179, 74, 0.3)',
          backgroundColor: 'rgba(25,34,49,0.5)',
        }]
      },
      options: {
        legend: {
          display: false,
        },
        animation: {
          easing: 'easeInOutQuad',
          duration: 520
        },
        scales: {
          xAxes: [{
            display: false,
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
      },
    })
    Chart.defaults.global.defaultFontColor='white';
    Chart.defaults.global.defaultFontFamily='Muli';
  }
  else
  {
  }
  };

  // url a trouver
  xmlhttp.open("GET", url  + '/dashboard_back.php?all=true', true);
  xmlhttp.send();
});

