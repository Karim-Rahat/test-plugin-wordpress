<?php 
$random_number = wp_rand();
$dvfaq_scroll_anim = dvfaq_get_option('scroll_anim', 'enable');
if ($dvfaq_scroll_anim == 'enable') {
    $dvfaq_scroll_anim = 'true';
} else {
    $dvfaq_scroll_anim = 'false';
}
$dvfaq_scrolltop = dvfaq_get_option('top_spacing', 60);
if (empty($dvfaq_scrolltop)) {
    $dvfaq_scrolltop = 60;
}
?>
<div id="dvfaq-wrapper-<?php echo esc_attr($random_number); ?>" class="dvfaq-<?php echo esc_attr($skin); ?>">
    <?php if ($topicmenu == 'top') { ?>
        <div class="dvfaq-wrapper-menu">
            <?php dvfaq_faq_menu($categoryid, $random_number, $topictitle); ?>
        </div>
    <?php } ?>
    <div class="dvfaq-wrapper">
        <?php if (($topicmenu == 'left') || (empty($topicmenu))) { ?>
        <div class="dvfaq-wrapper-left">
            <div class="theiaStickySidebar">
            <?php dvfaq_faq_menu($categoryid, $random_number, $topictitle); ?>
            </div>
        </div>
        <?php } ?>
        <div class="dvfaq-wrapper-center">
            <?php 
            if ($searchbox != 'no') {
                dvfaq_faq_search(); 
            }
            ?>
            <?php if ($switcher != 'no') { ?>
            <ul class="dvfaq-switcher">
                <li class="dvfaq-switcher-open"><span class="dvfaqicon-plus"></span></li>
                <li class="dvfaq-switcher-close"><span class="dvfaqicon-minus"></span></li>
            </ul>
            <?php } ?>
            <?php dvfaq_faq_content($categoryid, $random_number); ?>
        </div>
        <?php if ($topicmenu == 'right') { ?>
        <div class="dvfaq-wrapper-right">
            <div class="theiaStickySidebar">
            <?php dvfaq_faq_menu($categoryid, $random_number, $topictitle); ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<script>
    jQuery(document).ready(function(){    
        jQuery("#dvfaq-wrapper-<?php echo esc_attr($random_number); ?>").dvfaqJquery({
            topAnim: <?php echo esc_js($dvfaq_scroll_anim); ?>,
            topSpacing: <?php echo esc_js($dvfaq_scrolltop); ?>
        });
    }); 
</script>