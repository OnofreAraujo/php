var imagens = ["http://localhost/projeto/assets/imagens/banner.png", "http://localhost/projeto/assets/imagens/banner2.jpg", "http://localhost/projeto/assets/imagens/banner3.jpg"];
var atual = 0;

function nextCharge(){
	atual = (atual + 1) % imagens.length;
	document.querySelector('#banner img').src = imagens[atual];
}



function previousCharge(){
	
	if(atual == 0) {
		atual = imagens.length-1;
	}else{
		atual = (atual - 1);
	}
	
	document.querySelector('#banner img').src = imagens[atual];
	clearInterval(mudanca);
	mudanca = setInterval(nextCharge, 10000);
}

$(document).ready(function(){
	var mudanca = setInterval(nextCharge, 10000);
	nextCharge();

   $('#banner .next').click(function(event){ 
      nextCharge();
      clearInterval(mudanca);
      mudanca = setInterval(nextCharge, 10000);
   });


   $('#banner .previous').click(function(e){
   		previousCharge();

   });

});