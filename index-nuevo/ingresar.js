
/* JQUERY Cookies Plugin */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') {
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1).replace(/\+/g, '%20'));
                    break;
                }
            }
        }
        return cookieValue;
    }
};




/* ERROR CARTEL JQUERY PLUGIN by Diego */
;(function ($) {

	var w = [];

	$.msgError = function (element, options) {
		return $.msgError.impl.init(element, options);
	};

	$.msgError.close = function () {
		$.msgError.impl.close();
	};

	$.fn.msgError = function (options) {
		return $.msgError.impl.init(this, options);
	};

	$.msgError.defaults = {
		mensaje    : 'ERROR. ¡Por favor reportar!',
        container  : '#access_box',
        leftOffset : 5,
        topOffset : 0
	};

	$.msgError.impl = {

		o: null,
		d: {},
		e: null,

		init: function (element, options) {
			var s = this;
			if (s.d.data){ return false; }
			s.o = $.extend({}, $.msgError.defaults, options);
			s.e = element;
			s.create(element);
			s.setPosition(element);
            s.show();
			return s;
		},

		create: function (element) {
			var s = this;
			w = s.getDimensions(s.e);
			s.d.flag = $('<div></div>').attr('class','flag-error').attr('data-element',s.e.attr('name')).appendTo(s.o.container);
			s.d.msg = $('<div></div>').html(s.o.mensaje).appendTo(s.d.flag);
		},

		getDimensions: function (element) {
			var el = element;
			if(element.offset() == null){
				var h = $.browser.opera && $.browser.version > '9.5' && $.fn.jquery <= '1.2.6' ? document.documentElement['clientHeight'] :
					$.browser.opera && $.browser.version < '9.5' && $.fn.jquery > '1.2.6' ? window.innerHeight :
					el.height();
				return [h, el.width()];
			}else{
				return [el.outerHeight(), el.outerWidth()];
			}
		},

		setPosition: function (element) {
			var s = this, top, left, oLeft = 0, oTop = 0, Contenedor = $(s.o.container),
                FlagDim = s.getDimensions(s.d.flag),
				he = w[0]/2,
				ve = w[1];

			if(element.offset() != null && Contenedor.offset() != null){
				oLeft = element.offset().left - (Contenedor.offset().left + 10);
				oTop = element.offset().top - (Contenedor.offset().top + 10);
                oLeft += (ve + s.o.leftOffset) + 10;
                oTop += ((he - (FlagDim[0]/2)) + s.o.topOffset);
			}
			s.d.flag.css({left: oLeft, top: oTop});
		},

        show: function (){
            var s = this;
            s.d.flag.css({opacity:0});
            s.d.flag.animate({opacity: 1,left: "-=10"}, 300);
        },

		close: function () {
			var s = this;
            if(s.d.flag){
                s.d.flag.hide().remove();
                s.d = {};
            }
		}
	};
})(jQuery);
if(window.location.hostname == 'dattatec.biz'){
	window.location = 'https://donweb.com/ingresar?org=.biz';
}

var _gaq = [
  [ '_setAccount', 'UA-457299-1' ],
  [ '_trackPageview' ]
];

var mac_id = $.cookie("mac_id");

