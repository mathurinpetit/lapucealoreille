<?php
$dae_models = array('logo' => './models/logo.dae',
    'classic_puce' => './models/puce_classic_without_texture.dae',
    'attache_or' => './models/attache_or.dae',
    'attache_argent' => './models/attache_argent.dae');

$models = array('classic_or_rond' => array('puce_model' => 'classic_puce',
                                           'attache_model' => 'attache_or',
                                            'texture' => 'texture_or_petit_rond'));

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>La Puce à l'oreille - classic or rond</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    </head>
    <body>
        <script type="text/css" src="css/main.css"></script>
        <script src="lib/jquery-1.8.3.js"></script>
        <script src="lib/three.js/three.js"></script>
        <script src="lib/three.js/ColladaLoader.js"></script>

        <script src="lib/three.js/Detector.js"></script>
        <script src="lib/three.js/Stats.js"></script>
        <script src="lib/Puces.js"></script>        
        <script src="lib/microcache.js"></script>
        <script>
            if (! Detector.webgl )
                Detector.addGetWebGLMessage();
            
            var container, stats;
            
            var camera, scene, renderer, mirrorCube;                        
            var t = 0;
            var clock = new THREE.Clock();
            
            
            
            /*
             * Fonctions de chargement en chaine des modèles collada
             */
            
<?php
$cpt = 0;
$dae_models_keys = array_keys($dae_models);
foreach ($dae_models as $key => $dae_model):
    ?>
                                    
            var <?php echo 'loader_for_' . $key; ?> = new THREE.ColladaLoader();
    <?php echo 'loader_for_' . $key; ?>.options.convertUpAxis = true;
                                    
            var <?php echo $key; ?> = null;  
            var <?php echo $key . 'Func'; ?> = function(){
    <?php echo 'loader_for_' . $key; ?>.load("<?php echo $dae_model; ?>", function colladaReady(collada) {
                model = collada.scene;                
                model.scale.x = model.scale.y = model.scale.z = 0.02;
                model.updateMatrix();
    <?php echo $key; ?> = model;
    <?php
    if ($cpt < count($dae_models) - 1):
        echo $dae_models_keys[$cpt + 1] . 'Func();';
    else :
        ?> 
                        init();
                        animate();    
    <?php
    endif;
    ?>           
            },function(){} ); 
        };
    <?php
    $cpt++;
endforeach;
?>

var pucePool =null;
                    
function init() {
    camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 0.1, 2000 );
    camera.position.set( 0, 0 , 0 );

    renderer = new THREE.WebGLRenderer();  
    renderer.setSize( window.innerWidth, window.innerHeight );
    renderer.shadowMapEnabled = true;
    renderer.shadowMapSoft = true;
        
    renderer.shadowCameraNear = 0.5;
    renderer.shadowCameraFar = camera.far;
    renderer.shadowCameraFov = 50;
        
    renderer.shadowMapBias = 0.0039;
    renderer.shadowMapDarkness = 0.5;
    renderer.shadowMapWidth = 1024;
    renderer.shadowMapHeight = 1024;
    renderer._microCache = new MicroCache(); 
                
                
    container = document.createElement( 'div' );
    $(container).attr('id','classic_or_rond');
    document.body.appendChild( container );
    scene = new THREE.Scene();
                
                
    pucePool = new PUCES(scene,renderer);   
            
            <?php 
            $cpt = 0;
            while($cpt<2){
            foreach ($models as $model_name => $model): ?>
                pucePool.createPuce("<?php echo $model_name.$cpt; ?>",
                <?php echo $model["puce_model"]; ?>,   
                    <?php echo $model["attache_model"]; ?>,
    "<?php echo $model["texture"]; ?>" ,
    true, true, true ,0.02);
               pucePool.vitesseTranslation( "<?php echo $model_name.$cpt; ?>", 0);
               pucePool.setPosition( "<?php echo $model_name.$cpt; ?>", <?php echo $cpt*2 - 1; ?>, -0.4, -5);
          <?php  endforeach;
              $cpt++;  
          
            }
          ?>
              
    initSpot(0,20,-20,true,false);                                
    initSpot(0,20,20,true,false);
               
               
                
    // Lights
    var ambianteLight = new THREE.AmbientLight( 0x333333 );
    scene.add(ambianteLight);
                                          
    stats = new Stats();
    stats.domElement.style.position = 'absolute';
    stats.domElement.style.top = '0px';
    container.appendChild( stats.domElement );


    window.addEventListener( 'resize', onWindowResize, false );
    container.appendChild( renderer.domElement );
}
            
function onWindowResize() {

    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
                
    renderer.setSize( window.innerWidth, window.innerHeight );

}
                     
function animate() {
                
    requestAnimationFrame( animate );
    render();
    stats.update();
}
            
function render() {

    var timer = Date.now() * 0.0005;         
    pucePool.update(renderer);     
    
    renderer.clear();
    renderer.render( scene, camera );
}
               
function initSpot(x,y,z,shadow,debug){
    var spot = new THREE.SpotLight( 0xffffff,1);
    setSpotParameters(spot,x,y,z,shadow,debug);   
    scene.add(spot); 
}
            
function setSpotParameters(spot,x,y,z,shadow,debug){
    spot.position.set(x,y,z);
    if(shadow){
        spot.shadowDarkness = 0.7; 
        spot.shadowMapWidth = 1024;
        spot.shadowMapHeight = 1024;

        spot.shadowCameraNear = 0.1;
        spot.shadowCameraFar = 100;
        spot.shadowCameraFov = 70;
        spot.castShadow = true;
    }

    spot.shadowCameraVisible = debug;
}     
         
            
$(document).ready(function() {
    /**
     * Appel du premier modèle à charger => lancement de l'init et de l'animate
     */      
<?php echo $dae_models_keys[0] . 'Func();'; ?>
});    
        </script>
        <?php foreach ($models as $model_name => $model) : 
     
            ?>
        
        <form id="<?php echo $model_name; ?>" action="./<?php echo $model_name; ?>" method="post" ></form>
        <?php 
        endforeach; ?>
    </body>
</html>