<?php 

class DB{

    private static function connection(){
        $username=$_ENV['username'];
        $password=$_ENV['password'];
        $host=$_ENV['host'];
        $db=$_ENV['database'];
        $port = $_ENV['port'];
        $pdo = new PDO("mysql:dbname=$db;port=$port;host=$host", $username, $password);
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