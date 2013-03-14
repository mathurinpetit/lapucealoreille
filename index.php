<?php
include_once 'db/DB.php';

$db = new DB();
$dae_models = $db->getAllDaeModels();
$models = $db->getAllModels();
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
        <script src="lib/microcache.js"></script>
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
            var panelCanvas;
            
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
     $(audio).attr('loop','');
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
    renderer._microCache = new MicroCache();    
                
                
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
    false, false, true ,0.05);
               
          <?php  endforeach; ?>                       
                
    //   initTrees(loader);
    initSpot(0,20,-20,true,false);                                
    initSpot(0,20,20,true,false);
       
    highLight = new THREE.SpotLight( 0xffffff,0);
    scene.add(highLight);
                               
    scene.fog = new THREE.FogExp2( 0xffffff, 0.04 );

                
    // Lights
    var ambianteLight = new THREE.AmbientLight( 0x333333 );
    scene.add(ambianteLight);    
       
    createFloor();
    
    if(debug){            
        stats = new Stats();
        stats.domElement.style.position = 'absolute';
        stats.domElement.style.top = '0px';
        container.appendChild( stats.domElement );
    }
    
    panier = new Panier(this);
    panier.domElement.style.position = 'absolute';
    panier.domElement.style.top = '30px';
    panier.domElement.style.right = '30px';
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
    
    console.log(event.src);
    return;
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


