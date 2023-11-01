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
        titleWrap.classList.add('gridTitleSpan')
        titleWrap.innerHTML = "<h4>"+item.Title+"</h4>"; 
        var footWrap = document.createElement("span");
        footWrap.classList.add('gridFootSpan')
        footWrap.innerHTML += '<span class="PlayDate"><p>'+item.Date+'</p></span>';
        mediaIconWrap = document.createElement('span'); 
        if(item.Audio){
            mediaIconWrap.innerHTML += '<span class="Audio"><svg xmlns="http://www.w3.org/2000/svg" height="24" fill="currentColor" viewBox="0 -960 960 960" width="24"><path d="M400-120q-66 0-113-47t-47-113q0-66 47-113t113-47q23 0 42.5 5.5T480-418v-422h240v160H560v400q0 66-47 113t-113 47Z"/></svg></span>'
        }
        if(item.File){
            mediaIconWrap.innerHTML += '<span class="File"><svg xmlns="http://www.w3.org/2000/svg" height="24" fill="currentColor" viewBox="0 -960 960 960" width="24"><path d="m380-300 280-180-280-180v360ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg></span>'
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
                console.log(items);
                if(items[0]['error'] && items[0]['error'] === 'The ID is invalid'){
                    fetch('/API/sermons/latest').then(response => response.json()).then(items => {
                        PlayerBuild(items);
                   });
                }else{
                    PlayerBuild(items);
                }
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