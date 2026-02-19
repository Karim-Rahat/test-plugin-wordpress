<div class="dvfaq-cat-container">
<?php
$dvfaq_heading_level = dvfaq_get_option('heading_level', 'h4');
$dvfaq_topics_args = array(
    'taxonomy'      =>  'dvfaqtopics',
    'orderby'       =>  'date',
    'order'         =>  'ASC'
);
$dvfaq_topics = get_terms($dvfaq_topics_args);
if (empty($categoryid)) {
    $categoryid = get_terms("dvfaqcategories", array('get' => 'all', 'fields' => 'ids'));
}
else {
    $categoryid = array($categoryid);
}    
foreach($dvfaq_topics as $topic) { 
    $topicid = $topic->term_id;
?>
<?php 
$dvfaq_faq_args = array(
    'post_type' => 'dvfaq',
    'posts_per_page' => 999,
    'orderby' => 'date',
    'order'   => 'ASC',
    'tax_query' => array(
        'relation' => 'AND',
		array(
			'taxonomy' => 'dvfaqcategories',
			'field'    => 'term_id',
            'terms'    => $categoryid
		),
        array(
			'taxonomy' => 'dvfaqtopics',
			'field'    => 'term_id',
            'terms'    => $topicid
		)
	),
); 
$dvfaq_faq_query = new WP_Query( $dvfaq_faq_args );
$dvfaq_topic_count = $dvfaq_faq_query->post_count;
?>
<?php if ($dvfaq_topic_count) { ?>    
<<?php echo esc_attr($dvfaq_heading_level); ?> id="dvfaq-topic-<?php echo esc_html($random_number); ?>-<?php echo esc_attr($topicid); ?>" class="dvfaq-cat-title"><?php echo esc_html($topic->name); ?></<?php echo esc_attr($dvfaq_heading_level); ?>>
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
<?php } ?>
<?php wp_reset_postdata(); ?>
<?php
    $getterms = get_terms("dvfaqtopics", array('get' => 'all', 'fields' => 'ids'));
    $dvfaq_faq_args2 = array(
    'post_type' => 'dvfaq',
    'posts_per_page' => 999,
    'orderby' => 'date',
    'order'   => 'ASC',
    'tax_query' => array(
        'relation' => 'AND',
		array(
			'taxonomy' => 'dvfaqcategories',
			'field'    => 'term_id',
            'terms'    => $categoryid
		),
        array(
			'taxonomy' => 'dvfaqtopics',
			'field'    => 'term_id',
            'terms'    => $getterms,
            'operator' => 'NOT IN'
		)
	),
); 
$dvfaq_faq_query2 = new WP_Query( $dvfaq_faq_args2 );
$dvfaq_topic_count2 = $dvfaq_faq_query2->post_count;
?>
<?php if ($dvfaq_topic_count2) { ?> 
<<?php echo esc_attr($dvfaq_heading_level); ?> id="dvfaq-topic-<?php echo esc_html($random_number); ?>-others" class="dvfaq-cat-title"><?php esc_html_e( 'Others', 'dvfaq') ?></<?php echo esc_attr($dvfaq_heading_level); ?>>  
<div class="dvfaq-accordion-wrapper">     
<?php while($dvfaq_faq_query2->have_posts()) : $dvfaq_faq_query2->the_post(); ?>
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
<?php } ?>    
    
<?php wp_reset_postdata(); ?> 
</div>