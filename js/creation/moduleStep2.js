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
    <div class="legend legend${id}">
      <label>Stylisation du texte :</label>
      <ul class="display">
        <li><span>{{</span> Texte sur la gauche<br> ( ex : {{votre texte{{ ) *</li>
        <li><span>}}</span> Texte sur la droite<br> ( ex : }}votre texte}} ) *</li>
        <li><span>||</span> Texte centré<br> ( ex : ||votre texte|| ) *</li>
        <li><span>~~</span> Texte justifié<br> ( ex : ~~votre texte~~ ) *</li>
        <li><span>**</span> Texte en <b>gras</b><br> ( ex: **votre texte** )</li>
        <li><span>//</span> Texte en <elem style="font-style: italic">italique</elem> <br>( ex: //votre texte// )</li>
        <li><span>__</span> Texte <elem style="text-decoration: underline">souligné</elem> <br>( ex: __votre texte__ )</li>
      </ul>
      <br>
      <p class="display">* pour la justification du texte, il y a une particularité a respecter: chaque bloc doit être entouré du symbole en question. <br>
      <elem style="text-decoration: underline">ex</elem>: <br>{{Ma phrase d\'introduction
        <br>
        mon paragraphe}} -> cet exemple ne marchera pas
        <br><br>
        {{Ma phrase d\'introduction}}
          <br>
        {{mon paragraphe}} -> il faudra suivre cet exemple </p>
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

    let legend = document.querySelector(`.legendn${nextId}`);
    const ul = legend.childNodes[3];
    const inst = legend.childNodes[7];
    legend.addEventListener("click", ()=>{
      if(ul.classList.contains("display"))
      {
        ul.classList.remove("display");
        inst.classList.remove("display");
      }
      else
      {
        ul.classList.add("display");
        inst.classList.add("display");
      }
    });
    
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

const legends = document.querySelectorAll(".legend");

legends.forEach(legend => {
  const ul = legend.childNodes[3];
  const inst = legend.childNodes[7];
  legend.addEventListener("click", ()=>{
    console.log(ul, inst);
    if(ul.classList.contains("display"))
    {
      ul.classList.remove("display");
      inst.classList.remove("display");
    }
    else
    {
      ul.classList.add("display");
      inst.classList.add("display");
    }
  });
});
