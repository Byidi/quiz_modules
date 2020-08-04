window.addEventListener('load', function () {
  var url = myScript.script_directory;
  var xmlhttp = new XMLHttpRequest();
  var obj = {
    "type" : "global",
    "filtre" : "general",
    "id" : "null"
  }
  dbParam = JSON.stringify(obj);
  xmlhttp.onreadystatechange = function () {
    if(this.readyState == 4 && this.status == 200)
    {
      var myArray = JSON.parse(this.responseText);
      var classement = myArray.classement
      const globalbtn = document.querySelector(".glob"),
            quizbtn = document.querySelector(".quizz"),
            catbtn = document.querySelector(".cat"),
            genfilter = document.querySelector(".gen"),
            sitesfilter = document.querySelector(".sites"),
            select = document.querySelector(".select"),
            selected = document.querySelector(".selected"),
            quizLi = document.querySelectorAll(".quizLi"),
            tagLi = document.querySelectorAll(".tagLi"),
            label = document.querySelector(".labelList"),
            selectCat = document.querySelector(".listCat"),
            selectQuiz = document.querySelector(".listQuiz"),
            dropIcon = document.querySelector(".dropIcon"),
            legend = document.querySelector(".square_legend");
      select.style.display = "none";
      globalbtn.style.background = "#e6e6e6";
      quizbtn.style.background = "#EBB94A";
      catbtn.style.background = "#EBB94A";
      genfilter.style.background = "#e6e6e6";
      sitesfilter.style.background = "#EBB94A";

      let filter = "general",
          type = "global",
          quiz_id = "null",
          tag_id = "null";

      function buildTable(array, filter, type)
      {
        const tbody = document.querySelector("tbody"),
              thead = document.querySelector("thead"),
              spanAbove = document.querySelector(".span_above"),
              spanUnder = document.querySelector(".span_under");
        let pos,
            isAbove = 0,
            isUnder = 0;
        thead.innerHTML = '';
        tbody.innerHTML = '';
        if(type == 'global')
        {
          if(filter=='general')
          {
            thead.innerHTML=`
              <th>Pos</th>
              <th>Joueur</th>
              <th>Site</th>
              <th>Nbr de quiz</th>
              <th>Temps global (en sec.)</th>
              <th>Score</th>
            `
          }
          else
          {
            thead.innerHTML=`
              <th>Pos</th>
              <th>Site</th>
              <th>Nbr de quiz</th>
              <th>Temps global (en sec.)</th>
              <th>Score</th>
            `
          }
        }
        else if(type== 'quiz')
        {
          if(filter=='general')
          {
            thead.innerHTML=`
              <th>Pos</th>
              <th>Joueur</th>
              <th>Site</th>
              <th>Temps global (en sec.)</th>
              <th>Score</th>
            `
          }
          else
          {
            thead.innerHTML=`
              <th>Pos</th>
              <th>Site</th>
              <th>Nbr de quiz</th>
              <th>Temps global (en sec.)</th>
              <th>Score</th>
            `
          }
        }
        else
        {
          if(filter=='general')
          {
            thead.innerHTML=`
              <th>Pos</th>
              <th>Joueur</th>
              <th>Site</th>
              <th>Nbr de quiz</th>
              <th>Temps global (en sec.)</th>
              <th>Score</th>
            `
          }
          else
          {
            thead.innerHTML=`
              <th>Pos</th>
              <th>Site</th>
              <th>Nbr de quiz</th>
              <th>Temps global (en sec.)</th>
              <th>Score</th>
            `
          }
        }
        for (let i = 0; i < array.length; i++) {
          pos = i + 1;
          const tr = document.createElement("tr");
          tbody.appendChild(tr);
          if(pos == 1)
          {
            tr.classList.add("gold");
          }
          else if(pos == 2)
          {
            tr.classList.add("silver");
          }
          else if(pos == 3)
          {
            tr.classList.add("bronze");
          }
          if(type == 'quiz')
          {
            if(filter == 'general')
            {
              tr.innerHTML += `
                <td>${pos}</td>
                <td>${array[i].Joueur}</td>
                <td>${array[i].Site}</td>
                <td>${array[i].Temps}</td>
                `
              if(array[i].Score > 50)
              {
                isAbove += 1;
                tr.innerHTML += `  
                  <td class="green">${parseInt(array[i].Score)}</td>
                `
              }
              else
              {
                isUnder += 1;
                tr.innerHTML += `  
                  <td class="red">${parseInt(array[i].Score)}</td>
                `
              }
            }
            else
            {
              tr.innerHTML += `
              <td>${pos}</td>
              <td>${array[i].Site}</td>
              <td>${array[i].Nombre}</td>
              <td>${array[i].Temps}</td> 
              `
              if(array[i].Moyenne > 50)
              {
                isAbove += 1;
                tr.innerHTML += `  
                  <td class="green">${parseInt(array[i].Moyenne)}</td>
                `
              }
              else
              {
                isUnder += 1;
                tr.innerHTML += `  
                  <td class="red">${parseInt(array[i].Moyenne)}</td>
                `
              }
            }
          }
          else if( type == 'tag')
          {
            if(filter == 'general')
            {
              tr.innerHTML += `
                <td>${pos}</td>
                <td>${array[i].Joueur}</td>
                <td>${array[i].Site}</td>
                <td>${array[i].Nombre}</td>
                <td>${array[i].Temps}</td>
              `
            }
            else
            {
              tr.innerHTML += `
              <td>${pos}</td>
              <td>${array[i].Site}</td>
              <td>${array[i].Nombre}</td>
              <td>${array[i].Temps}</td>
            `
            }
            if(array[i].Moyenne > 50)
            {
              isAbove += 1;
              tr.innerHTML += `  
                <td class="green">${parseInt(array[i].Moyenne)}</td>
              `
            }
            else
            {
              isUnder += 1;
              tr.innerHTML += `  
                <td class="red">${parseInt(array[i].Moyenne)}</td>
              `
            }
          }
          else
          {
            if(filter == 'general')
            {
              tr.innerHTML += `
                <td>${pos}</td>
                <td>${array[i].display_name}</td>
                <td>${array[i].meta_value}</td>
                <td>${array[i].count}</td>
                <td>${array[i].time}</td>
              `
            }
            else
            {
              tr.innerHTML += `
              <td>${pos}</td>
              <td>${array[i].city}</td>
              <td>${array[i].quizCount}</td>
              <td>${array[i].time}</td>
            `
            }
            if(array[i].moyenne > 50)
            {
              isAbove += 1;
              tr.innerHTML += `  
                <td class="green">${parseInt(array[i].moyenne)}</td>
              `
            }
            else
            {
              isUnder += 1;
              tr.innerHTML += `  
                <td class="red">${parseInt(array[i].moyenne)}</td>
              `
            }
          }
        }
        spanAbove.innerHTML = `Moyenne > 50 : ${isAbove}`;
        spanUnder.innerHTML = `Moyenne < 50 : ${isUnder}`;
      }
      function request(obj, type, filter){
        var table = obj;
        dbParamPost = JSON.stringify(table);
        var xmlhttpPost = new XMLHttpRequest();
        if(type == 'global' && filter == 'general')
        {
          xmlhttpPost.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200)
            {
              var myArray = JSON.parse(this.responseText);
              buildTable(myArray.classement, filter, type);
            }
          }
        }
        else
        {
          xmlhttpPost.onreadystatechange = function(){
              if(this.readyState == 4 && this.status == 200)
              {
                var myArray = JSON.parse(this.responseText);
                buildTable(myArray, filter, type);
              }
          }
        }
        xmlhttpPost.open("POST", url  + '/leaderboard_admin.php', true);
        xmlhttpPost.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttpPost.send(dbParamPost);
      }
      quizLi.forEach(li => {
        li.addEventListener("click", ()=>{
          selectQuiz.classList.add("none");
          let idQuiz = li.dataset.id,
              QuizName = li.textContent;
          selected.innerHTML= QuizName;
          quiz_id = idQuiz;
          obj = {
            "type" : type,
            "filtre" : filter,
            "id" : quiz_id
          };
          request(obj, type, filter);
          selectQuiz.classList.add("none");
        })
      });
      tagLi.forEach(li =>{
        li.addEventListener("click", ()=>{
          selectCat.classList.add("none");
          let idTag = li.dataset.id,
              tagName = li.textContent;
          selected.innerHTML= tagName;
          tag_id = idTag;
          obj = {
            "type" : type,
            "filtre" : filter,
            "id" : tag_id
          };
          request(obj, type, filter);
          selectCat.classList.add("none");
        })
      })
      selected.addEventListener("click", ()=>{
        if(type == "quiz")
        {
          if(selectQuiz.classList.contains("none"))
          {
            selectQuiz.classList.remove("none");
          }
          else
          {
            selectQuiz.classList.add("none");
          }
        }
        else if(type == "tag")
        {
          if(selectCat.classList.contains("none"))
          {
            selectCat.classList.remove("none");
          }
          else
          {
            selectCat.classList.add("none");
          }
        }
      })
      
      globalbtn.addEventListener("click", ()=>{
        type = "global"
        globalbtn.style.background = "#e6e6e6";
        quizbtn.style.background = "#EBB94A";
        catbtn.style.background = "#EBB94A";
        label.innerHTML = "";
        obj = {
          "type" : type,
          "filtre" : filter,
          "id" : "null"
        }
        select.style.display = "none";
        request(obj, type, filter);
      })
      quizbtn.addEventListener("click", ()=>{
        type= "quiz"
        globalbtn.style.background = "#EBB94A";
        quizbtn.style.background = "#e6e6e6";
        catbtn.style.background = "#EBB94A";
        label.innerHTML = "Votre quiz :";
        select.style.display = "flex";
        selectCat.classList.add("none");
        selectQuiz.classList.add("none");
      })
      catbtn.addEventListener("click", ()=>{
        type= "tag"
        globalbtn.style.background = "#EBB94A";
        quizbtn.style.background = "#EBB94A";
        catbtn.style.background = "#e6e6e6";
        label.innerHTML = "Votre catégorie :";
        select.style.display = "flex";
        selectQuiz.classList.add("none");
        selectCat.classList.add("none");
      })
      genfilter.addEventListener("click", ()=>{
        filter = "general"
        genfilter.style.background = "#e6e6e6";
        sitesfilter.style.background = "#EBB94A";
        if(type == "quiz")
        {
          obj = {
            "type" : type,
            "filtre" : filter,
            "id" : quiz_id
          }
        }
        else if ( type == "tag") 
        {
          obj = {
            "type" : type,
            "filtre" : filter,
            "id" : tag_id
          }
        }
        else
        {
          obj = {
            "type" : type,
            "filtre" : filter,
            "id" : "null"
          }
        }
        request(obj, type, filter);
      })
      sitesfilter.addEventListener("click", ()=>{
        filter = "sites"
        genfilter.style.background = "#EBB94A";
        sitesfilter.style.background = "#e6e6e6";
        if(type == "quiz")
        {
          obj = {
            "type" : type,
            "filtre" : filter,
            "id" : quiz_id
          }
        }
        else if ( type == "tag") 
        {
          obj = {
            "type" : type,
            "filtre" : filter,
            "id" : tag_id
          }
        }
        else
        {
          obj = {
            "type" : type,
            "filtre" : filter,
            "id" : "null"
          }
        }
        request(obj, type, filter);
      })
      
      buildTable(classement, filter, type);
      // drop the legend
      dropIcon.addEventListener("click", ()=>{
        if(legend.classList.contains("dropLegend"))
        {
          legend.classList.remove("dropLegend");
        }
        else
        {
          legend.classList.add("dropLegend");
        }
      });
    }
  }
  xmlhttp.open("POST", url  + '/leaderboard_admin.php', true);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send(dbParam);
});