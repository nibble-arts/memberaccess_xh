<?php

namespace ma;

class Newsletter {

	public static function send($subject, $text) {


		if ($subject != "" && $text != "") {

			$emails = [];
			$count = 0;

			// collect newsletter emails
			foreach (Users::get_users() as $user) {

				if($user->has_function("newsletter")) {
					$emails[] = $user->email();
				}
			}


			// send mails
			foreach ($emails as $email) {

				if (Mail::send([
					"to" => $email,
					"subject" => $subject,
					"message" => $text
				])) {
					$count++;
				}
			}

			Message::success("newsletter_sent");
			// Message::success($count . " " . Text::newsletter_count());
		}

		else {
			Message::failure("newsletter_sub_txt_missing");
		}

	}
}