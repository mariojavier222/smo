<?php
/*
 * Created on 29/11/2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

error_reporting(E_ALL);

require("php4_Mail.php");
require("php4_AttachmentMail.php");
require("php4_Multipart.php");

$to = "you@company.com";
$addTo = "other@company.com";
$msgOK = "Send OK";
$msgFAILED = "Send FAILED";
$subject = $message = "Nothing";

/**
 * Send simple mails in several formats
 * Send 1: High priority and content in HTML (already have <html> tags)
 * Send 2: Normal priority and content in complete HTML (without have <html> tags)
 * Send 3: Low Priority and content in text format
 */
$mail = new Mail($to, $subject, "", "aaa@aaa.com.br");

$mail->setBodyHtml("<h1>".$message."</h1>");
$mail->setPriority(ABSTRACTMAIL_HIGH_PRIORITY);
if ($mail->send())
	echo $msgOK;
else
	echo $msgFAILED;

$mail->resetHeaders();
$mail->setHtml("<h1>".$message."</h1>");
$mail->setPriority(ABSTRACTMAIL_NORMAL_PRIORITY);
if ($mail->send())
	echo $msgOK;
else
	echo $msgFAILED;

$mail->resetHeaders();
$mail->setBodyText($message);
$mail->setPriority(ABSTRACTMAIL_LOW_PRIORITY);
if ($mail->send())
	echo $msgOK;
else
	echo $msgFAILED;
/**/

/**
 * Send mails with  attachments in several formats
 * Send 1: High priority and content in HTML (already have <html> tags)
 * Send 2: Normal priority and content in complete HTML (without have <html> tags)
 * Send 3: Low Priority and content in text format
 */
$mail2 = new AttachmentMail($to, $subject, "", "aaa@aaa.com.br");

$mp1 = new Multipart("attach/phpconference.jpg");
$mail2->addAttachment($mp1);
$mail2->addAttachment(new Multipart("attach/php.gif"));

$mail2->addTo($addTo);
$mail2->setBodyHtml("<h1>".$message."</h1>");
$mail2->setPriority(ABSTRACTMAIL_HIGH_PRIORITY);
if ($mail2->send())
	echo $msgOK;
else
	echo $msgFAILED;

$mail2->resetHeaders();
$mail2->setHtml("<h1>".$message."</h1>");
$mail2->setPriority(ABSTRACTMAIL_NORMAL_PRIORITY);
if ($mail2->send())
	echo $msgOK;
else
	echo $msgFAILED;

$mail2->resetHeaders();
$mail2->setBodyText($message);
$mail2->setPriority(ABSTRACTMAIL_LOW_PRIORITY);
if ($mail2->send())
	echo $msgOK;
else
	echo $msgFAILED;
/**/
?>