<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<link href="jQuery-Visualize-master/css/basic.css" type="text/css" rel="stylesheet" />
<link href="jQuery-Visualize-master/css/visualize.css" type="text/css" rel="stylesheet" />
<link href="jQuery-Visualize-master/css/visualize-light.css" type="text/css" rel="stylesheet" />
<!--<link href="jQuery-Visualize-master/css/visualize-dark.css" type="text/css" rel="stylesheet" />-->

<script type="text/javascript" src="jquery-1.9.0.min.js"></script>	

<script type="text/javascript" src="jQuery-Visualize-master/js/visualize.jQuery.js"></script>	

<script type="text/javascript">
$(document).ready(function(){
$('table').visualize();
$('table').visualize({type: 'pie', height: '300px', width: '420px'});
$('table').visualize({type: 'bar', width: '420px'});
$('table').visualize({type: 'area', width: '420px'});
$('table').visualize({type: 'line', width: '420px'});


$('table td')
		.click(function(){
			if( !$(this).is('.input') ){
				$(this).addClass('input')
					.html('<input type="text" value="'+ $(this).text() +'" />')
					.find('input').focus()
					.blur(function(){
						//remove td class, remove input
						$(this).parent().removeClass('input').html($(this).val() || 0);
						//update charts	
						$('.visualize').trigger('visualizeRefresh');
					});					
			}
		})
		.hover(function(){ $(this).addClass('hover'); },function(){ $(this).removeClass('hover'); });


	
})
</script>
</head>

<body>

<table>
	<caption>Grafico de Lakers</caption>
	<thead>
		<tr>
			<td></td>
			<th scope="col">food</th>
			<th scope="col">auto</th>
			<th scope="col">household</th>
			<th scope="col">furniture</th>
			<th scope="col">kitchen</th>
			<th scope="col">bath</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th scope="row">Mary</th>
			<td>190</td>
			<td>160</td>
			<td>40</td>
			<td>120</td>
			<td>30</td>
			<td>70</td>
		</tr>
		<tr>
			<th scope="row">Tom</th>
			<td>3</td>
			<td>40</td>
			<td>30</td>
			<td>45</td>
			<td>35</td>
			<td>49</td>
		</tr>
		<tr>
			<th scope="row">Brad</th>
			<td>10</td>
			<td>180</td>
			<td>10</td>
			<td>85</td>
			<td>25</td>
			<td>79</td>
		</tr>
		<tr>
			<th scope="row">Kate</th>
			<td>40</td>
			<td>80</td>
			<td>90</td>
			<td>25</td>
			<td>15</td>
			<td>119</td>
		</tr>		
	</tbody>
</table>

</body>
</html>
