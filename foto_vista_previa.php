<?php
include_once("variables_globales.php");
function getHeight($image) {
	$size = getimagesize($image);
	$height = $size[1];
	return $height;
}
//You do not need to alter these functions
function getWidth($image) {
	$size = getimagesize($image);
	$width = $size[0];
	return $width;
}
$foto = $path_images."temp/".$_GET['foto'].".jpg";
$ancho = getWidth($foto);
$alto = getHeight($foto);
$Doc = $_GET['Doc'];
$Tipo = $_GET['Tipo'];
//$ancho=600;
//$alto=400;
//echo "foto: ".$foto."<br>";
//echo "ancho: ".$ancho."<br>";
//echo "alto: ".$alto."<br>";

?>
<html>
<head>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />

<script language="javascript" src="js/jquery.js"></script>
<script language="javascript" src="js/jquery-ui-1.8.9.custom.min.js"></script>
<script src="js/jquery.Jcrop.js"></script>

<script language="Javascript">

// Remember to invoke within jQuery(window).load(...)
// If you don't, Jcrop may not initialize properly
jQuery(window).load(function(){

	jQuery('#cropbox').Jcrop({
		onChange: showPreview,
		onSelect: showPreview,
		minSize: [500,500],
		maxSize: [800,600],
		setSelect: [500,500,50,50],
		aspectRatio: 1
	});

});

// Our simple event handler, called from onChange and onSelect
// event handlers, as per the Jcrop invocation above
function showPreview(coords)
{
	if (parseInt(coords.w) > 0)
	{
		var rx = 100 / coords.w;
		var ry = 100 / coords.h;

		jQuery('#preview').css({
			width: Math.round(rx * <?php echo $ancho;?>) + 'px',
			height: Math.round(ry * <?php echo $alto;?>) + 'px',//*/
			/*width: Math.round(rx * 100) + 'px',
			height: Math.round(ry * 100) + 'px',//*/
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	}
	showCoords(coords);
}
function showCoords(c)
{
	jQuery('#x').val(c.x);
	jQuery('#y').val(c.y);
	jQuery('#x2').val(c.x2);
	jQuery('#y2').val(c.y2);
	jQuery('#w').val(c.w);
	jQuery('#h').val(c.h);
};

$(document).ready(function(){

$(".botones").button();

$("#botonFoto").click(function(evento){
	$("#formEnviar").submit();

});

});//fin de la funcion ready

</script>

</head>

<body>
    <form action="foto_subir_final.php" method="post" target="webFoto" id="formEnviar">
		<table align="left" bgcolor="#CCCCCC">
		<tr>
		<td width="105"><div style="width:100px;height:100px;overflow:hidden;">
			<img src="images/temp/<?php echo $_GET['foto'];?>.jpg" id="preview" />
	    </div>
		</td>
   		<td width="383"><button class="botones" id="botonFoto">Guardar</button>
		</td>
		</tr>
        <tr>
        <td colspan="2">       
		<img src="images/temp/<?php echo $_GET['foto'];?>.jpg" id="cropbox" />
        </td>
		</tr>
		</table>
			<input type="hidden" size="4" id="x" name="x" />
			<input type="hidden" size="4" id="y" name="y" />
			<input type="hidden" size="4" id="x2" name="x2" />
			<input type="hidden" size="4" id="y2" name="y2" />
			<input type="hidden" size="4" id="w" name="w" />
			<input type="hidden" size="4" id="h" name="h" />
       		<input type="hidden" id="Doc" name="Doc" value="<?php echo $Doc;?>"/>
       		<input type="hidden" id="Tipo" name="Tipo" value="<?php echo $Tipo;?>"/>
	    <input name="foto" type="hidden" id="foto" value="<?php echo $_GET['foto'];?>">
</form>  	

	</body>
  

</html>
