window.addEventListener('load', function () {
  var urlListe = myScript.script_directory;
  var home_url = myScript.script_directory;
  var xmlhttpListe = new XMLHttpRequest();
  xmlhttpListe.onreadystatechange = function () {
  if(this.readyState == 4 && this.status == 200)
  {
    var modules = JSON.parse(this.responseText);
    const list = document.querySelector(".list"),
    content = document.querySelector(".modulesL");
    for(i=0; i<modules.length; i++)
    {
      let status = "Publié";
      if(modules[i].status === "0"){
        status = "Brouillon";
      }
      list.innerHTML += `
      <tr>
        <td>
          <span>${modules[i].title}</span>
        </td>
        <td>
          <span>${modules[i].tag_name}</span>
        </td>
        <td>
          <span>${status}</span>
        </td>
        <td>
          <p data-id="${modules[i].id}" class="delete">Supprimer</p>
          <a href="${home_url}/module_edit.php?id=${modules[i].id}" target="_blank" class="modify">Modifier</a>
        </td>
      </tr>
      `
    }
    const btns = document.querySelectorAll(".delete");
    btns.forEach(btn => {
      btn.addEventListener("click", (e)=>{
        let id =e.target.dataset.id;
        const div = document.createElement("div");
        div.classList.add("popup");
        div.innerHTML = `
          <p>Vous êtes certain de vouloir supprimer ce module ?
          </p>
          <div>
            <span id="yes">Oui</span>
            <span id="no">Non</span>
          </div>
          
        `;
        content.appendChild(div);
        const yes = document.querySelector("#yes"),
        no = document.querySelector("#no");
        yes.addEventListener("click" , ()=>{
          var urlModule = urlListe + '/deleteModule.php/?idModule=' + id;
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function () {
            if(this.readyState == 4 && this.status == 200)
            {
              window.location.reload();
            }
            else
            {
            }
          }
          xmlhttp.open("GET", urlModule , true);
          xmlhttp.send();
        })
        no.addEventListener("click", ()=>{
          content.removeChild(div);
        })
      })
    });
  }
  else
  {
  }
  };

  // url a trouver
  xmlhttpListe.open("GET", urlListe  + '/menu_modules.php?all=true', true);
  xmlhttpListe.send();
});