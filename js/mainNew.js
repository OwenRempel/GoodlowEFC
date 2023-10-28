function GridFetch(number = 'all'){
        fetch('/API/sermons?limit='+number).then(response => response.json()).then(items => {
            BuildSermonGrid(items);
        });
}
function BuildSermonGrid(items){
    var send = document.getElementById('SermonGrid');
    send.innerHTML = '';
    for (let i = 0; i < items.Data.length; i++) {
        const item = items.Data[i];
        var wrap = document.createElement('div');
        var titleWrap = document.createElement('span');
        titleWrap.innerHTML = "<h3>"+item.Title+"</h3>";
        titleWrap.innerHTML += '<p>'+item.Date+'</p>';  
        var mediaWrap = document.createElement("span");
        if(item.File){
            mediaWrap.innerHTML += '<span class="material-symbols-outlined">slideshow</span>'
        }
        if(item.Audio){
            mediaWrap.innerHTML = '<span class="material-symbols-outlined">music_note</span>'
        }
        wrap.className('sermonGridItem');
        wrap.appendChild(titleWrap);
        wrap.appendChild(mediaWrap);
        send.appendChild(wrap)
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
    var send = document.getElementById('Player');
    send.innerHTML = '';
    for (let i = 0; i < items.Data.length; i++) {
        var wrap = document.createElement('div');
        var titleWrap = document.createElement('div');
        var dateWrap = document.createElement('span');    
        var mediaWrap = document.createElement('span');
    }
}