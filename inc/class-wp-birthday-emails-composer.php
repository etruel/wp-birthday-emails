<?php

/**
 * This class to handling display form in admin page.
 */
class WPBirthdayemails_Composer {
	/**
	 * Add function to hook.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add more menu to sidebar left in admin.
	 */
	public function add_menu() {
		add_menu_page(
			esc_html__( 'Birthday Emails Compose', 'wp-birthday-emails' ),
			esc_html__( 'Birthday Emails', 'wp-birthday-emails' ),
			'manage_options',
			'wp-birthday-emails-composer',
			array( $this, 'form' ),
			WPBIRTHDAYEMAILS_URL . '/images/mail.png'
		);
	}

	/**
	 * Display form in admin page with field.
	 */
	public function form() {
		?>
		<div class="wrap">
			<form method="POST" action="options.php">
				<?php
				settings_fields( 'wpbirthdayemails_setting' );
				do_settings_sections( 'wp-birthday-emails-composer' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register setting two field , title and content.
	 */
	public function register_settings() {
		register_setting( 'wpbirthdayemails_setting', 'wp-birthday-emails' );

		add_settings_section(
			'general',
			esc_html__( 'Componer emails a enviar', 'wp-birthday-emails' ),
			'',
			'wp-birthday-emails-composer'
		);

		add_settings_field(
			'title',
			esc_html__( 'Titulo', 'wp-birthday-emails' ),
			array( $this, 'render_title' ),
			'wp-birthday-emails-composer',
			'general'
		);

		add_settings_field(
			'content',
			esc_html__( 'Contenido', 'wp-birthday-emails' ),
			array( $this, 'render_content' ),
			'wp-birthday-emails-composer',
			'general'
		);
	}

	/**
	 * Output field title.
	 */
	public function render_title() {
		$option = get_option( 'wp-birthday-emails' );
		$title  = isset( $option['title'] ) ? $option['title'] : '';
		echo '<input name="wp-birthday-emails[title]" type="text" class="widefat" value="' . esc_attr( $title ) . '">';
	}

	/**
	 * Output field content.
	 */
	public function render_content() {
		$option  = get_option( 'wp-birthday-emails' );
		$content = isset( $option['content'] ) ? $option['content'] : '';
		wp_editor( $content, 'wpbirthdayemails_content', array(
			'textarea_name' => 'wp-birthday-emails[content]',
		));
		echo '<br><strong>Etiquetas permitidas:</strong><br>';
		echo '<p id="emailtags"><span>{first_name}</span>  <span>{last_name}</span>  <span>{nickname}</span>  <span>{user_email}</span>  <span>{user_birth}</span></p>';

	}

}