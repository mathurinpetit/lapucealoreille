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
        this.objects[id].vitesseTranslation = 0.05;
        this.objects[id].wrapPuce = null;
        this.objects[id].wrapAttache = null;
        this.objects[id].direction = new THREE.Vector3( 1, 0, 1).normalize();
        this.objects[id].lengthMovement = 10;
        this.objects[id].hasDirection = true;
        
        var cubeTarget;
        
        var modelCloned = model.clone();
        var attacheCloned = attacheModel.clone();
        
        this.objects[id].lastPosition = modelCloned.position.clone();
        
        attacheCloned.position.y += 2.54;
        modelCloned.position.y += 2;
        if(this.objects[id].shadow){
            modelCloned.traverse(function ( child ) {
                child.castShadow = true;
            } );
            
            attacheCloned.traverse(function ( child ) {
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
                transparent: true,
                opacity: 0
            });
         
            var meshPuce = new THREE.Mesh(geometryPuce, material);
            meshPuce.position = modelCloned.position;
            meshPuce.rotation = modelCloned.rotation;
            meshPuce.id = id;
            this.objects[id].wrapPuce = meshPuce;
            this.scene.add(meshPuce);
            
            var geometryAttache = new THREE.CubeGeometry(0.2, 1.5, 0.9);         
            var meshAttache = new THREE.Mesh(geometryAttache, material);
            meshAttache.position = attacheCloned.position.clone();
            meshAttache.position.y +=0.75;
            meshAttache.id = id;
            this.objects[id].wrapAttache = meshAttache;
            this.scene.add(meshAttache);
        }
        
        this.scene.add( modelCloned );
        this.scene.add( attacheCloned );
        this.objects[id].puce = modelCloned;
        this.objects[id].attache = attacheCloned;
    }
    
    this.moove = function(obj3d){
        var lastPosition = this.objects[obj3d].lastPosition.clone();
        var lengthMovement = this.objects[obj3d].lengthMovement;
        if(this.objects[obj3d].hasDirection){
            var v = this.objects[obj3d].vitesseTranslation;
           this.objects[obj3d].puce.position.x += this.objects[obj3d].direction.x * v;
           this.objects[obj3d].puce.position.y += this.objects[obj3d].direction.y * v;
           this.objects[obj3d].puce.position.z += this.objects[obj3d].direction.z * v;
           this.objects[obj3d].attache.position.x += this.objects[obj3d].direction.x * v;
           this.objects[obj3d].attache.position.y += this.objects[obj3d].direction.y * v;
           this.objects[obj3d].attache.position.z += this.objects[obj3d].direction.z * v;
           if(this.objects[obj3d].pickable){
               this.objects[obj3d].wrapAttache.position.x += this.objects[obj3d].direction.x * v;
               this.objects[obj3d].wrapAttache.position.y += this.objects[obj3d].direction.y * v;
               this.objects[obj3d].wrapAttache.position.z += this.objects[obj3d].direction.z * v;
           }
           var vect = lastPosition.subSelf(this.objects[obj3d].puce.position);
           if(vect.length() > lengthMovement){
              this.objects[obj3d].lengthMovement = 0; 
              this.objects[obj3d].hasDirection = false;
           }
        }
        else{            
            var x = -1 + Math.random() * 2;
            var y = 0;
            var z = -1 + Math.random() * 2;  
            this.setDirection(obj3d,x,y,z);
            this.objects[obj3d].lengthMovement = 10; 
            this.objects[obj3d].hasDirection = true; 
        }
    }
    
    this.setDirection = function(obj3d,x,y,z){
           this.objects[obj3d].direction.x = x;
           this.objects[obj3d].direction.y = y;
           this.objects[obj3d].direction.z = z;
           this.objects[obj3d].direction.normalize();
    }  
    
    this.update = function(renderer){
            for(var obj3d in this.objects){
                if(this.objects[obj3d].reflexivity){
                    this.objects[obj3d].mirrorCamera.updateCubeMap( renderer, scene );
                }
            var v = this.objects[obj3d].vitesseRotation;
            this.objects[obj3d].puce.rotation.y += v;
            this.objects[obj3d].attache.rotation.y += v * 2;
            if(this.objects[obj3d].pickable){
                this.objects[obj3d].wrapPuce.rotation.y += v;
                this.objects[obj3d].wrapAttache.rotation.y += v * 2;
            }
            this.moove(obj3d);
        }
    }
    
     this.getPosition = function(obj3d){
        return this.objects[obj3d].puce.position.clone();
     }
        
     this.setVitesseRotation = function(obj3d, v){
        this.objects[obj3d].vitesseRotation = v;
     }
     
        
     this.vitesseTranslation = function(obj3d, v){
        this.objects[obj3d].vitesseTranslation = v;
     }
     
     this.setVitesseTranslationRotationForAll = function(vT,vR){
         for(var obj3d in this.objects){
                this.objects[obj3d].vitesseRotation = vR;
                this.objects[obj3d].vitesseTranslation = vT;
         }
     }
     
     this.setPosition = function(id, x,y,z){
        this.objects[id].puce.position.x = this.objects[id].attache.position.x = x;
        this.objects[id].puce.position.y = y;
        this.objects[id].attache.position.y = y+0.54;
        this.objects[id].puce.position.z = this.objects[id].attache.position.z = z;
        if(this.objects[id].pickable){
            this.objects[id].wrapAttache.position.z = z;
            this.objects[id].wrapAttache.position.x = x;
        }
     }
     
     this.copyPuce = function(id,newId){
         this.createPuce(newId,
         this.objects[id].model,
         this.objects[id].attacheModel,
         this.objects[id].modelTexture,
         this.objects[id].shadow,
         this.objects[id].reflexivity,
         this.objects[id].pickable,
         this.objects[id].vitesseRotation);
     }
     
     this.copyPuceForPanel = function(id,newId){
         this.createPuce(newId,
         this.objects[id].model,
         this.objects[id].attacheModel,
         this.objects[id].modelTexture,
         false,
         false,
         false,
         0.08);
         this.vitesseTranslation(newId, 0.0);
     }
}  