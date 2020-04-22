<?php

namespace ma;

class Newsletter {

	public static function send($subject, $text) {

		if ($subject != "" && $text != "") {


			$count = 0;
			$text = str_replace("\n", "<br>", $text);
			// collect emails
			$emails = self::get_addresses();

			// send mails
			foreach ($emails as $email) {

				$m = '<html><head></head><body>';
					$m .= '<p>' . Config::newsletter_title() . '</p><hr>';
					$m .= '<p>' . $text . '</p>';

					// add unsubscribe message
					$m .= View::unsubscribe($email["user"]);
				$m .= '</body></html>';
				
				if (Mail::send([
					"to" => $email["email"],
					"subject" => $subject,
					"message" => $m
				])) {
					$count++;
				}
				
				break;
			}

			
			// save newsletter history
			$path = Config::basepath() . "memberaccess/newsletter/";
			$file = $path . time() . "_newsletter.html";

			if (!file_exists($path)) {
				mkdir ($path);
			}

			file_put_contents($file, $text);

			// set success message
			Message::success("newsletter_sent");
			// Message::success($count . " " . Text::newsletter_count());
		}

		else {
			Message::failure("newsletter_sub_txt_missing");
		}

	}


	public static function get_addresses() {

		// collect newsletter emails
		foreach (Users::get_users() as $user) {

			if($user->has_function("newsletter")) {
				$emails[] = ["email" => $user->email(), "user" => $user->username()];
			}
		}

		return $emails;
	}


	// unsubscribe newsletter
	public static function unsubscribe($user) {

		if ($user = Users::get_user($user)) {

			// unsubscribe if is subscribed
			if ($user->has_function("newsletter")) {

				$user->remove_function("newsletter");
				Users::save();

				Message::success("newsletter_unsubscribed");
			}

			// user is not subscribed
			else {
				Message::failure("newsletter_not_subscribed");
			}
		}
	}
}