function GetSermonGrid(){
    var ID = window.location.href.split('/')
    var send = document.getElementById('SermonBuilder');
    send.innerHTML = '';
    if(ID.length === 5 && ID[4]){
        var SermonID = ID[4];
        var view = document.getElementById('showAll');
        view.addEventListener('click', function(){
            window.location.replace('/sermons')
        })
        view.innerText = 'Back'
        var share = document.getElementById('share');
        share.style.display = 'flex';
        var img = document.createElement('img');
        img.src = "/MediaFiles/photos/facebook.png";
        img.classList.add('facebookshare')
        img.addEventListener('click', function(){
            window.open('http://www.facebook.com/share.php?u=www.goodlowchurch.ca/sermons/'+SermonID,'popup','width=600,height=800')
        })
        share.innerHTML = '<h4>Share: </h4>'
        share.appendChild(img)
        fetch('/API/sermons/'+SermonID).then(response => response.json()).then(items => {
            
            send.appendChild(sermonBuilder(items, false));
        });
    }else{
        fetch('/API/sermons?limit='+number).then(response => response.json()).then(items => {
            send.appendChild(sermonBuilder(items, true));
        });
    }
    
    
}
function PlayerFetch(ID='none'){
    if(ID === 'none'){
        fetch('/API/sermons/latest').then(response => response.json()).then(items => {
             PlayerBuild(items);
        });
    }else{
        fetch('/API/sermons/'+SermonID).then(response => response.json()).then(items => {
            PlayerBuild(items);
        });
    }
    
}
function PlayerBuild(items){
    console.log(items);
}