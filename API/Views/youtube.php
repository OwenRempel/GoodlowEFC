<?php

include('../DB/secret.php');

global $YOUTUBE_API_KEY;

$DB = new DB;

if(isset($Routes[1]) and !empty($Routes[1])){
    if($Routes[1] == 'list'){
        if(isset($_GET['limit'])){
            $limit = 'Limit '.intval($_GET['limit']);
        }else{
            $limit = '';
        }
        $data = $DB->query('SELECT Title, VideoID, Date, About from Youtube Order by Date Desc '.$limit.'');

        echo json_encode($data);
        

    }else{
        echo stouts('That Route is not valid', 'error');
    }
}else{
    if(isset($_GET['all'])){
        $all = '&maxResults=100';
    }else{
        $all = '&maxResults=5';
    }
    
     
    
    $url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet'.$all.'&playlistId=UUQm1w9hwatrJB8iU4R_Hoyw&key='.$YOUTUBE_API_KEY;
    $data = file_get_contents($url);
    
    
    $data = json_decode($data, 1);
    $count = 0;
    
    
    foreach($data['items'] as $Video){
        //echo json_encode($Video);
    
        $Date = explode('T',$Video['snippet']['publishedAt'])[0];
    
        
        $temp = [
            'Title'=>$Video['snippet']['title'],
            'VideoID'=>$Video['snippet']['resourceId']['videoId'],
            'Date'=>$Date,
            'About'=>$Video['snippet']['description'], 
            'ID'=>$Video['id']
        ];
        //echo json_encode($temp);
    
        $check = $DB->query('SELECT VideoID From Youtube WHERE VideoID=:vid', array('vid'=>$temp['VideoID']));
        if(!isset($check[0]['VideoID'])){
            $DB->query(
                'INSERT INTO Youtube (Title, VideoID, Date, About, ID)
                 VALUES (:Title, :VideoID, :Date, :About, :ID)',
                 $temp
            );
            $count += 1;
        }
        
    }
    
    echo json_encode(['success'=>'Videos Updated '.$count.' Added To The Database']);
}





