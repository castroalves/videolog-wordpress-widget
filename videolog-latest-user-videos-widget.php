<?php

/*
Plugin Name: Videolog Latest User Videos
Plugin URI: http://videolog.tv/
Description: Exibe os últimos vídeos de um usuário. É possível limitar a quantidade de vídeos exibidos, o tamanho da miniatura ou player do vídeo e o tipo de miniatura a ser exibida (player embed ou imagem em miniatura).
Author: Cadu de Castro Alves
Version: 0.1
Author URI: http://twitter.com/castroalves
*/

class Videolog_Latest_User_Videos_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'widget_videolog_latest_user_videos', // Base ID
            'Videolog - Últimos Vídeos', // Widget Name
            array('description' => __('Exibe os últimos vídeos de um usuário.'), )
        );
 
    }
 
    public function form($instance) {
     
        //  Get the data of the widget
        $title = empty($instance['title']) ? '' : strip_tags($instance['title']);
        $token = empty($instance['token']) ? '' : strip_tags($instance['token']);
        $user_id = empty($instance['user_id']) ? '' : strip_tags($instance['user_id']);
        $width = empty($instance['width']) ? 264 : strip_tags($instance['width']);
        $height = empty($instance['height']) ? 172 : strip_tags($instance['height']);
        $quantity = empty($instance['quantity']) ? 5 : strip_tags($instance['quantity']);
        $thumb_type = ($instance['thumb_type'] == 1) ? 1 : strip_tags($instance['thumb_type']);

        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('token'); ?>"><?php echo __('Token'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('token'); ?>" name="<?php echo $this->get_field_name('token'); ?>" type="text" value="<?php echo attribute_escape($token); ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('user_id'); ?>"><?php echo __('ID do Usuário'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('user_id'); ?>" name="<?php echo $this->get_field_name('user_id'); ?>" type="text" value="<?php echo attribute_escape($user_id); ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('width'); ?>"><?php echo __('Width'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo attribute_escape($width); ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('height'); ?>"><?php echo __('Height'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo attribute_escape($height); ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('quantity'); ?>"><?php echo __('Quantidade de Vídeos'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('quantity'); ?>" name="<?php echo $this->get_field_name('quantity'); ?>" type="text" value="<?php echo attribute_escape($quantity); ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('thumb_type'); ?>"><?php echo __('Formato de exibição dos vídeos'); ?>:
                <select class="widefat" id="<?php echo $this->get_field_id('thumb_type'); ?>" name="<?php echo $this->get_field_name('thumb_type'); ?>">
                    <option value="0" <?php selected(0, $thumb_type) ?>>Embed</option>
                    <option value="1" <?php selected(1, $thumb_type) ?>>Imagem (Miniatura)</option>
                </select>
            </label></p>
        <?php
    }
 
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['token'] = strip_tags($new_instance['token']);
        $instance['user_id'] = strip_tags($new_instance['user_id']);
        $instance['width'] = strip_tags($new_instance['width']);
        $instance['height'] = strip_tags($new_instance['height']);
        $instance['quantity'] = strip_tags($new_instance['quantity']);
        $instance['thumb_type'] = strip_tags($new_instance['thumb_type']);
 
        return $instance;
    }
 
    public function widget($args, $instance) {
        extract($args);
     
        //  Get the data of the widget
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $token = empty($instance['token']) ? ' ' : $instance['token'];
        $user_id = empty($instance['user_id']) ? ' ' : $instance['user_id'];
        $width = empty($instance['width']) ? ' ' : $instance['width'];
        $height = empty($instance['height']) ? ' ' : $instance['height'];
        $quantity = empty($instance['quantity']) ? ' ' : $instance['quantity'];
        $thumb_type = empty($instance['thumb_type']) ? ' ' : $instance['thumb_type'];
     
        //  Outputs the widget in its standard ul li format.
        echo $before_widget;
        if (!empty( $title )) {
            echo $before_title . $title . $after_title;
        };

        if(empty($token) || empty($user_id)) {
            echo "Não há vídeos.";
            exit;
        }
        //echo '<ul style="list-style:none;margin-left:0px;">';

        // Inclui todas as classes
        require_once 'videolog-lib/autoload.inc.php';

        // Cria um novo objeto videolog passando o seu token
        // $token = "c36acd2d-792c-4462-b984-782fe008c70b"; // For debugging purposes

        $videos = new vlog($token);

        // Pega todos os videos do usuario
        //$user_id = 887357; // For debugging purposes
        $response = $videos->getUserVideos($user_id);
        $video = $response->usuario->videos;

        //var_dump($video); exit;

        //  Let's display the image(s)
        for($i = 0; $i < $quantity; $i++):
        ?>
            <h4><a href="<?php echo $video[$i]->link; ?>" title="<?php echo $video[$i]->titulo; ?>" target="_blank"><?php echo $video[$i]->titulo; ?></a></h4>
            <?php if($thumb_type == 1): // If true, display image. Else, display embed ?>
            <a href="<?php echo $video[$i]->link; ?>" title="<?php echo $video[$i]->titulo; ?>" target="_blank">
                <img src="<?php echo $video[$i]->thumb; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
            </a>
            <?php else: ?>
            <iframe width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="http://embed.videolog.tv/v/index.php?id_video=<?php echo $video[$i]->id; ?>&amp;width=<?php echo $width; ?>&amp;height=<?php echo $height; ?>&amp;related=&amp;hd=&amp;color1=ffffff&amp;color2=ffffff&amp;color3=333333&amp;slideshow=true&amp;config_url=" scrolling="no" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
            <?php endif; ?>
        <?php

        endfor;
     
        //echo '</ul>';
        echo $after_widget;
        //  Done
    }
 
}

// register Videolog_Latest_User_Videos_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "Videolog_Latest_User_Videos_Widget" );' ) );

?>