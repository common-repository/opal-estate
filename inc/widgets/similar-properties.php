<?php
/**
 * $Desc
 *
 * @version    $Id$
 * @package    wpbase
 * @author      Team <info@wpopal.com >
 * @copyright  Copyright (C) 2015  prestabrain.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/questions/
 */

class Opalestate_similar_properties_Widget extends WP_Widget{

    public function __construct() {
         parent::__construct(
            // Base ID of your widget
            'opalestate_similarproperties_widget',
            // Widget name will appear in UI
            __('OpalEstate:Similar Properties', 'opalestate'),
            // Widget description
            array( 'description' => __( 'Similar Properties By Same Types and Status Of the post', 'opalestate' ), )
        );
    }

    public function widget( $args, $instance ) {


        extract( $args );
	    extract( $instance );
      
        //Check

        $tpl = OPALESTATE_THEMER_WIDGET_TEMPLATES .'widgets/similar-properties.php';
        $tpl_default = OPALESTATE_PLUGIN_DIR .'templates/widgets/similar-properties.php';

        if(  is_file($tpl) ) {
            $tpl_default = $tpl;
        }
        require $tpl_default;
    }


    // Form

    public function form( $instance ) {
        //Set up some default widget settings.
        $defaults = array(
            'title' => __('Similar Properties', 'opalestate'),
            'num' => '5'
        );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e('Title:', 'opalestate'); ?></label>
            <input type="text" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:100%;" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('num')); ?>"><?php _e('Limit:', 'opalestate'); ?></label>
            <br>
            <input id="<?php echo esc_attr($this->get_field_id('num')); ?>" name="<?php echo esc_attr($this->get_field_name('num')); ?>" type="text" value="<?php echo esc_attr( $instance['num'] ); ?>" />
        </p>
    <?php
    }

    //Update the widget

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        //Strip tags from title and name to remove HTML
        $instance['title']      = strip_tags( $new_instance['title'] );
        $instance['num'] = $new_instance['num'];
        return $instance;
    }

}

register_widget( 'Opalestate_similar_properties_Widget' );

?>