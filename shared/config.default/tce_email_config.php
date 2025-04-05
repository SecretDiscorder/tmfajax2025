<?php
//============================================================+
// File name   : tce_email_config.php
// Begin       : 2001-10-20
// Last Update : 2023-03-XX
//
// Description : Default values for public variables of
//               C_mailer class (PHPMailer configuration)
//               
// Author: Nicola Asuni (dengan modifikasi)
//============================================================+

/**
 * @file
 * Email configuration file.
 * @package com.tecnick.tcexam.shared.cfg
 * @author Nicola Asuni (modifikasi oleh Anda)
 * @since 2005-02-24
 */

// -----------------------------------------------------------------------------
// --- GENERAL EMAIL SETTINGS --------------------------------------------------
// -----------------------------------------------------------------------------

// Email priority (1 = High, 3 = Normal, 5 = Low). Default value is 3.
$emailcfg['Priority'] = 1;

// Sets the CharSet of the message. Default value is 'UTF-8'.
$emailcfg['CharSet'] = 'UTF-8';

// Sets the Content-type of the message. 
// Untuk HTML gunakan 'text/html' (jika Anda menginginkan tampilan email dalam HTML)
$emailcfg['ContentType'] = 'text/html';

// Sets the Encoding of the message. Options: '8bit', '7bit', 'binary', 'base64', 'quoted-printable'.
$emailcfg['Encoding'] = '8bit';

// Sets the Encoding of the attachments. Default value is 'base64'
$emailcfg['AttachmentsEncoding'] = 'base64';

// Sets the default Administrator email. 
$emailcfg['AdminEmail'] = 'bimapust@bima-pustaka.my.id';

// Sets the From email address.
$emailcfg['From'] = 'bimapust@bima-pustaka.my.id';

// Sets the From name of the message.
$emailcfg['FromName'] = 'TCExam';

// Sets the Sender email of the message. (optional)
$emailcfg['Sender'] = 'bimapust@bima-pustaka.my.id';

// Sets 'Reply-To' address (optional)
$emailcfg['Reply'] = '';

// Sets 'Reply-To' name (optional)
$emailcfg['ReplyName'] = '';

// Sets word wrapping on the message. Default value is false.
$emailcfg['WordWrap'] = false;

// Method to send mail: ('mail', 'sendmail', or 'smtp').
$emailcfg['Mailer'] = 'smtp';

// Sets the path of the sendmail program.
$emailcfg['Sendmail'] = '/usr/sbin/sendmail';

// Turns Microsoft mail client headers on or off. Default is false.
$emailcfg['UseMSMailHeaders'] = true;

// Sets default value for Header of messages.
$emailcfg['MsgHeader'] = "
<"."?xml version=\"1.0\" encoding=\"#CHARSET#\"?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"#LANG#\" lang=\"#LANG#\" dir=\"#LANGDIR#\">
<body>
";

//Sets default value for Footer of messages.
$emailcfg['MsgFooter'] = '</body></html>';

// -----------------------------------------------------------------------------
// --- SMTP VARIABLES ----------------------------------------------------------
// -----------------------------------------------------------------------------

// Sets the SMTP hosts. 
$emailcfg['Host'] = 'server.hostdata.id';

// Sets the SMTP server port. For Gmail SMTP, gunakan 465 untuk SSL atau 587 untuk TLS.
$emailcfg['Port'] = 25;

// Sets the SMTP HELO. Default value is ''.
$emailcfg['Helo'] = '';

// Sets SMTP authentication. Untuk Gmail, harus diaktifkan.
$emailcfg['SMTPAuth'] = true;

// Sets the prefix to the server. Options are '', 'ssl' or 'tls'.
// Jika menggunakan port 465, gunakan 'ssl'. Jika menggunakan port 587, gunakan 'tls'.
$emailcfg['SMTPSecure'] = 'tls';

// Sets the SMTP server timeout in seconds.
$emailcfg['Timeout'] = 100;

// Sets SMTP class debugging on or off. Default value is false.
$emailcfg['SMTPDebug'] = false;

// -----------------------------------------------------------------------------
// --- EMAIL ACCOUNT CREDENTIALS (SMTP) --------------------------------------
// -----------------------------------------------------------------------------

// Username for SMTP authentication (Gmail address)

// Sets SMTP username. Default value is ''.
$emailcfg['Username'] = '';

// Sets SMTP password. Default value is ''.
$emailcfg['Password'] = '';
//============================================================+
// END OF FILE
//============================================================+
