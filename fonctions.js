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