<?php
add_shortcode('dvfaq', 'dvfaq');
add_shortcode('dvfaqtopic', 'dvfaqtopic');
add_shortcode('dvfaqsingle', 'dvfaqsingle');
add_shortcode('dvsharing', 'dvsharing');

add_filter("the_content", "dvfaq_content_filter");
add_filter("widget_text", "dvfaq_content_filter", 9);

function dvfaq_content_filter($content) {
 
	// array of custom shortcodes requiring the fix 
	$block = join("|", array("dvfaq","dvsharing","dvfaqtopic"));
 
	// opening tag
	$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
		
	// closing tag
	$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);
 
	return $rep;
 
}

// Main Shortcode
class dvfaq_shortcode {
    static $add_script;

	static function dvfaq_init() {
		add_shortcode('dvfaq', array(__CLASS__, 'dvfaq_handle_shortcode'));
		add_action('wp_footer', array(__CLASS__, 'dvfaq_scripts_output'));
	}

	static function dvfaq_handle_shortcode($atts) {
        self::$add_script = true;
		extract(shortcode_atts(array(
            "categoryid" => 'categoryid',
            "skin" => 'skin',
            "topicmenu" => 'topicmenu', // top, left, right, none
            "searchbox" => 'searchbox', // yes, no
            "topictitle" => 'topictitle',
            "switcher" => 'switcher' // yes, no
        ), $atts));
        ob_start();
        include('faq-all.php');
        $content = ob_get_clean();
        return $content;
	}

	static function dvfaq_scripts_output() { 
        if ( ! self::$add_script ) {
			return;
        }
        wp_enqueue_script('theia-sticky-sidebar');
        wp_enqueue_script('dvfaq-scripts');
    }
}

dvfaq_shortcode::dvfaq_init();

// Single Topic
class dvfaqtopic {
    static $add_script;

	static function dvfaq_init() {
		add_shortcode('dvfaqtopic', array(__CLASS__, 'dvfaq_handle_shortcode'));
		add_action('wp_footer', array(__CLASS__, 'dvfaq_scripts_output'));
	}

	static function dvfaq_handle_shortcode($atts) {
        self::$add_script = true;
		extract(shortcode_atts(array(
            "title" => 'title',
            "topicid" => 'topicid',
            "skin" => 'skin',
            "searchbox" => 'searchbox', // yes, no
            "switcher" => 'switcher', // yes, no
            "paginate" => 'paginate',
            "order" => 'order',
            "orderby" => 'orderby'
        ), $atts));   
        ob_start();
        include('faq-topic.php');
        $content = ob_get_clean();
        return $content;
	}

	static function dvfaq_scripts_output() { 
        if ( ! self::$add_script ) {
			return;
        }
        wp_enqueue_script('dvfaq-scripts');
    }
}

dvfaqtopic::dvfaq_init();

// Single Post
function dvfaqsingle($atts, $content = null) {
	extract(shortcode_atts(array(
            "postid" => 'postid',
            "headinglevel" => 'headinglevel',
            "skin" => 'skin'
        ), $atts));   
    ob_start();
    include('faq-single.php');
    $content = ob_get_clean();
    return $content;
}

// Sharing
function dvsharing($atts, $content = null) {
	extract(shortcode_atts(array(
        "postid" => 'postid'
	), $atts));   
    ob_start();
    include('faq-sharing.php');
    $content = ob_get_clean();
    return $content;
}
?>