<title>Seleccionar fecha con jQuery UI</title>
   <link type="text/css" href="css/le-frog/jquery-ui-1.8.1.custom.css" rel="Stylesheet" />   
   <script type="text/javascript" src="../../jquery-1.4.2.min.js"></script>
   <script type="text/javascript" src="js/jquery-ui-1.8.1.custom.min.js"></script>
   <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
<script>
$(document).ready(function(){
   $("#campofecha").datepicker({
      showOn: 'both',
      buttonImage: 'calendar.png',
      buttonImageOnly: true,
      changeYear: true,
      numberOfMonths: 2,
      onSelect: function(textoFecha, objDatepicker){
         $("#mensaje").html("<p>Has seleccionado: " + textoFecha + "</p>");
      }
   });
})
</script>
<form>
   Fecha: <input type="text" name="fecha" id="campofecha">
</form>

<div id="mensaje"></div>


<a href="#" id="cambiames">Mostrar formulario para cambiar mes</a>
<script>
$(document).ready(function(){
   $("#cambiames").click(function(){
      $("#campofecha").datepicker( "option", "changeMonth", true );
   });
});
</script>
