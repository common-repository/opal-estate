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

class Opalestate_search_properties_Widget extends WP_Widget{

    public function __construct() {
         parent::__construct(
            // Base ID of your widget
            'opalestate_search_properties_widget',
            // Widget name will appear in UI
            __('OpalEstate:Search Properties', 'opalestate'),
            // Widget description
            array( 'description' => __( 'Search Properties widget.', 'opalestate' ), )
        );
    }

    public function widget( $args, $instance ) {

        
        extract( $args );
	    extract( $instance );
        //Our variables from the widget settings.
        $title  = apply_filters('widget_title', esc_attr($instance['title']));


        //Check

        $tpl = OPALESTATE_THEMER_WIDGET_TEMPLATES .'widgets/search-properties.php'; 
        $tpl_default = OPALESTATE_PLUGIN_DIR .'templates/widgets/search-properties.php';
  
        if(  is_file($tpl) ) { 
            $tpl_default = $tpl;
        }
        require $tpl_default;
            
        echo ($after_widget);
    }


    // Form

    public function form( $instance ) {
        //Set up some default widget settings.
        $defaults = array( 
            'title' => __('Search Properties', 'opalestate')
        );              
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e('Title:', 'opalestate'); ?></label>
            <input type="text" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:100%;" />
        </p>
    <?php
    }

    //Update the widget

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        //Strip tags from title and name to remove HTML
        $instance['title']      = strip_tags( $new_instance['title'] );
        return $instance;
    }
    
}

register_widget( 'Opalestate_search_properties_Widget' );

?>