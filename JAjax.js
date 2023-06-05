// JavaScript Document

//Comportamientos comunes en Java Script


        
function abrir_menu(){
//    $("#mostrarCuadro").attr("class", "mostrarBuscador");
////    $("#mostrarCuadro").slideToggle();
}
$(document).ready(function(){
	
    $.validator.addMethod("fechaCompleta",
        function(value, element) {
            // put your own logic here, this is just a (crappy) example
            return value.match(/^\d\d?\/\d\d?\/\d\d\d\d$/);
        },
        "Ingrese una fecha con formato dd/mm/aaaa"
        );
	
    jQuery.fn.correo = function()	{
        if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(this).val()))	{
            return true;
        }else{
            //alert("No es correo electronico");
            $(this).focus();
            return false;
        }
    }
    	
    $(".menu_opciones").click(function(evento){
        evento.preventDefault();
        $("#cargando").show();
        vComando = this.id;
        $.ajax({
            cache: false,
            async: false,			
            url: vComando + ".php",
            success: function (data){
                $("#principal").html(data);
                abrir_menu();
                $("#cargando").hide();
            }
        });//fin ajax
    });//*/
	$(".tablaMenu").click(function(evento){
        evento.preventDefault();
        $("#cargando").show();
        vComando = this.id;
        $.ajax({
            cache: false,
            async: false,			
            url: vComando + ".php",
            success: function (data){
                $("#principal").html(data);
                abrir_menu();
                $("#cargando").hide();
            }
        });//fin ajax
    });//*/
    $(".acceso_directo").click(function(evento){
        evento.preventDefault();
        $("#cargando").show();
        vComando = this.id;
        //alert(vComando);
        $.ajax({
            cache: false,
            async: false,			
            url: vComando + ".php",
            success: function (data){
                $("#principal").html(data);
                $("#cargando").hide();
            }
        });//fin ajax
    });//*/

    $("#VerMensajes").click(function(evento){
        evento.preventDefault();
        $("#cargando").show();
        $.ajax({
            cache: false,
            async: false,			
            url: "cargarBandeja.php",
            success: function (data){
                $("#principal").html(data);
                $("#cargando").hide();
            }
        });//fin ajax
    });//*/

    $("a.texto.noticia").click(function(evento){
        evento.preventDefault();		
        vID = this.id;
        //alert(vID);
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);
            },
            data: {
                opcion: 'mostrarNoticiaSola', 
                ID: vID
            },
            url: 'cargarOpciones.php',
            success: function(data){ 
                $("#principal").html('<br /><br />');
                $("#principal").append(data);
            }
        });//fin ajax//*/
    });//*/
	
    $("#cargando").ajaxStart(function(){
        $(this).show();
    });
    $("#cargando").ajaxSuccess(function(){
        $(this).hide();
    });
    $("#cargando").ajaxStop(function(){
        $(this).hide();
    });//*/

	
});//fin de la funcion ready

function mostrarCartel(cuerpo, titulo){
	
	//cuerpo = htmlEntities(cuerpo);
	jAlert(cuerpo, titulo);
}//fin function

function mostrarAlerta(cuerpo, titulo){
    cuerpo = "<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
    $("#dialog").html(cuerpo);
	$('#dialog').dialog('option', 'position', 'center');
    $("#dialog").dialog({
        draggable: false, 
        hide: 'drop', 
        title: titulo, 
        zIndex: 3900, 
        resizable: false, 
		//width: 500,
		//height:300,
        modal: true,
		buttons: {
            'Aceptar': function() {
                $(this).dialog('close');
            }
        }//fin buttons
    });//fin dialog
    
}//fin funcion

function mostrarAlertaGrande(cuerpo, titulo){
    cuerpo = "<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
    $("#dialog").html(cuerpo);
	$('#dialog').dialog('option', 'position', 'center');
    $("#dialog").dialog({
        draggable: false, 
        hide: 'drop', 
        title: titulo, 
        zIndex: 3900, 
        resizable: false, 
		width: 500,
		height:300,
        modal: true,
		buttons: {
            'Aceptar': function() {
                $(this).dialog('close');
            }
        }//fin buttons
    });//fin dialog
    
}//fin funcion


