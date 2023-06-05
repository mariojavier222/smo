<?php
//$foto = $_SERVER['DOCUMENT_ROOT']."/sta/fotos/temp/".$_POST['foto'].".jpg";
$foto = $_SERVER['DOCUMENT_ROOT']."/local/cesap/fotos/temp/".$_POST['foto'].".jpg";
?>



<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
<script src="js/jquery.Jcrop.min.js" type="text/javascript"></script>
<!--<script src="js/jquery.min.js" type="text/javascript"></script>-->

<script type="text/javascript">

    jQuery(function($){

      // Create variables (in this scope) to hold the API and image size
      var jcrop_api, boundx, boundy;
      
      $('#target').Jcrop({
        onChange: updatePreview,
        onSelect: updatePreview,
		onRelease:  clearCoords,
		minSize: [500,500],
		maxSize: [800,800],
		setSelect:   [ 100, 100, 50, 50 ],
        aspectRatio: 1
      },function(){
        // Use the API to get the real image size
        var bounds = this.getBounds();
        boundx = bounds[0];
        boundy = bounds[1];
        // Store the API in the jcrop_api variable
        jcrop_api = this;
      });

      function updatePreview(c)
      {
        if (parseInt(c.w) > 0)
        {
          var rx = 100 / c.w;
          var ry = 100 / c.h;

          $('#preview').css({
            width: Math.round(rx * boundx) + 'px',
            height: Math.round(ry * boundy) + 'px',
            marginLeft: '-' + Math.round(rx * c.x) + 'px',
            marginTop: '-' + Math.round(ry * c.y) + 'px'
          });
        }
		showCoords(c);
      };
	  
	  // Simple event handler, called from onChange and onSelect
    // event handlers, as per the Jcrop invocation above
    function showCoords(c)
    {
      $('#x1').val(c.x);
      $('#y1').val(c.y);
      $('#x2').val(c.x2);
      $('#y2').val(c.y2);
      $('#w').val(c.w);
      $('#h').val(c.h);
    };

    function clearCoords()
    {
      $('#coords input').val('');
      $('#h').css({color:'red'});
      window.setTimeout(function(){
        $('#h').css({color:'inherit'});
      },500);
    };
	
	var opciones= {
		   beforeSubmit: validarForm, //funcion que se ejecuta antes de enviar el form
		   success: mostrarRespuesta //funcion que se ejecuta una vez enviado el formulario
	};

	$("#form_subir").ajaxForm(opciones) ;
	//$("#form_nueva").ajaxForm(opciones_nuevo) ;

	 //lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarForm(formData, jqForm, options){
	 var bError = false;
	  $("#cargando").show(); //muestro el loader de ajax
		return true;
	 };
	 function mostrarRespuesta (responseText){
		  $("#principal").html(responseText); // Aca utilizo la función append de JQuery para añadir el responseText  dentro del div "ajax_loader"
		  $("#cargando").fadeOut();
	 };	
	 
	 
	 $("#botEnviar").button();

});

  </script>


<style type="text/css">
<!--
.foto {
	background-color: #FFF;
	padding: 2px;
	border-top-width: 1px;
	border-right-width: 2px;
	border-bottom-width: 2px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-top-color: #333;
	border-right-color: #333;
	border-bottom-color: #333;
	border-left-color: #333;
}
-->
</style>
<div id="outer">
	<div class="jcExample">
	<div class="article">

		<h1>Recorte la regi&oacute;n de la foto que desea guardar</h1>

  <table>
			<tr>
				<td colspan="2"  class="foto">
					<img src="fotos/temp/<?php echo $_POST['foto'];?>.jpg" id="target" />
				</td>
		</tr>
  <tr>
				<td width="100" align="left" class="foto">
					<div style="width:100px;height:100px;overflow:hidden;">
						<img src="fotos/temp/<?php echo $_POST['foto'];?>.jpg" id="preview" />
					</div>
				</td>
			  <td align="left" class="texto">Foto recortada (ser&aacute; la foto que quedar&aacute; guardada en el sistema)</td>
			</tr>
		</table>

		<!-- This is the form that our event handler fills -->
	  <form action="subir_foto_final_usuario.php" method="post" id="form_subir">

      <div>
			<!--<label>X1 </label>-->
            <input type="hidden" size="4" id="x1" name="x1" />
			<!--<label>Y1 </label>-->
            <input type="hidden" size="4" id="y1" name="y1" />
			<!--<label>X2 </label>-->
            <input type="hidden" size="4" id="x2" name="x2" />
			<!--<label>Y2 </label>-->
            <input type="hidden" size="4" id="y2" name="y2" />
			<!--<label>W </label>-->
            <input type="hidden" size="4" id="w" name="w" />
			<!--<label>H </label>-->
    <input type="hidden" size="4" id="h" name="h" />
            <input name="foto" type="hidden" id="foto" value="<?php echo $_POST['foto'];?>">
	        <input name="Usuario" type="hidden" id="Usuario" value="<?php echo $_POST['Usuario'];?>">            
            <button id="botEnviar">Guardar foto</button>
      </div>
		</form>

	</div>
	</div>
	</div>	