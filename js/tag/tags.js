window.addEventListener("load", ()=>{
  const deleteTagsBtn = document.querySelectorAll(".deleteBtn"),
  confirmDivs = document.querySelectorAll(".confirm");
  deleteTagsBtn.forEach(btn => {
    btn.addEventListener("click", ()=>{
      let id= btn.dataset.id,
      div = document.querySelector(`#confirm${id}`),
      // yes = document.querySelector(`.deleteTag${id}`),
      no = document.querySelector(`#no${id}`);

      div.classList.remove("hidden");
      no.addEventListener("click", ()=>{
        div.classList.add("hidden");
      })
    })
  });
});