var AccesoH = {
    init: function(){
        $('#access_box [data-name="user"]').focus();

        $('#access_box').on('keypress.access_box','[data-name="user"],[data-name="pass"]',function(e) {
            //e.preventDefault();
            if (e.which == 13) {
                $('#access_box .bt_ingresar').click();
            }
        }).on('keypress.access_box','input[name="forget"]',function(e) {
            //e.preventDefault();
            if (e.which == 13) {
                $('#access_box .bt_forget').click();
            }
        }).on('blur.access_box','input',function(e){
            $(this).removeClass('error');
            if($('.error').size() < 1 || $(this).attr('name') == $('.flag-error').attr('data-element') || $(this).attr('name') == 'email2'){
                AccesoH.remover_errors();
            }
            if($(this).attr('name') == 'email' && $.trim($(this).val()) != ''){
                var oEmail = $(this);
                var data = {Email:$.trim(oEmail.val())};
                $(this).removeClass('UserNoDisp');
                $.ajax({
                    url: '/ajax-check-user.php',
                    dataType: 'json',
                    type:'POST',
                    data: data
                }).done(function(a){
                        if(a.root.site.disponible == 'NO'){
                            oEmail.addClass('error').msgError({mensaje:'Este email ya está siendo utilizado.<br />Si eres el propietario de la cuenta,<br />intenta restablecer tu contraseña.'});
                            oEmail.addClass('UserNoDisp');
                        }
                    });
            }
        }).on('click.access_box','.bt_ingresar',function(e){
            e.preventDefault();
            AccesoH.remover_errors();
            var User = $.trim($('#access_box [data-name="user"]').val());
            var Pass = $.trim($('#access_box [data-name="pass"]').val());
            if(User == ''){
                $('#access_box [data-name="user"]').addClass('error').msgError({mensaje:'Ingresa tu email o ID de cliente'});
            }else if(Pass == ''){
                $('#access_box [data-name="pass"]').addClass('error').msgError({mensaje:'Ingresa tu contraseña'});
            }else{
                $('#access_box .login_elements').fadeOut('fast',function(){
                    $('#access_box .login_spinner').show();
                    AccesoH.login(User,Pass);
                });
            }
        }).on('click.access_box','.bt_crear',function(e){
            e.preventDefault();
            AccesoH.remover_errors();
            var OKenvio = true,
            reg_exp = /[0-9a-z]([-_.+0-9a-z])*@[0-9a-zñáéíóúÇçãõ]([-.]?[0-9a-zñáéíóúÇçãõ])*\.[a-z]{2,7}/i,
            oEmail = $('#access_box input[name="email"]'),
            valEmail = $.trim(oEmail.val()),
            oEmail2 = $('#access_box input[name="email2"]'),
            valEmail2 = $.trim(oEmail2.val()),
            oPass = $('#access_box input[name="password"]'),
            valPass = $.trim(oPass.val());

            /* VALIDACIONES */
            if(valEmail == '' || !reg_exp.test(valEmail)){
                OKenvio = false;
                oEmail.addClass('error').msgError({mensaje:'Ingresa un email de contacto válido'});
            }

            if(OKenvio && valEmail != valEmail2){
                OKenvio = false;
                oEmail.addClass('error');
                oEmail2.addClass('error').parent().msgError({mensaje:'Los emails ingresados son diferentes'});
            }

            if(OKenvio && (valPass == '' || valPass.length < 8)){
                OKenvio = false;
                oPass.addClass('error').msgError({mensaje:'Elige una contraseña segura que<br />incluya 8 o más caracteres'});
            }

            if(OKenvio && $('.UserNoDisp').size() > 0){
                OKenvio = false;
                oEmail.addClass('error').msgError({mensaje:'Este email ya está siendo utilizado.<br />Si eres el propietario de la cuenta,<br />intenta restablecer tu contraseña.'});
            }

            if(OKenvio){
                $('#access_box .new_elements').fadeOut('fast',function(){
                    $('#access_box .new_spinner').show();
                    AccesoH.new_account(valEmail,valPass);
                });
            }
        }).on('click.access_box','.bt_forget',function(e){
            e.preventDefault();
            AccesoH.remover_errors();
            var oForget = $('#access_box [name="forget"]'),
                valForget = $.trim(oForget.val());
            if(valForget == ''){
                oForget.addClass('error').msgError({mensaje:'Ingresa el email asociado a tu Area de Cliente o<br />el dominio de alguno de los servicios de tu cuenta'});
            }else{
                $('#access_box .olvido_elements').fadeOut('fast',function(){
                    $('#access_box .olvido_spinner').show();
                    AccesoH.olvido(valForget);
                });
            }
        }).on('click.access_box','.forget_lk',function(e){
            e.preventDefault();
            $("#access_box").flip({
            	direction:'lr',
                content: $('#flip_olvido'),
                speed: 100,
                color: 'rgba(255,255,255,0.3)',
                onAnimation: function(){
                    AccesoH.remover_errors();
                },
                onEnd: function(){
                    $('#access_box input[name="forget"]').focus();
                }
            });
        }).on('click.access_box','.new_lk',function(e){
            e.preventDefault();
            $("#access_box").flip({
                direction:'rl',
                content: $('#flip_new'),
                speed: 100,
                color: 'rgba(255,255,255,0.3)',
                onAnimation: function(){
                    AccesoH.remover_errors();
                },
                onEnd: function(){
                    $('#access_box input[name="email"]').focus();
                }
            });
        }).on('click.access_box','.content_volver, .olvido_volver',function(e){
            e.preventDefault();
            $("#access_box").flip({
                direction:$(this).attr('rel'),
                content: $('#flip_login'),
                speed: 100,
                color: 'rgba(255,255,255,0.3)',
                onAnimation: function(){
                    AccesoH.remover_errors();
                },
                onEnd: function(){
                    $('#access_box [data-name="user"]').focus();
                }
            });
        }).on('click.access_box','.redes',function(e){
            e.preventDefault();
            AccesoH.loginOAUTH($(this).attr('rel'));
        });

    },

    remover_errors: function(){
        $('#access_box input').removeClass('error');
        $.msgError.close();
    },

    login: function(User,Pass,origen){
        if(typeof origen == 'undefined'){ origen = 'login' }
        var OAuthProvider = $('#OAuthProvider').val();
        var OAuthState = $('#OAuthState').val();
        var data = {account:'old',usernameLogin:User,passLogin:Pass,accion:'login',OAuthProvider:OAuthProvider,OAuthState:OAuthState};
        $.ajax({
            url: '/ajax-login.php',
            dataType: 'json',
            type:'POST',
            data: data
        }).done(function(a){
                if(origen == 'login'){
                    AccesoH.login_done(a);
                }else if(origen == 'new'){
                    AccesoH.new_login_done(a);
                }

        }).fail(AccesoH.login_fail);
    },

    login_fail: function(a){
        _gaq.push(['_trackEvent', 'INGRESAR', 'Login', 'AJAX FAIL']);
        alert('Error. Por favor reportar.');
    },

    login_done: function(a){
        if(a.root.site.login.logueado == 's'){
            _gaq.push(['_trackEvent', 'INGRESAR', 'Login', 'OK - Login']);
            var URL = 'https://donweb.com/ajax-login.php?redirect_adm=1&init_page='+InitPage;
            setTimeout(function(){
                $('#access_box [name="Login_form"]').attr("action",URL);
                $('#access_box [name="Login_form"]').submit();
            },500);
        }else if(a.root.site.login.logueado == 'n'){
            _gaq.push(['_trackEvent', 'INGRESAR', 'Login', 'DATOS INVALIDOS']);
            var ErrorSTR = 'Alguno de los datos no es correcto.<br />Por favor, verifícalo y vuelve a intentar.';
            $('#access_box .login_spinner').fadeOut('fast',function(){
                $('#access_box .login_elements').show();
                $('#access_box [data-name="user"],#access_box [data-name="pass"]').addClass('error');
                $('#access_box [data-name="user"]').parent().msgError({mensaje:ErrorSTR});
            });
        }else{
            _gaq.push(['_trackEvent', 'INGRESAR', 'Login', 'ERROR']);
            alert('Error. Por favor reportar.');
        }
    },

    new_account: function(User,Pass){
        var data = {Pais:codPaisActual, Idioma:langActual, Email:User, Email_check:User, Contrasena:Pass, Telefono:'', Privacidad:'SI', TyC:'SI',accion:'newuser'};
        $.ajax({
            url: '/ajax-new-user-2step.php',
            dataType: 'json',
            type:'POST',
            data: data
        }).done(AccesoH.new_done).fail(AccesoH.new_fail);
    },

    new_fail: function(a){
        _gaq.push(['_trackEvent', 'INGRESAR', 'Nueva Cuenta', 'AJAX FAIL']);
        alert('Error. Por favor reportar.');
    },

    new_done: function(a){
        var Resp = a.root.site;
        if(Resp.ok){
            _gaq.push(['_trackEvent', 'NUEVO CLIENTE', 'Pagina Ingresar', mac_id]);
            _gaq.push(['_trackEvent', 'INGRESAR', 'Nueva Cuenta', '']);
            AccesoH.login(Resp.datos.user,Resp.datos.pass,'new');
        }else if(Resp.error.length === undefined){
            _gaq.push(['_trackEvent', 'INGRESAR', 'Nueva Cuenta', 'Error 1']);
            $('#access_box .new_spinner').fadeOut('fast',function(){
                $('#access_box .new_elements').show();
                if(Resp.error['campo'] == 'mcNU_email' || Resp.error['campo'] == 'NU_email'){
                    $('#access_box input[name="email"]').addClass('error').parent().msgError({mensaje:Resp.error['texto']});
                }else if(Resp.error['campo'] == 'mcNU_pass_fake'){
                    $('#access_box input[name="password"]').addClass('error').msgError({mensaje:Resp.error['texto']});
                }else{
                    $('#access_box input[name="email"]').parent().parent().msgError({mensaje:Resp.error['texto']});
                }
            });
        }else if(Resp.error.length > 0){
            _gaq.push(['_trackEvent', 'INGRESAR', 'Nueva Cuenta', 'Error 2']);
            var Temp = '', ErrorSTR = '';
            for (i = 0; i < Resp.error.length; i++) {
                if(Resp.error[i]['campo'] == 'mcNU_email' || Resp.error[i]['campo'] == 'NU_email'){
                   $('#access_box input[name="email"]').addClass('error');
                }
                if(Resp.error[i]['campo'] == 'mcNU_pass_fake'){
                    $('#access_box input[name="password"]').addClass('error');
                }
                Temp = '- '+Resp.error[i]['texto']+'<br />';
                if (ErrorSTR != Temp){ ErrorSTR += Temp; }
            }
            $('#access_box .new_spinner').fadeOut('fast',function(){
                $('#access_box .new_elements').show();
                $('#access_box input[name="email"]').parent().parent().msgError({mensaje:ErrorSTR});
            });
        }
    },

    new_login_done: function(a){
        _gaq.push(['_trackEvent', 'INGRESAR', 'Login', 'OK - Nueva Cuenta']);
        $('#access_box .new_spinner').fadeOut('fast',function(){
            $('#access_box .new_result').show();
        });
    },

    olvido: function(User){
        var data = {op:'ask_confirm',search_value:User};
        $.ajax({
            url: 'https://administracion.donweb.com/ws/api.dattatec.php?jsoncallback=?',
            dataType: 'json',
            data: data
        }).done(AccesoH.olvido_done).fail(AccesoH.olvido_fail);
    },

    olvido_fail: function(a){
        _gaq.push(['_trackEvent', 'INGRESAR', 'Restablecer contraseña', 'AJAX FAIL']);
        alert('Error. Por favor reportar.');
    },

    olvido_done: function(a){
        var Error = '';
        if(a.result=='error'){
            switch(a.errno){
                case '1':
                    Error = 'Completa los datos solicitados.';
                    break;
                case '2':
                    Error = 'El email o dominio ingresado<br />no corresponden a un Area de Cliente.';
                    break;
                case '4':
                    Error = 'Ha ocurrido un error, vuelve<br />a intentarlo más tarde.';
                    break;
                case '8':
                    Error = 'Los datos ingresados son invalidos.';
                    break;
            }
            _gaq.push(['_trackEvent', 'INGRESAR', 'Restablecer contraseña', 'ERROR: '+Error]);
            $('#access_box .olvido_spinner').fadeOut('fast',function(){
                $('#access_box .olvido_elements').show();
                $('#access_box [name="forget"]').addClass('error').msgError({mensaje:Error});
            });
        }else if(a.result == 'ok'){
            _gaq.push(['_trackEvent', 'INGRESAR', 'Restablecer contraseña', 'Inicio OK']);
            $('#access_box .olvido_spinner').fadeOut('fast',function(){
                $('#access_box .olvido_result').show();
            });
        }
    },


    loginOAUTH: function(providerOAUTH){
        var opOAUTH, opOAUTHw, opOAUTHh;
        var urlOAUTH = 'https://donweb.com/'+urlIdiomaPais+'/login-'+providerOAUTH+'?origen=login';
        _gaq.push(['_trackEvent', 'INGRESAR', 'Acceso OAuth', 'Click '+providerOAUTH]);

        switch (providerOAUTH) {
            case "live":
                opOAUTHw = 650;
                opOAUTHh = 520;
                break;
            case "google":
                opOAUTHw = 780;
                opOAUTHh = 430;
                break;
            case "facebook":
                opOAUTHw = 650;
                opOAUTHh = 430;
                break;
            default:
                opOAUTHw = 780;
                opOAUTHh = 430;
                break;
        }
        opOAUTH = 'width='+opOAUTHw+',height='+opOAUTHh+',left='+(screen.width-opOAUTHw)/2+',top='+(screen.height-opOAUTHh)/2+',status=NO';
        var dattatecOAuth = window.open(urlOAUTH, 'dattatecOAuth', opOAUTH);
    }
};

