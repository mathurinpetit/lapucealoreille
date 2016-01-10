<?php
include_once 'db/DB.class.php';
include_once 'db/DAEModelsLoader.class.php';
$db = new DB();
$dae_models = $db->getAllDaeModels();
$models = $db->getAllModels();

$daeModelLoader = new DAEModelsLoader();
$loadProcess = $daeModelLoader->createLoadProcess($dae_models);

$modelId = 1;
if (isset($_REQUEST['model'])) {
    $modelId = (int) $_REQUEST['model'];
}
?>



<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>La Pusse à l'Oreille</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
        <link href="css/main_withoutGl.css" rel="stylesheet" type="text/css">
        <script src="lib/jquery.min.js"></script>  
        <script src="lib/jquery.mobile.custom.min.js"></script>  
        <script src="lib/bootstrap-3.3.5/js/bootstrap.min.js"></script>
        <script src="lib/lapussealoreille.js"></script>  
        <link rel="stylesheet" href="lib/bootstrap-3.3.5/css/bootstrap.css">
        <link rel="stylesheet" href="lib/bootstrap-3.3.5/css/bootstrap-theme.css">

    </head>
    <body> 

        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.5";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

        <div id="container_light" class="container">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                    <div class="row">
                        <div class="col-xs-3" style="height: 84px"> <img src="./logo/lapussealoreille.png" class="img-responsive pull-right" style="padding-top:20px;" /></div>
                        <div class="col-xs-7"> 
                            <h1 class="pull-left visible-md visible-lg visible-xl">La Pusse à l'Oreille</h1>
                            <h3 class="pull-left visible-xs visible-sm">La Pusse à l'Oreille</h3>                            
                        </div>

                    </div>  
                </div>
            </div>
            <div class="visible-xs visible-sm">
                <div id="myCarousel" class="carousel slide " data-ride="carousel">
                    <div class="carousel-inner" role="listbox">

                        <?php
                        $cpt = 1;
                        foreach ($models as $key => $model) :
                            ?>
                            <div class=" row item <?php
                            if ($cpt == $modelId): echo "active";
                            endif;
                            ?>">
                                <div class="col-md-12 col-xs-12">                     
                                    <div class="col-xs-4 col-md-5" id="links"  style="padding-top: 20px;">
                                        <?php if ($key == 'diy') : ?>
                                            <a href="visualisation/<?php echo $key; ?>/0" title="<?php echo $model['model_libelle']; ?>" data-gallery class="image_container_diy"  >
                                                <img src="./<?php echo $model['images_thumbs'][0]; ?>"
                                                     class="img-responsive img-thumbnail"
                                                     alt="<?php echo $model['model_libelle']; ?>" />
                                            </a>

                                        <?php else: ?>
                                            <?php for ($j = 0; $j < 4; $j++) :
                                                ?>                                                            
                                                <a href="visualisation/<?php echo $key; ?>/<?php echo $j ?>" title="<?php echo $model['model_libelle'] . ' (' . ($j + 1) . ')'; ?>" data-gallery class="image_container">
                                                    <img src="./<?php echo $model['images_thumbs'][$j]; ?>"
                                                         class="img-responsive img-thumbnail"
                                                         alt="<?php echo $model['model_libelle'] . ' (' . ($j + 1) . ')'; ?>" />
                                                </a>
                                            <?php endfor; ?>
                                        <?php endif; ?>
                                    </div> 
                                    <div class="col-xs-7  col-md-6 text_boucle">
                                        <?php if ($model['model_libelle']) : ?> 
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6">

                                                    <h3>
                                                        <span id="<?php echo $key; ?>_model_libelle" class="titre"><?php echo $model['model_libelle']; ?></span>
                                                    </h3>
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="center-block" style="padding-top: 18px;">
                                                        <button id="<?php echo $key; ?>" class="btn_submit btn btn-default" value=""><img src="./models/images/<?php echo $key; ?>_small.png" class="pull-left" style="padding:0px;" />&nbsp;&nbsp;&nbsp;<span style="padding-top: 15px;">Acheter pour <?php echo $model['prix']; ?> €</span></button>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <br/>
                                        <?php if ($model['caracteristiques']) : ?>  
                                            <span id="<?php echo $key; ?>_caracteristique" class="carcateristique"><?php echo str_replace('\\', '<br/>', $model['caracteristiques']); ?></span>
                                        <?php endif; ?>
                                        <br/>
                                        <br/>
                                        <?php if ($model['description']) : ?>  

                                            <span id="<?php echo $key; ?>_description" class=" description text-justify" >
                                                <?php echo $model['short_description']; ?>
                                            </span>
                                        <?php endif; ?>  
                                        <form id="panier_paypal_<?php echo $key; ?>" hidden target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                            <input type="hidden" name="cmd" value="_s-xclick">
                                            <input type="hidden" name="hosted_button_id" value="<?php echo $model['paypal']; ?>">
                                            <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
                                            <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
                                        </form>
                                    </div>
                                </div> 
                            </div>  
                            <?php $cpt++; ?>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>

            <div class="visible-xl visible-md visible-lg">
                <div class=" col-md-offset-1 col-md-10">                    
                    <?php
                    $cpt = 1;
                    foreach ($models as $key => $model) :
                        ?>
                        <div class=" well well-lg">
                            <div class="row">
                                <h1>
                                    <span id="<?php echo $key; ?>_model_libelle" class="titre2"><?php echo $model['model_libelle']; ?></span>
                                </h1>

                            </div>
                            <div class="row">
                                <div class="col-md-12">   
                                    <div class="col-md-7" id="links"  style="padding-top: 20px;">
                                        <?php if ($key == 'diy') : ?>
                                            <a href="visualisation/<?php echo $key; ?>/0" title="<?php echo $model['model_libelle']; ?>" data-gallery class="image_container_diy"  >
                                                <img src="./<?php echo $model['images_thumbs'][0]; ?>"
                                                     class="img-responsive img-thumbnail"
                                                     alt="<?php echo $model['model_libelle']; ?>" />
                                            </a>

                                        <?php else: ?>
                                            <?php for ($j = 0; $j < 4; $j++) :
                                                ?>                                                            
                                                <a href="visualisation/<?php echo $key; ?>/<?php echo $j ?>" title="<?php echo $model['model_libelle'] . ' (' . ($j + 1) . ')'; ?>" data-gallery class="image_container">
                                                    <img src="./<?php echo $model['images_thumbs'][$j]; ?>"
                                                         class="img-responsive img-thumbnail"
                                                         alt="<?php echo $model['model_libelle'] . ' (' . ($j + 1) . ')'; ?>" />
                                                </a>
                                            <?php endfor; ?>
                                        <?php endif; ?>
                                    </div> 
                                    <div class="col-md-5 text_boucle">
                                        <?php if ($model['model_libelle']) : ?> 
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="center-block" style="padding-top: 18px;">
                                                        <button id="<?php echo $key; ?>" class="btn_submit btn btn-default" value=""><img src="./models/images/<?php echo $key; ?>_small.png" class="pull-left" style="padding:0px;" />&nbsp;&nbsp;&nbsp;<span style="padding-top: 15px;">Acheter pour <?php echo $model['prix']; ?> €</span></button>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <br/>
                                        <?php if ($model['caracteristiques']) : ?>  
                                            <span id="<?php echo $key; ?>_caracteristique" class="caracteristique2"><?php echo str_replace('\\', '<br/>', $model['caracteristiques']); ?></span>
                                        <?php endif; ?>
                                        <br/>
                                        <br/>
                                        <?php if ($model['description']) : ?>  

                                            <span id="<?php echo $key; ?>_description" class=" description2 text-justify" >
                                                <?php echo $model['description']; ?>
                                            </span>
                                        <?php endif; ?>      

                                        <form id="panier_paypal_<?php echo $key; ?>" hidden target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                            <input type="hidden" name="cmd" value="_s-xclick">
                                            <input type="hidden" name="hosted_button_id" value="<?php echo $model['paypal']; ?>">
                                            <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
                                            <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
                                        </form>
                                    </div>
                                    <?php if ($key == 'diy') : ?>
                                        <div class="row">
                                            <div class="col-md-8" style="padding-top: 20px; "  >
                                                
                                                <iframe class="visible-lg visible-xl" width="870" height="315" src="https://www.youtube.com/embed/_NvqhNxjjIc" frameborder="0" allowfullscreen id="" ></iframe>
                                            <iframe class="visible-md" width="720" height="315" src="https://www.youtube.com/embed/_NvqhNxjjIc" frameborder="0" allowfullscreen id="" ></iframe>
                                            
                                            </div>
                                        </div>
                                    <?php endif; ?>    
                                </div> 
                            </div>
                        </div>  
                        <?php $cpt++; ?>
                    <?php endforeach; ?>
                    <br/><br/>
                    <a href="/3d">Accèdez à l'ancienne boutique</a>
                </div>
            </div>


            <br/>          
            <div class="col-md-12 col-xs-10 col-xs-offset-1">                     
                <div class="col-xs-4 col-md-5"></div>
                <div class="col-xs-6  col-md-6">
                    <div class="row">
                        <div class="col-xs-6  col-md-6" style="padding-top: 5px; padding-bottom: 25px;">
                            <div class="pull-right">
                                <a href="https://twitter.com/LaPucelOreille" class="twitter-follow-button" data-show-count="false">Suivre @LaPucelOreille</a>
                                <script>!function (d, s, id) {
                                        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                                        if (!d.getElementById(id)) {
                                            js = d.createElement(s);
                                            js.id = id;
                                            js.src = p + '://platform.twitter.com/widgets.js';
                                            fjs.parentNode.insertBefore(js, fjs);
                                        }
                                    }(document, 'script', 'twitter-wjs');</script>
                            </div>
                        </div>
                        <div class="col-xs-6  col-md-6" style="padding-top: 5px; padding-bottom: 25px;">

                            <div class="fb-like" width="200px" layout="button_count"></div>          
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <br/>         
        </div>           
    </body>
</html>

