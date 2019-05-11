$(document).ready(function(){
  $(".header-bar-search").hover(function(){
  	$(".header-bar-search input").css("display", "inline");
    $("#inputSearch").css("opacity", "1");
    $("#inputSearch").css("width", "25em");


  }, function(){
   $("#inputSearch").css("width", "0");
    $("#inputSearch").css("opacity", "0");
    $(".header-bar-search input").css("display", "none");
  });

});
