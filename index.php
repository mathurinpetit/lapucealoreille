<?php
$dae_models = array('logo' => './models/logo.dae',
    'classic_puce' => './models/puce_classic_without_texture.dae',
    'attache_or' => './models/attache_or.dae',
    'attache_argent' => './models/attache_argent.dae');

$models = array('classic_or_rond' => array('puce_model' => 'classic_puce',
                                           'attache_model' => 'attache_or',
                                            'texture' => 'texture_or_petit_rond'),
    'classic_argent' => array('puce_model' => 'classic_puce',
                                           'attache_model' => 'attache_argent',
                                            'texture' => 'texture_argent'),
    'classic_argent_carre' => array('puce_model' => 'classic_puce',
                                           'attache_model' => 'attache_argent',
                                            'texture' => 'texture_argent_carre'));

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>La Puce à l'oreille</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
                
        <LINK href="css/main.css" rel="stylesheet" type="text/css">
        <script src="lib/jquery-1.8.3.js"></script>
        <script src="lib/three.js/three.js"></script>
        <script src="lib/three.js/FirstPersonControls.js"></script>
        <script src="lib/three.js/ColladaLoader.js"></script>
        <script src="lib/three.js/Detector.js"></script>
        <script src="lib/three.js/Stats.js"></script>
        <script src="lib/Panier.js"></script>
        <script src="lib/Puces.js"></script>
    </head>
    <body>        
        <script>
            if (! Detector.webgl )
                Detector.addGetWebGLMessage();
            var debug = true;
            var container, stats, panier;

            var camera, scene, renderer, controls, projector;
            var highLight;
            var sound;
            
            var plane;
            var panelOverlay = [];             
            var panelText; 
            var t = 0;
            var clock = new THREE.Clock();
            var mouse = { x: 0, y: 0 }, INTERSECTED;
            var stop = false;
            var selectedModel_0, selectedModel_1;
            
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


var Sound = function ( sources, radius, volume ) {
    var audio = document.createElement( 'audio' );
    for ( var i = 0; i < sources.length; i ++ ) {
        var source = document.createElement( 'source' );
        source.src = sources[ i ];
        audio.appendChild( source );
    } 
    
    this.position = new THREE.Vector3();
    
    this.playSound = function () {
        audio.play();
    }
            
    this.updateSound = function ( camera ) {
        var distance = this.position.distanceTo( camera.position );
        if ( distance <= radius ) {
            audio.volume = volume * ( 1 - distance / radius );
        } else {
            audio.volume = 0;
        }
    }
    
    audio.addEventListener('ended', function(){
        this.currentTime = 0;
    }, false);
}

var pucePool =null;
                    
function init() {
    camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 0.1, 2000 );
    camera.position.set( -15, 2 , 0 );

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
                
                
                
    container = document.createElement( 'div' );
    document.body.appendChild( container );
    
    scene = new THREE.Scene();
    projector = new THREE.Projector();
                
                
    controls = new THREE.FirstPersonControls( camera );
    controls.movementSpeed = 4;
    controls.lookSpeed = 0.05;
    controls.lookVertical = false;
    controls.constrainVertical = false;
    controls.verticalMin = 1.1;
    controls.verticalMax = 2.2;

            

                
                
    pucePool = new PUCES(scene);   
        
    sound = new Sound( [ './sounds/sheherazade.mp3', './sounds/sheherazade.ogg'], 50, 1 );
    sound.position.x = 0;
    sound.position.y = 0;
    sound.position.z = 0;
    sound.playSound();  
            
            <?php 
            foreach ($models as $model_name => $model): ?>
                pucePool.createPuce("<?php echo $model_name; ?>",
                <?php echo $model["puce_model"]; ?>,   
                    <?php echo $model["attache_model"]; ?>,
    "<?php echo $model["texture"]; ?>" ,
    true, false, true ,0.05);
               
          <?php  endforeach; ?>              


   
                
    //  pucePool.createPuce(modelPath , modelTexturePath , attacheModelPath,false, false, true );
    //  pucePool.createPuce(modelPath , modelTexturePath , attacheModelPath,false, false, true );                


            
                
    //   initTrees(loader);
    initSpot(0,20,-20,true,false);                                
    initSpot(0,20,20,true,false);
       
    highLight = new THREE.SpotLight( 0xffffff,0);
    scene.add(highLight);
                               
    scene.fog = new THREE.FogExp2( 0x778877, 0.04 );

                
    // Lights
    var ambianteLight = new THREE.AmbientLight( 0x333333 );
    scene.add(ambianteLight);
                
    //     sphereDebug(0,2,12);             
       
       
    createFloor();
    
    if(debug){            
        stats = new Stats();
        stats.domElement.style.position = 'absolute';
        stats.domElement.style.top = '0px';
        container.appendChild( stats.domElement );
    }
    
    panier = new Panier(this);
    panier.domElement.style.position = 'absolute';
    panier.domElement.style.top = '0px';
    panier.domElement.style.right = '500px';
    container.appendChild( panier.domElement );


    window.addEventListener( 'resize', onWindowResize, false );
    document.addEventListener( 'mousemove', onDocumentMouseMove, false );
    document.addEventListener( 'click', onDocumentMouseClick, false );
    container.appendChild( renderer.domElement );
}
            
