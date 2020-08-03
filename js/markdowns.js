const paragraphs = document.querySelectorAll(".textContent");

paragraphs.forEach(text => {
  text.innerHTML = text.innerHTML.replace(new RegExp(/\*\*(.*?)\*\*/g), `<elem style="font-weight: bold">\$1</elem>` );
  text.innerHTML = text.innerHTML.replace(new RegExp(/\/\/(.*?)\/\//g), `<elem style="font-style: italic">\$1</elem>` );
  text.innerHTML = text.innerHTML.replace(new RegExp(/__(.*?)__/g), `<elem style="text-decoration: underline">\$1</elem>` );
  text.innerHTML = text.innerHTML.replace(new RegExp(/{{(.*?){{/g), "<div style='text-align: left;'>\$1</div>" );
  text.innerHTML = text.innerHTML.replace(new RegExp(/}}(.*?)}}/g), "<div style='text-align: right;'>\$1</div>" );
  text.innerHTML = text.innerHTML.replace(new RegExp(/\|\|(.*?)\|\|/g), "<div style='text-align: center;'>\$1</div>" );
  text.innerHTML = text.innerHTML.replace(new RegExp(/\~\~(.*?)\~\~/g), "<div style='text-align: justify;'>\$1</div>" );
});