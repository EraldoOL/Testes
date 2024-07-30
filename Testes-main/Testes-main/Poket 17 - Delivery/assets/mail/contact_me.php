<?php
// Import PHPMailer classes into the global namespace
// These must be at the to of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-6.0.2/src/PHPMailer.php';
require 'PHPMailer-6.0.2/src/SMTP.php';
require 'PHPMailer-6.0.2/src/Exception.php';

$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$phone = strip_tags(htmlspecialchars($_POST['phone']));
$subject = strip_tags(htmlspecialchars($_POST['subject']));
$message = strip_tags(htmlspecialchars($_POST['message']));

$site = 'https://www.metropoleweb.com';
$recipient = 'contato@metropoleweb.com';
$recipient_name = 'Metropole Web';

$recipient_subject = $subject;
$customer_subject = 'Recebi seu email e retorno em breve! - Metropole Web';

try {
	$recipient_html_msg = recipient_html_msg($site, $name, $email_address, $phone, $recipient_subject, $message);
	$recipient_text_msg = recipient_text_msg($site, $name, $email_address, $phone, $recipient_subject, $message);

	$customer_html_msg = customer_html_msg($name, $site);
	$customer_text_msg = customer_text_msg($name, $site);

	$mail = new PHPMailer(true);
	$email_customer = send_email($mail, $recipient, $recipient_name, $email_address, $name, $customer_subject, $customer_html_msg, $customer_text_msg);
	
	$mail = new PHPMailer(true);
	$email_recipient = send_email($mail, $recipient, $name, $recipient, $recipient_name, $recipient_subject, $recipient_html_msg, $recipient_text_msg);

	return $email_recipient && $email_customer;
} catch (Exception $e) {
	return false;
}

function send_email($mail, $from_email, $from_name, $to_email, $to_name, $subject, $html_msg, $text_msg) {
	$mail->CharSet = 'UTF-8';
	$mail->SetLanguage('br');

	//Recipients
	$mail->setFrom($from_email, $from_name);
	$mail->addAddress($to_email, $to_name);
	$mail->addReplyTo($from_email, $from_name);

	//Content
	$mail->isHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $html_msg;
	$mail->AltBody = $text_msg;

	return $mail->send();
}

function recipient_html_msg($site, $customer_name, $customer_email, $customer_phone, $subject, $message) {
	return "
		<!DOCTYPE html>
		<html lang='pt-br'>
		<head>
			<meta charset='utf-8' />
			<style>
				p { font-family: verdana, geneva, sans-serif; }
			</style>
		</head>
		<body>
			<p>Nova mensagem em <a href='$site'>$site</a></p>
			<p><strong>Nome:</strong></p>
			<p>$customer_name</p>
			<p><strong>Email:</strong></p>
			<p>$customer_email</p>
			<p><strong>Telefone:</strong></p>
			<p>$customer_phone</p>
			<p><strong>Assunto:</strong></p>
			<p>$subject</p>
			<p><strong>Mensagem:</strong></p>
			<p>$message</p>
		</body>
		</html>";
}

function recipient_text_msg($site, $customer_name, $customer_email, $customer_phone, $subject, $message) {
	return "
		Nova mensagem em $site\n
		Nome:\n
		$customer_name\n
		Email:\n
		$customer_email\n
		Telefone:\n
		$customer_email\n
		Assunto:\n
		$subject
		Mensagem:\n
		$message";
}

function customer_html_msg($customer_name, $site) {
	return "
		<!DOCTYPE html>
		<html lang='pt-br'>
		<head>
			<meta charset='utf-8' />
			<style>
				p { font-family: verdana, geneva, sans-serif; }
			</style>
		</head>
		<body>
			<p>Olá $customer_name, recebemos seu email e retornaremos em breve.</p>
			<p><a href='$site'>$site</a></p>
		</body>
		</html>";
	}

function customer_text_msg($customer_name, $site) {
	return "Olá $customer_name, recebemos seu email e retornaremos em breve.\n$site";
}