function onWindowResize() {

    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
                
    renderer.setSize( window.innerWidth, window.innerHeight );

}
            
function onDocumentMouseMove( event ) {
    event.preventDefault();
    mouse.x = ( event.clientX / window.innerWidth ) * 2 - 1;
    mouse.y = - ( event.clientY / window.innerHeight ) * 2 + 1;
} 
                        
function onDocumentMouseClick( event ) {
    event.preventDefault();
    if(INTERSECTED != null){
        pucePool.setVitesseTranslationRotationForAll(0,0);
        displayModelPanel(INTERSECTED.id);
    }
} 

function onDocumentMousePanelClick( event ) {
    event.preventDefault();
    for (var i in panelOverlay){
        scene.remove(panelOverlay[i]);
        delete panelOverlay[i];
    }
    scene.remove(panelText);
    scene.remove(logo);
    pucePool.removeModel(selectedModel_0);
    pucePool.removeModel(selectedModel_1);
    document.removeEventListener('DOMMouseScroll', onDocumentMouseWheel, false);
    document.removeEventListener( 'click', onDocumentMousePanelClick, false );
    pucePool.setVitesseTranslationRotationForAll(0.05,0.05);
    stop = false;
} 



function onDocumentMouseWheel( event ){
    event.preventDefault();
    var delta = 0;
    if (event.wheelDelta) {
        delta = event.wheelDelta / 600;
 
    } else if (event.detail) {
         delta = -event.detail / 20;
    }
    var pos0 = pucePool.getPosition(selectedModel_0);
    var pos1 = pucePool.getPosition(selectedModel_1);
    
    if(delta > 0 && pos0.y < -0.4) 
        return;
    if(delta < 0 && pos0.y > 8.30) 
        return;
    for(var i in panelOverlay){
        panelOverlay[i].position.y += -delta;
    }
    panelText.position.y += -delta;
    logo.position.y += -delta;
    pucePool.setPosition(selectedModel_0,pos0.x, pos0.y - delta, pos0.z);
    pucePool.setPosition(selectedModel_1,pos1.x, pos1.y - delta, pos1.z);

}


function animate() {
                
    //  updateTrees();
                
    requestAnimationFrame( animate );
    render();
    if(debug)
        stats.update();
}
            
function render() {

    var timer = Date.now() * 0.0005;              
    pucePool.update(renderer);     
    if(!stop){            
        controls.update( clock.getDelta() );
    }
    sound.updateSound( camera );   
    highLightInit(4);
                            
    renderer.clear();
    renderer.render( scene, camera );
}
            
