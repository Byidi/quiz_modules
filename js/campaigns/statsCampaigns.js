window.addEventListener("load", ()=>{
  var url = myScript.script_directory,
      home_url = myScript.script_directory;
  let id,
      objet;
  const ulCamp = document.querySelector(".listCamp"),
        ulCompare = document.querySelector(".listCampC"),
        campName = document.querySelector(".name_camp"),
        spanCamp = document.querySelectorAll(".camp_name"),
        compareName = document.querySelector(".compare_camp"),
        spanCompare = document.querySelectorAll(".compare_name"),
        quizBody = document.querySelector(".bodyQ"),
        quizCompare = document.querySelector(".compareQ"),
        modBody = document.querySelector(".bodyM"),
        modCompare = document.querySelector(".compareM"),
        totalBody = document.querySelector(".total"),
        totalCompare = document.querySelector(".totalCompare"),
        nbrMod = document.querySelector(".nbrMod"),
        nbrModCompare = document.querySelector(".nbrModCompare"),
        nbrQuiz = document.querySelector(".nbrQuiz"),
        nbrQuizCompare = document.querySelector(".nbrQuizCompare"),
        lisCamp = document.querySelectorAll(".liC"),
        lisComp = document.querySelectorAll(".liComp");

  compareName.addEventListener("click", ()=>{
    if(ulCompare.classList.contains("hidden"))
    {
      ulCompare.classList.remove("hidden");
      ulCamp.classList.add("hidden");
    }
    else
    {
      ulCompare.classList.add("hidden");
    }
  })
  campName.addEventListener("click", ()=>{
    if(ulCamp.classList.contains("hidden"))
    {
      ulCamp.classList.remove("hidden");
      ulCompare.classList.add("hidden");
    }
    else
    {
      ulCamp.classList.add("hidden");
    }
  })

  lisCamp.forEach(li => {
    li.addEventListener("click", ()=>{
      id = li.dataset.id;
      ulCamp.classList.add("hidden");
      spanCamp.forEach(span => {
        span.innerHTML =li.textContent;
      });
      objet = {
        "id" : id
      }
      requestPOST(objet, quizBody, modBody, totalBody, nbrQuiz, nbrMod, "trQ", "trM", "trT", "moyQ", "moyQT", "", "", "", "", "", "");
    })
  });
  lisComp.forEach(li => {
    li.addEventListener("click", ()=>{
      id = li.dataset.id;
      ulCompare.classList.add("hidden");
      spanCompare.forEach(span => {
        span.innerHTML =li.textContent;
      });
      objet = {
        "id" : id
      }
      requestPOST(objet, quizCompare, modCompare, totalCompare, nbrQuizCompare, nbrModCompare, "trQC", "trMC", "trTC", "moyQC", "moyQTC", "spanPQ", "spanMQ", "spanPM", "spanPQT", "spanPMT", "spanMT");
    })
  });

  function compare(){
    const trQ = document.querySelectorAll(".trQ");

    for (let i = 0; i < trQ.length; i++) {
      const numbQ = document.querySelector(`.trQ${i}`).textContent,
            numbM = document.querySelector(`.trM${i}`).textContent,
            numbQC = document.querySelector(`.trQC${i}`).textContent,
            numbMC = document.querySelector(`.trMC${i}`).textContent,
            numbMoy = document.querySelector(`.moyQ${i}`).textContent,
            numbMoyC = document.querySelector(`.moyQC${i}`).textContent;
      const spanPQ = document.querySelector(`.spanPQ${i}`),
            spanPM= document.querySelector(`.spanPM${i}`),
            spanMQ= document.querySelector(`.spanMQ${i}`);
      const diffPQC = parseInt(numbQC) - parseInt(numbQ),
            diffPMC = parseInt(numbMC) - parseInt(numbM),
            diffMC = parseInt(numbMoyC)-parseInt(numbMoy);

      spanPQ.innerHTML= `(${diffPQC}%)`;
      spanPM.innerHTML= `(${diffPMC}%)`;
      spanMQ.innerHTML= `(${diffMC}pts.)`;
      if(diffPQC == 0)
      {
        spanPQ.classList.add("orange");
      }
      else if(diffPQC > 0)
      {
        spanPQ.classList.add("green");
      }
      else
      {
        spanPQ.classList.add("red");
      }
      if(diffPMC == 0)
      {
        spanPM.classList.add("orange");
      }
      else if(diffPMC > 0)
      {
        spanPM.classList.add("green");
      }
      else
      {
        spanPM.classList.add("red");
      }
      if(diffMC == 0)
      {
        spanMQ.classList.add("orange");
      }
      else if(diffMC > 0)
      {
        spanMQ.classList.add("green");
      }
      else
      {
        spanMQ.classList.add("red");
      }
    }

    const partTQ = document.querySelector(`.trTQ`).textContent,
          partTM = document.querySelector(`.trTM`).textContent,
          partTCQ = document.querySelector(`.trTCQ`).textContent,
          partTCM = document.querySelector(`.trTCM`).textContent,
          moyenne = document.querySelector(`.moyQT`).textContent,
          moyenneTotal = document.querySelector(`.moyQTC`).textContent;
    const spanPQT= document.querySelector(`.spanPQT`),
          spanPMT= document.querySelector(`.spanPMT`),
          spanMT= document.querySelector(`.spanMT`);
    const diffTPQ = parseInt(partTCQ) - parseInt(partTQ),
          diffTPM = parseInt(partTCM) - parseInt(partTM),
          diffTMC = parseInt(moyenneTotal) - parseInt(moyenne);
    spanPQT.innerHTML= `(${diffTPQ}%)`;
    spanPMT.innerHTML= `(${diffTPM}%)`;
    spanMT.innerHTML= `(${diffTMC}pts.)`;
    if(diffTPQ == 0)
    {
      spanPQT.classList.add("orange");
    }
    else if(diffTPQ > 0)
    {
      spanPQT.classList.add("green");
    }
    else
    {
      spanPQT.classList.add("red");
    }
    if(diffTPM == 0)
    {
      spanPMT.classList.add("orange");
    }
    else if(diffTPM > 0)
    {
      spanPMT.classList.add("green");
    }
    else
    {
      spanPMT.classList.add("red");
    }
    if(diffTMC == 0)
    {
      spanMT.classList.add("orange");
    }
    else if(diffTMC > 0)
    {
      spanMT.classList.add("green");
    }
    else
    {
      spanMT.classList.add("red");
    }
  }
  function requestPOST(obj, bodyQ, bodyM, bodyT, nbQuizDiv, nbModDiv, trQ, trM, trT, moy, moyT, spPQ, spMQ, spPM, spPQT, spPMT, spMT){
    var table = obj;
    dbParamPost = JSON.stringify(table);
    var xmlhttpPost = new XMLHttpRequest();
    xmlhttpPost.onreadystatechange = function(){
      if(this.readyState == 4 && this.status == 200)
      {
        var myArray = JSON.parse(this.responseText),
            nbQuiz = myArray.nbQuiz,
            nbMod = myArray.nbModule;
        const sites = myArray.sites;
        buildTable(myArray, sites, nbQuiz, nbMod, nbQuizDiv, nbModDiv, bodyQ, bodyM, bodyT, trQ, trM, trT, moy, moyT, spPQ, spMQ, spPM, spPQT, spPMT, spMT);
        compare();
      }
    }
    xmlhttpPost.open("POST", url  + '/app/campaign_stats.php', true);
    xmlhttpPost.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttpPost.send(dbParamPost);
  }

  function buildTable(array, sites, nbrquiz, nbrmod, nbrquizDiv, nbrmodDiv, bodyQ, bodyM, bodyT, trQ, trM, trT, moy, moyT, spPQ, spMQ, spPM, spPQT, spPMT, spMT){
    bodyQ.innerHTML = "";
    bodyM.innerHTML = "";
    bodyT.innerHTML = "";
    let nbr = -1;
    for (let [key, value] of Object.entries(sites)) {
      nbr += 1;
      bodyQ.innerHTML += `
        <tr class="${trQ}">
          <td>${key}</td>
          <td><span class="part ${trQ + nbr}">${value.participationQuiz}</span><span class="${spPQ + nbr}"></span></td>
          <td><span class="moyQ ${moy + nbr}">${parseInt(value.moyenneQuiz)}</span><span class="${spMQ + nbr}"></span></td>
          <td>${parseInt(value.tempsQuiz)}</td>
        </tr>
      `
      
      bodyM.innerHTML += `
      <tr>
      <td>${key}</td>
      <td><span class="part ${trM + nbr}">${value.participationModule}</span><span class="${spPM + nbr}"></span></td>
      </tr>
      `
    }
    bodyT.innerHTML = `
    <tr>
    <td><span class="part ${trT + "Q"}">${array.total.participationQuiz}</span><span class="${spPQT}"></span></td>
    <td><span class="part ${trT + "M"}">${array.total.participationQuiz}</span><span class="${spPMT}"></span></td>
    <td><span class="moyQ ${moyT}">${parseInt(array.total.moyenneQuiz)}</span><span class="${spMT}"></span></td>
    <td>${parseInt(array.total.tempsQuiz)}</td>
    </tr>
    `;
    const moyQ =document.querySelectorAll(".moyQ"),
          parts = document.querySelectorAll(".part");
    nbrmodDiv.innerHTML = nbrmod;
    nbrquizDiv.innerHTML = nbrquiz;
    moyQ.forEach(td => {
      if(parseInt(td.textContent)>50)
      {
        td.classList.add("green");
      }
      else
      {
        td.classList.add("red");
      }
    });
    parts.forEach(td => {
      if(parseInt(td.textContent)>50)
      {
        td.classList.add("green");
      }
      else
      {
        td.classList.add("red");
      }
    });
  }
})