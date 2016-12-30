<?php

/**
 * This class to handling scheduled send mail to user .
 */
class WPBirthdayemails_Cron {

	/**
	 * Add hook and run cron send mail.
	 */
	public function __construct() {
		$timestamp = wp_next_scheduled( 'wpbirthdayemails_send_cron' );
		if ( ! $timestamp ) {
			wp_schedule_event( current_time( 'timestamp'), 'daily', 'wpbirthdayemails_send_cron' );
		}

		add_action( 'wpbirthdayemails_send_cron', array( $this, 'send_mails' ) );
	}

	//REPLACE ATTR CONTENT {ELEMTENT}
	public static function replace_content($content,$email){
		//variables user
		$micontent = $content;
		$user = get_user_by( 'email', $email);
		$nick = $user->nickname;
		$first = $user->first_name;
		$last = $user->last_name;
		$birth = $user->user_birth;

		$attr = '';
		$user = '';
		$content = explode('{',$content);
		for($i=0; $i<count($content);$i++){
			$content[$i] = explode('}',$content[$i]);
			for($j=0; $j<count($content[$i]);$j++){
				//search attr elements in {}
				$attr = $content[$i][$j];
				switch ($attr) {
					case 'user_email':
						$micontent = str_replace('{user_email}',$email, $micontent);
					break;
					case 'nickname':
						$micontent = str_replace('{nickname}',$nick, $micontent);
					break;
					case 'first_name':
						$micontent = str_replace('{first_name}',$first, $micontent);
					break;
					case 'last_name':
						$micontent = str_replace('{last_name}',$last, $micontent);
					break;
					case 'user_birth':
						$micontent = str_replace('{user_birth}',$birth, $micontent);
					break;
				}
			}
		}

		//refresh content attr
		return $micontent;
	}	

	/**
	 * Send mail to all user birthday is today.
	 */
	public function send_mails() {
		//data email
		$option  = get_option( 'wp-birthday-emails');
		$content_temp = '';
		//data mail
		$subject = isset( $option['title'] ) ? $option['title'] : '';
		$content = isset( $option['content'] ) ? $option['content'] : '';
		$emails  = WPBirthdayemails_List_User::get_list_user_birthday();
		//foreach
		for($i=0; $i<count($emails);$i++){
			$content_temp =self::replace_content($content,$emails[$i]);
			wp_mail( $emails[$i], $subject, $content_temp, array( 'Content-Type: text/html; charset=UTF-8' ) );
			sleep(2);
		}
	}
}