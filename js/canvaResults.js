window.addEventListener('load', function () {
  var url = myScript.script_directory;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if(this.readyState == 4 && this.status == 200)
    {
      var myArray = JSON.parse(this.responseText),
      userResults = myArray.userResults;
      
      let lastResults,
      labels = [],
      points = [];

      if(userResults.length > 10)
      {
        lastResults = userResults.slice(Math.max(userResults.length - 10, 1));
      }
      else
      {
        lastResults = userResults;
      }
  
      for ( i = 0; i < lastResults.length; i++) {
        labels.push(lastResults[i].name);
        points.push(parseInt(lastResults[i].score));
      } 
      var ctx = document.getElementById('myChart'),
      myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels : labels,
          datasets: [{
            label: 'score',
            data: points,
            pointBackgroundColor: '#E2B34A',
            borderWidth: 3,
            borderColor: 'rgba(226, 179, 74, 0.3)',
            backgroundColor: 'rgba(25,34,49,0.5)',
          }]
        },
        options: {
          legend: {
            display: false,
          },
          animation: {
            easing: 'easeInOutQuad',
            duration: 520
          },
          scales: {
            xAxes: [{
              display: false,
              gridLines: {
                color: 'rgba(0,0,0,0)',
              }
            }],
            yAxes: [{
              gridLines: {
                color: 'rgba(0,0,0,0)',
              },
              ticks: {
                beginAtZero: true,
                max: 100
              }
            }]
          },
          elements: {
            line: {
              tension: 0.35
            }
          },
          tooltips: {
            titleFontFamily: 'Muli',
            backgroundColor: 'rgba(0,0,0,0.3)',
            caretSize: 5,
            cornerRadius: 2,
            xPadding: 10,
            yPadding: 10
          },
        },
      })
      Chart.defaults.global.defaultFontColor='white';
      Chart.defaults.global.defaultFontFamily='Muli';
    }
  } 
  // url a trouver
  xmlhttp.open("GET", url  + '/dashboard_back.php?all=true', true);
  xmlhttp.send();
});