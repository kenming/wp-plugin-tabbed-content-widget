<?php
/*
Plugin Name: Tabbed Contents Widget.
Plugin URI: https://github.com/kenming/
Description: 使用 Bootstrap 框架 nav-tab Layout 展現位於 Sidebar Widget 的動態內容 
Author: Kenming Wang
Version: 0.6
Author URI: http://www.kenming.idv.tw
*/

define('THE_PLUGIN_DIR', plugin_dir_path(__FILE__));
require_once(THE_PLUGIN_DIR . '/queryContentFuncs.php' );

add_shortcode('show-recent-comments','show_recent_comments');
add_filter('widget_text', 'do_shortcode'); 

add_shortcode('show-random-posts','show_rand_posts');
add_filter('widget_text', 'do_shortcode'); 

class TabbedContentsWidget extends WP_Widget {

    //the widget construction    
    function __construct() {
        parent::__construct(
            'tabbed_contents_widget',
            __( 'Tabbed Contents Widget', 'tabbed-contents_widget' ),
            array(
                'customize_selective_refresh' => true,
            )
        );
    }

    // The widget form (for the backend )
	public function form( $instance ) {	
		// Set widget defaults
	    $defaults = array(
        'title'    => '',
        'hideTitle' => '',
        'recent-comments' => '',
        'random-posts' => ''
        );
        
        // Parse current settings with defaults
	    extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

        <!-- Title -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'tabbed-contents_widget' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <!-- HidTitle Checkbox -->
        <p>
            <input id="<?php echo $this->get_field_id('hideTitle'); ?>" name="<?php echo $this->get_field_name('hideTitle'); ?>" type="checkbox" <?php checked(isset($instance['hideTitle']) ? $instance['hideTitle'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hideTitle'); ?>"><?php _e('Do not display the title', 'enhancedtext'); ?></label>
        </p>
        
        <!-- Select Which type of contents to display -->
        <label>Select which type of contents to show :</label>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id('recent-comments') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent-comments' ) ); ?>" type="checkbox" <?php checked(isset($instance['recent-comments']) ? $instance['recent-comments'] : 0); ?>
            <label for="<?php echo esc_attr( $this->get_field_id( 'recent-comments' ) ); ?>"><?php _e( 'Recent Comments', 'tabbed-contents_widget' ); ?></label>
        </p>

        <p>
            <input id="<?php echo esc_attr( $this->get_field_id('random-posts') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'random-posts' ) ); ?>" type="checkbox" <?php checked(isset($instance['random-posts']) ? $instance['random-posts'] : 0); ?>
            <label for="<?php echo esc_attr( $this->get_field_id( 'random-posts' ) ); ?>"><?php _e( 'Random Posts', 'tabbed-contents_widget' ); ?></label>
        </p>        

	<?php }

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
        $instance['hideTitle'] = isset( $new_instance['hideTitle'] );
        $instance['recent-comments'] = isset( $new_instance['recent-comments'] ) ;
        $instance['random-posts'] = isset( $new_instance['random-posts'] ) ;

        return $instance;
	}

	// Display the widget
	public function widget( $args, $instance ) {
        extract( $args );

        // Check the widget options
        $title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $hideTitle =   ! empty( $instance['hideTitle'] ) ? true : false;
        $recent_comments = ! empty( $instance['recent-comments'] ) ? $instance['recent-comments'] : false;
        $random_posts = ! empty( $instance['random-posts'] ) ? $instance['random-posts'] : false;        

        // WordPress core before_widget hook (always include )
        echo $before_widget;

    // Display the widget
    echo '<div class="tabbed-content wp_tabbed-content">';

            // Display widget title if defined
            if ( $title && ! $hideTitle) {
                echo $before_title . $title . $after_title;
            }

            $render_html = '<ul class="nav nav-tabs">';

            // Display something if checkbox is true
            if ( $recent_comments ) {
                $render_html .= '<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#nav-recent-comments">最新迴響</a></li>';
            }

            // Display something if checkbox is true
            if ( $random_posts ) {
                $render_html .= '<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#nav-random-posts">隨機文章</a></li>';
            }
            
            $render_html .= '</ul>';            
            echo $render_html; ?>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="nav-recent-comments">
                    <?php echo do_shortcode('[show-recent-comments]'); ?>
                </div>
                <div class="tab-pane" id="nav-random-posts">
                    <?php echo do_shortcode('[show-random-posts]'); ?>
                </div>
            </div>

        <?php echo '</div>';

        // WordPress core after_widget hook (always include )
        echo $after_widget;
        }
}


// Register and load the widget
function tabbed_contents_widget_init() {
    register_widget( 'TabbedContentsWidget' );
}
add_action( 'widgets_init', 'tabbed_contents_widget_init' );