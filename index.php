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

            var camera, scene, renderer, objects, controls, sun, sunLight;
            var particleLight, pointLight;
            var dae, skin;


            function init() {

                container = document.createElement( 'div' );
                document.body.appendChild( container );
                scene = new THREE.Scene();

                camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 2000 );
                camera.position.set( -25, 3, -15 );
                controls = new THREE.FirstPersonControls( camera );
                controls.movementSpeed = 3;
                controls.lookSpeed = 0.05;
                controls.lookVertical = true;
                controls.constrainVertical = false;
                controls.verticalMin = 1.1;
                controls.verticalMax = 2.2;


				
                

                // scene.fog = new THREE.FogExp2( 0xffffff, 0.05 );
                createFloor();
             
                // Lights
                var ambianteLight = new THREE.AmbientLight( 0x111111 );
                scene.add(ambianteLight);
                
                initSun();
                sphereDebug(0,2,0);
                sphereDebug(0,0,2);
                var loader = new THREE.ColladaLoader();
                loader.options.convertUpAxis = true;
                initTrees(loader);
				
                

                stats = new Stats();
                stats.domElement.style.position = 'absolute';
                stats.domElement.style.top = '0px';
                container.appendChild( stats.domElement );


                window.addEventListener( 'resize', onWindowResize, false );

                renderer = new THREE.WebGLRenderer({antialias: true});            
                renderer.setSize( window.innerWidth, window.innerHeight );
                
                renderer.shadowMapEnabled = true; 
                
                container.appendChild( renderer.domElement );
            }

            function onWindowResize() {

                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();

                renderer.setSize( window.innerWidth, window.innerHeight );

            }

            var t = 0;
            var clock = new THREE.Clock();

            function animate() {
                
                updateTrees();
                
                requestAnimationFrame( animate );
                render();
                stats.update();

            }

            function render() {

                var timer = Date.now() * 0.0005;
                controls.update( clock.getDelta() );
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

                // create a new mesh with
                // sphere geometry - we will cover
                // the sphereMaterial next!
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
                var radius = 1,
                segments = 16,
                rings = 16;    
                var sphereMaterial =
                    new THREE.MeshPhongMaterial(
                {
                    color: 0xFFB90F
                });
                var sun = new THREE.Mesh(

                new THREE.SphereGeometry(
                radius,
                segments,
                rings),

                sphereMaterial);

                sun.position.x = 0;
                sun.position.y = 20;
                sun.position.z = -40;
                scene.add(sun);
                
                var sunLight = new THREE.SpotLight( 0xffffff, 4 );
                sunLight.position.x = 0;
                sunLight.position.y = 20;
                sunLight.position.z = -40;
                sunLight.castShadow = true;
                sunLight.shadowCameraVisible = true; 
                scene.add( sunLight );              

            }
            
            function initTrees(loader){
                loader.load( './models/tree.dae', function ( collada ) {
                    var tree = collada.scene;
                    tree.scale.x = tree.scale.y = tree.scale.z = 0.002;
                    var made = false;
                    for(var i=-15; i<15;i+=10){
                        for(var j=-15; j<15;j+=10){
                            if(!made){
                                var x = i + Math.random()*10;
                                var z = j + Math.random()*10;
                                createTree(tree,x,z);
                            }
                            made=!made;
                        }
                    }
                });
            }
            
            function createTree(tree,x,z){
                var treeCloned = tree.clone();	 
                treeCloned.position.x = x;
                treeCloned.position.z = z;
                treeCloned.rotation.y = Math.random() * Math.PI * 2;
                daemesh = treeCloned.children[1];
                daemesh.castShadow = true;
                daemesh.receiveShadow = true;
                scene.add( treeCloned );
                    
            }
            
            function createFloor(){
                var planeGeo = new THREE.PlaneGeometry(50,50, 10, 10);
                var planeMat = new THREE.MeshPhongMaterial({color: 0x777777});
                var plane = new THREE.Mesh(planeGeo, planeMat);
                plane.rotation.x = -Math.PI/2;
                plane.receiveShadow = true;
                plane.castShadow = true;
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
