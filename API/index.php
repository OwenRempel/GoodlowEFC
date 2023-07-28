<?php

if(!isset($_ENV['Env_Check'])){
    $data = file_get_contents('.env');
    $items = explode(PHP_EOL, $data);
    foreach($items as $item){
        $ex = explode('=', $item);
        $_ENV[$ex[0]] = trim($ex[1], '"');
    }
}

require('DB/DB.php');

require('Extras/FormBuilderArray.php');

require 'vendor/autoload.php';
use Aws\S3\S3Client;

$client = new Aws\S3\S3Client([
        'version' => 'latest',
        'region'  => 'us-east-1',
        'endpoint' => 'https://nyc3.digitaloceanspaces.com',
        'use_path_style_endpoint' => false, // Configures to use subdomain/virtual calling format.
        'credentials' => [
                'key'    => "DO008FV3WPNLN2TZGQ4P",
                'secret' => "3QDOloo0qc7PX1Vqu0aMDQKeb5S9RGEaWQeapWslga4",
            ],
]);

//setting the correct timezone
date_default_timezone_set('America/Dawson_Creek');

//Cors Headers
//|\|/\|/|\|/|\|/|\|/|\|/|\|/|\|/|\|/|\|/|\|/|\

// Allow from any origin TODO:Finalize for Production
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 60000"); //set cache for a long time TODO: change this before production

//Setup
//|\|/\|/|\|/|\|/|\|/|\|/|\|/|\|/|\|/|\|/|\|/|\
//Request method OPTIONS
if($_SERVER["REQUEST_METHOD"] == "OPTIONS"){
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); //Make sure you remove those you do not want to support

    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    //Just exit with 200 OK with the above headers for OPTIONS method
    exit(0);
}
//Check to see if Setup complete

if(!is_file('Built')){
    DB::exFile(file_get_contents('sql/run.sql'));
    touch('Built');
}
//Functions
//|\|/\|/|\|/|\|/|\|/|\|/|\|/|\|/|\|/|\|/|\|/|\

//push output as json
function stouts($message, $type='info'){
    $types = explode(',', str_replace(' ', '', 'info, success, error'));
    if(in_array($type, $types)){
        return json_encode([$type=>$message]);
    }else{
        return json_encode(['info'=>$message]);
    }
}
//Main router for all incoming requests
function InitRouter(){
    //Globals
    global $FormBuilderArray;
    
    //Get url without parms
    $full_url = explode('?', $_SERVER['REQUEST_URI']);
    //Split into Array
    $Routes =  explode('/', $full_url[0]);
    //Remove first item of array to account for inital /
    array_shift($Routes);
    array_shift($Routes);
    //Set method
    $method = $_SERVER['REQUEST_METHOD'];
    //Build file path from url
    if($Routes[0] == 'login'){
        //handle login
        include('Extras/login.php');
    }elseif($Routes[0] == 'token'){
        //handle token checks
        include('Extras/token.php');
    }elseif($Routes[0] == 'logout'){
        //handle logout
        include('Extras/logout.php');
    }elseif(isset($FormBuilderArray['Routes'][$Routes[0]])){
        //checks if the route is in the route array
        $localArray = $FormBuilderArray['Routes'][$Routes[0]];
        //load all the form data
        if($method == 'POST' or $method == 'PUT'){
            $PHPinput = file_get_contents('php://input');
            $PostInput = json_decode($PHPinput, 1);
            parse_str($PHPinput, $_PUT);    
            
            //This is the check to see if the api should use $_POST or php://input
            if(isset($_POST[$localArray['formName']])){
                $PostData = $_POST;
            }elseif(isset($_PUT[$localArray['formName']])){
                $PostData = $_PUT;
            }elseif(isset($PostInput[$localArray['formName']])){
                $PostData = $PostInput;
            }else{
                echo stouts('No data Received ALL', 'error');
                exit();
            }
        }
           
        //checks to see if the route is a view file
        if(isset($FormBuilderArray['Routes'][$Routes[0]]['view'])){
            $viewFile = "./Views/".$Routes[0].'.php';
            if(is_file($viewFile)){
                include($viewFile);
                exit();
            }
        }
        if($method == 'GET'){
            if(isset($Routes[1]) and strtolower($Routes[1]) == 'search' and isset($Routes[2])){
                search($localArray, $Routes[2]);
            }elseif(isset($Routes[1]) and strtolower($Routes[1]) == 'info' and isset($Routes[2]) ){
                //Return the data and structure for updating item
                selectUpdateFormItem($localArray, $Routes[0], $Routes[2]);
            }elseif(isset($Routes[1]) and  strtolower($Routes[1]) == 'info'){
                //get the form structure for 
                getFormStruct($localArray, $Routes[0], $Routes[1]);
            }elseif(isset($Routes[1]) and !empty($Routes[1])){
                //Handle the individual requests
                selectFormItem($localArray, $Routes[1]);
            }elseif(!isset($Routes[1]) or empty($Routes[1])){
                selectFormData($localArray);

            }else{
                echo stouts('The action you have entered is not alowed on a GET Request', 'error');
            }
        }elseif($method == "POST"){
           
            //This is where the receved form is entered into the database
            if(!empty($PostData)){
                insertFormData($PostData, $localArray, $Routes);
            }else{
                echo stouts('No data recieved POST', 'error');
            }
        }elseif($method == 'PUT'){
            if(!empty($PostData) and !empty($Routes[1])){
                updateFormData($PostData, $localArray, $Routes[1]);
            }else{
                echo stouts('No data recieved PUT', 'error');
            }
        }elseif($method == 'DELETE'){
            if(!empty($Routes[1])){
                deleteItem($localArray, $Routes[1]);
            }else{
                echo stouts('Please include an ID', 'error');
            }
        }
    }else{
        echo stouts('That Route Does Not Exist', 'error');
    }
}

