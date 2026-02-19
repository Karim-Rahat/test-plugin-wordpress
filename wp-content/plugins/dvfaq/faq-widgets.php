<?php
class dvfaq_widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'dvfaq_widget',
            esc_html__('FAQ', 'dvfaq'),
            array('classname' => 'dvfaq_widget widget_recent_entries','description' => esc_html__( 'Displays faq', 'dvfaq' )) 
        );
    }
    // Creating widget front-end
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $topicid = $instance['topicid'];
        $order = $instance['order'];
        $orderby = $instance['orderby'];
        $max = $instance['max'];

        echo $args['before_widget'];
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        if (empty($topicid)) {
            $topicid = get_terms("dvfaqtopics", array('get' => 'all', 'fields' => 'ids'));
        }
        else {
            $topicid = array($topicid);
        } 
        if (empty($max)) {
            $max = 5;
        }
        if (empty($order)) {
            $order = 'ASC';
        }
        if (empty($orderby)) {
            $orderby = 'date';
        }
        $dvfaq_faq_args = array(
            'post_type' => 'dvfaq',
            'posts_per_page' => $max,
            'orderby' => $orderby,
            'order'   => $order,
            'tax_query' => array(
                array(
                    'taxonomy' => 'dvfaqtopics',
                    'field'    => 'term_id',
                    'terms'    => $topicid
                )
            ),
        ); 
        $dvfaq_faq_query = new WP_Query( $dvfaq_faq_args );
        ?>
            <ul>
                <?php while($dvfaq_faq_query->have_posts()) : $dvfaq_faq_query->the_post(); ?>
                <li><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></li>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </ul>
        <?php
        echo $args['after_widget'];
    }
         
    // Widget Backend 
    public function form( $instance ) {
        $defaults = array('title' => '','topicid' => '','order' => 'ASC','orderby' => 'date', 'max' => 5);
		$instance = wp_parse_args( (array) $instance, $defaults );

        // Widget admin form
?>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'dvfaq' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php if (isset($instance['title'])) { echo esc_attr($instance['title']); } ?>" />
    </p>
    <p>
    <label for="<?php echo esc_attr($this->get_field_id( 'topicid' )); ?>"><?php esc_attr_e('Topic:', 'dvfaq'); ?></label>
    <select class="fwselect" id="<?php echo $this->get_field_id('topicid'); ?>" name="<?php echo $this->get_field_name('topicid'); ?>">
        <option value=""><?php esc_attr_e('All Topics', 'dvfaq'); ?></option>
        <?php foreach(get_terms('dvfaqtopics','parent=0&hide_empty=0') as $term) { ?>
        <option <?php if (isset($instance['topicid'])) { if ($instance['topicid'] == $term->term_id) { echo esc_attr('selected="selected"'); }} ?> value="<?php echo esc_attr($term->term_id); ?>"><?php echo esc_html($term->name); ?></option>
        <?php } ?>      
    </select>
    </p>
    <p>
    <label for="<?php echo esc_attr($this->get_field_id( 'order' )); ?>"><?php esc_attr_e('Order:', 'dvfaq'); ?></label>
    <select class="fwselect" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
        <option <?php if ($instance['order'] == 'ASC') { echo esc_attr('selected="selected"'); } ?> value="ASC"><?php esc_attr_e('ASC', 'dvfaq'); ?></option>
        <option <?php if ($instance['order'] == 'DESC') { echo esc_attr('selected="selected"'); } ?> value="DESC"><?php esc_attr_e('DESC', 'dvfaq'); ?></option>      
    </select>
    </p>
    <p>
    <label for="<?php echo esc_attr($this->get_field_id( 'orderby' )); ?>"><?php esc_attr_e('Order by:', 'dvfaq'); ?></label>
    <select class="fwselect" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
        <option <?php if ($instance['orderby'] == 'date') { echo esc_attr('selected="selected"'); } ?> value="date"><?php esc_attr_e('Date', 'dvfaq'); ?></option> 
        <option <?php if ($instance['orderby'] == 'ID') { echo esc_attr('selected="selected"'); } ?> value="ID"><?php esc_attr_e('ID', 'dvfaq'); ?></option> 
        <option <?php if ($instance['orderby'] == 'title') { echo esc_attr('selected="selected"'); } ?> value="title"><?php esc_attr_e('Title', 'dvfaq'); ?></option> 
        <option <?php if ($instance['orderby'] == 'rand') { echo esc_attr('selected="selected"'); } ?> value="rand"><?php esc_attr_e('Random', 'dvfaq'); ?></option> 
        <option <?php if ($instance['orderby'] == 'comment_count') { echo esc_attr('selected="selected"'); } ?> value="comment_count"><?php esc_attr_e('Comment Count', 'dvfaq'); ?></option> 
    </select>
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'max' )); ?>"><?php esc_html_e( 'Number of faq to show:', 'dvfaq' ); ?></label>
        <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id( 'max' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'max' )); ?>" type="number" value="<?php if (isset($instance['max'])) { echo esc_attr($instance['max']); } ?>" />
    </p>
<?php 
}
     
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['topicid'] = $new_instance['topicid'];
        $instance['order'] = $new_instance['order'];
        $instance['orderby'] = $new_instance['orderby'];
        $instance['max'] = $new_instance['max'];
        return $instance;
    }
}

// Register and load the widget
function dvfaq_load_widgets() {
    register_widget( 'dvfaq_widget' );
}
add_action( 'widgets_init', 'dvfaq_load_widgets' );
?>