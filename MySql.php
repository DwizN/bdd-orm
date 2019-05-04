<?php 

class MySql
{
    const DEFAULT_USER = "root";
    const DEFAULT_HOST = "127.0.0.1";
    const DEFAULT_PASS = "root";
    const DEFAULT_DBNAME = "bdaa";
    const DEFAULT_PORT = "8889";

    private $PDOInstance = null ;

    private static $MysqlInstance = null ;

    public function __construct(){
        try{
            $this->PDOInstance  = new PDO('mysql:host='. self::DEFAULT_HOST.';port='.self::DEFAULT_PORT.';dbname='. self::DEFAULT_DBNAME,  self::DEFAULT_USER , self::DEFAULT_PASS);

        }catch(Exception $e){
            var_dump($e->getMessage());die;
        }
    }

    public static function getInstance() {
        if(is_null(self::$MysqlInstance)){
            self::$MysqlInstance = new MySql() ;
        }
        return self::$MysqlInstance ;
    }

    public function getConnection(){
        return $this->PDOInstance ;
    }
}