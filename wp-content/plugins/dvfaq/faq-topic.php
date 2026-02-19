<?php 
$random_number = wp_rand();
if (empty($order)) {
    $order = 'ASC';
}
if (empty($orderby)) {
    $orderby = 'date'; 
}
$dvfaq_heading_level = dvfaq_get_option('heading_level', 'h4');
?>
<div id="dvfaq-wrapper-<?php echo esc_attr($random_number); ?>" class="dvfaq-<?php echo esc_attr($skin); ?>">
    <?php 
    if (($searchbox != 'no') && (empty($paginate))) {
        dvfaq_faq_search(); 
    }
    ?>
    <?php if (($switcher != 'no') && (empty($paginate))) { ?>
    <ul class="dvfaq-switcher">
        <li class="dvfaq-switcher-open"><span class="dvfaqicon-plus"></span></li>
        <li class="dvfaq-switcher-close"><span class="dvfaqicon-minus"></span></li>
    </ul>
<?php } ?>    
<div class="dvfaq-cat-container">
<?php
if (empty($topicid)) {
    $topicid_array = get_terms("dvfaqtopics", array('get' => 'all', 'fields' => 'ids'));
} else {
    $topicid_array = explode(',', $topicid);   
}
$dvfaq_faq_args = array(
    'post_type' => 'dvfaq',
    'posts_per_page' => 999,
    'orderby' => $orderby,
    'order'   => $order,
    'tax_query' => array(
        array(
			'taxonomy' => 'dvfaqtopics',
			'field'    => 'term_id',
            'terms'    => $topicid_array
		)
	),
); 
$dvfaq_faq_query = new WP_Query( $dvfaq_faq_args );
?>
<?php if (!empty($title)) { ?>    
<<?php echo esc_attr($dvfaq_heading_level); ?> id="dvfaq-topic-<?php echo esc_html($random_number); ?>" class="dvfaq-cat-title"><?php echo esc_html($title); ?></<?php echo esc_attr($dvfaq_heading_level); ?>>
<?php } ?>    
<div class="dvfaq-accordion-wrapper">    
<?php while($dvfaq_faq_query->have_posts()) : $dvfaq_faq_query->the_post(); ?>
    <?php 
    $open_by_default = get_post_meta( get_the_id(), 'dvfaq_cmb2_openaccordion', true ); 
    $accordion_header = '';
    $accordion_content = '';
    if($open_by_default == 'yes') {
        $accordion_header = 'dvfaq-active-header';
        $accordion_content = 'dvfaq-open-content';
    } else {
         $accordion_header = 'dvfaq-inactive-header';
    }
    ?>
    <div class="dvfaq-accordion-container dvfaq-live-search-results">
        <div class="dvfaq-accordion-header <?php echo esc_attr($accordion_header); ?>" itemscope="" itemtype="http://schema.org/Question"><?php the_title(); ?></div>
        <div class="dvfaq-accordion-content <?php echo esc_attr($accordion_content); ?>" itemscope="" itemtype="http://schema.org/Answer">
            <?php do_action('dvfaq_before_content'); ?>
            <?php 
                if (has_excerpt( get_the_ID() )) {
                    the_excerpt();
            ?>
            <div class="dvfaq-readmore">
                <a target="_blank" href="<?php esc_url(the_permalink()); ?>"><?php esc_html_e( 'Read More...', 'dvfaq') ?></a>
            </div>
            <?php } else { ?>
            <?php the_content(); ?>
            <div class="dvfaq-after-content">
            <?php do_action('dvfaq_after_content'); ?>
            </div>
            <div class="dvfaq-clear"></div>  
            <?php } ?>  
        </div>
    </div>
<?php endwhile; ?>   
</div>
<?php wp_reset_postdata(); ?>
</div>
</div>
<script>
    jQuery(document).ready(function(){    
        jQuery("#dvfaq-wrapper-<?php echo esc_attr($random_number); ?>").dvfaqJquery();
        <?php if (!empty($paginate)) { ?>
        jQuery("#dvfaq-wrapper-<?php echo esc_attr($random_number); ?>").find('.dvfaq-accordion-container').paginate(<?php echo esc_js($paginate); ?>);
        <?php } ?>
    }); 
</script>