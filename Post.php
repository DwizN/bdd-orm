<?php
require_once "Entity.php" ;
class Post extends Entity {

    protected static $tableName = "Post";
    public $id;
    public $content;
    private $notme;

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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getNotme()
    {
        return $this->notme;
    }

    /**
     * @param mixed $notme
     */
    public function setNotme($notme)
    {
        $this->notme = $notme;
    }
}
?>