function onCanvasMouseMove(event){
    var x, y;
  if (event.layerX || event.layerX == 0) { 
    x = event.layerX;
    y = event.layerY;
  }
  x-=panelCanvas.offsetLeft;
  y-=panelCanvas.offsetTop;

  //is the mouse over the link?
  if(x>=linkX && x <= (linkX + linkWidth) && y<=linkY && y>= (linkY-linkHeight)){
      document.body.style.cursor = "pointer";
      inLink=true;
  }
  else{
      document.body.style.cursor = "";
      inLink=false;
  }
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
    else
    {
        logo.rotation.y -= 0.05;
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
    highLightDisable(id);
    var vector = camera._vector.clone();
    var direction = vector.subSelf( camera.position ).normalize();
    
    selectedModel_0 = id+'_panel_0';
    selectedModel_1 = id+'_panel_1';
    
    var v = createTransparentPanel(direction,id);
    
    var x0 = camera.position.x + (v.x)*8;
    var z0 = camera.position.z + (v.z)*8;
    
    var vectX = 2.2*v.z;
    var vectZ = -2.2*v.x;
    
    pucePool.copyPuceForPanel(id, selectedModel_0);
    pucePool.setPosition(selectedModel_0, x0+vectX, 0.2, z0+vectZ);  
    
    
    pucePool.copyPuceForPanel(id, selectedModel_1);
    pucePool.setPosition(selectedModel_1, x0 + 1.6 * vectX, 0.2, z0 + 1.6 * vectZ);
    
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
    
    panelCanvas = document.createElement("canvas");
    var xc = panelCanvas.getContext("2d");
    panelCanvas.width = panelCanvas.height = 1024;
    var caracteristique = document.getElementById(modelType+"_caracteristique").value;
    var description = document.getElementById(modelType+"_description").value;
    
    //xc.shadowBlur = 1;
    xc.fillStyle = "black";
    xc.font = "14pt arial bold";
    xc.fillText("LA PUCE A L'OREILLE - Modèle Classic Or Rond Small", 208, 215);
    xc.font = "10pt arial bold";
    
    wrapText(xc, caracteristique, 440, 270, 400, 25);
    xc.font = "8pt arial bold";
    wrapText(xc, description, 440, 340, 400, 20);
        
    var model_img_0 = document.getElementById(modelType+'_img_0');
    var model_img_1 = document.getElementById(modelType+'_img_1');
    var model_img_2 = document.getElementById(modelType+'_img_2');
    var model_img_3 = document.getElementById(modelType+'_img_3');
    xc.drawImage(document.getElementById('contour'), 190, 150,670,880);
    
    xc.drawImage(model_img_0, 208, 510, 300, 225);
    xc.drawImage(model_img_1, 530, 510, 300, 225);
    xc.drawImage(model_img_2, 208, 770, 300, 225);
    xc.drawImage(model_img_3, 530, 770, 300, 225);
    
        
    var panelTextGeo = new THREE.PlaneGeometry(15, 15);
    
    
    var buttonGeo = new THREE.PlaneGeometry(1.90, 0.3);    
    var buttonMat = new THREE.MeshPhongMaterial( {color: 0xff0000});
    
    
   
//    var panelOverlayGeo_1 = new THREE.PlaneGeometry(0.5, 3.5);
//    var panelOverlayGeo_2 = new THREE.PlaneGeometry(6.5, 3.5);
//    var panelOverlayGeo_3 = new THREE.PlaneGeometry(10, 10.25);
    var texture = new THREE.Texture(panelCanvas);
    var xm = new THREE.MeshLambertMaterial({ map: texture, transparent:true });
    xm.doubleSided = true; 
    xm.map.needsUpdate = true;
    
    panelText = new THREE.Mesh(panelTextGeo, xm);
    
//    var panelOverlay_0 = new THREE.Mesh(panelOverlayGeo_0,panelOverlayMat);
//    var panelOverlay_1 = new THREE.Mesh(panelOverlayGeo_1,panelOverlayMat);
//    var panelOverlay_2 = new THREE.Mesh(panelOverlayGeo_2,panelOverlayMat);
//    var panelOverlay_3 = new THREE.Mesh(panelOverlayGeo_3,panelOverlayMat);
    buttonPanier = new THREE.Mesh(buttonGeo, buttonMat);
    
    var v = panelText.position.clone();
    v.addSelf( direction );
    v.y = 0;
    var vI = v.clone().multiplyScalar(-1);
    panelText.lookAt( vI ); 
    logo.lookAt(vI);
//    panelOverlay_0.lookAt( vI ); 
//    panelOverlay_1.lookAt( vI ); 
//    panelOverlay_2.lookAt( vI );
//    panelOverlay_3.lookAt( vI );
    buttonPanier.lookAt(vI);
    panelText.doubleSided = buttonPanier.doubleSided = true; //panelOverlay_0.doubleSided = panelOverlay_1.doubleSided = panelOverlay_2.doubleSided = panelOverlay_3.doubleSided = true; 
    
   
    var baseX = camera.position.x + direction.x * 8;
    var baseZ = camera.position.z + direction.z * 8;
    
    panelText.position.x = buttonPanier.position.x = baseX ; //= panelOverlay_0.position.x = panelOverlay_1.position.x = panelOverlay_2.position.x = panelOverlay_3.position.x = baseX ;
    panelText.position.y = buttonPanier.position.y = -1.2; //= panelOverlay_0.position.y = panelOverlay_1.position.y = panelOverlay_2.position.y = panelOverlay_3.position.y = -1.2;
    panelText.position.z = buttonPanier.position.z =baseZ ; // panelOverlay_0.position.z = panelOverlay_1.position.z = panelOverlay_2.position.z = panelOverlay_3.position.z = baseZ ;
    
    panelText.position.x -= direction.x * 0.1;
    panelText.position.z -= direction.z * 0.1;
    
    

//    panelOverlay_0.position.y += 4.5;
//    panelOverlay_1.position.y += 2;
//    panelOverlay_2.position.y += 2;
//    panelOverlay_1.position.z += - 4.75*v.x;
//    panelOverlay_1.position.x += 4.75*v.z;
//    panelOverlay_2.position.z += 1.75*v.x;
//    panelOverlay_2.position.x += -1.75*v.z;
//    panelOverlay_3.position.y -= 4.875;
    buttonPanier.position.y += 3;
    buttonPanier.position.x += -3.85*v.z - direction.x * 0.2;
    buttonPanier.position.z += 3.85*v.x - direction.z * 0.2;
    
    buttonPanier.id = 'ajout_panier';
    
    scene.add(panelText);
    
    scene.add(buttonPanier);
    
//    scene.add(panelOverlay_0);
//    panelOverlay.push(panelOverlay_0);
//    
//    scene.add(panelOverlay_1);
//    panelOverlay.push(panelOverlay_1);
//    
//    scene.add(panelOverlay_2);
//    panelOverlay.push(panelOverlay_2);
//    
//    scene.add(panelOverlay_3);
//    panelOverlay.push(panelOverlay_3);
    
    var logoPosBase = new THREE.Vector3(baseX, -1.2+4.5, baseZ);
    logo.position.z = logoPosBase.z + 3.4*v.x + direction.z * -1.5;
    logo.position.x = logoPosBase.x - 3.4*v.z + direction.x * -1.5;
    logo.position.y = logoPosBase.y - 0.6;  
    //logo.rotation.y = Math.PI;
    logo.scale.x = logo.scale.y = logo.scale.z = 0.05;
    scene.add( logo );
    
    panelCanvas.addEventListener("mousemove", onCanvasMouseMove, false);
    document.addEventListener('DOMMouseScroll', onDocumentMouseWheel, false);
    document.addEventListener( 'click', onDocumentMousePanelClick, false );
    highLightEnable(panelText, direction, 10, v); 
    highLight.intensity = 1.5;
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
                console.log(INTERSECTED.id);
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
        var cars = text.split("\\");
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
     
     function clickPanier(){
        $('form#payer_or').submit();
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
         <img id="contour" src="./assets/contour.png" width="1024" height="1024" hidden />
        <?php foreach ($models as $key => $model) : ?>
            <?php if($model['image_0']) : ?>
                <img id="<?php echo $key;?>_img_0"
                    src="./models/images/<?php echo $model['image_0'];?>"
                    alt="<?php echo $key;?>"
                    width="160" height="120"
                    hidden />
            <?php endif; ?>
            <?php if($model['image_1']) : ?>
                <img id="<?php echo $key;?>_img_1"
                 src="./models/images/<?php echo $model['image_1'];?>"
                 alt="<?php echo $key;?>"
                 width="160" height="120"
                 hidden />
            <?php endif; ?>
            <?php if($model['image_2']) : ?>  
             <img id="<?php echo $key;?>_img_2"
                 src="./models/images/<?php echo $model['image_2'];?>"
                 alt="<?php echo $key;?>"
                 width="160" height="120"
                 hidden />
            <?php endif; ?>
            <?php if($model['image_3']) : ?>  
             <img id="<?php echo $key;?>_img_3"
                 src="./models/images/<?php echo $model['image_3'];?>"
                 alt="<?php echo $key;?>"
                 width="160" height="120"
                 hidden />
             <?php endif; ?>
             <?php if($model['caracteristiques']) : ?>  
                <input id="<?php echo $key;?>_caracteristique"
                    value="<?php echo $model['caracteristiques'];?>" hidden >
            <?php endif; ?>
                
             <?php if($model['description']) : ?>  
                <input id="<?php echo $key;?>_description"
                    value="<?php echo $model['description'];?>" hidden >
            <?php endif; ?>
        <?php endforeach; ?>
        
       <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" hidden id="payer_or">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="KTNJ55KFEU7QW">
<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>



        
    </body>
</html>