<?php 

require('secret.php');

class DB{

    private static function connection(){
        global $ADMIN_SECRET_KEYS;
        $username=$ADMIN_SECRET_KEYS['username'];
        $password=$ADMIN_SECRET_KEYS['password'];
        $host="127.0.0.1";
        $db="GoodlowEFC";
        $pdo = new PDO("mysql:dbname=$db;host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    public static function exFile($sql){
        $auth = self::connection();
        $qr = $auth->exec($sql);
    }
    public static function query($query, $params = array()){
        $stat = self::connection()->prepare($query);
        $stat->execute($params);
        if(explode(" ", $query)[0] == 'SELECT'){
            $data = $stat->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }else{
            return 1;
        }
    }
}