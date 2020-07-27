function inputFile()
{
  const fakeBtn = document.querySelectorAll("#fakebtn");

  fakeBtn.forEach(fake => {
    fake.addEventListener("click", (e)=>{
      let id = e.target.dataset.id,
        real = document.querySelector(`#realbtn${id}`),
        span = document.querySelector(`#img_select${id}`);

      real.click();

      real.addEventListener("change", ()=>{
        if(real.value)
        {
          // match() method get a correlation table between a regular expression (image path -> realBtn.value)  and a rational expression (regex)
          span.innerHTML = real.value.replace(/C:\\fakepath\\/, '');
        }
        else
        {
          span.innerHTML = "Aucune image sélectionnée";
        }
      })
    })
  });
}

function getPageHtml(id){
    return `
    <div>
        <label>Titre de la page :</label>
        <input type="text" name="content_${id}_title" value="">
    </div>
    <div>
        <label>Contenu de la page :</label>
        <textarea name="content_${id}" value=""></textarea>
    </div>
    <div class="media">
        <div>
            <label>Image :</label>
            <button type="button" disabled><p id="fakebtn" data-id="${id}">Séléctionnez une image</p></button>
            <span id="img_select${id}">Aucune image sélectionnée.</span>
            <input id="realbtn${id}" type="file" name="content_${id}_img" hidden>
        </div>
        <p><strong>OU</strong></p>
        <div>
            <label>Video :</label>
            <input type="text" name="content_${id}_video" value="">
        </div>
    </div>
    <i class="trash${id} trash fas fa-trash" data-id="${id}"></i>
    `;
}

function addDeleteEvent()
{
        let trashes = document.querySelectorAll('.trash');
        trashes.forEach(function(trash){
            trash.removeEventListener("click", deleteBlock);
            trash.addEventListener("click", deleteBlock);
        });
}

function deleteBlock(event)
{
    let page = event.target.parentNode;
    let id = event.target.dataset.id;
    let nbrPage = document.querySelector("input[name=nbrPage]").value;
    let totalText = document.querySelector('.total').innerHTML;

    if(nbrPage > 1){
        nbrPage--;
        document.querySelector("input[name=nbrPage]").value = nbrPage;
        document.querySelector('.total').innerHTML = totalText.replace(/\d+/gi, nbrPage);
        page.remove();
    }
}
inputFile();
addDeleteEvent();

var id = parseInt(document.querySelector("input[name=nbrPage]").value);
var nbrPages = 1;
let pages = document.querySelectorAll(".questionPage");

nbrPages = pages.length;

const plus = document.querySelector(".plus"),
      form = document.querySelector("form"),
      total = document.createElement("p");

var nextId = document.querySelectorAll(".questionPage").length + 1;

total.classList.add("total");
form.appendChild(total);
total.innerHTML='Total de pages: ' + document.querySelector("input[name=nbrPage]").value;

plus.addEventListener("click", ()=>{
  if( document.querySelector("input[name=nbrPage]").value  == 25)
  {
    plus.disabled = true;
  }
  else
  {
    let nbrPages = parseInt(document.querySelector("input[name=nbrPage]").value) + 1;
    document.querySelector("input[name=nbrPage]").value = nbrPages;

    let totalText = document.querySelector('.total').innerHTML;
    total.innerHTML = totalText.replace(/\d+/gi, nbrPages);

    var div = document.createElement("div");
    div.classList.add("questionPage");
    div.classList.add("new");
    div.innerHTML = getPageHtml('n'+nextId);
    form.appendChild(div);

    addDeleteEvent();
    inputFile();
    nextId++;
  }
})

const validate = document.querySelector(".validate");
const sketching = document.querySelector(".sketching");

validate.addEventListener("click", ()=>{
  document.querySelector("input[value='Valider']").click();
})
sketching.addEventListener("click", ()=>{
  document.querySelector("input[value='Enregistrer le brouillon']").click();
})

const textareas = document.querySelectorAll("textarea");

textareas.forEach(text => {
  text.addEventListener("input", ()=>{
    const words = text.value.split(" ");

    // console.log(words[words.length - 1]);
  })
});
