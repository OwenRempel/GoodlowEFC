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
        titleWrap.innerHTML = "<h4>"+item.Title+"</h4>"; 
        var footWrap = document.createElement("span");
        footWrap.innerHTML += '<span class="PlayDate"><p>'+item.Date+'</p></span>';
        mediaIconWrap = document.createElement('span'); 
        if(item.Audio){
            mediaIconWrap.innerHTML += '<span class="Audio material-symbols-outlined">music_note</span>'
        }
        if(item.File){
            mediaIconWrap.innerHTML += '<span class="File material-symbols-outlined">slideshow</span>'
        }
        footWrap.appendChild(mediaIconWrap);
        wrap.classList.add('sermonGridItem');
        wrap.appendChild(titleWrap);
        wrap.appendChild(footWrap);
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
function PlayerBuild(item){
    console.log(item);
    var send = document.getElementById('Player');
    send.innerHTML = '';
    var titleWrap = document.createElement('div');
    titleWrap.innerHTML = "<h4>"+item.Title+"</h4>"; 
    titleWrap.innerHTML += '<p>'+item.Date+'</p>';
        
    var mediaWrap = document.createElement('span');
    if(item.Audio){
        var file = document.createElement('audio');
        //This allows the media player to work when not a root url
        file.src = item.Audio
        file.classList.add('audioplay')
        file.setAttribute('controls', true)
        file.setAttribute('crossorigin', "anonymous")
        mediaWrap.appendChild(file)
    }   
    if(item.File){
        var file = document.createElement('a');
        file.href = item.File
        file.setAttribute('target', '_blank')
        file.innerText = 'PowerPoint'
        file.classList.add('btn','btwidemob', 'download-pptx')
        mediaWrap.appendChild(file)
    } 
    
    send.appendChild(titleWrap);
    send.appendChild(mediaWrap);
    
}