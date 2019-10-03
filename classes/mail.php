<?php

namespace ma;

class Mail {

	public static function send($mail) {	

		if (isset($mail["to"]) && isset($mail["subject"]) && isset($mail["message"])) {

			// create header
			$header = "MIME-Version: 1.0\r\nContent-type: text/plain; charset=UTF-8\r\nFrom: noreply@filmautoren.at";

			// create subject
			$subject = '=?UTF-8?B?' . base64_encode($mail["subject"]) . '?=';

			// send mail
			$result = mail($mail["to"], $subject, $mail["message"], $header);

			// mail sent
			if ($result) {
				$failure = true;
			}

			// mail error
			else {
				$failure = "confirm_mail_send_error";
			}

			return $failure;
		}
	}
}

?>