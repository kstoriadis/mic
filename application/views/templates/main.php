<?php 
  $error_msgs = array();
  $success_msgs = array();
  if ($this->session->flashdata('error_message')) { 
    $error_msgs[] = $this->session->flashdata('error_message');
  }
  if (isset($_error_message)) { 
    $error_msgs[] = $_error_message;
  }
  if ($this->session->flashdata('success_message')) { 
    $success_msgs[] = $this->session->flashdata('success_message');
  }
  if (isset($_success_message)) { 
    $success_msgs[] = $_success_message;
  }
?>
<!DOCTYPE html>
<html lng="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo assets_url('plugins/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet" media='screen'>
    <link href="<?php echo assets_url('css/bootstrap-responsive.css')?>" rel="stylesheet">
      <link rel="stylesheet" href="<?php echo assets_url('plugins/font-awesome/css/font-awesome.css')?>">
      <link rel="stylesheet" href="<?php echo assets_url('plugins/prism/prism.css')?>">
      <link rel="stylesheet" href="<?php echo assets_url('css/styles.css')?>">
      <link rel="stylesheet" href="<?php echo assets_url('css/hamburgers.css')?>">
      <link rel="stylesheet" href="<?php echo assets_url('plugins/jquery.mmenu/jquery.mmenu.css')?>">

    <link href="<?php echo assets_url('css/custom.css')?>" rel="stylesheet">
    <?php if (isset($_css) && $_css) {
      foreach($_css as $e) { 
        echo "<link href='$e' rel='stylesheet'>\n";
      }
    }
    ?>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php echo assets_url('js/html5shiv.js')?>"></script>
    <![endif]-->
      <script src="<?php echo assets_url("plugins/jquery-1.11.3.min.js")?>"></script>
      <script src="<?php echo assets_url("plugins/jquery.easing.1.3.js")?>"></script>
      <script src="<?php echo assets_url("/js/bootstrap.min.js")?>"></script>
      <script src="<?php echo assets_url("/js/bootstrap-datepicker.js")?>"></script>
      <script src="<?php echo assets_url("plugins/jquery-scrollTo/jquery.scrollTo.min.js")?>"></script>
      <script src="<?php echo assets_url("plugins/prism/prism.js")?>"></script>
      <script src="<?php echo assets_url("js/main.js")?>"></script>
      <script src="<?php echo assets_url("plugins/jquery.mmenu/jquery.mmenu.js")?>"></script>

    <link rel="icon" href="<?php echo assets_url('/img/favicon.ico')?>" type="image/x-icon" />
  </head>

  <body>
    <?php if (!(isset($_hide_menu) && $_hide_menu)){ echo $_template_menu_content ?>
<script type="text/javascript">
    $(document).ready(function(){

        var $menu = $("#menuL").mmenu({
            //   options
        });
        var $icon = $("#my-icon");
        var API = $menu.data( "mmenu" );

        $icon.on( "click", function() {
            API.open();
        });
/*
        API.bind( "open:finish", function() {
                $icon.addClass( "is-active" );
        });
        API.bind( "close:finish", function() {
                $icon.removeClass( "is-active" );
        });*/
    })
</script>
    <?php } ?>
<div class="wrapperTotal container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="clearfix encabezadoApp">
            <div class="logoCitepMic">
                <img src="<?= assets_url('img/citep_mic.gif')?>" >
            </div>
            <div class="logoCitepUba">
                <a href="http://citep.rec.uba.ar" target="_blank">
                    <img  src="<?= assets_url('img/logo-citep-uba.png')?>" >
                </a>
            </div>
            </div>
        </div>


        <?php if (isset($micSeleccionada) or (isset($_template_menu)) and $_template_menu == 'templates/menu_lateral'){ ?>
        <div class="col-md-4 ">
            <div class="clearfix encabezadoMic">
            <?php if (isset($_template_menu) and $_template_menu == 'templates/menu_lateral'){ ?>
                <button id="my-icon" class="hamburger hamburger--spin botonMenu" type="button">
                    <span class="hamburger-box">
                      <span class="hamburger-inner"></span>
                    </span>
                </button>
            <?php }?>
            </div>
        </div>

        <div class="col-md-8 ">
            <!-- IMAGEN MIC-->
            <div class="clearfix encabezadoMic">
                <?php if (isset($micSeleccionada)){ ?>
                <img src="<?= assets_url('/img/foco_public.png') ?>" width="76" height="92">
                <span class="logosmall">Focos</span> <span class="logolightsmall"> en juego</span>
                <?php }?>
            </div>
        </div>
        <?php }?>
    </div>
    <div class="row cuerpoContenido">
        <?php if ($error_msgs or $success_msgs){?>
        <div class="col-md-12">
            <div class="cuerpoContenido">
              <?php foreach ($error_msgs as $msg) { ?>
              <div class="alert alert-error" style='position:relative;top:60px;'>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $msg ?>
              </div>
              <?php } ?>

              <?php foreach ($success_msgs as $msg) { ?>
              <div class="alert alert-success" style='position:relative;top:60px;'>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $msg ?>
              </div>
              <?php } ?>
            </div>
        </div>
        <?php } ?>

      <?php echo $_template_content ?>
    </div> <!-- /container -->
    <!-- FOOTER -->
    <?php if (isset($micSeleccionada)){ ?>
    <div class="row">
        <div class="col-md-12">
            <div class="footerCitep">
                <div class="col-md-12 citepMICFOOTER">
                + CitepMIC:
                </div>
                <div class="col-md-4">
                </div>
                <?php if ($micSeleccionada != 'FOCOS'){ ?>

                    <div class="col-md-2">
                        <span class="logoxxsmall">Focos</span>
                        <span class="logolightxxsmall"> en juego</span><br />
                        <img src="<?= assets_url('/img/foco.png')?>" width="116" height="120" border="0">
                    </div>
                <?php } ?>
                <?php if ($micSeleccionada != 'PRISMAS'){ ?>
                <div class="col-md-2">
                        <span class="logoxxsmall">Prismas</span>
                        <span class="logolightxxsmall"> entramados</span><br />
                        <img src="<?= assets_url('/img/prismas.png')?>" width="116" height="120" border="0">
                </div>
                <?php } ?>
                <?php if ($micSeleccionada != 'CROQUIS'){ ?>
                    <div class="col-md-2">
                        <span class="logoxxsmall">Croquis</span>
                        <span class="logolightxxsmall"> en movimiento</span><br />
                        <img src="<?= assets_url('/img/croquis.png')?>" width="116" height="120" border="0">
                    </div>
                <?php } ?>
                <div class="col-md-4">
                </div>
                <div class="col-md-12">
                <div class="logoCitepUbaFooter">
                    <a href="http://citep.rec.uba.ar" target="_blank">
                        <img  src="<?= assets_url('img/logo-citep-uba.png')?>" >
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
</div>

  </body>
</html>
