<?php
$dvfaq_heading_level = dvfaq_get_option('heading_level', 'h4');
$dvfaq_topics_args = array(
    'taxonomy'      =>  'dvfaqtopics',
    'orderby'       =>  'date',
    'order'         =>  'ASC'
);
$dvfaq_topics = get_terms($dvfaq_topics_args);
$dvfaq_topic_count = count($dvfaq_topics);

if (empty($categoryid)) {
    $categoryid = get_terms("dvfaqcategories", array('get' => 'all', 'fields' => 'ids'));
}
else {
    $categoryid = array($categoryid);
}
if ( $dvfaq_topic_count > 0 ) {
?>
<div class="dvfaq-faq-menu"> 
    <?php if (!empty($topictitle)) { ?>
    <<?php echo esc_attr($dvfaq_heading_level); ?> class="dvfaq-menu-title">
        <?php echo esc_html($topictitle); ?>
    </<?php echo esc_attr($dvfaq_heading_level); ?>>
    <?php } ?>
<ul>  
<?php	
foreach ( $dvfaq_topics as $topic ) {
    $topicid = $topic->term_id;
    $topicname = $topic->name;
    $dvfaq_faq_args = array(
    'post_type' => 'dvfaq',
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
        ),
    ),
    ); 
    $dvfaq_faq_query = new WP_Query( $dvfaq_faq_args );
    $dvfaq_topic_count = $dvfaq_faq_query->post_count;
    if (!empty($dvfaq_topic_count)) { 
    ?>
    <li><a href="#dvfaq-topic-<?php echo esc_attr($random_number); ?>-<?php echo esc_attr($topicid); ?>" data-cat="dvfaq-topic-<?php echo esc_attr($random_number); ?>-<?php echo esc_attr($topicid); ?>"><strong><?php echo esc_html($topicname); ?><span><?php echo esc_html($dvfaq_topic_count); ?></span></strong></a></li>
    <?php
    }
    wp_reset_postdata();
}
    $getterms = get_terms("dvfaqtopics", array('get' => 'all', 'fields' => 'ids'));
    $dvfaq_faq_args2 = array(
    'post_type' => 'dvfaq',
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
        ),
    ),
    ); 
    $dvfaq_faq_query2 = new WP_Query( $dvfaq_faq_args2 );
    $dvfaq_topic_count2 = $dvfaq_faq_query2->post_count;
    if (!empty($dvfaq_topic_count2)) { 
    ?>
    <li><a href="#dvfaq-topic-<?php echo esc_attr($random_number); ?>-others" data-cat="dvfaq-topic-<?php echo esc_attr($random_number); ?>-others"><strong><?php esc_html_e( 'Others', 'dvfaq') ?><span><?php echo esc_html($dvfaq_topic_count2); ?></span></strong></a></li>
    <?php
    }
    wp_reset_postdata();    
    ?>  
</ul>
</div>    
<?php } ?>