//handler for the search
function search($localArray, $query){
    $DB = new DB;

    $sendData = [];
    $selectItems = [];
    foreach($localArray['items'] as $item){
        if($item['type'] == 'file' and isset($_GET['nofile'])){
            continue;
        }
        
        $sendData['Info'][$item['name']] = $item['inputLabel'];
        $selectItems[] = $item['name'];
        
    }
    $selectItems[] = 'ID';
    $selectItems = implode(', ', $selectItems);

    if(isset($_GET['limit'])){
        if($limit == 'all'){
            $limit = '';
        }else{
            $limit = 'Limit '.intval($_GET['limit']);
        }
    }else{
        $limit = '';
    }
    if($localArray['sort'] != 'Adate'){
        $sort = "order By ".$localArray['sort']." Desc, Adate Desc"; 
    }else{
        $sort = "order By ".$localArray['sort']." Desc";
    }
    if(isset($localArray['search'])){
        $searchKeys = explode(',', $localArray['search']);
        $searchItems = [];
        $search = 'WHERE ';
        $pdoData = [];
        $i = 0;
        foreach($searchKeys as $key){
            $searchItems[] = "$key like :ID".$i;
            $pdoData['ID'.$i] = '%'.strval($query).'%';
            $i += 1;
        }
        $search = $search.implode(' OR ', $searchItems);

    }else{
        echo stouts('You cannot search this DB', 'error');
        exit();
    }

    //echo json_encode($pdoData);
    //echo "SELECT * from ".$localArray['tableName']." ".$search." ".$sort." " .$limit." ";
    //exit();  
    
    $data = $DB->query("SELECT ".$selectItems." from ".$localArray['tableName']." ".$search." ".$sort." " .$limit."  ", $pdoData);
    
  

    foreach($data as $key=>$row){
        foreach($localArray['items'] as $item){
             $data[$key][$item['name']] = $row[$item['name']];
        }
    }
    
    $sendData['Data'] = $data;
    echo json_encode($sendData);

}
//handler updating the db
function updateFormData($formData, $localArray, $ID){
    $DB = new DB;
    
    if(!isset($formData['Token'])){
        echo stouts('Please include Login token', 'error');
        exit();
    }
    $data = $DB->query('SELECT * FROM `LoginAuth` WHERE Token = :token', array('token'=>$formData['Token']));

    if(empty($data)){
        echo stouts('Auth Token has expried or is invalid', 'error');
        exit();
    }
    
    $outArray = [];
    $dataArray = [];
    foreach($localArray['items'] as $items){
        if(isset($formData[$items['name']]) and $formData[$items['name']] != ""){
            $outArray[] = $items['name'];
            $dataArray[$items['name']] =  $formData[$items['name']];
        }
    }
    $final = [];
    foreach($outArray as $out){
        $final[] = $out.'=:'.$out;
    }
    $final = implode(', ', $final);
    $dataArray['ID'] = $ID;
    $dataUpdate = $DB->query('UPDATE '.$localArray['tableName'].' SET '.$final.' WHERE ID=:ID', $dataArray);

    echo stouts($localArray['tableTitle'].' Updated successfully', 'success');


}
//form builder for the update system
function selectUpdateFormItem($formArray, $redirectName, $ID){
    $sendData = [];
    $DB = new DB;
    if(!isset($_GET['token'])){
        echo stouts('Please include token parm', 'error');
        exit();
    }
    $data = $DB->query('SELECT Token, Expire from LoginAuth WHERE Token = :token', array('token'=>$_GET['token']));
    
    if(empty($data)){
        echo stouts('Auth Token has expried or is invalid', 'error');
        exit();
    }
    
   
    foreach($formArray['items'] as $item){
        $sendData['Info'][$item['name']] = $item['inputLabel'];
        $selectItems[] = $item['name'];
    }

    $selectItems[] = 'ID';
    $selectItems = implode(', ', $selectItems);
    

    $data = $DB->query("SELECT $selectItems from ".$formArray['tableName']." WHERE ID=:ID order By Adate Desc", array('ID'=>$ID));
    if(!isset($data[0]['ID'])){
        echo stouts('The ID is invalid', 'error');
        exit();
    }

    $updateData = $data[0];

    $FormPassthroughItems = [
        'name',
        'type',
        'typeName',
        'checkboxLabel',
        'required',
        'selectLabel',
        'textareaLabel', 
        'options',
        'inputLabel',
        'placeholder',
        'checkboxTitle',
        'password_check'
    ];
    $arrayToSend = [];
    
    $arrayToSend['form']['formName'] = $formArray['formName'];
    $arrayToSend['form']['formTitle'] = 'Update '.$formArray['formDesc'];
    $arrayToSend['form']['callBack'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/API/'.$redirectName.'/'.$ID;
    foreach($formArray['items'] as $items){
        $itemArray = [];
        if($items['type'] == 'date'){
            $itemArray['defaultValue'] = date('Y-m-d');
        }
        if($items['type'] == 'file'){
            continue;
        }
        if($items['type'] == 'date' and isset($formArray['fileUpload'])){
            continue;
        }
        foreach($items as $itemName => $item){
            if(in_array($itemName, $FormPassthroughItems)){
                $itemArray[$itemName] = $item;
            }
        }
        if(!empty($updateData[$items['name']])){
            $itemArray['defaultValue'] = $updateData[$items['name']];
        }
         
        $arrayToSend['form']['fields'][] = $itemArray;
    }
    echo json_encode($arrayToSend); 
}
//get structure for building the add forms
function getFormStruct($formArray, $redirectName, $action){
    if($action == 'add'){
        $redirectName = $redirectName.'/'.$action;
    }
   
    $FormPassthroughItems = [
        'name',
        'type',
        'typeName',
        'checkboxLabel',
        'required',
        'selectLabel',
        'textareaLabel', 
        'options',
        'inputLabel',
        'placeholder',
        'checkboxTitle',
        'password_check'
    ];
    $arrayToSend = [];
    $arrayToSend['form']['formName'] = $formArray['formName'];
    $arrayToSend['form']['formTitle'] = 'Add '.$formArray['formTitle'];
    $arrayToSend['form']['callBack'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/API/'.$redirectName;
    foreach($formArray['items'] as $items){
       
        $itemArray = [];
        if(isset($items['type']) and $items['type'] == 'date'){
            $itemArray['defaultValue'] = date('Y-m-d');
        }
        if(isset($items['type']) and $items['type'] == 'datetime-local'){
            $itemArray['defaultValue'] = date('Y-m-d\TH:i');
        }
        if(isset($items['passwordConfirm'])){
            $arrayToSend['form']['passwordCheck'] = [$items['passwordConfirm'], $items['name']];
        }
        foreach($items as $itemName => $item){
            if(in_array($itemName, $FormPassthroughItems)){
                $itemArray[$itemName] = $item;
            }
        }
        $arrayToSend['form']['fields'][] = $itemArray;
    }
    echo json_encode($arrayToSend);
}
//get individual info
function selectFormItem($localArray, $ID){
    global $FormBuilderArray;
    $sendData = [];
    $DB = new DB;
   
    
    foreach($localArray['items'] as $item){
        $sendData['Info'][$item['name']] = $item['inputLabel'];
        $selectItems[] = $item['name'];
    }
    
    $selectItems[] = 'ID';
    $selectItems = implode(', ', $selectItems);

    if($localArray['sort'] != 'Adate'){
        $sort = "order By ".$localArray['sort']." Desc, Adate Desc"; 
    }else{
        $sort = "order By ".$localArray['sort']." Desc";
    }
    
    if($ID == 'latest'){
        $data = $DB->query("SELECT $selectItems from ".$localArray['tableName']."  ".$sort." Limit 1");
    }else{
        $data = $DB->query("SELECT $selectItems from ".$localArray['tableName']." WHERE ID=:ID ".$sort." ", array('ID'=>$ID));
    }

    if(!isset($data[0]['ID'])){
        echo stouts('The ID is invalid', 'error');  
        exit();
    }
    
    foreach($data as $key=>$row){
        foreach($localArray['items'] as $item){
            if($item['typeName'] = 'FormInput' and isset($item['type']) and $item['type'] == 'datetime-local'){
                $data[$key][$item['name']] = date('D M d Y h:i A', strtotime($row[$item['name']])); 
            }elseif($item['typeName'] = 'FormInput' and isset($item['type']) and $item['type'] == 'date'){
                $data[$key][$item['name']] = date('M d Y', strtotime($row[$item['name']])); 
            }elseif($item['typeName'] = 'FormInput' and isset($item['type']) and $item['type'] == 'file' and isset($_ENV['file_url_prefex'])){
                $data[$key][$item['name']] = $_ENV['file_url_prefex'].$row[$item['name']]; 
            }
        }
    }

    $sendData['Data'] = $data;
    echo json_encode($sendData);
}
//get bulk data
function selectFormData($localArray){
    $sendData = [];
    $selectItems = [];
    foreach($localArray['items'] as $item){
        if(isset($item['type']) and $item['type'] == 'file' and isset($_GET['nofile'])){
            continue;
        }
        
        $sendData['Info'][$item['name']] = $item['inputLabel'];
        $selectItems[] = $item['name'];
        
    }
    $selectItems[] = 'ID';
    $selectItems = implode(', ', $selectItems);

    $DB = new DB;
    $sortDirection = 'Desc';
    if(isset($localArray['sortDirection'])){
        $sortDirection = $localArray['sortDirection'];
    }
    if(isset($_GET['limit'])){
        if($_GET['limit'] == 'all'){
            $limit = '';
        }else{
            $limit = 'Limit '.intval($_GET['limit']);
        }
    }else{
        $limit = '';
    }

    if(isset($localArray['future'])){
        $where = 'WHERE DATE('.$localArray['future'].') >= DATE(NOW()) ';
        $sortDirection = 'ASC';
    }else{
        $where = '';
    }
    
    if($localArray['sort'] != 'Adate'){
        $sort = "order By ".$localArray['sort']." ".$sortDirection.", Adate ".$sortDirection.""; 
    }else{
        $sort = "order By ".$localArray['sort']." ".$sortDirection."";
    }

    $data = $DB->query("SELECT $selectItems from ".$localArray['tableName']." ".$where." ".$sort." " .$limit."  ");
    
  

    foreach($data as $key=>$row){
        foreach($localArray['items'] as $item){
            if($item['typeName'] = 'FormTextarea' and isset($row[$item['name']]) and strlen($row[$item['name']]) > 200 and (!isset($_GET['full']) or $_GET['full'] == 0)){
                $data[$key][$item['name']] = substr($row[$item['name']], 0, 200)."...";
            }elseif($item['typeName'] = 'FormInput' and isset($item['type']) and $item['type'] == 'datetime-local'){
                $data[$key][$item['name']] = date('D M d Y h:i A', strtotime($row[$item['name']])); 
            }elseif($item['typeName'] = 'FormInput' and isset($item['type']) and $item['type'] == 'date'){
                $data[$key][$item['name']] = date('M d Y', strtotime($row[$item['name']])); 
            }elseif($item['typeName'] = 'FormInput' and isset($item['type']) and $item['type'] == 'file' and isset($_ENV['file_url_prefex']) and !empty($data[$key][$item['name']])){
                $data[$key][$item['name']] = $_ENV['file_url_prefex'].$row[$item['name']]; 
            }
        }
    }
    
    $sendData['Data'] = $data;
    echo json_encode($sendData);
    
    
}
//insert form data
function insertFormData($RecivedFormData, $localArray, $Routes){
    GLOBAL $client;
    $DB = new DB;

    
    if(!isset($RecivedFormData['Token'])){
        echo stouts('Please include auth token', 'error');
        exit();
    }
    $data = $DB->query('SELECT Token, Expire from LoginAuth WHERE Token = :token', array('token'=>$RecivedFormData['Token']));
    
    if(empty($data)){
        echo stouts('Auth Token has expried or is invalid', 'error');
        exit();
    }
    
    $insertStringArray = [];
    $pdoDataArray = [];
    
    foreach($RecivedFormData as $itemKey => $item){
        foreach($localArray['items'] as $formItem){
            if($itemKey == $formItem['name']){

                $pdoDataArray[$itemKey] = $item; 
                $insertStringArray[] = $itemKey;
         
                continue;
            }
        }
    }
    //handle any file uploads
    if(isset($localArray['fileUpload']) and $localArray['fileUpload']){
        foreach($_FILES as $key=>$file){
            foreach($localArray['items'] as $fileItems){
                if($fileItems['name'] == $key and $file['size'] != 0){
                    $file_name = $file['name'];
                    $file_size =$file['size'];
                    $file_tmp =$file['tmp_name'];
                    $file_type=$file['type'];
                    $file_name_array = explode('.',$file['name']);
                    $file_ext=strtolower(array_pop($file_name_array));
                    $file_name = time()."_".implode('.',$file_name_array);
                    $ext= array("pdf", "ppt", "pptx", "doc", "docx", 'jpg', 'mp3');
                    if(isset($fileItems['accept'])){
                        $ext = explode(',', $fileItems['accept']);
                    }
                    if(!in_array($file_ext,$ext)){
                        $errors[]="extension not allowed, please choose Allowed file type owen. $file_ext";
                    }
                    
                    if($file_size > 80000000){
                        $errors[]='File size must be less than 80 MB';
                    }
                    if(empty($errors)){
                        if(isset($pdoDataArray['Date'])){
                            $date = $pdoDataArray['Date'];
                        }else{
                            $date = date('Y-m-d');
                        }
                        if(isset($_ENV['use_spaces']) and $_ENV['use_spaces'] == "true"){
                            $client->putObject([
                                'Bucket' => 'te3',
                                'Key'    => "GoodlowEFC/Files/".$Routes[0]."/".$date."/".$file_name.".".$file_ext,
                                'SourceFile' => $file_tmp,
                                'ACL'    => 'public-read',
                            ]);
                            $insertStringArray[] = $key;
                            $pdoDataArray[$key] = "Files/".$Routes[0]."/".$date."/".$file_name.".".$file_ext;
                        }else{
                            $Uploadfile_dir = "Files/".$Routes[0]."/".$date."/";
                            $file_dir = '../'.$Uploadfile_dir;
                            if(!is_dir($file_dir)){
                                mkdir($file_dir, 0755, true);
                            }
                            $fileurl = $file_dir.$file_name.".".$file_ext;
                            $uploadfileurl = $Uploadfile_dir.$file_name.".".$file_ext;
                            move_uploaded_file($file_tmp, $fileurl);
                            $insertStringArray[] = $key;
                            $pdoDataArray[$key] = $uploadfileurl;
                        }
                        
                    }
                    else{
                        echo json_encode(['error'=>$errors]);
                        exit();
                    }
                }
            }
        }
    }
    
    if(isset($localArray['UUID']) and $localArray['UUID']){
        //creation of the UUID for the table item
        do{
            $UUID = bin2hex(random_bytes(24));
        }while(!empty($DB->query("SELECT ID from ".$localArray['tableName']." WHERE ID = '$UUID'")));
        $insertStringArray[] = 'ID';
        $pdoDataArray['ID'] = $UUID;
    }
    
  
    
    $values = implode(', ',$insertStringArray);
    $dataValues =':'.implode(', :',$insertStringArray);
    $DB->query("INSERT INTO ".$localArray['tableName']." ($values) VALUES ($dataValues)", $pdoDataArray);
    
    if(isset($localArray['tokenAuth'])){
        $data = $DB->query('DELETE from '.$localArray['tokenAuth'].' WHERE Token = :token', array('token'=>$RecivedFormData['Token']));
    }

    echo stouts($localArray['formTitle'].' Added Sucessfuly', 'success');
    
}

//delete item
function deleteItem($formArray, $ID){
    if(!isset($_GET['token'])){
        echo stouts('Please include token parm', 'error');
        exit();
    }
    
    $DB = new DB;
    
    $data = $DB->query('SELECT * FROM `LoginAuth` WHERE Token = :token', array('token'=>$_GET['token']));

    if(empty($data)){
        echo stouts('Auth Token has expried or is invalid', 'error');
        exit();
    }
   

    $data = $DB->query("SELECT ID from ".$formArray['tableName']." WHERE ID=:ID", array('ID'=>$ID));
    if(!isset($data[0]['ID'])){
        echo stouts('The ID is invalid', 'error');
        exit();
    }

    $del = $DB->query('DELETE FROM '.$formArray['tableName'].' WHERE ID=:ID', array('ID'=>$ID));
    echo stouts('Item successfully deleted', 'success');
}

//init the router
InitRouter();