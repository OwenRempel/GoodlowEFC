
//This is the main js for getting all of the data for the pages
//Here is where we will get the most recent Bulletin

function GetBulletin(){
    var send = document.getElementById('BulletinBuilder');
    fetch('/API/bulletins?limit=1').then(response => response.json()).then(items => {
        var link = items.Data[0].Url;
        send.href = link
    });
}


//Youtube Messages
function GetYoutubeVideo(){

    var send = document.getElementById('YoutubeBuilderHome');
    
    fetch('/API/youtube/list?limit=1').then(response => response.json()).then(items => {
        var link = 'https://www.youtube.com/embed/'+items[0].VideoID
        send.src = link
    });
}
function YoutubeBuilder(data){
    var fullwrap = document.createElement('div');
    fullwrap.classList.add('YoutubeVideos')
    for (let i = 0; i < data.length; i++) {
        const item = data[i];
        var videoItem = document.createElement('div');
        videoItem.classList.add('videoItem');
        var video = document.createElement('div');
        video.classList.add('video');
        var YoutubeItem = document.createElement('iframe');
        YoutubeItem.classList.add('YoutubeItem');
        YoutubeItem.src = 'https://www.youtube.com/embed/'+item.VideoID;
        YoutubeItem.setAttribute('frameBorder', '0');
        YoutubeItem.setAttribute('allowFullScreen', '')
        var videoAbout = document.createElement('div');
        videoAbout.classList.add('videoAbout');
        videoAbout.innerHTML = '<h2>'+item.Title+'</h2> <p>'+item.Date+'</p>'
        var aboutWraper = document.createElement('div');
        aboutWraper.classList.add('aboutWraper');
        aboutWraper.innerHTML = '<p>'+item.About+'</p>';




        video.appendChild(YoutubeItem)
        videoItem.appendChild(video)
        videoAbout.appendChild(aboutWraper);
        videoItem.appendChild(videoAbout)
        fullwrap.appendChild(videoItem)

    }
    return fullwrap
}
function GetYoutubeVideos(){
    var send = document.getElementById('youtubeBuilder');
    fetch('/API/youtube/list?limit=10').then(response => response.json()).then(items => {
        var out = YoutubeBuilder(items);
        send.appendChild(out);
    });
}


