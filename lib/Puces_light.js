/**
 * @author mathurin Petit
 */

var PUCES = function(scene,renderer){ 
    
    this.scene  = scene;    
    this.objects = [];
    this.renderer  = renderer; 
    
    
    this.createPuce = function(id, model, attacheModel, modelTexture, shadow, reflexivity, pickable, vitesseRotation,type) { 
        this.objects[id] = {};
        this.objects[id].id = id; 
        this.objects[id].model = model.clone(); 
        this.objects[id].modelTexture = modelTexture; 
        this.objects[id].attacheModel = attacheModel.clone(); 
        this.objects[id].shadow = shadow; 
        this.objects[id].reflexivity = reflexivity; 
        this.objects[id].pickable = pickable;
        this.objects[id].vitesseRotation = vitesseRotation;
        this.objects[id].vitesseTranslation = 0.05;
        this.objects[id].wrapPuce = null;
        this.objects[id].direction = new THREE.Vector3( 1, 0, 1).normalize();
        this.objects[id].lengthMovement = 10;
        this.objects[id].hasDirection = true;
        this.objects[id].type = type;
        this.objects[id].angle_start = 0;
        this.objects[id].distPuceAttache = 1.08;
        
        var modelCloned = this.objects[id].model;
        var attacheCloned = this.objects[id].attacheModel;
        
        this.objects[id].lastPosition = modelCloned.position.clone();
        
        attacheCloned.position.y += 5.08;
        modelCloned.position.y += 4;
        if(this.objects[id].shadow){
            modelCloned.traverse(function ( child ) {
                child.castShadow = true;
            } );
            
            attacheCloned.traverse(function ( child ) {
                child.castShadow = true;
            } );
        }
        
        var texture = this.renderer._microCache.getSet(modelTexture, THREE.ImageUtils.loadTexture('./textures/'+modelTexture+'.jpg'));
                  
           var faceMaterial = new THREE.MeshLambertMaterial( {
                ambient: 0x444444,
                specular: 0x999999,
                map: texture
            } );
        modelCloned.children[0].children[0].children[9].material = faceMaterial;
                
        
        if(this.objects[id].pickable){
            
            var geometryPuce = new THREE.CubeGeometry(2, 4, 2);
            var material = new THREE.MeshLambertMaterial({                
                transparent: true,
                opacity: 0
            });
         
            var meshPuce = new THREE.Mesh(geometryPuce, material);
            meshPuce.position = modelCloned.position;
            meshPuce.rotation = modelCloned.rotation.clone();
            meshPuce.id = id;
            this.objects[id].wrapPuce = meshPuce;
            this.scene.add(meshPuce);
        }
        
        this.scene.add( modelCloned );
        this.scene.add( attacheCloned );
        this.objects[id].puce = modelCloned;
        this.objects[id].attache = attacheCloned;
    }
    
    
    this.update = function(stop){
        if(!stop){
            for(var obj3d in this.objects){
            var v = this.objects[obj3d].vitesseRotation;
            this.objects[obj3d].puce.rotation.y -= v;
            this.objects[obj3d].attache.rotation.y -= v ;
            if(this.objects[obj3d].pickable){
                this.objects[obj3d].wrapPuce.rotation.y -= v;
            }
        }
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
     
     this.setPosition = function(id, x, y, z){
        this.objects[id].puce.position.x = this.objects[id].attache.position.x = x;
        this.objects[id].puce.position.y = y;
        this.objects[id].attache.position.y = y+this.objects[id].distPuceAttache;
        this.objects[id].puce.position.z = this.objects[id].attache.position.z = z;
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
         this.setScale(newId, 0.02); 
     }
     
     this.removeModel = function(selectedModel){
         if(this.objects[selectedModel].reflexivity){
             this.scene.remove(this.objects[selectedModel].mirrorCamera);
         }
         if(this.objects[selectedModel].pickable){
            this.scene.remove(this.objects[selectedModel].wrapPuce);
         }
        this.scene.remove( this.objects[selectedModel].puce );
        this.scene.remove( this.objects[selectedModel].attache );
        delete this.objects[selectedModel];
     }
     
     this.getModelType = function(id){
         return this.objects[id].type;
     }
     
     this.majPucesPositionPanel = function(camera,far,direction){
         for(var obj3d in this.objects){
               if(this.getPosition(obj3d).distanceTo(camera.position) < far){
//                   var newPos = new Vector3(this.getPosition(obj3d).x + far * direction.x,
//                   this.getPosition(obj3d).y + far * direction.y, this.getPosition(obj3d).z + far * direction.z);
                   this.setDirection(obj3d,direction.x,direction.y,direction.z);
                   this.objects[obj3d].near = true;
               }
         }
     }
     
     this.setInitPosition = function(id,cpt){
         var x = 0.5;
         var z = (cpt%2) * 2 - 1;
         var y =  - (Math.floor(cpt/2) * 12);
         this.setPosition(id, x, y, z);
     }
     
     this.translateAllWithVector = function(x,y,z){  
        for(var id in this.objects){
            if(id.indexOf("panel") === -1){
            this.objects[id].puce.position.x += x;
            this.objects[id].attache.position.x += x;
            this.objects[id].puce.position.y += y;
            this.objects[id].attache.position.y = this.objects[id].puce.position.y+this.objects[id].distPuceAttache;
            this.objects[id].puce.position.z += z;
            this.objects[id].attache.position.z += z;
            }
         }
     }
     
     this.length = function(){  
         var cpt = 0;
        for(var id in this.objects){
            if(id.indexOf("panel") === -1){
            cpt++;
            }
         }
         return cpt;
     }
     
     this.positionOfFirst = function(){  
        for(var id in this.objects){
            if(id.indexOf("panel") === -1){
                return this.objects[id].puce.position;
            }
         }
     }
     
     this.positionOfLast = function(){  
         var tmp = null;
        for(var id in this.objects){
            if(id.indexOf("panel") === -1){
                tmp = this.objects[id].puce.position;
            }
         }
         return tmp;
     }
     
     this.setScale = function(id, scale_value){
         this.objects[id].puce.scale.x = this.objects[id].puce.scale.y = this.objects[id].puce.scale.z = scale_value;
         this.objects[id].attache.scale.x = this.objects[id].attache.scale.y = this.objects[id].attache.scale.z = scale_value;
         this.objects[id].distPuceAttache = 0.54;
     }
     
     
 }