function oAuthSuccess(oaprovider) {
    _gaq.push(['_trackEvent', 'INGRESAR', 'Acceso OAuth', 'Success '+oaprovider]);
    $('#access_box .login_elements').fadeOut('fast',function(){
        $('#access_box .login_spinner').show();
        setTimeout(function(){
            var URL = 'https://administracion.donweb.com/clientes/index.php?init_page='+InitPage;
            window.location.href=URL;
        },500);
    });
}

function ShowShare() {
     var Archivo = encodeURIComponent(fondo.file.replace('.jpg',''));
	 var Archivo = $.trim(Archivo);
    $('.linkDownload').html('<a title="Descargar este fondo" href="http://media.donweb.com/#'+Archivo+'" target="_blank">Descargar</a>');
    $('.linkShare ul')
        .append('<li><a title="Comparte la imagen en Facebook" class="shaFace" href="http://www.facebook.com/sharer.php?s=100&p[url]=http://donweb.com/fondos_ingresar/downloads/'+fondo.file+'&p[images][0]=https://donweb.com/fondos_ingresar/downloads/'+fondo.file+'&p[title]='+encodeURIComponent("Descargate el Wallpaper DonWeb de hoy")+'&p[summary]='+textIMG.alt+'" target="_blank">Facebook</a></li>')
        .append('<li><a title="Pinea la imagen en Pinterest" class="shaPint" href="http://pinterest.com/pin/create/button/?url=https://donweb.com/fondos_ingresar/downloads/'+encodeURIComponent(fondo.file)+'&media=https://donweb.com/fondos_ingresar/downloads/'+encodeURIComponent(fondo.file)+'&description='+encodeURIComponent(textIMG.alt)+'" target="_blank">Pinterest</a></li>')
        .append('<li><a title="Comparte la imagen en Twitter" class="shaTwi" href="https://twitter.com/intent/tweet?original_referer=https://donweb.com/fondos_ingresar/downloads/'+encodeURIComponent(fondo.file)+'&source=tweetlink&text='+encodeURIComponent("Descargate el Wallpaper DonWeb de hoy")+'&url=https://donweb.com/fondos_ingresar/downloads/'+encodeURIComponent(fondo.file)+'" data-via="DonWeb" data-lang="es" target="_blank">Twitter</a></li>')
        .append('<li><a title="Comparte la imagen en Google Plus" class="shaGoo" href="https://plus.google.com/share?url='+encodeURIComponent("https://donweb.com/fondos_ingresar/downloads/index.php?img=")+Archivo+encodeURIComponent("&tit=Descarga+este+Wallpaper+DonWeb")+encodeURIComponent("&desc=")+encodeURIComponent(textIMG.alt.replace(/ /g,"+"))+'" target="_blank">Google +</a></li>');
}

