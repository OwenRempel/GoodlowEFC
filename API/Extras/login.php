<?php
   
if($method != 'POST'){
    echo stouts('You can only POST data to this route', 'error');
    exit();
}

$DB = new DB;

$InputData = json_decode(file_get_contents('php://input'), 1);


if(isset($_POST['username']) and isset($_POST['password'])){
    $PostInput = $_POST;   
}elseif(isset($InputData['username']) and isset($InputData['password'])){
    $PostInput = $InputData;
}else{
    echo stouts('Missing paramaters please include a username and password', 'error');
}


$userCheckData = $DB->query(
    'SELECT UserName, UserPassword, Users.ID from Users 
    WHERE UserName = :username',
  array('username'=> $PostInput['username']));

if(!isset($userCheckData[0]['UserName'])){
    echo stouts('That username does not exist', 'error');
    exit();
}
$userCheckData = $userCheckData[0];

//password check

if(!password_verify($PostInput['password'], $userCheckData['UserPassword'])){
    echo stouts('Passwords dont match', 'error');
    exit();
}

//check to see if a login token already exists for the user

$checkUserIsLoggedIn = $DB->query('SELECT * FROM LoginAuth WHERE UsersID = :user', array('user'=>$userCheckData['ID']));
//makes sure that the token hasent expired
if(isset($checkUserIsLoggedIn[0]) and strtotime($checkUserIsLoggedIn[0]['Expire']) > time()){
    //return the data to the user if they are already logged in
    echo json_encode([
        'info'=>'Your already logged in',
        'Token'=>$checkUserIsLoggedIn[0]['Token']
        ]);
    exit();
}elseif((isset($checkUserIsLoggedIn[0]) and strtotime($checkUserIsLoggedIn[0]['Expire']) <= time())){
    $DB->query('DELETE FROM LoginAuth WHERE ID=:id', array('id'=>$checkUserIsLoggedIn[0]['ID']));
}
//If the user is not already logged in then add any entry to the table
do{
    $UUID = bin2hex(random_bytes(20));
}while(!empty($DB->query("SELECT Token from LoginAuth WHERE Token='$UUID'")));

try {
    $DB->query(
        'INSERT into LoginAuth (Token, Expire, UsersID, IP)
         Values (:token, :expire, :user, :ip)',
        array(
            'token'=>$UUID,
            'expire'=>date('Y-m-d H:i:s',time() + (60 * 60 * 12 * 30)),
            'user'=>$userCheckData['ID'],
            'ip'=>$_SERVER['REMOTE_ADDR']
        ));
} catch (\Throwable $th) {
    echo stouts('Error:'.$th, 'error');
}
$senddata = [
    'success'=>'User Logged In',
    'Token'=>$UUID,
    
];

echo json_encode($senddata);


