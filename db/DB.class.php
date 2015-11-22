<?php
class DB {

    private $database = null;
    const IMGDIR = 'models/images';
    const IMGTHUMBDIR =  'models/thumbs';

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
            $row['images'] = $this->getAllImages($row['type_model']);
            $row['images_thumbs'] = $this->getAllImages($row['type_model'],1);
            $result[$row['type_model']] = $row;
        }
        return $result;
    }    
     
    public function getAllImages($type_model,$thumbs = false) {
        $result = array();
        
        if($thumbs){
            $filesPaths = scandir(self::IMGTHUMBDIR);
        foreach ($filesPaths as $path) {   
            if(preg_match("/^".$type_model."_[0-9]+/",$path)){
                
                $result[] = '/'.self::IMGTHUMBDIR.'/'.$path;
            }
        }
        return $result;
        }
        
        $filesPaths = scandir(self::IMGDIR);
        foreach ($filesPaths as $path) {   
            if(preg_match("/^".$type_model."_[0-9]+/",$path)){
                
                $result[] = '/'.self::IMGDIR.'/'.$path;
            }
        }
        return $result;
    }
    
}
?>
