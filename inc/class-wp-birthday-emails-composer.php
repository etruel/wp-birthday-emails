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
		add_action( 'admin_footer', array($this,'reparaciones_load_scripts' ));
		add_action( 'wp_ajax_email_send_p',  array(__CLASS__,'email_send_p_callback' ));
		
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


	//funcion ajax para probar
	public static function email_send_p_callback(){
		check_ajax_referer('email_send_ajax' );
		$option  = get_option( 'wp-birthday-emails');
		$content_temp = '';
		//data mail
		$subject = isset( $option['title'] ) ? $option['title'] : '';
		$content = isset( $option['content'] ) ? $option['content'] : '';
		$emails  = WPBirthdayemails_List_User::get_list_user_birthday();
		for($i=0; $i<count($emails);$i++){
			$content_temp =WPBirthdayemails_Cron::replace_content($content,$emails[$i]);
			wp_mail( $emails[$i], $subject, $content_temp, array( 'Content-Type: text/html; charset=UTF-8' ) );
			sleep(2);	
		}
		echo 'enviado';
	}

	//funcion para llamar a un javascript
	public  function reparaciones_load_scripts(){
		$nonce = wp_create_nonce('email_send_ajax');
	?>

	<script type="text/javascript">
		jQuery(document).ready(function($){

				//creando la funcion ajax
				function ajax_post(){
					var data = {
						titulo : 'efecto ajax'
					}
					$.post( 
							"<?php echo admin_url( 'admin-ajax.php' ); ?>", 
							{
							action : "email_send_p",
							_ajax_nonce : "<?php echo $nonce; ?>",
							data:data
							},
							function( result ) {
								if(result.indexOf('enviado')>-1){
									$("#send_email_div").text("MENSAJE ENVIADO").delay(1000).fadeOut(1000);
								}
						});
				}
				//abrimos el boton download
				$("#send_p").click(function(){
					$("#send_email_div").show('fast');
					$("#send_email_div").text("ENVIANDO MENSAJE...");
					ajax_post();
				});
		});
	</script>

	<?php
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
				 <input type="button"  name="send_p" id="send_p" class="button button-primary" value="Probar Correo">
				<span style="display:none; margin-left: 10px; font-size: 16px; color:black; font-weight: bold;" id="send_email_div">ENVIANDO MENSAJE..</span>

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