document.addEventListener("DOMContentLoaded", function() {
  var header = document.getElementById("myHeader");
  var sticky = header.offsetTop;

  window.onscroll = function() {
    if (window.pageYOffset > sticky) {
      header.classList.add("sticky");
    } else {
      header.classList.remove("sticky");
    }
  };
/*
  var liste = document.getElementById("navList")
  var burger = document.getElementById("burger");
  burger.onmouseover = function(){
    if (liste.style.display === "block") {
      liste.style.display = "none";
    } else {
      liste.style.display = "block";
    }
  } ;*/
});