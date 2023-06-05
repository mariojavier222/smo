<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ejemplo de Login</title>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.4.custom.min.js"></script>
<style>
#wrapper{
	text-align: center;
}
 
#toppanel {
	position: absolute;
	top: 134px;
	width: 612px;
	z-index: 25;
	text-align: center;
	margin-left: auto;
	margin-right: auto;
}
 
#panel {
	width: 612px;
	position: relative;
	top: 1px;
	height: 400px;
	margin-left: auto;
	margin-right: auto;
	height: 0px;
	z-index: 10;
	overflow: hidden;
	text-align: left;
}
 
#panel_contents {
	height: 100%;
	width: 616px;
	position: absolute;
	z-index: -1;
}
 
#show_button {
	position: relative;
	top: -30px;
	left: 460px;
}
 
#hide_button {
	position: relative;
	font-size: 90%;
	font-weight: bold;
	z-index: 26;
	margin-top: -36px;
}
</style>
<script type="text/javascript"><!--
	$(document).ready(function() {
		$("div#show_button").click(function(){
			$("div#show_button").toggle();
			$("div#panel").animate({
				height: "460px"
			}, "fast", "swing", function() {$("div#hide_button").toggle();});
 
		});
 
		$("div#hide_button").click(function(){
			$("div#hide_button").toggle();
			$("div#panel").animate({
				height: "0px"
			}, "fast", "swing", function(){$("div#show_button").toggle();});
 
		});
 
	});
--></script>
</head>

<body>
<div id="wrapper">
    <div id="panel">
        <div id="panel_contents">
		<div class="border" id="login">
        <p>Username:
          <input type="text" size="15" name="username" id="username" />
          <br />
          Password:
          <input type="password" size="15" name="password" id="password" />
          <br />
          <input type="button" accesskey="l" id="login_btn" name="login" value="Login" />
        </p>
      </div>
        </div>
    </div>
    <div id="show_button"><a href="#">Login</a></div>
    <div id="hide_button"><a href="#">Esconder</a></div>
</div>
</body>
</html>
