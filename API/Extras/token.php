<?php 

if($method != 'POST'){
    echo stouts('You can only POST data to this route', 'error');
    exit();
}

$DB = new DB;

$Input = json_decode(file_get_contents('php://input'), 1);

if(isset($_POST['Token'])){
    $InputData = $_POST;
}elseif(isset($Input['Token'])){
    $InputData = $Input;
}else{
    echo stouts('Please include Token', 'error');
    exit();
}

$checkData = $DB->query('SELECT Token, Expire, ID From LoginAuth WHERE Token = :token',
 array(
     'token'=>$InputData['Token']
 ));

if(isset($checkData[0]['Token'])){
    $time = $checkData[0]['Expire'] = strtotime($checkData[0]['Expire']);
    if($time > time()){
        echo json_encode([
            'success'=>'Valid Token',
            'Token'=>$checkData[0]['Token']
        ]);
        exit();
    }else{
        $DB->query('DELETE FROM LoginAuth WHERE ID=:id', array('id'=>$checkData[0]['ID']));
        echo stouts('Your token has expired');
    }
    
}else{
    echo stouts('This token is not valid', 'error');
}
