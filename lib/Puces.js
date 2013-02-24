/**
 * @author mathurin Petit
 */

var PUCES = function(scene){ 
    
    this.scene  = scene;    
    this.objects = [];
  
    this.createPuce = function(id, model, attacheModel, modelTexture, shadow, reflexivity, pickable, vitesseRotation) { 
        this.objects[id] = {};
        this.objects[id].id = id; 
        this.objects[id].model = model; 
        this.objects[id].modelTexture = modelTexture; 
        this.objects[id].attacheModel = attacheModel; 
        this.objects[id].shadow = shadow; 
        this.objects[id].reflexivity = reflexivity; 
        this.objects[id].pickable = pickable;
        this.objects[id].vitesseRotation = vitesseRotation; 
        this.objects[id].mirrorCamera; 
        this.objects[id].wrap = null;
        
        var cubeTarget;
        
        var modelCloned = model.clone();
        modelCloned.position.y = 2;
        modelCloned.position.x = Math.random() *  5;
        modelCloned.position.z = Math.random() *  5;
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
        
        if(this.objects[id].pickable){
            
            var geometryPuce = new THREE.CubeGeometry(1, 1, 0.2);
            var material = new THREE.MeshLambertMaterial({
                
                transparent: true
            });
         
            var mesh = new THREE.Mesh(geometryPuce, material);
            material.opacity = 0;
            mesh.position = modelCloned.position;
            mesh.rotation = modelCloned.rotation;
            mesh.id = id;
            this.objects[id].wrap = mesh;
            this.scene.add(mesh);
        }
        
        this.scene.add( modelCloned );
        this.objects[id].puce = modelCloned;
        
        
    }
    
    this.update = function(renderer){
            for(var obj3d in this.objects){
                if(this.objects[obj3d].reflexivity){
                    this.objects[obj3d].mirrorCamera.updateCubeMap( renderer, scene );
                }
            this.objects[obj3d].puce.rotation.y += this.objects[obj3d].vitesseRotation;
            this.objects[obj3d].wrap.rotation.y += this.objects[obj3d].vitesseRotation;
        }
    }
        
     this.setVitesseRotation = function(id, v){
        this.objects[id].vitesseRotation = v;
     }
}  
