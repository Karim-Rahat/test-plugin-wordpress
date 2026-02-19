<div id="dvfaq-single-post-<?php echo esc_attr($postid); ?>" class="dvfaq-single-post dvfaq-<?php echo esc_attr($skin); ?>">
<?php 
$dvfaq_faq_args = array(
    'post_type' => 'dvfaq',
    'page_id' => $postid
); 
$dvfaq_faq_query = new WP_Query( $dvfaq_faq_args );
?>
<?php while($dvfaq_faq_query->have_posts()) : $dvfaq_faq_query->the_post(); ?>
<<?php echo esc_attr($headinglevel); ?> class="dvfaq-single-title"><?php the_title(); ?></<?php echo esc_attr($headinglevel); ?>>
<?php the_content(); ?>
<div class="dvfaq-after-content">    
<?php do_action('dvfaq_after_content'); ?>
</div>    
<div class="dvfaq-clear"></div>
<?php endwhile; ?>
</div>
<script>
jQuery(document).ready(function () {
    "use strict";
    jQuery("#dvfaq-single-post-<?php echo esc_js($postid); ?>").find("iframe").wrap( "<div class='dvfaq-video'></div>" );
});
</script>
<?php wp_reset_postdata(); ?>