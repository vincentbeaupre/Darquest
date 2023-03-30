function launchSnackbar() {
  // Get the snackbar DIV
  var x = document.getElementById("snackbar");

  // Add the "show" class to DIV
  x.className = "show";

  // After 3 seconds, remove the show class from DIV
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

function displayMenu() {
  var menu = document.querySelector(".menu");
  if (menu.style.display === "flex") {
    menu.style.display = "none";
  } else {
    menu.style.display = "flex";
  }
}

function displayDetails(idItem,typeItem){
  var item = document.getElementById(idItem);
  if(item.style.display === "flex"){
    item.style.display = "none";
  }
  else{
    item.style.display = "flex";
  }
}