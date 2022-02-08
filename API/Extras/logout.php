<?php

if($method != 'POST'){
    echo stouts('You can only POST to this url', 'error');
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


$data = $DB->query('SELECT Token, ID From LoginAuth WHERE Token = :token', array('token'=>$InputData['Token']));
if(isset($data[0]['Token'])){
    $DB->query('DELETE FROM LoginAuth where ID='.$data[0]['ID'].'');
    echo stouts('User has been Loged out', 'success');
}else{
    echo stouts('That token is invalid', 'error');
}