function mostrarAlerta1(cuerpo, titulo){
    cuerpo = "<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
    $("#dialog").html(cuerpo);
    $("#dialog").dialog({
        draggable: true, 
        hide: 'slide', 
        title: titulo, 
        zIndex: 3900, 
        resizable: false, 
        modal: true
    });//fin dialog
    setTimeout('$("#dialog").dialog("close");',1500);
}//fin funcion



/*function mostrarMensaje(cuerpo, titulo){
	cuerpo = "<p>" + cuerpo + "</p>";
	$("#dialog").html(cuerpo);
//	$("#dialog").dialog();
		$("#dialog").dialog({ height: 'auto',draggable: true, hide: 'slide', title: titulo, zIndex: 3900, resizable: false, modal: true, 
			buttons: {
					'Aceptar': function() {
					$(this).dialog('close');
				}
			}//fin buttons
 		});//fin dialog///
}//fin funcion//*/

function validarNumero(numero){
    if (!/^([0-9])*$/.test(numero))
        return false;
    else
        return true;
}//fin funcion


jQuery.fn.extend({
    everyTime: function(interval, label, fn, times, belay) {
        return this.each(function() {
            jQuery.timer.add(this, interval, label, fn, times, belay);
        });
    },
    oneTime: function(interval, label, fn) {
        return this.each(function() {
            jQuery.timer.add(this, interval, label, fn, 1);
        });
    },
    stopTime: function(label, fn) {
        return this.each(function() {
            jQuery.timer.remove(this, label, fn);
        });
    }
});


jQuery.extend({
    timer: {
        guid: 1,
        global: {},
        regex: /^([0-9]+)\s*(.*s)?$/,
        powers: {
            // Yeah this is major overkill...
            'ms': 1,
            'cs': 10,
            'ds': 100,
            's': 1000,
            'das': 10000,
            'hs': 100000,
            'ks': 1000000
        },
        timeParse: function(value) {
            if (value == undefined || value == null)
                return null;
            var result = this.regex.exec(jQuery.trim(value.toString()));
            if (result[2]) {
                var num = parseInt(result[1], 10);
                var mult = this.powers[result[2]] || 1;
                return num * mult;
            } else {
                return value;
            }
        },
        add: function(element, interval, label, fn, times, belay) {
            var counter = 0;
			
            if (jQuery.isFunction(label)) {
                if (!times) 
                    times = fn;
                fn = label;
                label = interval;
            }
			
            interval = jQuery.timer.timeParse(interval);

            if (typeof interval != 'number' || isNaN(interval) || interval <= 0)
                return;

            if (times && times.constructor != Number) {
                belay = !!times;
                times = 0;
            }
			
            times = times || 0;
            belay = belay || false;
			
            if (!element.$timers) 
                element.$timers = {};
			
            if (!element.$timers[label])
                element.$timers[label] = {};
			
            fn.$timerID = fn.$timerID || this.guid++;
			
            var handler = function() {
                if (belay && this.inProgress) 
                    return;
                this.inProgress = true;
                if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
                    jQuery.timer.remove(element, label, fn);
                this.inProgress = false;
            };
			
            handler.$timerID = fn.$timerID;
			
            if (!element.$timers[label][fn.$timerID]) 
                element.$timers[label][fn.$timerID] = window.setInterval(handler,interval);
			
            if ( !this.global[label] )
                this.global[label] = [];
            this.global[label].push( element );
			
        },
        remove: function(element, label, fn) {
            var timers = element.$timers, ret;
			
            if ( timers ) {
				
                if (!label) {
                    for ( label in timers )
                        this.remove(element, label, fn);
                } else if ( timers[label] ) {
                    if ( fn ) {
                        if ( fn.$timerID ) {
                            window.clearInterval(timers[label][fn.$timerID]);
                            delete timers[label][fn.$timerID];
                        }
                    } else {
                        for ( var fn in timers[label] ) {
                            window.clearInterval(timers[label][fn]);
                            delete timers[label][fn];
                        }
                    }
					
                    for ( ret in timers[label] ) break;
                    if ( !ret ) {
                        ret = null;
                        delete timers[label];
                    }
                }
				
                for ( ret in timers ) break;
                if ( !ret ) 
                    element.$timers = null;
            }
        }
    }
});//*/

if (jQuery.browser.msie)
    jQuery(window).one("unload", function() {
        var global = jQuery.timer.global;
        for ( var label in global ) {
            var els = global[label], i = els.length;
            while ( --i )
                jQuery.timer.remove(els[i], label);
        }
    });