$(document).ready(function(){

    if($.cookie("login_userName") != null){
        window.location.href='https://administracion.donweb.com/clientes/index.php?ingresar=logued&init_page='+InitPage;
    }else{
        _gaq.push(['_trackEvent', 'INGRESAR', 'Visita', '']);

        $.getScript(ImageProtocol+'://'+ImageHostName+'/js/sp/ingresar_load_img.js');

        $('#access_box').html($('#flip_login').html());

        AccesoH.init();

        $('#content').on('click','#fdo_description_lk',function(e){
            _gaq.push(['_trackEvent', 'INGRESAR', 'Click Texto Fondo', $(this).children('img').attr('alt')]);
            var URL = $(this).attr('href');
            if($(this).attr('target') != '_blank'){
                e.preventDefault();
                setTimeout(function(){
                    window.location.href=URL;
                },300);
            }
        }).on('click','a.linkShare',function(e){
            e.preventDefault();
            $(".share-box ul").slideToggle('fast');
        }).on('click','.share-box ul li a',function(){
            _gaq.push(['_trackEvent', 'INGRESAR', 'IMG Share Click', $(this).attr('class')]);
            $(".share-box ul").slideUp('fast');
        }).on('click','.linkDownload a',function(){
            _gaq.push(['_trackEvent', 'INGRESAR', 'IMG Download Click', fondo.file]);
        });

        /* CARGA FUENTES GOOGLE WEB FONTS */
        WebFontConfig = {
            google: { families: [ 'Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700:latin' ] }
        };

        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);

    }

});