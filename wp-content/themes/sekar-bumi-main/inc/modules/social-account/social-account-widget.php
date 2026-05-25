<?php
class Sekarbumi_Widget_Social_Account extends WP_Widget {

	function __construct() {

        /* Widget settings. */
        $widgetOps = array(
            'classname'   => 'social-widget',
            'description' => esc_html__( 'Add Social widget to your sidebar', 'sekarbumi' )
        );

        /* Widget control settings. */
        $controlOps = array(
            'width'   => 250,
            'height'  => 350,
            'id_base' => 'social-widget'
        );

        /* Create the widget. */
        WP_Widget::__construct(
            'social-widget',
            esc_html__( 'Sekarbumi Social Account Widget', 'sekarbumi' ),
            $widgetOps,
            $controlOps
        );

    } // End Constructor

    function form( $instance ) {

        /* Set up some default widget settings. */
        $defaults = array( 'title' => esc_html__( 'Social Media', 'sekarbumi' ) );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title:', 'sekarbumi' ); ?></label>
            <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" />
        </p>
        <!-- Widget Title: Icon Ratio -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'icon_ratio' ) ); ?>"><?php esc_html_e( 'Icon Ratio', 'sekarbumi' ); ?></label>
            <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'icon_ratio' ) ); ?>" value="<?php echo esc_attr( $instance['icon_ratio'] ?? '' ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'icon_ratio' ) ); ?>" />
        </p>
        <?php
    } // End form()

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        /* Strip tags for title and name to remove HTML (important for text inputs). */
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['icon_ratio'] = strip_tags( $new_instance['icon_ratio'] );

        return $instance;

    }

	function widget( $args, $instance ) {

        extract( $args, EXTR_SKIP );

        /* Our variables from the widget settings. */
        $title 	      = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) : esc_html__( 'Social Media', 'sekarbumi' );
        $icon_ratio 	= $instance['icon_ratio'] ?? '1.2';

        /* Before widget (defined by themes). */
        print $before_widget;

        /* Display the widget title if one was input (before and after defined by themes). */
        if ( $title )
            print $before_title . esc_attr( $title ) . $after_title;

        $html = print( do_shortcode( '[sekarbumi_widget_social_account icon_ratio="'. esc_attr( $icon_ratio ) .'"]' ) );

        /* After widget (defined by themes). */
        print $after_widget;

    } // End widget()

}