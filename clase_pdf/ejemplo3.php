<?php
/*======================================================================
 PDFBarcode - Usage example
 http://www.grana.to/pdfbarcode

 Copyright (C) 2004 Valerio Granato (valerio at grana.to)
 Last Modified: 2004-06-01 21:36 CEST

 Author:  Valerio Granato (valerio at grana.to)
 Version: 0.92
 Package: PDFBarcode

 Thanks to:
 Tibor Thurnay <tibor.thurnay at swr3.de>
 - genbarcode added to programs needed to run the example
 - now barcodes can be rotated: 0°, 90°, 180°, 270°


 A simple usage example - to use this example you NEED:
 - GNU-Barcode
 - Cpdf (see links above)
 - genbarcode by by Folke Ashberg (http://www.ashberg.de/php-barcode/download/)

*/

include ('class.ezpdf.php');
include ('pdfbarcode.php');
$pdf =& new Cezpdf('a5', 'landscape');

$fp=popen('/usr/bin/genbarcode 1234567890', "r");
$bars=rtrim(fgets($fp, 1024));
$text=rtrim(fgets($fp, 1024));
$encoding=rtrim(fgets($fp, 1024));
pclose($fp);

if (ereg('^(EAN|ISBN)', $encoding)) { $fontscale = 4; } else { $fontscale = 0; }

$id = barcode($pdf, $text, $bars, '1', $fontscale, '20', '400', 'fonts/Helvetica.afm');

// the x is 28 and not 20 to align vertically to the bars
// and not to the first char
$pdf->addText(28, 320, 12, $encoding. ' 0°'); 
$pdf->addObject($id);

$fp=popen('/usr/bin/genbarcode 87217254', "r");
$bars=rtrim(fgets($fp, 1024));
$text=rtrim(fgets($fp, 1024));
$encoding=rtrim(fgets($fp, 1024));
pclose($fp);

if (ereg('^(EAN|ISBN)', $encoding)) { $fontscale = 4; } else { $fontscale = 0; }

$id = barcode($pdf, $text, $bars, '1', $fontscale, '120', '200', 'fonts/Helvetica.afm', 1);

$pdf->addText(70, 275, 12, $encoding. ' 90°');
$pdf->addObject($id);

$fp=popen('/usr/bin/genbarcode 001200130014 128C', "r");
$bars=rtrim(fgets($fp, 1024));
$text=rtrim(fgets($fp, 1024));
$encoding=rtrim(fgets($fp, 1024));
pclose($fp);

if (ereg('^(EAN|ISBN)', $encoding)) { $fontscale = 4; } else { $fontscale = 0; }

$id = barcode($pdf, $text, $bars, '1', $fontscale, '300', '340', 'fonts/Helvetica.afm', 2);

$pdf->addText(200, 320, 12, $encoding. ' 180°');
$pdf->addObject($id);

$fp=popen('/usr/bin/genbarcode 0102030405', "r");
$bars=rtrim(fgets($fp, 1024));
$text=rtrim(fgets($fp, 1024));
$encoding=rtrim(fgets($fp, 1024));
pclose($fp);

if (ereg('^(EAN|ISBN)', $encoding)) { $fontscale = 4; } else { $fontscale = 0; }

$id = barcode($pdf, $text, $bars, '1', $fontscale, '200', '165', 'fonts/Helvetica.afm', 3);

$pdf->addText(200, 275, 12, $encoding. ' 270°');
$pdf->addObject($id);
$pdf->ezStream();
?>
