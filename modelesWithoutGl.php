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
        <script src="lib/bootstrap-3.1.1/js/bootstrap.js"></script>
        <link rel="stylesheet" href="lib/bootstrap-3.1.1/css/bootstrap.css">
        <link rel="stylesheet" href="lib/bootstrap-3.1.1/css/bootstrap-theme.css">
    </head>
    <body> 
        <div id="container_light" class="container">
            <h1>La Puce à l'oreille</h1>
            <div id="carousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php
                    $cpt = 0;
                    foreach ($models as $model_name => $model) :
                        ?>
                        <li data-target="#carousel" data-slide-to="<?php echo $cpt ?>" class="<?php echo (!$cpt) ? "active" : "" ?>"></li>
                        <?php
                        $cpt++;
                    endforeach;
                    ?>
                </ol>

                <div class="carousel-inner">
                    <?php $cpt = 0;
                    foreach ($models as $key => $model) :
                        ?>
                        <div class="item <?php echo (!$cpt) ? "active" : "" ?>">
                            <div class="row">
                            <div class="col-lg-1"></div>                            
                            <div class="col-lg-7">
                            <div class="row">
                                <div class="col-lg-6 image_container">
                                    <img src="./models/images/<?php echo $model['image_0']; ?>"
                                         class="img-responsive"
                                         alt="<?php echo $key; ?>" />
                                </div>
                                <div class="col-lg-6 image_container">
                                    <img src="./models/images/<?php echo $model['image_1']; ?>"
                                         class="img-responsive"
                                         alt="<?php echo $key; ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 image_container">
                                    <img src="./models/images/<?php echo $model['image_2']; ?>"
                                         class="img-responsive"
                                         alt="<?php echo $key; ?>" />
                                </div>
                                <div class="col-lg-6 image_container">
                                    <img src="./models/images/<?php echo $model['image_3']; ?>"
                                         class="img-responsive"
                                         alt="<?php echo $key; ?>" />
                                </div>
                            </div> 
                            </div>
                            <div class="col-lg-3">
                                <?php if ($model['model_libelle']) : ?> 
                                <h3>
                                <span id="<?php echo $key; ?>_model_libelle" ><?php echo $model['model_libelle']; ?></span>
                                <?php endif; ?>
                                </h3>
                                <br/>
                                <br/>
                                <?php if ($model['caracteristiques']) : ?>  
                                <span id="<?php echo $key; ?>_caracteristique" ><?php echo str_replace('\\','<br/>', $model['caracteristiques']); ?></span>
                            <?php endif; ?>
                                <br/>
                                <br/>
                                <?php if ($model['description']) : ?>  
                                    <span id="<?php echo $key; ?>_description" class="text-justify" >
                                        <?php echo $model['description']; ?>
                                    </span>
                                <?php endif; ?>  
                                <form id="panier_paypal_<?php echo $key; ?>" hidden target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="<?php echo $model['paypal']; ?>">
                <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
                <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
                                </form>
                                <br/>
                                <br/>
                                <div class="center-block">
                                <button id="<?php echo $key; ?>" class="btn_submit" value="">Acheter pour <?php echo $model['prix'];?> €</button>
                                </div>
                
                            </div>                            
                            <div class="col-lg-1"></div> 
                        </div>    
                        </div>
                        <?php $cpt++; ?>

<?php endforeach; ?>
                </div>

                <a class="left carousel-control control-custom" href="#carousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a class="right carousel-control control-custom" href="#carousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
            </div>     
        </div>
        <script>
            $('.carousel').carousel({
                interval: 100000
            });
            $(document).ready(function(){
                $('.btn_submit').click(function(){
                    var id = '#panier_paypal_'+$(this).attr('id');
                    $(id).submit();
                })
            });
        </script>
    </body>
</html>
