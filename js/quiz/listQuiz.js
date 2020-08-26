window.addEventListener('load', function () {
  var urlListe = myScript.script_directory;
  var home_url = myScript.script_directory;
  var xmlhttpListe = new XMLHttpRequest();
  xmlhttpListe.onreadystatechange = function () {
  if(this.readyState == 4 && this.status == 200)
  {
    var quizs = JSON.parse(this.responseText);
    const list = document.querySelector(".list"),
    content = document.querySelector(".quizsL");
    for(i=0; i<quizs.quiz.length; i++)
    {
      let status = "Publié";
      if(quizs.quiz[i].status === "0"){
        status = "Brouillon";
      }
      list.innerHTML += `
      <tr>
        <td>
          <span>${quizs.quiz[i].name}</span>
        </td>
        <td>
        <span>${quizs.quiz[i].tag_name}</span>
        </td>
        <td>
          <span>${status}</span>
        </td>
        <td>
          <p data-id="${quizs.quiz[i].id}" class="delete_listQuiz">Supprimer</p>
          <a href="${home_url}/quiz_edit.php?id=${quizs.quiz[i].id}" target="_blank" class="modify_listQuiz">Modifier</a>
        </td>
      </tr>
      `;
    }
    const btns = document.querySelectorAll(".delete_listQuiz");
    btns.forEach(btn => {
      btn.addEventListener("click", (e)=>{
        let id =e.target.dataset.id;
        const div = document.createElement("div");
        div.classList.add("popup");
        div.innerHTML = `
          <p>Vous êtes certain de vouloir supprimer ce quiz ?
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
          var urlQuiz = urlListe + '/deleteQuiz.php/?idQuiz=' + id;
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
          xmlhttp.open("GET", urlQuiz , true);
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
  xmlhttpListe.open("GET", urlListe  + '/menu_quiz.php?all=true', true);
  xmlhttpListe.send();
});