window.addEventListener('load', function () {
  var urlList = myScript.script_directory;
  // var home_url = myScript.theme_directory;
  var xmlhttpList = new XMLHttpRequest();
  xmlhttpList.onreadystatechange = function () {
  if(this.readyState == 4 && this.status == 200)
  {
    var myArray = JSON.parse(this.responseText);
    const ulCamp = document.querySelector('.camps');

    for (let i = 0; i < myArray.length; i++) {
      let dateStart = myArray[i].Début.split(""),
          dateEnd = myArray[i].Fin.split(""),
          newStart = [],
          newEnd = [];
      for (let i = 0; i < 10; i++) {
        newStart.push(dateStart[i]);
        newEnd.push(dateEnd[i]);
      }
      ulCamp.innerHTML += `
        <li>
          <span>${myArray[i].Nom}</span>
          <div>du ${newStart.join("")} au ${newEnd.join("")}</div>
          <div>
            <button data-id="${myArray[i].Id}" data-name="${myArray[i].Nom}" data-start="${newStart.join("")}" data-end="${newEnd.join("")}" class="modify">Modifier</button>
            <button data-name="${myArray[i].Nom}" data-id="${myArray[i].Id}" class="erase">Supprimer</button>
          </div>
        </li>
      `
    }

    function request(obj){
      var table = obj;
      dbParamPost = JSON.stringify(table);
      var xmlhttpPost = new XMLHttpRequest();
      xmlhttpPost.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200)
        {
          window.location.reload();
        }
      }
      xmlhttpPost.open("POST", urlList  + '/campaign_list.php', true);
      xmlhttpPost.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xmlhttpPost.send(dbParamPost);
    }

    // modifying part
    let id,
        name,
        start,
        end,
        objet;
    const modifybtns = document.querySelectorAll(".modify"),
          erasebtns = document.querySelectorAll(".erase"),
          startInput = document.querySelector(".start"),
          nameInput = document.querySelector(".name"),
          endInput = document.querySelector(".end"),
          nameCamp = document.querySelector(".nameCamp");
    modifybtns.forEach( (btn)=>{
      btn.addEventListener("click", ()=>{
        id = btn.dataset.id;
        name = btn.dataset.name;
        start = btn.dataset.start;
        end = btn.dataset.end;
        startInput.value = start;
        endInput.value = end;
        nameInput.value = name;
        modDiv.classList.remove("hidden");
        confirmDiv.classList.add("hidden");
      })
    })

    const validate = document.querySelector(".confirmMod");
    validate.addEventListener("click", ()=>{
      const recupStart = startInput.value,
            recupEnd = endInput.value,
            recupName = nameInput.value;
      objet = {
        "id": id,
        "name": recupName,
        "dateStart": recupStart,
        "dateEnd" : recupEnd
      }
      request(objet);
    })
    
    // erasing part
    const closeBtn = document.querySelectorAll(".close"),
          yes = document.querySelector(".yes"),
          confirmDiv = document.querySelector(".confirm"),
          modDiv = document.querySelector(".modifyDiv");
    erasebtns.forEach((btn)=>{
      btn.addEventListener("click", ()=>{
        id = btn.dataset.id;
        name = btn.dataset.name;
        nameCamp.innerHTML = name;
        confirmDiv.classList.remove("hidden");
        modDiv.classList.add("hidden");
      })
    })
    yes.addEventListener("click", ()=>{
      objet = {
        "id" : id
      }
      request(objet);
    })
    closeBtn.forEach(btn => {
      btn.addEventListener("click", ()=>{
        confirmDiv.classList.add("hidden");
        modDiv.classList.add("hidden");
      });
    });

  }
  };
  // url a trouver
  xmlhttpList.open("GET", urlList  + '/campaign_list.php?all=true', true);
  xmlhttpList.send();
});