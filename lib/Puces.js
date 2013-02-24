/**
 * @author mathurin Petit
 */

var PUCES = function(scene){ 
    
    this.scene  = scene;    
    this.objects = [];
  
    this.createPuce = function(id, model, attacheModel, modelTexture, shadow, reflexivity, pickable ) { 
        this.objects[id] = {};
        this.objects[id].id = id; 
        this.objects[id].model = model; 
        this.objects[id].modelTexture = modelTexture; 
        this.objects[id].attacheModel = attacheModel; 
        this.objects[id].shadow = shadow; 
        this.objects[id].reflexivity = reflexivity; 
        this.objects[id].pickable = pickable;
        this.objects[id].vitesseRotation = 0.01; 
        this.objects[id].mirrorCamera; 
        
        var cubeTarget;
        
        var modelCloned = model.clone();
        modelCloned.position.y = 2;
        modelCloned.position.x = Math.random() *  5;
        modelCloned.position.z = 0;
        modelCloned.rotation.y = Math.random() * Math.PI * 2;
        
        if(this.objects[id].shadow){
            modelCloned.traverse(function ( child ) {
                child.castShadow = true;
            } );
        }
        
        var texture = THREE.ImageUtils.loadTexture( './textures/'+modelTexture+'.jpg' );
        
        if(this.objects[id].reflexivity){
                this.objects[id].mirrorCamera = new THREE.CubeCamera( 0.1, 100, 128);
                this.scene.add( this.objects[id].mirrorCamera );
                cubeTarget = this.objects[id].mirrorCamera.renderTarget;
            
            var cubeReflexionMaterial = new THREE.MeshLambertMaterial( {
                color: 0xffffff,
                ambient: 0xffffff,
                map: texture,
                combine: THREE.MixOperation,
                reflectivity: 0.5,
                envMap: cubeTarget
            } );
            modelCloned.children[0].children[0].children[4].children[10].material = cubeReflexionMaterial;
            this.objects[id].mirrorCamera.position.copy( modelCloned.position );
            this.objects[id].mirrorCamera.renderTarget.minFilter = THREE.LinearMipMapLinearFilter; 
        }
        else{
            var faceMaterial = new THREE.MeshLambertMaterial( {
                ambient: 0x444444,
                specular: 0x999999,
                map: texture
            } );
            modelCloned.children[0].children[0].children[4].children[10].material = faceMaterial;
        }        
        this.scene.add( modelCloned );
        
        
        if(pickable){
            var geometryPuce = new THREE.CubeGeometry(1, 1, 1);
            var material = new THREE.MeshLambertMaterial({
                transparent: true
            });
                
            var mesh = new THREE.Mesh(geometryPuce, material);
            material.opacity = 0;
            mesh.position = modelCloned.position;
            mesh.id = this.id;
            scene.add(mesh);
        }
        
    }
    
    this.update = function(renderer){
            for(var obj3d in this.objects){
                if(this.objects[obj3d].reflexivity){
                    this.objects[obj3d].mirrorCamera.updateCubeMap( renderer, scene );
                }
        }
    }
}  
