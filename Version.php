<?php
require_once "Entity.php" ;
class Version extends Entity {

    protected static $tableName = "version";
    public $id;
    public $tab;
    public $version;
    public $nb_fields;

/**
     * @return string
     */
    public static function getTableName()
    {
        return self::$tableName;
    }

    /**
     * @param string $tableName
     */
    public static function setTableName($tableName)
    {
        self::$tableName = $tableName;
    }

        /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }




    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->tab;
    }

    /**
     * @param mixed $content
     */
    public function setTable($tab)
    {
        $this->tab = $tab;
    }

        /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $content
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

            /**
     * @return mixed
     */
    public function getNbFields()
    {
        return $this->nb_fields;
    }

    /**
     * @param mixed $nbfields
     */
    public function setNbFields($nb_fields)
    {
        $this->nb_fields = $nb_fields;
    }

}
?>