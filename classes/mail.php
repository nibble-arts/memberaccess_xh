<?php

namespace ma;

class Mail {

	public static function send($mail) {	

		if (isset($mail["to"]) && isset($mail["subject"]) && isset($mail["message"])) {

			// create header
			$header = "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html; charset=UTF-8\r\n";
			$header .= "From: " . Config::email_reply();
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			// create subject
			$subject = '=?UTF-8?B?' . base64_encode($mail["subject"]) . '?=';

			// send mail
			$result = mail($mail["to"], $subject, $mail["message"], $header);

			return $failure;
		}
	}
}

?>