function sermonSearchGrid(e){
    var send = document.getElementById('SermonGrid');
    var val = e.value
    if(val !== ''){
        fetch('/API/sermons/search/'+val).then(response => response.json()).then(items => {
            send.innerHTML = '';
            if(items){
                BuildSermonGrid(items);
            }else{
                send.innerHTML = "<h4 class='noResult'>No results found</h4>"
            }
            
        });
    }else{
        GridFetch();
    }
}


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
            mediaIconWrap.innerHTML += '<span class="Audio"><img src="/MediaFiles/photos/music_note.svg"></img></span>'
        }
        if(item.File){
            mediaIconWrap.innerHTML += '<span class="File"><img src="/MediaFiles/photos/slideshow.svg"></img></span>'
        }
        footWrap.appendChild(mediaIconWrap);
        wrap.classList.add('sermonGridItem');
        wrap.appendChild(titleWrap);
        wrap.appendChild(footWrap);
        wrap.addEventListener('click', function(){
            PlayerFetch(item.ID);
        })
        send.appendChild(wrap)
    }
}
function PlayerFetch(ID='none'){
    if(ID === 'none'){
        var Url_ID = window.location.href.split('/');
        console.log(Url_ID);
        if(Url_ID[4]){
            fetch('/API/sermons/'+Url_ID[4]).then(response => response.json()).then(items => {
                PlayerBuild(items);
            });
        }else{
            fetch('/API/sermons/latest').then(response => response.json()).then(items => {
                PlayerBuild(items);
           });
        }
    }else{
        window.history.replaceState(null, null, '/sermons/'+ID);
        fetch('/API/sermons/'+ID).then(response => response.json()).then(items => {
            PlayerBuild(items);
        });
    }
    
}
function PlayerBuild(item){
    item = item.Data[0]
    document.title = item.Title+' - Goodlow EFC';
    var send = document.getElementById('Player');
    send.innerHTML = '';
    var titleWrap = document.createElement('div');
    titleWrap.innerHTML = "<h3>"+item.Title+"</h3>"; 
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
    titleWrap.classList.add('playerTitle')
    mediaWrap.classList.add('playerMedia')
    send.appendChild(titleWrap);
    send.appendChild(mediaWrap);
}