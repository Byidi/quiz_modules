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
          span.innerHTML = "Aucune image séléctionnée";
        }
      })
    })
  });
}

function getQuestionHtml(id)
{
    return `
    <div>
    <label>Votre question:</label>
      <input type="text" name="question_${id}" value="">
    </div>
    <div class="answers">
    <label>Vos réponses:</label>
    <div class="abcd">
          <div class="answer">
          <label>A.</label>
              <input type="text" name="q_${id}_reponse_1" value="">
              <label class="true" id="truea">
                  <input type="radio" value="true" name="q_${id}_isTrue_1" checked>
                  <span>
                      <i class="fas fa-check"></i>
                      </span>
                      </label>
              <label class="false" id="falsea">
              <input type="radio" value="false" name="q_${id}_isTrue_1">
                  <span>
                  <i class="fas fa-times"></i>
                  </span>
                  </label>
                  </div>
            <div class="answer">
            <label>B.</label>
                <input type="text" name="q_${id}_reponse_2" value="">
                <label class="true" id="trueb">
                    <input type="radio" value="true" name="q_${id}_isTrue_2">
                    <span>
                        <i class="fas fa-check"></i>
                    </span>
                </label>
                <label class="false" id="falseb">
                <input type="radio" value="false" name="q_${id}_isTrue_2" checked>
                <span>
                <i class="fas fa-times"></i>
                </span>
                </label>
                </div>
                <div class="answer">
                <label>C.</label>
                <input type="text" name="q_${id}_reponse_3"  value="">
                <label class="true" id="truec">
                <input type="radio" value="true" name="q_${id}_isTrue_3">
                <span>
                <i class="fas fa-check"></i>
                </span>
                </label>
                <label class="false" id="falsec">
                <input type="radio" value="false" name="q_${id}_isTrue_3" checked>
                <span>
                <i class="fas fa-times"></i>
                </span>
                </label>
            </div>
            <div class="answer">
                <label>D.</label>
                <input type="text" name="q_${id}_reponse_4"  value="">
                <label class="true" id="trued">
                <input type="radio" value="true" name="q_${id}_isTrue_4">
                    <span>
                        <i class="fas fa-check"></i>
                        </span>
                </label>
                <label class="false" id="falsed">
                    <input type="radio" value="false" name="q_${id}_isTrue_4" checked>
                    <span>
                    <i class="fas fa-times"></i>
                    </span>
                    </label>
                    </div>
        </div>
        </div>
    <div>
    <label>Le media:</label>
    </div>
    <div class="media">
      <div>
        <label>Image :</label>
        <button type="button" disabled><p id="fakebtn" data-id="${id}">Séléctionnez une image</p></button>
        <span id="img_select${id}">Aucune image sélectionnée.</span>
        <input id="realbtn${id}" type="file" name="q_${id}_img" hidden>
      </div>
      <p><strong>OU</strong></p>
      <div>
        <label>Video :</label>
        <input type="text" name="q_${id}_video" value="">
      </div>
    </div>
    <i class='trash${id} trash fas fa-trash' data-id="${id}"></i>`;
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
  let question = event.target.parentNode;
  let id = event.target.dataset.id;
  let nbrQuestion = document.querySelector("input[name=nbrQuestion]").value;
  let totalText = document.querySelector('.total').innerHTML;

  if(nbrQuestion > 1){
      nbrQuestion--;
      document.querySelector("input[name=nbrQuestion]").value = nbrQuestion;
      document.querySelector('.total').innerHTML = totalText.replace(/\d+/gi, nbrQuestion);
      question.remove();
  }
}

inputFile();
addDeleteEvent();

const plus = document.querySelector(".plus"),
      form = document.querySelector("form"),
      total = document.createElement("p");

var nextId = document.querySelectorAll(".questionPage").length + 1;

total.classList.add("total");
form.appendChild(total);
total.innerHTML='Total de questions: ' + document.querySelector("input[name=nbrQuestion]").value;

plus.addEventListener("click", ()=>{
  if( document.querySelector("input[name=nbrQuestion]").value == 100)
  {
    plus.disabled = true;
  }
  else
  {
    let nbrQuestion = parseInt(document.querySelector("input[name=nbrQuestion]").value) + 1;
    document.querySelector("input[name=nbrQuestion]").value = nbrQuestion;

    let totalText = document.querySelector('.total').innerHTML;
    total.innerHTML = totalText.replace(/\d+/gi, nbrQuestion);

    var div = document.createElement("div");
    div.classList.add("questionPage");
    div.classList.add("new");
    div.innerHTML= getQuestionHtml('n'+nextId);
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