//Sermons
function sermonBuilder(data, share = false){
    var fullwrap = document.createElement('div');
    fullwrap.classList.add('Audio')
    for (let i = 0; i < data.Data.length; i++) {
        const item = data.Data[i];
        var wrap = document.createElement('div');
        var titlewrap = document.createElement('div');
        var datewrap = document.createElement('span');
        var filewrap = document.createElement('span');    
        var audiowrap = document.createElement('span');
        if(share){
            var share = document.createElement('a');
            share.href = '/sermons/'+item.ID
            share.innerHTML = '<h2>'+item.Title+'</h2>'
            titlewrap.appendChild(share);
        }else{
            titlewrap.innerHTML = '<h2>'+item.Title+'</h2>'
        }
        datewrap.innerHTML = '<p>'+item.Date+'</p>'
        if(item.File){
            var file = document.createElement('a');
            file.href = item.File
            file.setAttribute('target', '_blank')
            file.innerText = 'PowerPoint'
            file.classList.add('btn','btwidemob', 'download-pptx')
            filewrap.appendChild(file)
        }   
        if(item.Audio){
            var file = document.createElement('audio');
            //This allows the media player to work when not a root url
            file.src = item.Audio
            file.classList.add('audioplay')
            file.setAttribute('controls', true)
            audiowrap.appendChild(file)
        }    
        
        titlewrap.classList.add('AudioTitle');
        datewrap.classList.add('AudioDate');
        filewrap.classList.add('AudioDoc');
        audiowrap.classList.add('AudioFile');
        wrap.classList.add('AudioItem');
        wrap.appendChild(titlewrap);
        wrap.appendChild(datewrap);
        wrap.appendChild(filewrap);
        wrap.appendChild(audiowrap);
       
        
        fullwrap.appendChild(wrap);
        
    }
    return fullwrap
   
}
function GetHomeSermon(){
    var send = document.getElementById('SermonBuilderHome');
    fetch('/API/sermons/latest').then(response => response.json()).then(items => {
        send.appendChild(sermonBuilder(items));
    });
}
function GetSermons(number = 'all'){
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
function sermonSearch(e){
    var send = document.getElementById('SermonBuilder');
    var val = e.value
    if(val !== ''){
        fetch('/API/sermons/search/'+val).then(response => response.json()).then(items => {
            send.innerHTML = '';
            send.appendChild(sermonBuilder(items));
        });
    }else{
        GetSermons(20);
    }
}

//Events

function GetEvents(){
    function eventBuilder(data){
        function headItem(head){
            var tr = document.createElement('tr');
            Object.keys(head).map(key => {
                var th = document.createElement('th');
                th.innerText = head[key]
                tr.appendChild(th)
            })
            return tr
        }
        function tbody(data){
            var tbody = document.createElement('tbody');
            for (let i = 0; i < data.length; i++) {
                const item = data[i];
                var tr = document.createElement('tr');
                Object.keys(item).map(key => {
                    if(key !== 'ID'){
                        var td = document.createElement('td');
                        td.innerHTML = item[key];
                        tr.appendChild(td);
                    }
                })
                tbody.appendChild(tr)
            }
            return tbody
        }
        

        var table = document.createElement('table');
        var thead = document.createElement('thead')
        thead.appendChild(headItem(data.Info));
        table.appendChild(thead)
        table.appendChild(tbody(data.Data))
        return table
        
    }
    var send = document.getElementById('eventBuilder');
    fetch('/API/events').then(response => response.json()).then(items => {
        send.appendChild(eventBuilder(items))
    });
}

//Blogs

function GetBlogs(){
    function blogItem(item, readmore = false){
        var blog = document.createElement('div');
        blog.classList.add('blog')
        var DateAuth = document.createElement('div')
        DateAuth.classList.add('dateAuthWrap')
        var content = document.createElement('div')
        content.classList.add('blogContent')
       
        blog.innerHTML= '<h3>'+item.Title+'</h3>';
        DateAuth.innerHTML = '<p>'+item.Date+'</p><p>By '+item.Name+'</p>';
        content.innerHTML = '<p>'+item.Content+'</p>';
        if(item.Attachment && !readmore){
            var Attachment = document.createElement('a');
            Attachment.href = '/API/'+item.Attachment
            Attachment.innerText = 'View Attachment'
            Attachment.setAttribute('target', '_blank')
            Attachment.classList.add('btn')
            content.appendChild(Attachment)
        }
        blog.appendChild(DateAuth);
        blog.appendChild(content);
        if(readmore){
            var view = document.createElement('a');
            view.href = '/blog/'+item.ID
            view.innerText = 'Read More'
            view.classList.add('btn')
            blog.appendChild(view)
        }
        return blog
    }
    function buildBlogs(data){
        var blogWrap = document.createElement('div');
        blogWrap.classList.add('blogWrap');
        for (let i = 0; i < data.length; i++) {
            const item = data[i]; 
            blogWrap.appendChild(blogItem(item, true));
        }
        return blogWrap
    }
    function buildBlog(data){
        var singleBlog = document.createElement('div');
        singleBlog.classList.add('singleBlog');
        singleBlog.appendChild(blogItem(data[0]))
        return singleBlog;
    }
    var ID = window.location.href.split('/')
    var send = document.getElementById('blogBuilder');
    if(ID.length === 5 && ID[4]){
        var BlogID = ID[4];
        var view = document.createElement('a');
        view.href = '/blog'
        view.innerText = 'Back'
        view.classList.add('btn')
        send.appendChild(view)

        fetch('/API/blogs/'+BlogID).then(response => response.json()).then(item => {
            send.appendChild(buildBlog(item.Data))
        });
    }else{
        fetch('/API/blogs').then(response => response.json()).then(items => {
            send.appendChild(buildBlogs(items.Data))
        });
    }
    
    
}

//Resources

function GetResources(){
    var send = document.getElementById('resourcesBuilder');
    function resBuilder(data){
        console.log(data)
        var wrap = document.createElement('div');
        wrap.classList.add('resourcesWrap');

        for (let i = 0; i < data.length; i++) {
            const item = data[i];
            var res = document.createElement('div');
            res.classList.add('resourceItem')
            var link = document.createElement('a');
            link.href = item.Link
            link.innerHTML = '<h3>'+item.Title+'</h3>'
            link.setAttribute('target', '_blank')
            var body = document.createElement('div');
            body.innerHTML = '<p>'+item.Content+'</p>'
            if(item.List){
                var list = document.createElement('ul');
                var listItems = item.List.split(',')
                for (let j = 0; j < listItems.length; j++) {
                    const listItem = listItems[j];
                    list.innerHTML += "<li>"+listItem+"</li>"
                }
                body.appendChild(list)
            }
            res.appendChild(link)
            res.appendChild(body)
            wrap.appendChild(res)
        }
        return wrap
    }
    fetch('/API/resources?full=1').then(response => response.json()).then(items => {
        
        send.appendChild(resBuilder(items.Data))
    });
}

function GetPodcast(){
    function podcastBuilder(data){
        var wrap = document.createElement('div');
        wrap.classList.add('podcastWraper')
        for(i in data){
            var frame = document.createElement('iframe');
            frame.classList.add('podcastVideo')
            frame.src = data[i].VideoID
            frame.setAttribute('frameborder', '0');
            frame.setAttribute('allowfullscreen', '');
            frame.setAttribute('mozallowfullscreen', '');
            frame.setAttribute('webkitallowfullscreenn', '');

            wrap.appendChild(frame)
        }
        
        return wrap
    }
    var send = document.getElementById('podcastBuilder');
    fetch('/API/podcast/list?limit=6').then(response => response.json()).then(items => {
       
       send.appendChild(podcastBuilder(items))
    });
}