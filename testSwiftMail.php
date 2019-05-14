<?php
require_once 'vendor/autoload.php';

require 'config.php';

	// Create the Transport

	$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
		->setUsername($gmailUser)
		->setPassword($gmailpwd);
	

	// Create the Mailer using your created Transport
	$mailer = new Swift_Mailer($transport);

	// Create a message
	$message = new Swift_Message("sujet test Isa");
	$message->setFrom([$gmailUser => "isabelleFrom"]);
	$message->setTo([$gmailUser => "isabelleTo"]);
	$message->setBody( "Coucou isa !");
	  

	// Send the message
	$result = $mailer->send($message);
	var_dump($result);

