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
        <script src="lib/boostrap-3.1.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="lib/boostrap-3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="lib/boostrap-3.1.1/css/bootstrap-theme.min.css">
    </head>
    <body> 
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
      <?php 
      $cpt=0;
      foreach ($models as $model_name => $model) :
      ?>
          <li data-target="#carousel-example-generic" data-slide-to="<?php echo $cpt ?>" class="<?php echo (!$cpt)? "active" : "" ?>"></li>
      <?php endforeach; ?>
  </ol>
        
  <div class="carousel-inner">
      <?php foreach ($models as $key => $model) : ?>
    <div class="item <?php echo (!$cpt)? "active" : "" ?>">
      <?php if($model['image_0']) : ?>
                <img id="<?php echo $key;?>_img_0"
                    src="./models/images/<?php echo $model['image_0'];?>"
                    alt="<?php echo $key;?>" />
            <?php endif; ?>            
      <div class="carousel-caption">
        <?php if($model['description']) : ?>  
                <span id="<?php echo $key;?>_description" ><?php echo $model['description'];?></span>
        <?php endif; ?>    
      </div>
    </div>
      <form id="<?php echo $model_name; ?>" action="visualisation.php" method="post" >
            <input type="button" id="<?php echo $model_name; ?>" name="model" value="<?php echo $model_name; ?>"/>
      </form>      
      <?php endforeach; ?>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div>     
        
        
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

 </body>
</html>
