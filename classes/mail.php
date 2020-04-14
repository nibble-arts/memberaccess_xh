<?php

namespace ma;

class Mail {

	public static function send($mail) {	

		if (isset($mail["to"]) && isset($mail["subject"]) && isset($mail["message"])) {

			// create header
			$header = "MIME-Version: 1.0\r\nContent-type: text/plain; charset=UTF-8\r\nFrom: " . Config::email_reply();

			// create subject
			$subject = '=?UTF-8?B?' . base64_encode($mail["subject"]) . '?=';

			// send mail
			$result = mail($mail["to"], $subject, $mail["message"], $header);

			return $failure;
		}
	}
}

?>