<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DAEModelsLoader
 *
 * @author mathurin
 */
class DAEModelsLoader {
    
        
    function createLoadProcess($dae_models) {
        $loadProcessString = "";
        $cpt = 0;
        $dae_models_keys = array_keys($dae_models);
        foreach ($dae_models as $key => $dae_model){
            $loadProcessString.="  var loader_for_$key = new THREE.ColladaLoader();";
            $loadProcessString.= " loader_for_$key.options.convertUpAxis = true; ";
            $loadProcessString.= " var $key = null; "; 
            $loadProcessString.= " var ".$key."Func =  function(){ ";
            $loadProcessString.= " loader_for_$key.load(\"".$dae_model."\", function colladaReady(collada) { ";
            $loadProcessString.= " model = collada.scene; "; 
            $loadProcessString.= " model.scale.x = model.scale.y = model.scale.z = 0.02; "; 
            $loadProcessString.= " model.updateMatrix(); "; 
            $loadProcessString.= " $key = model; "; 
            if ($cpt < count($dae_models) - 1){
                $loadProcessString.= " ".$dae_models_keys[$cpt + 1] . 'Func(); ';
            }else{
                $loadProcessString.= " init(); animate();  ";
            }        
            $loadProcessString.= " },function(){} );  }; ";
        $cpt++;
        }
        return $loadProcessString;
    }
}

?>
