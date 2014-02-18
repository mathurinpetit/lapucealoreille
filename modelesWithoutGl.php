<?php

include_once 'db/DB.class.php';
include_once 'db/DAEModelsLoader.class.php';
$db = new DB();
$dae_models = $db->getAllDaeModels();
$models = $db->getAllModels();

$daeModelLoader = new DAEModelsLoader();
$loadProcess = $daeModelLoader->createLoadProcess($dae_models);
?>



<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>La Puce à l'oreille</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <link href="css/main_withoutGl.css" rel="stylesheet" type="text/css">
        <script src="lib/jquery-1.8.3.js"></script>  
        <script src="lib/roundabout/jquery.roundabout.js"></script>
        <script src="lib/roundabout/jquery.roundabout-shapes.js"></script> 
        <script src="lib/roundabout/jquery.easing.js"></script>  
    </head>
    <body> 

        
<!--        <video onload="fullscreen()">
  <source src="movie.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>-->
        <?php foreach ($models as $model_name => $model) :  ?>
        
        <form id="<?php echo $model_name; ?>" action="visualisation.php" method="post" >
            <input type="button" id="<?php echo $model_name; ?>" name="model" value="<?php echo $model_name; ?>"/>
        </form>
        <?php 
        endforeach; ?>
<div class="facade">
         <ul class="column1">
        <?php foreach ($models as $key => $model) : ?>
             <li>
                 <div>
            <?php if($model['image_0']) : ?>
                <img id="<?php echo $key;?>_img_0"
                    src="./models/images/<?php echo $model['image_0'];?>"
                    alt="<?php echo $key;?>" />
            <?php endif; ?>            
            <?php if($model['caracteristiques']) : ?>  
                <span id="<?php echo $key;?>_caracteristique" ><?php echo $model['caracteristiques'];?> </span>
            <?php endif; ?>
                
             <?php if($model['description']) : ?>  
                <span id="<?php echo $key;?>_description" ><?php echo $model['description'];?></span>
            <?php endif; ?>             
                </div>
            <form id="panier_paypal_<?php echo $key; ?>" hidden target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="<?php echo $model['paypal']; ?>">
                <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
                <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
            </form>
             </li>
             
        <?php endforeach; ?>
       </ul> 
    
        <ul class="column2">
        <?php foreach ($models as $key => $model) : ?>
             <li>
                 <div>
            <?php if($model['image_0']) : ?>
                <img id="<?php echo $key;?>_img_0"
                    src="./models/images/<?php echo $model['image_0'];?>"
                    alt="<?php echo $key;?>" />
            <?php endif; ?>            
            <?php if($model['caracteristiques']) : ?>  
                <span id="<?php echo $key;?>_caracteristique" ><?php echo $model['caracteristiques'];?> </span>
            <?php endif; ?>
                
             <?php if($model['description']) : ?>  
                <span id="<?php echo $key;?>_description" ><?php echo $model['description'];?></span>
            <?php endif; ?>             
                </div>
             
        <?php endforeach; ?>
       </ul> 
    
       <ul class="column3">
        <?php foreach ($models as $key => $model) : ?>
             <li>
                 <div>
            <?php if($model['image_0']) : ?>
                <img id="<?php echo $key;?>_img_0"
                    src="./models/images/<?php echo $model['image_0'];?>"
                    alt="<?php echo $key;?>" />
            <?php endif; ?>            
            <?php if($model['caracteristiques']) : ?>  
                <span id="<?php echo $key;?>_caracteristique" ><?php echo $model['caracteristiques'];?> </span>
            <?php endif; ?>
                
             <?php if($model['description']) : ?>  
                <span id="<?php echo $key;?>_description" ><?php echo $model['description'];?></span>
            <?php endif; ?>             
                </div>
             
        <?php endforeach; ?>
       </ul> 
           <ul class="column4">
        <?php foreach ($models as $key => $model) : ?>
             <li>
                 <div>
            <?php if($model['image_0']) : ?>
                <img id="<?php echo $key;?>_img_0"
                    src="./models/images/<?php echo $model['image_0'];?>"
                    alt="<?php echo $key;?>" />
            <?php endif; ?>            
            <?php if($model['caracteristiques']) : ?>  
                <span id="<?php echo $key;?>_caracteristique" ><?php echo $model['caracteristiques'];?> </span>
            <?php endif; ?>
                
             <?php if($model['description']) : ?>  
                <span id="<?php echo $key;?>_description" ><?php echo $model['description'];?></span>
            <?php endif; ?>             
                </div>
             
        <?php endforeach; ?>
       </ul> 
     
            </div>

<div class="interact">
<a id="advance" href="#">Next Image</a>
</div> 


        <script>
            
        $(document).ready(function() {
var i = 0,
settings = [
{ duration: 1200, easing: 'easeOutBounce' },
{ duration: 1600, easing: 'easeOutElastic' },
{ duration: 600, easing: 'easeOutQuad' },
{ duration: 1000, easing: 'easeOutBack' }
];

$('ul.column1, ul.column3').roundabout({
clickToFocus: false,
minOpacity: 0,
minScale: 0,
minZ: 0,
duration: 1500,
shape: 'rollerCoaster'
});
$('ul.column2, ul.column4').roundabout({
clickToFocus: false,
minOpacity: 0,
minScale: 0,
minZ: 0,
reflect: true,
duration: 1500,
shape: 'rollerCoaster'
});

$('#advance').click(function() {
    console.log('here');
if ($('.column1').data("roundabout").animating || $('.column4').data("roundabout").animating) {
return false;
}
i++;
i = i++ % settings.length;
// fade out link
$(this).fadeTo(400, 0.5);
$('.column1').roundabout('animateToNextChild', settings[i].duration, settings[i].easing);
setTimeout(function() { $('.column2').roundabout('animateToNextChild', settings[i].duration + 100, settings[i].easing); }, 100);
setTimeout(function() { $('.column3').roundabout('animateToNextChild', settings[i].duration + 200, settings[i].easing); }, 200);
setTimeout(function() { $('.column4').roundabout('animateToNextChild', settings[i].duration + 250, settings[i].easing, function() { $('#advance').fadeTo(400, 1); }); }, 300);
return false;
});
}); 
        </script>

 </body>
</html>