function sphereDebug(x,y,z){
    // set up the sphere vars
    var radius = 1,
    segments = 16,
    rings = 16;
    
    var sphereMaterial =
        new THREE.MeshPhongMaterial(
    {
        color: 0xCC0000
    });

    var sphere = new THREE.Mesh(

    new THREE.SphereGeometry(
    radius,
    segments,
    rings),

    sphereMaterial);

    sphere.position.x = x;
    sphere.position.y = y;
    sphere.position.z = z;
    // add the sphere to the scene
    sphere.castShadow = true;
    sphere.receiveShadow = true;
    scene.add(sphere);
                
}
            
function initSpot(x,y,z,shadow,debug){
    var spot = new THREE.SpotLight( 0xffffff,0.7);
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

function displayModelPanel(id){
    
    stop = true;
    var pucePosition = pucePool.getPosition(id);
    highLightDisable(id);
    var vector = new THREE.Vector3( pucePosition.x, camera.position.y, pucePosition.z);
    var direction = vector.subSelf( camera.position ).normalize();
    
    selectedModel_0 = id+'_panel_0';
    selectedModel_1 = id+'_panel_1';
    
    var v = createTransparentPanel(direction,id);
    
    var x0 = camera.position.x + (v.x)*8;
    var z0 = camera.position.z + (v.z)*8;
    
    var vectX = 2.2*v.z;
    var vectZ = -2.2*v.x;
    
    pucePool.copyPuceForPanel(id, selectedModel_0);
    pucePool.setPosition(selectedModel_0, x0+vectX, 1.4, z0+vectZ);  
    
    
    pucePool.copyPuceForPanel(id, selectedModel_1);
    pucePool.setPosition(selectedModel_1, x0 + 1.6 * vectX, 1.4, z0 + 1.6 * vectZ);
    
}
                        
function initTrees(loader){
    loader.load( './models/tree.dae', function ( collada ) {
        var tree = collada.scene;
        tree.scale.x = tree.scale.y = tree.scale.z = 0.002;
        var made = false;
        // for(var i=-15; i<15;i+=10){
        //  for(var j=-15; j<15;j+=10){
        if(!made){
            var x = 0 + Math.random()*10;
            var z = 0 + Math.random()*10;
            createTree(tree,x,z);
        }
        made=!made;
        //        }
        //   }
    });
}
            
            
function createTree(tree,x,z){
    var treeCloned = tree.clone();	 
    treeCloned.position.x = x;
    treeCloned.position.z = z;
    treeCloned.rotation.y = Math.random() * Math.PI * 2;
    treeCloned.traverse(function ( child ) {

        child.castShadow = true;

    } );
    scene.add( treeCloned );
                    
}

function createFloor(){
    var groundGeo = new THREE.PlaneGeometry(400, 400);
    var groundMat = new THREE.MeshPhongMaterial( {color: 0xFFFFFF});
    var ground = new THREE.Mesh(groundGeo,groundMat); 
    ground.position.y = -1; 
    ground.rotation.x = -Math.PI/2; 
    ground.doubleSided = true; 
    ground.receiveShadow = true;
    scene.add(ground); 
}
 
function createTransparentPanel(direction,modelType){
    
    var c = document.createElement("canvas");
    
    var xc = c.getContext("2d");
    c.width = c.height = 1024;
    
    //xc.shadowBlur = 1;
    xc.fillStyle = "black";
    xc.font = "14pt arial bold";
    xc.fillText("LA PUCE A L'OREILLE - Modèle Classic Or Rond Small", 200, 40);
    xc.font = "10pt arial bold";
    xc.fillText("Modèle : \"Classics Or\"", 450, 160);
    xc.fillText("Type de puce : \"Classics Or rond (S)\"", 450, 200);
    xc.fillText("Attache : \"Or\"", 450, 240);
    xc.fillText("Cette paire est constituée d'une puce \"Classics Or rond\" Cette paire est constituée d'une puce \"Classics Or rond\"", 450, 300, 12);
    xc.fillText("dont le centre est rond et porte une attache simple dorée.", 400, 340);
    xc.fillText("Elle fait partie de la collection des \"Classics\", ",400, 380);
    xc.fillText("première collection vendue par La Puce à l'oreille.",400, 420);
    xc.fillText("C'est une boucle d'oreille élégante et simple",400, 460);
    xc.fillText("tout en restant originale. Entièrement fait main",400, 500);
    xc.fillText("avec des puces de récupération, elle présente une",400, 540);
    xc.fillText("mise en bijoux écologique d'un produit courant.",400, 580);
    xc.fillText("Première paire des créations La Puce à l'oreille,",500, 620);
    xc.fillText("Elle est le \"Must have\" de la marque.", 500,660);
    xc.fillStyle = "red";
    xc.fillRect(500, 700, 100, 50);
    
     var maxWidth = 400;
         var lineHeight = 60;
         var x = 20; // (canvas.width - maxWidth) / 2;
         var y = 58;


         var text = document.getElementById("text").value.toUpperCase();
    wrapText(xc, text, x, y, maxWidth, lineHeight);
    
    var htmlImg = document.getElementById("html5Img");
    xc.drawImage(htmlImg, 40, 500);
 
    
    
    var panelTextGeo = new THREE.PlaneGeometry(10, 10);
    
    var panelOverlayGeo_0 = new THREE.PlaneGeometry(10, 1.5);
    var panelOverlayGeo_1 = new THREE.PlaneGeometry(0.5, 3.5);
    var panelOverlayGeo_2 = new THREE.PlaneGeometry(6.5, 3.5);
    var panelOverlayGeo_3 = new THREE.PlaneGeometry(10, 7.25);
    
    var panelOverlayMat = new THREE.MeshPhongMaterial( {color: 0x000000, opacity:0.35, transparent: true});
    
    var texture = new THREE.Texture(c);
    var xm = new THREE.MeshLambertMaterial({map: texture, transparent:true });
    xm.doubleSided = true; 
    xm.map.needsUpdate = true;
    
    panelText = new THREE.Mesh(panelTextGeo, xm);
    
    var panelOverlay_0 = new THREE.Mesh(panelOverlayGeo_0,panelOverlayMat);
    var panelOverlay_1 = new THREE.Mesh(panelOverlayGeo_1,panelOverlayMat);
    var panelOverlay_2 = new THREE.Mesh(panelOverlayGeo_2,panelOverlayMat);
    var panelOverlay_3 = new THREE.Mesh(panelOverlayGeo_3,panelOverlayMat);
    
    var v = panelText.position.clone();
    v.addSelf( direction );
    v.y = 0;
    var vI = v.clone().multiplyScalar(-1);
    panelText.lookAt( vI ); 
    logo.lookAt(vI);
    panelOverlay_0.lookAt( vI ); 
    panelOverlay_1.lookAt( vI ); 
    panelOverlay_2.lookAt( vI );
    panelOverlay_3.lookAt( vI );
    panelText.doubleSided = panelOverlay_0.doubleSided = panelOverlay_1.doubleSided = panelOverlay_2.doubleSided = panelOverlay_3.doubleSided = true; 
    
    var baseX = camera.position.x + direction.x * 8;
    var baseZ = camera.position.z + direction.z * 8;
    
    panelText.position.x = panelOverlay_0.position.x = panelOverlay_1.position.x = panelOverlay_2.position.x = panelOverlay_3.position.x = baseX ;
    panelText.position.y = panelOverlay_0.position.y = panelOverlay_1.position.y = panelOverlay_2.position.y = panelOverlay_3.position.y = 0;
    panelText.position.z = panelOverlay_0.position.z = panelOverlay_1.position.z = panelOverlay_2.position.z = panelOverlay_3.position.z = baseZ ;
    
    panelText.position.x -= direction.x * 0.1;
    panelText.position.z -= direction.z * 0.1;
    
    
    
    panelOverlay_0.position.y +=4.5;
    panelOverlay_1.position.y += 2;
    panelOverlay_2.position.y += 2;
    panelOverlay_1.position.z += - 4.75*v.x;
    panelOverlay_1.position.x += 4.75*v.z;
    panelOverlay_2.position.z += 1.75*v.x;
    panelOverlay_2.position.x += -1.75*v.z;
    panelOverlay_3.position.y -= 3.375;
    
    scene.add(panelText);
    scene.add(panelOverlay_0);
    panelOverlay.push(panelOverlay_0);
    
    scene.add(panelOverlay_1);
    panelOverlay.push(panelOverlay_1);
    
    scene.add(panelOverlay_2);
    panelOverlay.push(panelOverlay_2);
    
    scene.add(panelOverlay_3);
    panelOverlay.push(panelOverlay_3);
    
    var logoPosBase = panelOverlay_0.position.clone();
    logo.position.z = logoPosBase.z + 2.75*v.x + direction.z * -0.5;
    logo.position.x = logoPosBase.x - 2.75*v.z + direction.x * -0.5;
    logo.position.y = logoPosBase.y - 0.5;  
    //logo.rotation.y = Math.PI;
    logo.scale.x = logo.scale.y = logo.scale.z = 0.05;
    scene.add( logo );
    
    document.addEventListener('DOMMouseScroll', onDocumentMouseWheel, false);
    document.addEventListener( 'click', onDocumentMousePanelClick, false );
    highLightEnable(panelText, direction, 10, v);    
    return v;
}
                
function highLightInit(dist){
    // find intersections
    var vector = new THREE.Vector3( mouse.x, mouse.y, 1 );
    projector.unprojectVector( vector, camera );
    var direction = vector.subSelf( camera.position ).normalize();
    var raycaster = new THREE.Raycaster( camera.position, direction );
    var intersects = raycaster.intersectObjects( scene.children );
    if ( intersects.length > 0 ) {

        if ( INTERSECTED != intersects[ 0 ].object ) {
            if ( INTERSECTED ){
                highLightDisable(INTERSECTED);
            }
            INTERSECTED = intersects[ 0 ].object;
            if(typeof INTERSECTED.id  != "number"){
                highLightEnable(INTERSECTED,vector,dist,direction);                                        
            }else{
                INTERSECTED = null;
            }
        }
    } else {
        if ( INTERSECTED ){
            highLightDisable(INTERSECTED); 
        }
        INTERSECTED = null;
    }   
}
       
       function addPanier(){
           console.log('addPanier');
       }
       
function highLightDisable(obj){
   if(!stop){
    highLight.intensity = 0;
   }    
    $('canvas').css('cursor','default');
}

                        
function highLightEnable(obj,vector,dist,direction){
    var d = direction.clone();
    d.multiplyScalar(dist);
    var position = vector.clone();
    position.sub(obj.position,d);
    setSpotParameters(highLight,position.x,position.y,position.z,true,false);
    highLight.target.position.set(obj.position.x,obj.position.y,obj.position.z);
    highLight.intensity = 1;
    $('canvas').css('cursor','pointer');
}
      
function wrapText(context, text, x, y, maxWidth, lineHeight) {
        var cars = text.split("\n");

        for (var ii = 0; ii < cars.length; ii++) {

            var line = "";
            var words = cars[ii].split(" ");

            for (var n = 0; n < words.length; n++) {
                var testLine = line + words[n] + " ";
                var metrics = context.measureText(testLine);
                var testWidth = metrics.width;

                if (testWidth > maxWidth) {
                    context.fillText(line, x, y);
                    line = words[n] + " ";
                    y += lineHeight;
                }
                else {
                    line = testLine;
                }
            }

            context.fillText(line, x, y);
            y += lineHeight;
        }
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
        
        <form id="<?php echo $model_name; ?>" action="visualisation.php" method="post" >
            <input type="text" hidden="<?php echo $model_name; ?>" name="model" value="<?php echo $model_name; ?>"/>
        </form>
        <?php 
        endforeach; ?>
        <img id="html5Img" src="/textures/texture_2.jpg" alt="logo html5" width="160" height="120" hidden onclick="addPanier()" />
        <input id="text" value="From that I was able to think get multi lines working (sorry Ramirez, yours didn't work for me!)\n. My complete code to wrap text in a canvas is as follows:" hidden>
    
    </body>
</html>