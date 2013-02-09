<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>La Puce à l'oreille</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    </head>
    <body>
        <script type="text/css" src="css/main.css"></script>
        <script src="lib/jquery-1.8.3.js"></script>
        <script src="lib/three.js/three.js"></script>
        <script src="lib/three.js/FirstPersonControls.js"></script>
        <script src="lib/three.js/ColladaLoader.js"></script>

        <script src="lib/three.js/Detector.js"></script>
        <script src="lib/three.js/Stats.js"></script>
        <script>
            if ( ! Detector.webgl ) Detector.addGetWebGLMessage();
            
            var container, stats;

            var camera, scene, renderer, objects, controls, sun, sunLight,sunLight2,puce;
            var particleLight, pointLight;
            var dae, skin;
            var puceCloned, plane;
            var mirrorCubeCamera,cubeTarget,mirrorCube;            
            var t = 0;
            var clock = new THREE.Clock();
            
            function init() {
                
                camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 0.1, 2000 );
                camera.position.set( 0, 2 , 0 );

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

                
                controls = new THREE.FirstPersonControls( camera );
                controls.movementSpeed = 3;
                controls.lookSpeed = 0.05;
                controls.lookVertical = false;
                controls.constrainVertical = false;
                controls.verticalMin = 1.1;
                controls.verticalMax = 2.2;

                
              //  mirrorCubeCamera = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, 1, 100 );
                
                mirrorCubeCamera = new THREE.CubeCamera( 0.1, 100000, 128   );
                scene.add( mirrorCubeCamera );
                cubeTarget = mirrorCubeCamera.renderTarget;
                var loader = new THREE.ColladaLoader();
                initPuces(loader);	
                loader.options.convertUpAxis = true;
             //   initTrees(loader);
                initSun();
       
                 scene.fog = new THREE.FogExp2( 0xffffff, 0.001 );
               

                
                // Lights
                var ambianteLight = new THREE.AmbientLight( 0x444444 );
                scene.add(ambianteLight);
                
           //     sphereDebug(0,4,10);
           //     sphereDebug(0,2,12);             
       
       
                createFloor();
        //        createPlafond();
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
                
                //  updateTrees();
                
                requestAnimationFrame( animate );
                render();
                stats.update();
            }

            function render() {

                var timer = Date.now() * 0.0005;
                mirrorCube.visible = false;
                mirrorCubeCamera.updateCubeMap( renderer, scene );
                mirrorCube.visible = true; 
                controls.update( clock.getDelta() );
                if(puceCloned)
                    puceCloned.rotation.y += 0.01;
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
            
            function initSun(){
                
                sunLight = new THREE.SpotLight( 0xffffff,1);
                sunLight.position.set(0,20,-20);
                
                sunLight.shadowDarkness = 0.7; 
                sunLight.shadowMapWidth = 1024;
                sunLight.shadowMapHeight = 1024;
                
                sunLight.shadowCameraNear = 0.1;
                sunLight.shadowCameraFar = 100;
                sunLight.shadowCameraFov = 70;
           //     sunLight.shadowCameraVisible = true;   
                sunLight.castShadow = true; 
                scene.add(sunLight); 
                
//                sunLight2 = new THREE.SpotLight( 0xffffff,1);
//                sunLight2.position.set(0,20,20);
//                
//                sunLight2.shadowDarkness = 0.7; 
//                sunLight2.shadowMapWidth = 1024;
//                sunLight2.shadowMapHeight = 1024;
//                
//                sunLight2.shadowCameraNear = 0.1;
//                sunLight2.shadowCameraFar = 100;
//                sunLight2.shadowCameraFov = 70;
//           //     sunLight.shadowCameraVisible = true;   
//                sunLight2.castShadow = true; 
//                scene.add(sunLight2); 
            }
            var xPos = 0;
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
            
            function initPuces(loader){
                loader.load( './models/puce_or_ronde_with_attache.dae', function ( collada ) {
                    puce = collada.scene;
                    puce.scale.x = puce.scale.y = puce.scale.z = 0.02;
                    var made = false;
                     for(var i=0; i<2;i++){
                    //  for(var j=-15; j<15;j+=10){
                  //  if(!made){
                        var x = xPos;
                        var z = 0 + Math.random()*10;
                        createPuce(puce,x,0);
                        xPos++;
                   // }
                   // made=!made;
                    //        }
                       }
                });
            }
            
            function createPuce(puce,x,z){                
                puceCloned = puce.clone();
                puceCloned.position.y = 2;
                puceCloned.position.x = x;
                puceCloned.position.z = z;
                puceCloned.rotation.y = Math.random() * Math.PI * 2;
                puceCloned.traverse(function ( child ) {
              //  child.castShadow = true;
                } );
             //   puceCloned.children[0].children[6].material = new THREE.MeshBasicMaterial( { color : 0xFFFFFF});// envMap: mirrorCubeCamera.renderTarget } );
                 
                 var texture = THREE.ImageUtils.loadTexture( './models/puce_or_ronde/texture1.png' );
                 var cubeMaterial1 = new THREE.MeshLambertMaterial( {
                    color: 0xffffff,
                    ambient: 0xffffff,
                    map: texture,
                    combine: THREE.MixOperation,
                    reflectivity: 0.5,
                    envMap: cubeTarget
                    } );
                 puceCloned.children[0].children[0].children[6].material = cubeMaterial1;
                 mirrorCubeCamera.position.copy( puceCloned.position );
              //  mirrorCubeCamera.renderTarget.minFilter = THREE.LinearMipMapLinearFilter; 
                
                scene.add( puceCloned );
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
                var cubeGeom = new THREE.CubeGeometry(100, 100, 100, 1, 1, 1);
                var mirrorCubeMaterial = new THREE.MeshPhongMaterial( {color: 0xFFFFFF});//, envMap: mirrorCubeCamera.renderTarget } );
                mirrorCube = new THREE.Mesh( cubeGeom, mirrorCubeMaterial );
                mirrorCube.position.set(0,-50,0);
                mirrorCube.receiveShadow = true;
                scene.add(mirrorCube); 
            }
            
            function createPlafond(){
                var planeGeo = new THREE.PlaneGeometry(500,500, 10, 10);
                var planeMat = new THREE.MeshPhongMaterial({color: 0x000000});
                plane = new THREE.Mesh(planeGeo, planeMat);
                plane.rotation.x = Math.PI/2;
                plane.position.y = 200;
                scene.add(plane);
            }
            
            function updateTrees(){
                //reperer la zone de camera et update le arbres
            }
            
            $(document).ready(function() {
                init();
                animate();
            });    
        </script>
    </body>
</html>
