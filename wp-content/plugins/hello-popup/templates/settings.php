<?php

    if (! defined("ABSPATH")) {
        exit;
    }

?>

<div class="wrap">
<div class="hp-container">

    
    <form id="hello-popup-settings-form">
        <?php wp_nonce_field('hello_popup_settings_nonce', 'hello_popup_settings_nonce_field'); ?>
        <input type="hidden" name="action" value="save_hello_popup_settings">
   <!-- Intorduction Section --> 
<div class="hp-settings-intro">
<div>

<div  style="display: flex; gap: 15px; align-items: flex-end;">

  <img src="<?php echo esc_url( HELLO_POPUP_URL . '/src/images/logo-2.png' ); ?>"  alt="<?php esc_attr_e( 'Hello Popup Logo', 'hello-popup' ); ?>"   />

<div>
  <h1><?php esc_html_e('Customize Hello Popup', 'hello-popup'); ?></h1>
  <p class="hp-subtitle">
  <?php esc_html_e('Capture attention and drive conversions instantly.', 'hello-popup'); ?>
  </p>

  
<button type="button" id="start-tour-btn" class="button button-secondary">
  Start Guide
</button>

</div>
</div>


</div>
 
  <div class="hp-setting-rows">

    <div class="hp-setting-row">
    <label for="hello_popup_enabled" class="hp-setting-label">
      <?php esc_html_e('Enable Popup', 'hello-popup'); ?>
    </label>
    <label class="hp-switch">
      <input type="checkbox" id="hello_popup_enabled" name="hello_popup_enabled" value="1"
             <?php checked(1, get_option('hello_popup_enabled', 1)); ?>>
      <span id="enable-toggle" class="hp-slider round"></span>
    </label>
    </div>

    <div class="hp-setting-row">
      <label for="hello_popup_auto_show" class="hp-setting-label">
        <?php esc_html_e('Auto Popup on Page Load', 'hello-popup'); ?>
      </label>
      <label class="hp-switch">
        <input type="checkbox" id="hello_popup_auto_show" name="hello_popup_auto_show" value="1"
               <?php checked(1, get_option('hello_popup_auto_show', '1')); ?>>
        <span class="hp-slider round"></span>
      </label>
    
  </div>
</div>
</div>


<!-- Popup title and description -->


<div class="hp-setting-section">
    <h2>
      <?php esc_html_e('Popup title and description', 'hello-popup'); ?>
    </h2>
    <input type="text" id="hello_popup_title" name="hello_popup_title"
           value="<?php echo esc_attr(get_option('hello_popup_title')); ?>"
           class="regular-text hp-input">
           <br>
           <br>

    <textarea id="hello_popup_message" name="hello_popup_message" rows="4" cols="50"
              class="hp-textarea"><?php echo esc_textarea(stripslashes(get_option('hello_popup_message'))); ?></textarea>
</div>


<!--  Popup Banner  Settings -->
<div class="hp-setting-section">
  <h2>
      <?php esc_html_e('Popup Banner', 'hello-popup'); ?>
    </h2>

    <div class="hp-settings-banner-cont">
<div>


    <input type="hidden" id="hello_popup_image" name="hello_popup_image"
               value="<?php echo esc_attr(get_option('hello_popup_image')); ?>" />

        <button type="button" class="hp-upload-btn" id="popup_image_button">
            <?php echo get_option('hello_popup_image') ? 'Change Image' : 'Select Image'; ?>
        </button>
        </div>
        <div>
        

        <div id="popup_image_preview_container">

            <?php $image = get_option('hello_popup_image'); ?>
            <img id="popup_image_preview"
                 src="<?php echo esc_url($image); ?>"
             style="<?php echo $image ? '' : 'display:none;'; ?>"  />

            <span id="popup_image_remove"
                  class="dashicons dashicons-no-alt hp-img-close"
                  style="<?php echo $image ? '' : 'display:none;'; ?>"></span>
        </div>
        </div>
    </div>
</div>

<!--  Popup CTA  Settings -->

<div class="hp-setting-section">
  <h2><?php esc_html_e('CTA info settings', 'hello-popup'); ?></h2>
        <table class="form-table">
          <tr valign="top">
    <th scope="row"><label for="hello_popup_cta_text"><?php esc_html_e('Title', 'hello-popup');?></label></th>
    <td>
        <input type="text" id="hello_popup_cta_text" name="hello_popup_cta_text"
               value="<?php echo esc_attr(get_option('hello_popup_cta_text', '')); ?>"  placeholder="<?php esc_attr_e( 'Enter button title', 'hello-popup' ); ?>" />
    </td>
</tr>
<tr valign="top">
    <th scope="row"><label for="hello_popup_cta_url"><?php esc_html_e('Redirect URL', 'hello-popup');?></label></th>
    <td>
        <input type="url" id="hello_popup_cta_url" name="hello_popup_cta_url"
               value="<?php echo esc_attr(get_option('hello_popup_cta_url', '')); ?>"
                placeholder="<?php esc_attr_e('Enter redirect link (e.g., https://example.com)', 'hello-popup'); ?>"
                 />
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="hello_popup_cta_color"><?php esc_html_e('Background color', 'hello-popup');?></label></th>
    <td>
        <input type="color" id="hello_popup_cta_color" name="hello_popup_cta_color"
               value="<?php echo esc_attr(get_option('hello_popup_cta_color', '#000')); ?>" />
    </td>
