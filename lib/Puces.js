/**
 * @author mathurin Petit
 */
var PUCES = {};

var modelList = new Array();

PUCES.CreatePuce = function(scene, loader, pucesList , modelPath , modelTexturePath , attacheModelPath, shadow , reflexivity, pickable ) {
    this.loader = loader;
    this.modelPath = modelPath; 
    this.modelTexturePath = modelTexturePath; 
    this.attacheModelPath = attacheModelPath; 
    this.shadow = shadow; 
    this.reflexivity = reflexivity; 
    this.pickable = pickable;
    this.vitesseRotation = 0.01; 
    this.id = 'boucle';
    
    
    //    loader.load( './models/attache_or.dae', function ( collada ) {
    //            var attache = collada.scene;
    //            //attache.scale = 0;
    //            attache.position.x = attache.position.y = attache.position.z = 100;
    //            var attache_material = new THREE.Material( { color:0xff0000,ambient: 0xffffff
    //            });
    //            attache.material = attache_material;
    //            scene.add( attache );
    //        });
    
    console.log(modelList[modelPath]);
    if(modelList[modelPath] == undefined){ 

        this.loader.load(modelPath, function ( collada ) {
            var puce = collada.scene;
            puce.scale.x = puce.scale.y = puce.scale.z = 0.02;
            modelList[modelPath] = puce;
            createPuce(puce, modelTexturePath);     
        });
    }
    else{
        createPuce(modelList[modelPath], modelTexturePath);
    }
    function createPuce(puce,texture){                
        var puceCloned = puce.clone();
        puceCloned.position.y = 2;
        puceCloned.position.x = 0;
        puceCloned.position.z = 0;
        puceCloned.rotation.y = Math.random() * Math.PI * 2;
        if(this.shadow){
            puceCloned.traverse(function ( child ) {
                child.castShadow = true;
            } );
        }
        var texture = THREE.ImageUtils.loadTexture( modelTexturePath );
        
        if(this.reflexivity){
            var cubeReflexionMaterial = new THREE.MeshLambertMaterial( {
                color: 0xffffff,
                ambient: 0xffffff,
                map: texture,
                combine: THREE.MixOperation,
                reflectivity: 0.5,
                envMap: cubeTarget
            } );
            puceCloned.children[0].children[0].children[4].children[10].material = cubeReflexionMaterial;
            mirrorCubeCamera.position.copy( puceCloned.position );
            mirrorCubeCamera.renderTarget.minFilter = THREE.LinearMipMapLinearFilter; 
        }
        else{
            var faceMaterial = new THREE.MeshLambertMaterial( {
                ambient: 0x444444,
                specular: 0x999999,
                map: texture
            } );
            puceCloned.children[0].children[0].children[4].children[10].material = faceMaterial;
        }        
        scene.add( puceCloned );
        pucesList[this.id] = puceCloned;
        if(pickable){
            var geometryPuce = new THREE.CubeGeometry(1, 1, 1);
            var material = new THREE.MeshLambertMaterial({
                transparent: true
            });
                
            var mesh = new THREE.Mesh(geometryPuce, material);
            material.opacity = 0;
            mesh.position = puceCloned.position;
            mesh.id = this.id;
            scene.add(mesh);
        }
    }
    return pucesList;
    
}