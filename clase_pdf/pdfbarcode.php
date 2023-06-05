<?php
/*======================================================================
 PDFBarcode
 http://www.grana.to/pdfbarcode

 Copyright (C) 2004 Valerio Granato (valerio at grana.to)
 Last Modified: 2004-06-01 23:23 CEST

 DESCRIPTION:
 A function to add barcodes to PDF documents created using Cpdf, a class
 by Wayne Munro <pdf@ros.co.nz> - http://www.ros.co.nz/pdf/.
 It takes in input the text and the bars coded as GNU-barcode
 (http://www.gnu.org/software/barcode/) and outputs an object id to
 place on your PDF document.

 WARRANTY:
 This library is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

 LICENCE
 You can do what you want with these files.
 The only thing you can't do, without author written permission,
 is to sell it or ask money for usage.

 If you find it useful, please donate. Donations will help the
 development of this and other function/classes.
 To donate go to http://www.grana.to/pdfbarcode .
 If you need customization send me a mail.

 Author:  Valerio Granato (valerio at grana.to)
 Version: 0.92
 Package: PDFBarcode

 Thanks to:
 Tibor Thurnay <tibor.thurnay at swr3.de>
 - genbarcode added to programs needed to run the example
 - now barcodes can be rotated: 0°, 90°, 180°, 270°

 USAGE:
 Call the function using
   $id = barcode($pdf, $text, $bars, $scale, $fontscale, $xstart, $ystart, $font, $rotation);

 where:
   $pdf is the Cpdf object (http://www.ros.co.nz/pdf/)

   $text is the genbarcode text as below (ISBN barcode 1234567890):
    '0:12:9 12:12:7 19:12:8 26:12:1 33:12:2 40:12:3 47:12:4 59:12:5 66:12:6 73:12:7 80:12:8 87:12:9 94:12:7',

   $bars is the size of bars as below (ISBN barcode 1234567890):
    '9a1a1312312112222122114111321a1a1123111141312121331121312a1a',

    From GNU-barcode manual (http://www.gnu.org/software/barcode/):

    <cite>
    The bar-string:
    Read char by char, all odd chars represent a space, all even a bar:
    <space-width><bar-width><space-width><bar-width>...

    An alphabetic character defines a tall bar, all others small ones.

    The character-string:
    <position>:<font-size>:<character> ...
    </cite>

   $scale is the generated barcode scale, from 1 to... infinite (what printer do you have? :-))
   $fontscale is a value that will be subtracted from the genbarcode font size. Tipically you will
    need it for ISBN or EAN13: for these encodings try a value of 3 or 4.

   $xstart is the horizontal start of the barcode, in user point.
   $ystart (as $xstart, but remember that pdf documents have the 0,0 in bottom left corner)
   $font is the server path of an .afm font file.
   $rotation is 0,1,2,3 to have bars rotated at 0° degrees, 90°, 180°, 270°.

 The function generate a Cpdf object that you could put on your page
 using addObject($id).

 A simple usage example - to use this example you NEED:
 - GNU-Barcode
 - Cpdf (see links above)
 - genbarcode  by Folke Ashberg (http://www.ashberg.de/php-barcode/download/)

<?php
include ('class.ezpdf.php');
$pdf =& new Cezpdf('a4');

$fp=popen('/usr/bin/genbarcode 1234567890', "r");
$bars=fgets($fp, 1024);
$text=fgets($fp, 1024);
$encoding=fgets($fp, 1024);
pclose($fp);

if ($encoding == 'ISBN' or $encoding == 'EAN-13') $fontscale = 4;

$id = barcode($pdf, $text, $bars, '1', $fontscale, '100', '500', './fonts/Helvetica.afm', 0);

$pdf->addObject($id);
$pdf->ezStream();
?>
*/

function barcode(&$pdf, $text, $bars, $scale, $fontscale, $xstart, $ystart, $font, $rotation=0) {

    $id = $pdf->openObject();

    if ($scale<1) {
        $scale=1;
    }

    $y=(int)$scale * 60;
    $spacebottom = 2*$scale;

    $short=round($y-($scale*10));
    $long=round($y-$spacebottom);

    $xpos = 0;
    $ypos = $ystart;
    $txtstart = 0;
    $maxbarspace = 0;

    switch ($rotation) {
       case 1:
        $angle = 90; break;
       case 2:
        $angle = 180; break;
       case 3:
        $angle = 270; break;
       default:
        $angle = 0;
    }

    for ($i=0;$i<strlen($bars);$i++){

        $barspace = $bars[$i]*$scale;

        if ($barspace > $maxbarspace) {
            $maxbarspace = $barspace;
        }

        $i++;
        @$barwidth=strtolower($bars[$i]);

        if (ereg("[a-z]", $barwidth)){
            $barwidth=ord($barwidth)-ord('a')+1;
            $height=$long;
            $txtstart += $barwidth*$scale;
        } else {
            $height=$short;
        }

        $barwidth = $barwidth*$scale;

        $pdf->setColor(1,1,1);
        if ($angle == 0) {
            $pdf->filledRectangle($xstart+$xpos, $ystart-$long, $barspace, $long);
            $xpos += $barspace;

            $pdf->setColor(0,0,0);
            $pdf->filledRectangle($xstart+$xpos, $ystart-$height, $barwidth, $height);
            $xpos += $barwidth;
        } elseif ($angle == 90) {
            $pdf->filledRectangle($xstart-$long, $ypos ,$long, $barspace);
            $ypos += $barspace;

            $pdf->setColor(0,0,0);
            $pdf->filledRectangle($xstart-$height, $ypos, $height, $barwidth);
            $ypos += $barwidth;
        } elseif ($angle == 180) {
            $pdf->filledRectangle($xstart+$xpos-$barspace, $ystart, $barspace, $long);
            $xpos -= $barspace;

            $pdf->setColor(0,0,0);
            $pdf->filledRectangle($xstart+$xpos-$barwidth, $ystart, $barwidth, $height);
            $xpos -= $barwidth;
        } elseif ($angle == 270) {
            $pdf->filledRectangle($xstart , $ypos ,$long, $barspace);
            $ypos += $barspace;

            $pdf->setColor(0,0,0);
            $pdf->filledRectangle($xstart, $ypos, $height, $barwidth);
            $ypos += $barwidth;
            }
    }

    $pdf->selectFont($font);

    $chars=explode(" ", $text);

    foreach($chars AS $c){
        $ar=explode(":", $c);
        $fontsize=($ar[1]-$fontscale)*$scale;

        if ($angle == 0) {
            $xtxtpos = $xstart+($ar[0]*$scale)+$txtstart/3;
            $pdf->addText($xtxtpos,$ystart-$long, $fontsize, "<b>$ar[2]</b>", 0);
        } elseif ($angle == 90) {
            $ytxtpos = $ypos-($ar[0]*$scale)-$txtstart/3;
            $pdf->addText($xstart-$long,$ytxtpos, $fontsize, "<b>$ar[2]</b>", 90);
        } elseif ($angle == 180) {
            $xtxtpos = $xstart-($ar[0]*$scale)-$txtstart/3;
            $pdf->addText($xtxtpos,$ystart+$long, $fontsize, "<b>$ar[2]</b>", 180);
        } elseif ($angle == 270) {
            $ytxtpos = $ystart+($ar[0]*$scale)+$txtstart/3;
            $pdf->addText($xstart+$long,$ytxtpos, $fontsize, "<b>$ar[2]</b>", 270);
        }
    }

    $pdf->closeObject($id);

    return $id;
}
?>