</tr>


</table>
</div>

<!-- Popup shortcode settings  -->

<div class="hp-setting-section">

    <h2><?php esc_html_e('Popup Shortcode', 'hello-popup'); ?></h2>
    <p class="hp-description">
        <?php esc_html_e('Paste any valid WordPress shortcode (e.g., Contact Form 7, WPForms, etc.) to display in the popup.', 'hello-popup'); ?>
        </p>
    <br>
    <input type="text" id="hello_popup_shortcode" name="hello_popup_shortcode"
                        value="<?php echo esc_attr(stripslashes(get_option('hello_popup_shortcode'))); ?>"
                        class="regular-text" placeholder="[your-shortcode]" />
</div>



<!-- Display popup on pages  -->



<?php
$pages = get_pages();
$selected_pages = get_option('hello_popup_selected_pages', []);

// If no saved value exists yet, default to home page
// if (empty($selected_pages)) {
//     $selected_pages = [get_option('page_on_front')];
// }
?>



<div class="hp-setting-section">
      <h2><?php esc_html_e('Show Popup On Pages', 'hello-popup'); ?></h2>
      <p>
  <?php esc_html_e('Select the pages where you want the popup to appear.', 'hello-popup'); ?>
</p>
    <div class="hp-multiselect-wrapper">
        <?php foreach ($pages as $page) : ?>
            <label style="display: block; margin-bottom: 5px;">
                <input type="checkbox" name="hello_popup_selected_pages[]"
                       value="<?php echo esc_attr($page->ID); ?>"
                       <?php checked(in_array($page->ID, $selected_pages)); ?>>
                <?php echo esc_html($page->post_title); ?>
            </label>
        <?php endforeach; ?>
    </div>
</div>



<!-- Popup others  settings  -->

<div class="hp-setting-section">
    <h2><?php esc_html_e('Others Settings', 'hello-popup'); ?></h2>
        <table class="form-table">

            <tr>
                <th scope="row"><?php esc_html_e('Popup Delay (ms)', 'hello-popup'); ?></th>
                <td>
                    <input type="number" id="hello_popup_delay" name="hello_popup_delay"
                        value="<?php echo esc_attr(get_option('hello_popup_delay', 1500)); ?>">
                </td>
            </tr>
            <tr valign="top">
    <th scope="row"><?php esc_html_e('Popup Hide Duration', 'hello-popup');?></th>
    <td>
        <input type="number" name="hello_popup_expiry" id="hello_popup_expiry" value="<?php echo esc_attr(get_option('hello_popup_expiry', 24)); ?>" min="1" />
        <p class="description"><?php esc_html_e('How long (in hours) the popup should remain hidden after the user closes it.', 'hello-popup');?></p>
    </td>
</tr>




<tr valign="top">
  <th scope="row"><?php esc_html_e('Popup Trigger Class', 'hello-popup');?></th>
  <td>
    <div style="display: flex; align-items: center; gap: 8px;">
      <input type="text" id="popup_trigger_class" value="hp-show-popup-btn" readonly  />
      <button type="button" class="button" id="copy_trigger_class_btn"><?php esc_html_e('Copy', 'hello-popup');?></button>
    </div>
        <p><?php esc_html_e('Add this class to any button or element to trigger the popup manually ', 'hello-popup');?></p>

    <small><?php esc_html_e('Example: &lt;button class="hp-show-popup-btn"&gt;Show Popup&lt;/button&gt; ', 'hello-popup');?></small>
  </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="hello_popup_close_btn_color"><?php esc_html_e('Popup Close  Icon Color', 'hello-popup');?></label></th>
    <td>
        <input type="color" id="hello_popup_close_btn_color" name="hello_popup_close_btn_color"
               value="<?php echo esc_attr(get_option('hello_popup_close_btn_color', '#000')); ?>" />
    </td>
</tr>


</table>
</div>

<!-- Customize existing style  -->

<div class="hp-setting-section">
<h2><?php esc_html_e('Advanced Settings', 'hello-popup'); ?></h2>
<p style="margin-top: -10px; margin-bottom: 15px;">
     <strong><?php echo esc_html__('Customize the popup design', 'hello-popup'); ?></strong>
    <?php echo esc_html__(' by overriding the default styles below. Use valid CSS.', 'hello-popup'); ?>
</p>

<?php
$default_css = "#hp-modal {}\n.hp-overlay {}\n.hp-container {}\n.hp-content {}\n.hp-cta-button {}\n";
$custom_css = get_option('hello_popup_custom_css', '');
if (empty(trim($custom_css))) {
    $custom_css = $default_css;
}
?>
<textarea id="hello_popup_custom_css" name="hello_popup_custom_css" rows="10" cols="30" class="large-text code"><?php echo esc_textarea($custom_css);  ?></textarea>
</div>


<!-- Save Settings Button -->
 <div id="hp-sticky-save" class="hp-save-settings ">
     <button type="submit" id="save-settings" class="button hp-update-btn button-primary hp-sticky-btn">
         <?php esc_html_e('Save Settings', 'hello-popup'); ?>
     </button>
 </div>

    </form>

    <div id="hello-popup-settings-message"></div>
    <div id="hello-toast-container" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999;"></div>

  </div>

</div>

<?php

