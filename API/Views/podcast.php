<?php
$DB = new DB;

if(isset($Routes[1]) and !empty($Routes[1])){
    if($Routes[1] == 'list'){
        if(isset($_GET['limit'])){
            $limit = 'Limit '.intval($_GET['limit']);
        }else{
            $limit = '';
        }
        $data = $DB->query('SELECT VideoID from Podcast Order by Date Desc '.$limit.'');

        echo json_encode($data);
        

    }else{
        echo stouts('That Route is not valid', 'error');
    }
}else{
    global $VIMEO_API_KEY;
    //main query
    $content = file_get_contents('https://api.vimeo.com/users/3610664/videos?access_token='.$VIMEO_API_KEY);
    $data = json_decode($content, 1);
    $count = 0;
    foreach($data['data'] as $row){
        $temp = [
            'ID'=>$row['resource_key'],
            'VideoID'=>$row['player_embed_url'],
            'Date'=>$row['release_time']
        ];

        $check = $DB->query('SELECT ID From Podcast WHERE ID=:vid', array('vid'=>$temp['ID']));
        if(!isset($check[0]['ID'])){
            $DB->query(
                'INSERT INTO Podcast (VideoID, Date, ID)
                    VALUES (:VideoID, :Date, :ID)',
                    $temp
            );
            $count += 1;
        }    
    }
    echo json_encode(['success'=>'Podcast Updated '.$count.' Added To The Database', 'count'=>$count]);
}