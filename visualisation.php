<?php
$model_name = $_REQUEST['model'];
$index = $_REQUEST['index'];
if (!$model_name || is_null($index)) {
    exit;
}
include_once 'db/DB.class.php';
include_once 'db/DAEModelsLoader.class.php';
$db = new DB();
$dae_models = $db->getAllDaeModels();
$models = $db->getAllModels();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>La Pusse à l'oreille - <?php echo $models[$model_name]["model_libelle"]; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->

        <script src="/lib/jquery.min.js"></script>  
        <script src="/lib/jquery.mobile.custom.min.js"></script>  
        <script src="/lib/bootstrap-3.3.5/js/bootstrap.min.js"></script>
        <script src="/lib/lapussealoreille.js"></script>   

        <link href="/css/main_withoutGl.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/lib/bootstrap-3.3.5/css/bootstrap.css">
        <link rel="stylesheet" href="/lib/bootstrap-3.3.5/css/bootstrap-theme.css">
    </head>
    <body>

        <div id="container_light" class="container">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner" role="listbox">

                    <?php foreach ($models[$model_name]['images'] as $cpt => $imgpath): ?>     
                                                     <!--             <img src="path/to/image.jpg" />
                                                        <iframe src="//www.youtube.com/embed/video_id" frameborder="0"></iframe>
                                                        <div>Some inline content.</div>-->
                        <div class=" row item <?php
                        if ($cpt == $index): echo "active";
                        endif;
                        ?>">
                            <br/>
                            <div class="col-md-12 col-xs-12 text-center" style="position: relative;">

                                <img src="<?php echo $imgpath; ?>"  class="img-responsive unselectable" style=" margin: auto; " />
                            </div>
                            <div class="col-md-12 col-xs-12 text-center">
                                <h4><?php echo $models[$model_name]['model_libelle'] ?></h4>
                                <p><?php echo $models[$model_name]['short_description']; ?></p>
                            </div>

                            <div class=" col-md-offset-2 col-md-8 visible-md visible-lg">
                                <br/>
                                <br/>
                                <div class="row" >
                                    <div class=" col-md-6" >

                                        <a href="/?model=<?php echo $models[$model_name]["id"]; ?>" class="btn btn-default btn-md"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;revenir aux modèles</a>
                                    </div>
                                    <div class=" col-md-6 text-right" >

                                        <button id="<?php echo $model_name; ?>" class="btn_submit btn btn-default" value=""><img src="/models/images/<?php echo $model_name; ?>_small.png" class="pull-left" style="padding:0px;" />&nbsp;&nbsp;&nbsp;<span style="padding-top: 15px;">Acheter pour <?php echo $models[$model_name]['prix']; ?> €</span></button>

                                    </div> 
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div> 
            <div class="visible-md visible-lg">

                <a class="col-md-1 left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>

                <a class="col-md-1 right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>

            </div>
        </div>
        <form id="panier_paypal_<?php echo $model_name; ?>" hidden target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="<?php echo $models[$model_name]['paypal']; ?>">
            <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
            <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
        </form>  


    </body>   

</html>