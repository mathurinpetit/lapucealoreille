<?php
class DB {

    private $database = null;
    
    function __construct() {
        $this->database = new SQLite3('lapucealoreille.db');
    }
    
    public function getDB(){
        return $this->database;
    }
    
    public function getAllDaeModels(){
        $dae_models = $this->getDB()->query('SELECT * FROM daemodels');
        $result = array();
        while ($row = $dae_models->fetchArray()) {
            $result[$row['id']] = 'models/'.$row['path'];
        }
        return $result;
    }
    
    public function getAllModels(){
        $models = $this->getDB()->query('SELECT * FROM models');
        $result = array();
        while ($row = $models->fetchArray()) {
            $result[$row['type_model']] = $row;
        }
        return $result;
    }
}
?>
