<?php
header("Content-Type: application/vnd.ms-excel charset=iso-8859-1");
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=".$_REQUEST['archivo'].".xls");
// Fix for crappy IE bug in download.
header("Pragma: ");
header("Cache-Control: ");
?>
<html>
<head></head>
<body><?=utf8_decode($_REQUEST['datatodisplay'])?>
</body>
</html>
