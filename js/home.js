window.addEventListener('load', function () {
  var admin = myScript.admin;
  var editor = myScript.editor;
  const bp = document.querySelector(".bpas-post-form-wrapper");

  if(admin || editor)
  {
    bp.style.display = "none";
  }
  else
  {
    bp.style.display = "none";
  }
});

