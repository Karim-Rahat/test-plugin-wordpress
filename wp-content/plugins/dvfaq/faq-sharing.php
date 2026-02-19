<?php $dvfaq_sharing_btns = dvfaq_get_option('sharing_btns'); ?>
<?php if (!empty($dvfaq_sharing_btns)) { ?>
<div class="dvfaq-sharing-wrapper">
<ul class="dvfaq-social-share-btns">
    <li class="dvfaq-social-share-btns-title">
        <?php esc_html_e( 'Share This:', 'dvfaq'); ?>
    </li>
    <?php if (in_array("email", $dvfaq_sharing_btns)) { ?>
    <li class="dvfaq-social-share-mail">
        <a href="mailto:?Subject=<?php echo rawurlencode(get_the_title()); ?>&amp;body=<?php the_permalink(); ?>" rel="nofollow" target="_blank" title="<?php esc_attr_e( 'Email', 'dvfaq'); ?>">
      <span class="dvfaqicon-mail-alt"></span>
    </a>
    </li>
    <?php } ?>
    <?php if (in_array("twitter", $dvfaq_sharing_btns)) { ?>
    <li class="dvfaq-social-share-twitter">
        <a href="https://twitter.com/intent/tweet?text=<?php the_permalink(); ?>" rel="nofollow" target="_blank" title="<?php esc_attr_e( 'Twitter', 'dvfaq'); ?>" class="dvfaq-popup">
      <span class="dvfaqicon-twitter"></span>   
    </a>
    </li>
    <?php } ?>
    <?php if (in_array("facebook", $dvfaq_sharing_btns)) { ?>
    <li class="dvfaq-social-share-facebook">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" rel="nofollow" target="_blank" title="<?php esc_attr_e( 'Facebook', 'dvfaq'); ?>" class="dvfaq-popup">
      <span class="dvfaqicon-facebook"></span>
    </a>
    </li>
    <?php } ?>
    <?php if (in_array("linkedin", $dvfaq_sharing_btns)) { ?>
    <li class="dvfaq-social-share-linkedin">
        <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php echo rawurlencode(get_the_title()); ?>" rel="nofollow" target="_blank" title="<?php esc_attr_e( 'Linkedin', 'dvfaq'); ?>" class="dvfaq-popup">
      <span class="dvfaqicon-linkedin"></span>  
    </a>
    </li>
    <?php } ?>
    <?php if (in_array("reddit", $dvfaq_sharing_btns)) { ?>
    <li class="dvfaq-social-share-reddit">
        <a href="http://www.reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php echo rawurlencode(get_the_title()); ?>" rel="nofollow" target="_blank" title="<?php esc_attr_e( 'Reddit', 'dvfaq'); ?>" class="dvfaq-popup">
      <span class="dvfaqicon-reddit"></span>  
    </a>
    </li>
    <?php } ?>
    <?php if (in_array("vk", $dvfaq_sharing_btns)) { ?>
    <li class="dvfaq-social-share-vk">
        <a href="http://vk.com/share.php?url=<?php the_permalink(); ?>" rel="nofollow" target="_blank" title="<?php esc_attr_e( 'VK', 'dvfaq'); ?>" class="dvfaq-popup">
      <span class="dvfaqicon-vkontakte"></span>  
    </a>
    </li>
    <?php } ?>
</ul>
<div class="dvfaq-clear"></div>
</div>
<?php } ?>