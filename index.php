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
        <script src="lib/Puces.js"></script>
        <script>
            if (! Detector.webgl )
                Detector.addGetWebGLMessage();
            
            var container, stats;

            var camera, scene, renderer, controls, sun, sunLight ,sunLight2, pucesList, projector;
            var puceCloned, plane;
            var mirrorCubeCamera,cubeTarget,mirrorCube;            
            var t = 0;
            var clock = new THREE.Clock();
            var mouse = { x: 0, y: 0 }, INTERSECTED;
            
            function init() {
                
                camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 0.1, 2000 );
                camera.position.set( -4, 2 , 0 );

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
                controls.movementSpeed = 3;
                controls.lookSpeed = 0.05;
                controls.lookVertical = true;
                controls.constrainVertical = true;
                controls.verticalMin = 1.1;
                controls.verticalMax = 2.2;

                

                mirrorCubeCamera = new THREE.CubeCamera( 0.1, 100, 128);
                scene.add( mirrorCubeCamera );
                cubeTarget = mirrorCubeCamera.renderTarget;
                var loader = new THREE.ColladaLoader();
                loader.options.convertUpAxis = true;
                pucesList = [];
                
                var modelPath = './models/puce_classic_without_texture.dae';
                var modelTexturePath = './textures/texture_argent_carre.jpg';
                var attacheModelPath = './models/attache_argent.dae';
               pucesList = PUCES.CreatePuce(scene, loader, pucesList , modelPath , modelTexturePath , attacheModelPath,false, false, true );
                
                console.log(pucesList);
                
                PUCES.CreatePuce(scene, loader, pucesList , modelPath , modelTexturePath , attacheModelPath,false, false, true );
                
                PUCES.CreatePuce(scene, loader, pucesList , modelPath , modelTexturePath , attacheModelPath,false, false, true );
                
                PUCES.CreatePuce(scene, loader, pucesList , modelPath , modelTexturePath , attacheModelPath,false, false, true );
                
                PUCES.CreatePuce(scene, loader, pucesList , modelPath , modelTexturePath , attacheModelPath,false, false, true );
                
                PUCES.CreatePuce(scene, loader, pucesList , modelPath , modelTexturePath , attacheModelPath,false, false, true );
                
                PUCES.CreatePuce(scene, loader, pucesList , modelPath , modelTexturePath , attacheModelPath,false, false, true );
                //initPuces(loader,'classic','argent');	
                //      sphereDebug(1,1,1);
              //  initPuces(loader,'classic','argent_carre');	
             //   initPuces(loader,'classic','or_petit_rond');	
                //   initTrees(loader);
                initSun();
       
                scene.fog = new THREE.FogExp2( 0xffffff, 0.001 );
               

                
                // Lights
                var ambianteLight = new THREE.AmbientLight( 0x444444 );
                scene.add(ambianteLight);
                
                //     sphereDebug(0,2,12);             
       
       
                createFloor();
                //         createPlafond();
                
                stats = new Stats();
                stats.domElement.style.position = 'absolute';
                stats.domElement.style.top = '0px';
                container.appendChild( stats.domElement );


                window.addEventListener( 'resize', onWindowResize, false );
                document.addEventListener( 'mousemove', onDocumentMouseMove, false );
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
                
                // find intersections
                var vector = new THREE.Vector3( mouse.x, mouse.y, 1 );
                projector.unprojectVector( vector, camera );
                var raycaster = new THREE.Raycaster( camera.position, vector.subSelf( camera.position ).normalize() );
                var intersects = raycaster.intersectObjects( scene.children );
                if ( intersects.length > 0 ) {
                    if ( INTERSECTED != intersects[ 0 ].object ) {
                        if ( INTERSECTED ) INTERSECTED.material.emissive.setHex( INTERSECTED.currentHex );
                        INTERSECTED = intersects[ 0 ].object;
                        INTERSECTED.currentHex = INTERSECTED.material.emissive.getHex();
                        INTERSECTED.material.emissive.setHex( 0xff0000 );
                    }
                } else {
                    if ( INTERSECTED ) INTERSECTED.material.emissive.setHex( INTERSECTED.currentHex );
                    INTERSECTED = null;
                } 
                
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
                
                sunLight2 = new THREE.SpotLight( 0xffffff,1);
                sunLight2.position.set(0,20,20);
                sunLight2.shadowDarkness = 0.7; 
                sunLight2.shadowMapWidth = 1024;
                sunLight2.shadowMapHeight = 1024;
                sunLight2.shadowCameraNear = 0.1;
                sunLight2.shadowCameraFar = 100;
                sunLight2.shadowCameraFov = 70; 
                sunLight2.castShadow = true; 
                scene.add(sunLight2); 
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
                var cubeGeom = new THREE.CubeGeometry(100, 100, 100, 1, 1, 1);
                var mirrorCubeMaterial = new THREE.MeshPhongMaterial( {color: 0xFFFFFF});//, envMap: mirrorCubeCamera.renderTarget } );
                mirrorCube = new THREE.Mesh( cubeGeom, mirrorCubeMaterial );
                mirrorCube.position.set(0,-50,0);
                mirrorCube.receiveShadow = true;
                scene.add(mirrorCube); 
            }
            
            function createPlafond(){
                var planeGeo = new THREE.CubeGeometry(-100,0,0,100,100,100);// 10,10);
                var planeMat = new THREE.MeshPhongMaterial({color: 0xFF0000});
                plane = new THREE.Mesh(planeGeo, planeMat);
                // plane.rotation.z = Math.PI/2;
                plane.position.y = 5;
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
        <a id="visualisation"></a>
    </body>
</html>
