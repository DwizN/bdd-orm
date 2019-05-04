<?php
require_once "EntityInterface.php" ;
require_once "mySql.php";
abstract class Entity implements Entityinterface {

    protected static $tableName = null ;
    private $reflexion ;


    public function __construct(){
        $this->reflexion = new \ReflectionClass($this);
    }

    public function save() {

        $properties = $this->reflexion->getProperties(ReflectionProperty::IS_PUBLIC);
        $props = array();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if($propertyName != "id"){
                $props[] = '' . $property->getName() . ' = "' . $property->getValue($this) .'"' ;
            }
        }
        if($this->id){
        $sqlQuery = "UPDATE " . static::getTableName() . " SET " . implode(' , ', $props) ." WHERE id = " .$this->id;
        } else {
        $sqlQuery = "INSERT INTO " . static::getTableName() . " SET " . implode(' , ', $props);
        }
        try {
            MySql::getInstance()->getConnection()->exec($sqlQuery);
        }catch(Exception $e){
            var_dump($e->getMessage());die;
        }
    }

    public function load($id) {

        $properties = $this->reflexion->getProperties(ReflectionProperty::IS_PUBLIC);
        $query = "SELECT * FROM ". static::getTableName() ." WHERE id = ". $id .";";
        $pdo = MySql::getInstance()->getConnection();
        $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);

        foreach ($properties as $property){
            $propertyName = $property->getName();
            $property->setValue($this,$result[$propertyName]);

        }
    }

    public static function find($clausewhere){
        $clausewhere = (isset($clausewhere)) ? $clausewhere : '1';
        $requete = $query = "SELECT * FROM ". static::getTableName() ." WHERE ". $clausewhere;
        $pdo = MySql::getInstance()->getConnection();
        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);


    }

    public static function getTableName(){
        $reflection= new \ReflectionClass(get_called_class());
        $class = get_called_class();
        return NULL !== $class::$tableName ? $class::$tableName : strtolower($treflection->getName());
    }
}
?>