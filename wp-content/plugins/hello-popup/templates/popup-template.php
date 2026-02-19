<?php

    if (! defined("ABSPATH")) {
        exit;
    }

    $enabled = get_option('hello_popup_enabled');

    if (! $enabled) {
        return;
    }

    $title     = esc_html(get_option('hello_popup_title', 'Welcome!'));
    $message   = wp_kses_post(get_option('hello_popup_message', 'Thanks for visiting.'));
    $image     = esc_url(get_option('hello_popup_image'));
    $attachment_id = attachment_url_to_postid($image);
    $delay     = (int) get_option('hello_popup_delay', 1500);
    $shortcode = get_option('hello_popup_shortcode');

?>


<div id="hp-modal"  data-delay="<?php echo esc_attr($delay); ?>" >
    <div class="hp-overlay"></div>
    <div class="hp-container">

        <span class="hp-close" style="color: <?php echo esc_attr(get_option('hello_popup_close_btn_color', '#000')); ?>;">&times;</span>
      <?php if ($attachment_id) : ?>
            <?php echo wp_get_attachment_image($attachment_id, 'full', false, ['alt' => 'Popup Image']); ?>
    
        <?php endif; ?>


        <div class="hp-content">

            <h2 class="hp-popup-title"><?php echo esc_html($title); ?></h2>
            <p class="hp-popup-description"><?php echo esc_html(stripslashes($message)); ?></p>
        </div>


        <?php if (!empty(get_option('hello_popup_cta_text'))) : ?>
    <div class="hp-cta-button-wrapper">
        <a href="<?php echo esc_url(get_option('hello_popup_cta_url')); ?>"
           class="hp-cta-button"
           style="background-color: <?php echo esc_attr(get_option('hello_popup_cta_color', '#000')); ?>;">
            <?php echo esc_html(get_option('hello_popup_cta_text')); ?>
        </a>
    </div>
<?php endif; ?>


        <div class="hp-shortcode">
            <?php if (! empty($shortcode)): ?>
            <p><?php echo do_shortcode(stripslashes($shortcode)); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php