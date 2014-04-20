<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>La Puce à l'oreille - Construction</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <link href="css/main.css" rel="stylesheet" type="text/css">
        <script src="lib/jquery-1.8.3.js"></script>        
        <script src="lib/three.js/three.js"></script>
        <script src="lib/three.js/gentilis_bold.typeface.js"></script>
        <script src="lib/three.js/ColladaLoader.js"></script>
        <script src="lib/three.js/Detector.js"></script>
        <script src="lib/three.js/Stats.js"></script>
        <script src="lib/microcache.js"></script>
    </head>
    <body> 
        <script>
            if (!Detector.webgl) {
                // Detector.addGetWebGLMessage();
                window.location.assign("http://lapucealoreille.dev/light");
            }
            var debug = false;
            var container, stats;

            var camera, scene, logo, renderer, projector;
            var highLight;

            var panelOverlay = [];
            var panelText;
            var t = 0;
            var clock = new THREE.Clock();
            var step = 0;
            var mouse = {x: 0, y: 0};
            var panelCanvas;

            function init() {
                camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 10000);
                camera.position.set(-15, 2, 0);
                camera.lookAt(new THREE.Vector3(0, 0, 0));

                renderer = new THREE.WebGLRenderer({antialias: true});
                renderer.setSize(window.innerWidth, window.innerHeight);
                renderer.shadowMapEnabled = false;
                renderer.shadowMapSoft = false;

                renderer.shadowCameraNear = 0.5;
                renderer.shadowCameraFar = camera.far;
                renderer.shadowCameraFov = 50;

                renderer.shadowMapBias = 0.0039;
                renderer.shadowMapDarkness = 0.5;
                renderer.shadowMapWidth = 512;
                renderer.shadowMapHeight = 512;
                renderer._microCache = new MicroCache();


                container = document.createElement('div');
                document.body.appendChild(container);

                scene = new THREE.Scene();
                projector = new THREE.Projector();

                initSpot(-70, 2, -3, true, false);
                initLogoAndText();
                highLight = new THREE.SpotLight(0xffffff, 0);
                scene.add(highLight);



                // Lights
                var ambianteLight = new THREE.AmbientLight(0x111111);
                scene.add(ambianteLight);

                if (debug) {
                    stats = new Stats();
                    stats.domElement.style.position = 'absolute';
                    stats.domElement.style.top = '0px';
                    container.appendChild(stats.domElement);
                }

                window.addEventListener('resize', onWindowResize, false);
                container.appendChild(renderer.domElement);
            }

            var loader_for_logo = new THREE.ColladaLoader();
            loader_for_logo.options.convertUpAxis = true;
            var logo = null;
            var textMesh = null;
            var point1 = null;
            var point2 = null;
            var point3 = null;
            var logoFunc = function() {
                loader_for_logo.load("models/logo.dae", function colladaReady(collada)
                {
                    model = collada.scene;
                    model.scale.x = model.scale.y = model.scale.z = 0.2;
                    model.updateMatrix();
                    logo = model;
                    init();
                    animate();
                }, function() {
                });
            };

            function onWindowResize() {

                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();

                renderer.setSize(window.innerWidth, window.innerHeight);

            }

            function initLogoAndText() {
                var vector = new THREE.Vector3(0, 0, 0);
                var direction = vector.subSelf(camera.position).normalize();
                var v = direction.clone();
                v.y = 0;
                var vI = v.clone().multiplyScalar(-1);
                logo.lookAt(vI);

                var logoPosBase = new THREE.Vector3(0, 0, 0);
                logo.position.z = logoPosBase.z + direction.z * -1.5 - 7.7;
                logo.position.x = logoPosBase.x + direction.x * -1.5;
                logo.position.y = logoPosBase.y + 4;

                logo.scale.x = logo.scale.y = logo.scale.z = 0.08;
                scene.add(logo);
                
                createTransparentPanel();
            }

            function createTransparentPanel() {

                var v = new THREE.Vector3(0, 0, 0);
                panelCanvas = document.createElement("canvas");
                var xc = panelCanvas.getContext("2d");
                panelCanvas.width = panelCanvas.height = 1048;
                xc.globalAlpha = 1;
                xc.shadowBlur = 1;
                xc.fillStyle = "black";
                xc.font = "15pt arial bold";
                xc.fillText("LA PUSSE A L'OREILLE ", 10, 410);
                xc.font = "12pt arial bold";
                xc.mozImageSmoothingEnabled = false;
                wrapText(xc, "Mathurin Petit\\226, rue de tolbiac\\75013 Paris\\06 45 18 10 87\\despucesauxoreilles@gmail.com\\mathurin.petit@gmail.com", 10, 470, 200, 20);

                xc.font = "10pt arial bold";
                wrapText(xc, "La Pusse à l'Oreille est un site de vente de boucle d'Oreille tendance et branchées.\\Ces boucles d'oreilles artisanales et fabriquées en France sont un cadeau idéale à s'offrir\\ou à offrir au quotidien à une tierce personne.\\\\Achetez en! Oui plein!", 340, 380, 10000, 20);

                var panelTextGeo = new THREE.PlaneGeometry(22, 22);
                var texture = new THREE.Texture(panelCanvas);
                var xm = new THREE.MeshPhongMaterial({map: texture, transparent: true});
                xm.doubleSided = true;
                xm.map.needsUpdate = true;

                panelText = new THREE.Mesh(panelTextGeo, xm);
                panelText.doubleSided = true;
                
                panelText.position.x = 0;
                panelText.position.z = 0;
                panelText.rotation.y = -Math.PI / 2;
                
                scene.add(panelText);
            }




            function animate() {

                //  updateTrees();

                requestAnimationFrame(animate);
                render();
                if (debug)
                    stats.update();
            }


            function render() {

                var timer = Date.now() * 0.05;
                step += clock.getDelta() / 8;
                logo.rotation.y -= 0.05;

                renderer.clear();
                renderer.render(scene, camera);
            }

            function initSpot(x, y, z, shadow, debug) {
                var spot = new THREE.SpotLight(0xffffff, 0.6);
                setSpotParameters(spot, x, y, z, shadow, debug);
                scene.add(spot);
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


            function setSpotParameters(spot, x, y, z, shadow, debug) {
                spot.position.set(x, y, z);
                if (shadow) {
                    spot.shadowDarkness = 0.6;
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
                logoFunc();
            });
        </script>


    </body>
</html>
