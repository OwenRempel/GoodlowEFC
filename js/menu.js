var menuIcon = document.getElementById('mobMenuIcon');
var mobMenu = document.getElementById('mobMenu');
var menu = document.getElementById('menu');

//photos/back.svg 

mobMenu.addEventListener('click', function(){
   menu.classList.toggle('menuSmall');
   menu.classList.toggle('menuBig');
   menu.classList.contains('menuSmall')
   if(!menu.classList.contains('menuSmall')){
       menuIcon.src = '/MediaFiles/photos/back.svg';
   }else{
    menuIcon.src = '/MediaFiles/photos/menu.svg';
   }
})