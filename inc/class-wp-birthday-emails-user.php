<?php

/**
 * This class to handling add one field to edit user and save in user_meta database
 */
class WPBirthdayemails_User {
	/**
	 * Add hook to show and save field.
	 */
	public function __construct() {
		//MOSTRAR CAMPOS CUANDO AÑADIMOS UN NUEVO USUARIO
		add_action( 'user_new_form', array( $this, 'show_field' ));
		add_action('user_register',array($this,'save_field'));
		
		add_action('admin_enqueue_scripts', array($this,'my_init_script'));
		
		add_action( 'personal_options', array( $this, 'show_field')); 
		add_action( 'personal_options_update', array( $this, 'save_field' ));
		add_action( 'edit_user_profile_update', array( $this, 'save_field' ));
		
	}

	public function my_init_script(){
		//style datepiker
		wp_enqueue_style('styleUI',WPBIRTHDAYEMAILS_URL.'/css/jqueryUI.css');

		//register script
		wp_register_script( 'jqueryUI', WPBIRTHDAYEMAILS_URL. '/js/jqueryUI.js' , __FILE__ );
		wp_register_script( 'datepiker', WPBIRTHDAYEMAILS_URL. '/js/script.js' , __FILE__ );
		//enqueue script
   		wp_enqueue_script('jqueryUI',WPBIRTHDAYEMAILS_DIR.'/js/jqueryUI.js',array('jquery'));
   		wp_enqueue_script('datepiker',WPBIRTHDAYEMAILS_DIR.'/js/script.js',array('jqueryUI'));

	}
	function register_my_script(){

	}


	/**
	 * Add more one field in table user to add birthday.
	 *
	 * @param $user
	 */
	public function show_field( $user ) { 		
		$birthday_email_ignore = (is_object($user)) ? get_user_meta($user->ID,'birthday_email_ignore', true ) : false;
		$birthday = (is_object($user)) ? get_user_meta($user->ID,'birthday', true ) : '';
		?>
		<tr>
				<th><h3><?php esc_html_e( 'Birthday', 'wp-birthday-emails' ) ?></h3></th>
				<td></td>
		</tr>
		<?php /*<table class="form-table">*/ ?>
			<tr>
				<th><label for="birthday_email_ignore"><?php esc_html_e( 'Ignore Birthday email', 'wp-birthday-emails' ); ?></label></th>
				<td>
					<label><input type="checkbox" <?php checked( $birthday_email_ignore,true, true ); ?>  value="1" name="birthday_email_ignore" id="birthday_email_ignore">
					 <?php esc_html_e( 'Check this field to don\'t send the email in the birthday date.', 'wp-birthday-emails' ) ?></label>
				</td>
			</tr>
			<tr>
				<th><label for="birthday"><?php esc_html_e( 'Birthday', 'wp-birthday-emails' ) ?></label></th>
				<td>
					<input type="text" name="birthday" id="birthday" value="<?php echo esc_attr( $birthday ); ?>" class="regular-text"/><br/>
                    <span class="description"> <?php esc_html_e( 'Please enter your birthday.', 'wp-birthday-emails' ) ?> <?php _e( 'Format', 'wp-birthday-emails' ) ?> <code>DD / MM / YYYY </code>.</span>
				</td>
			</tr>
		<?php /*</table>*/ ?>
		<?php
	}

	/**
	 * Save data in birthday field to database user_meta.
	 *
	 * @param $user_id
	 */
	public function save_field( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}
		
		update_user_meta( $user_id, 'birthday', $_POST['birthday'] );
		update_user_meta( $user_id, 'birthday_email_ignore', $_POST['birthday_email_ignore'] );
	}


}