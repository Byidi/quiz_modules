window.addEventListener('load', function () {
  var url = myScript.script_directory;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if(this.readyState == 4 && this.status == 200)
    {
      const gen = document.querySelector(".gen"),
      town = document.querySelector(".town");

      var myArray = JSON.parse(this.responseText),
      leadTown = myArray.top30UserVille,
      leadGen = myArray.top30User,
      top30Gen = leadGen.classement,
      top30Town = leadTown.classement,
      userStat = leadGen.userStat,
      userId = userStat.user_id;
      function buildTable(array, userId)
      {
        const tbody = document.querySelector("tbody");
        let pos;
        tbody.innerHTML = '';
        for (let i = 0; i < array.length; i++) {
          pos = i + 1;
          const tr = document.createElement("tr");
          tbody.appendChild(tr);
          if(pos == 1)
          {
            tr.classList.add("gold");
          }
          else if(pos == 2)
          {
            tr.classList.add("silver");
          }
          else if(pos == 3)
          {
            tr.classList.add("bronze");
          }
          else if(array[i].user_id == userId)
          {
            tr.classList.add("imp");
          }
          tr.innerHTML += `
            <td>${pos}</td>
            <td>${array[i].display_name}</td>
            <td>${array[i].meta_value}</td>
            <td>${parseInt(array[i].moyenne)}</td>
          `
        }
      } 
      buildTable(top30Gen, userId);
      gen.addEventListener("click", ()=>{
        town.classList.remove("activated");
        gen.classList.add("activated");
        buildTable(top30Gen, userId);
      })
      town.addEventListener("click", ()=>{
        gen.classList.remove("activated");
        town.classList.add("activated");
        buildTable(top30Town, userId);
      })
    }
  }; 
  xmlhttp.open("GET", url  + '/dashboard_back.php?all=true', true);
  xmlhttp.send();
});