<?php if (!$ejercicio->consigna): ?>
<h1>Actividad no existente</h1>
<?php return;endif ?>
<script type='text/javascript'>

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

  if (typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str){
      return this.slice(0, str.length) == str;
    };
  }
  $(document).ready(function() {
    $('.enviar').click(function() {
        var img = $(this);
        var div = img.parents('.modal');

      if (img.attr('disabled')) return false;
      var completed = true;

      div.find('textarea').each(function() {
        if ($(this).val().length <= 10 || completed == false) {
          completed = false;
        }
      })
      if (!completed) {
        div.find('div.alert').text('Complete las preguntas con al menos 10 caracteres').removeClass('hide');
        return false;
      } else { 
        div.find('div.alert').addClass('hide');
      }
      
      var form = $('form').serializeArray();
      var post_data = {
        'img_id': img.data('rel'),
          'nombre' : $('#nombre').val(),
          'email' : $('#email').val()
      };
      if (img.data('rel')) { 
        var key = 'respuesta[' + img.data('rel') + ']';

        for (i in form) { 
          var elem = form[i];
          if (elem['name'].startsWith(key)) { 
            post_data[elem['name']] = elem['value']
          }
          //TOdO: esto no anda
          else if (elem['name'].startsWith('pregunta')) {
                post_data[elem['name']] = elem['value']
            }
        } 
      } else { 
        respuestas = form; 
      }
      $(this).text('Enviando...');
      $(this).addClass('disabled');
      var xhr = $.ajax('<?php echo base_url("/arquetipos/ajax_respuesta/$public_id")?>',
        {
          data: post_data,
          type:'POST',
        })
        .done(function(data) {
            if (data['ok'] == true) {
              $("#img_" + img.data('rel')).addClass('grayscale'); 
              $("#img_small_" + img.data('rel')).addClass('grayscale'); 
            }
            //var anchor = $('a[href=#' + img.parents('div.modalResponder').attr('id') + ']');
            div.find('textarea').attr('disabled',1);
            img.attr('disabled',1);
            img.text('Respuestas enviadas');
            div.find('div.alert').addClass('hide');
        })
        .fail(function () { 
            div.find('div.alert').text('Problemas enviando respuestas, pruebe nuevamente').removeClass('hide');
            img.text('Enviar respuestas');
            img.removeClass('disabled');
        })
        .always(function() { 
            div.find('button')[0].click();
        });
        
      return false;
    })
  $('#infoModal').on('hidden', function () {
    setTimeout(function() {
      $("div#infoModal > div.infoModal_body").html('');
    }, 1000);
  });

  $('#infoModal').on('show', function () {
    $("div#infoModal > div.infoModal_body").html('<i class="icon-spinner icon-spin icon-large"></i>');
    $.get("<?php echo base_url('/arquetipos/ejercicio_get_description/' . $public_id) ?>", "", function (html) {
      $("div#infoModal > div.infoModal_body").html(html);
    });
  })
  $('#infoModal').modal('show');
});

</script>


<div class="col-md-12">
        <h2><?php echo $ejercicio->consigna ?></h2>
</div>
<div class="col-md-12">
    <h4><?php echo $ejercicio->desarrollo ?></h4>

    <div class="spacer"></div>
</div>

<div class="col-md-12">
    <div class="col-md-3">
        <b>Alias: </b> <?= $alias?>
    </div>
    <div class="col-md-2">
        <a class="cambiarAlias" href="<?php echo base_url('/arquetipos/cambiarAlias/' . $ejercicio->id)?>"><i class="fa fa-refresh" aria-hidden="true"></i> Cambiar Alias</a>
    </div>
</div>




<form method='post' action='<?php echo base_url('/arquetipos/respuestasAnteriores/' . $ejercicio->id)?>'>
    <div id="mensaje" class="alert alert-error pull-left hide" ">Area de Mensajes</div>
    <input type="hidden" id="obtenerRespuestas" name="obtenerRespuestas" value="1"/>

<?php foreach ($imagenes as $i=>$img): 
  $disabled = (isset($respuestas[$img->id]));
  ?>
    <div class="modal fade" id="modal-container-<?php echo $img->id?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        <?php echo $img->titulo ?>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row pad15">
                        <div class="col-md-4">
                            <img id="img_small_<?php echo $img->id?>" class='imagenModal <?php if ($disabled):?> grayscale <?php endif;?>'
                                 src="<?php echo $img->imagen_ubicacion?>"  >
                        </div>
                        <div class="col-md-8">
                            <?php foreach($preguntas as $preg): ?>
                                <input type="hidden" name="pregunta[<?php echo $img->id?>][<?php echo $preg->id?>]" value="<?php echo $preg->pregunta?>" />
                                <p ><?php echo $preg->pregunta?></p>
                                <div class="control-group">
                                    <div class="controls">
                <textarea name="respuesta[<?php echo $img->id?>][<?php echo $preg->id?>]" rows="2"
                          class="input-large" id="textarea<?php echo $img->id?>][<?php echo $preg->id?>]" style="width:100%;"
                          <?php if ($disabled):?>disabled<?php endif?>><?php echo @$respuestas[$img->id][$preg->id]?></textarea>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="mensaje" class="alert alert-error pull-left hide" ">Area de Mensajes</div>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button <?php if ($disabled):?>disabled<?php endif?> type="button" class='btn btn-success pull-right enviar' data-rel='<?php echo $img->id ?>'>
                        Enviar respuestas
                    </button>
                </div>
            </div>

        </div>

    </div>

<?php endforeach ?>

    <div class="container gallery">
<?php foreach ($imagenes as $i=>$img): ?>  
  <?php if ($i == 0 or $i == 3): ?>
    <div class="row">
        <div class="col-md-2">
        </div>
  <?php endif ?>
        <div class="col-md-3" >
              <a id="modal-<?php echo $img->id?>" href="#modal-container-<?php echo $img->id?>" role="button" class="btn imagenResponder"
                 data-toggle="modal">
                  <img class='imagenResponder <?php if (isset($respuestas[$img->id])): ?> grayscale <?php endif;?>' id='img_<?php echo $img->id?>'
                    src="<?php echo $img->imagen_ubicacion?>"  alt="" />
              </a>
          <p class="text-info"><?php echo $img->titulo ?></p>
            <?php
            if (isset($respuestasAnteriores)){
                foreach ($respuestasAnteriores as $resp): ?>
                <?php if ($resp->imagen_id == $img->id): ?>
                       <div style="width:100%;  float: left"><?= $resp->pregunta ?><br><span style="color: blue"> <?= $resp->respuesta ?></span>   </div>
                <?php endif ?>

                <?php endforeach; } ?>
        </div>
  <?php if ($i == 2 or $i == 5 or $i == sizeof($imagenes) -1): ?>
    </div>
  <?php endif ?>
<?php endforeach ?>
</div>

</form>
<div class="col-md-12">
    <a class="btn btn-large btn-default" href="<?php echo base_url('/arquetipos/respuestasAnteriores/' . $ejercicio->id)?> ">Mis respuestas</a>
</div>
<div class="col-md-12">
    <a class="btn btn-lg btn-default"  href='<?php echo base_url('/arquetipos/link_publico/' . $ejercicio->id)?>'><i class="fa fa-home"></i> Todas las respuestas</a>
</div>
