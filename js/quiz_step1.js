//file input animation

const realBtn = document.querySelector("#realbtn");
const fakeBtn = document.querySelector("#fakebtn");
const span = document.querySelector("#img_select");

fakeBtn.addEventListener("click", ()=>{
    realBtn.click();
});

realBtn.addEventListener("change", ()=>{
    if(realBtn.value)
    {
        // match() method get a correlation table between a regular expression (image path -> realBtn.value)  and a rational expression (regex)
        span.innerHTML = realBtn.value.replace(/C:\\fakepath\\/, '');
    }
    else
    {
        span.innerHTML = "Aucune image séléctionnée";
    }
})