<?php

  class wp_birthday_widget extends WP_Widget {
   function __construct() {
    
     parent::__construct(
            'birthdays_list', // Base ID
            __('Birthdays list'), // Name
            array( 'description' => __( 'Happy birthday list', 'wp_birthday_widget' ), ) // Args
      );
    

   }
   function form($instance) {
       // Construye el formulario de administración
         // Valores por defecto
        $defaults = array('titulo' => 'Happy birthday list', 'descripcion'=> '');
        // Se hace un merge, en $instance quedan los valores actualizados
        $instance = wp_parse_args((array)$instance, $defaults);
        // Cogemos los valores
        $titulo = $instance['titulo'];
        $descripcion = $instance['descripcion'];
        // Mostramos el formulario
    ?>
        <p>
            Titulo
            <input class="widefat" type="text" name="<?php echo $this->get_field_name('titulo');?>"
                   value="<?php echo esc_attr($titulo);?>"/>
        </p>
        <p>
            Descripcion
            <input class="widefat" type="text" name="<?php echo $this->get_field_name('descripcion');?>"
                   value="<?php echo esc_attr($descripcion);?>"/>
        </p>
    <?php
   }
   

   function update($new_instance, $old_instance) {
       // Guarda las opciones del Widget
       $instance = $old_instance;
        // Con sanitize_text_field elimiamos HTML de los campos
        $instance['titulo'] = sanitize_text_field($new_instance['titulo']);
        $instance['descripcion'] = sanitize_text_field($new_instance['descripcion']);
        return $instance;
   }
   
   function widget($args, $instance) {
       // Construye el código para mostrar el widget públicamente
    ?>
      <h1><span style="position: relative; margin-top: 10px;" class="dashicons dashicons-calendar"></span> <?php print($instance['titulo']); ?></h1>
      <p><?php print($instance['descripcion']); ?></p>
    <?php
    self::get_list_user_birthday();
   }


//cumpleañeros de hoy
public static function get_list_user_birthday() {
    $today    = date( 'd/m' );
    $users    = get_users();
    $count    = 1;
    $all_mails = [];
    //loop to find user birthday today.
    foreach ( $users as $user ) {
      $user_d = get_user_meta( $user->ID, 'birthday', true );
      $ignore_email = get_user_meta($user->ID, 'birthday_email_ignore', true);
      $user_d = substr( $user_d, 0, 5 );
      if ( $user_d == $today  && $ignore_email!='1') {
        $count ++;
        if ( $count == 2 ) {
          //no
        }
        echo '<span  ><span style="position:relative; margin-top:5px;" class="dashicons dashicons-awards"></span> <b>' . esc_html( $user->display_name ) . '</b>' . '</span></br>';
      }
    }
    if ( $count == 1 ) {
      echo '<h4><span style="position:relative; margin-top:5px;" class="dashicons dashicons-dismiss"></span> No hay Cumple-Añeros</h4>';
    }
    
  }

}

add_action('widgets_init', 'register_widget_email_birthday');
function register_widget_email_birthday() {
    register_widget('wp_birthday_widget');
}

?>