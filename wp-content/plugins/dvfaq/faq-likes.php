<?php

// LIKES
function dvfaq_like_it() {
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'dvfaq_like_it_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }
    
    $dvfaqcookie = "dvfaqcookie-" . $_REQUEST['post_id'];
 
    if (!isset($_COOKIE[$dvfaqcookie])) {
        $likes = get_post_meta( $_REQUEST['post_id'], '_dvfaq_likes', true );
        $likes = ( empty( $likes ) ) ? 0 : $likes;
        $new_likes = $likes + 1;
 
        update_post_meta( $_REQUEST['post_id'], '_dvfaq_likes', $new_likes );
 
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            setcookie($dvfaqcookie, "dvfaqrated", time()+31500000);
            echo esc_html($new_likes);
            die();
        }
        else {
            setcookie($dvfaqcookie, "dvfaqrated", time()+31500000);
            wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
            exit();
        }
    } else {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            $likes = get_post_meta( $_REQUEST['post_id'], '_dvfaq_likes', true );
            $likes = ( empty( $likes ) ) ? 0 : $likes;
            echo esc_html($likes . ' ' . esc_html__( 'You already rated this!', 'dvfaq' ));
            die();
        }
        else {
            wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
            exit();
        }
    }
}
 
function dvfaq_like_it_button_html( $content ) {
    global $post;
    $like_text = '';
    if( is_singular('dvfaq') ) {
        $nonce = wp_create_nonce( 'dvfaq_like_it_nonce' );
        $link = admin_url('admin-ajax.php?action=dvfaq_like_it&post_id='.$post->ID.'&nonce='.$nonce);
        $likes = get_post_meta( get_the_ID(), '_dvfaq_likes', true );
        $likes = ( empty( $likes ) ) ? 0 : $likes;
        $like_text = '<div class="dvfaq-like-title">' . esc_html__( 'Was this helpful?', 'dvfaq' ) . '</div><div class="dvfaq-like-it"><a class="dvfaq-like-button" href="'.$link.'" data-id="' . get_the_ID() . '" data-nonce="' . $nonce . '"><span class="dvfaqicon-thumbs-up-alt"></span></a><span class="dvfaq-like-count dvfaq-like-count-'.get_the_ID().'">' . $likes . '</span></div>';
    }
    return $content . $like_text;
}

function dvfaq_like_it_button() {
    $nonce = wp_create_nonce( 'dvfaq_like_it_nonce' );
    $link = admin_url('admin-ajax.php?action=dvfaq_like_it&post_id='. get_the_ID() .'&nonce='.$nonce);
    $likes = get_post_meta( get_the_ID(), '_dvfaq_likes', true );
    $likes = ( empty( $likes ) ) ? 0 : $likes;
    ?>
    <div class="dvfaq-like-title"><?php esc_html_e( 'Was this helpful?', 'dvfaq' ); ?></div>
    <div class="dvfaq-like-it"><a class="dvfaq-like-button" href="<?php echo esc_url($link); ?>" data-id="<?php the_ID(); ?>" data-nonce="<?php echo esc_attr($nonce); ?>"><span class="dvfaqicon-thumbs-up-alt"></span></a><span class="dvfaq-like-count dvfaq-like-count-<?php the_ID(); ?>"><?php echo esc_html($likes); ?></span></div>
    <?php
}



// DISLIKES

function dvfaq_dislike_it() {
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'dvfaq_dislike_it_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }
    
    $dvfaqcookie = "dvfaqcookie-" . $_REQUEST['post_id'];
 
    if (!isset($_COOKIE[$dvfaqcookie])) {
        $dislikes = get_post_meta( $_REQUEST['post_id'], '_dvfaq_dislikes', true );
        $dislikes = ( empty( $dislikes ) ) ? 0 : $dislikes;
        $new_dislikes = $dislikes + 1;
 
        update_post_meta( $_REQUEST['post_id'], '_dvfaq_dislikes', $new_dislikes );
 
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            setcookie($dvfaqcookie, "dvfaqrated", time()+31500000);
            echo esc_html($new_dislikes);
            die();
        }
        else {
            setcookie($dvfaqcookie, "dvfaqrated", time()+31500000);
            wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
            exit();
        }
    } else {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            $dislikes = get_post_meta( $_REQUEST['post_id'], '_dvfaq_dislikes', true );
            $dislikes = ( empty( $dislikes ) ) ? 0 : $dislikes;
            echo esc_html($dislikes . ' ' . esc_html__( 'You already rated this!', 'dvfaq' ));
            die();
        }
        else {
            wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
            exit();
        }
    }
}

 
function dvfaq_dislike_it_button_html( $content ) {
    global $post;
    $dislike_text = '';
    if( is_singular('dvfaq') ) {
        $nonce = wp_create_nonce( 'dvfaq_dislike_it_nonce' );
        $link = admin_url('admin-ajax.php?action=dvfaq_dislike_it&post_id='.$post->ID.'&nonce='.$nonce);
        $dislikes = get_post_meta( get_the_ID(), '_dvfaq_dislikes', true );
        $dislikes = ( empty( $dislikes ) ) ? 0 : $dislikes;
        $dislike_text = '<div class="dvfaq-dislike-it">
        <a class="dvfaq-dislike-button" href="'.$link.'" data-id="' . get_the_ID() . '" data-nonce="' . $nonce . '"><span class="dvfaqicon-thumbs-down-alt"></span></a><span class="dvfaq-dislike-count dvfaq-dislike-count-'.get_the_ID().'">' . $dislikes . '</span></div>';
    }
    return $content . $dislike_text;
}

function dvfaq_dislike_it_button() {
    $nonce = wp_create_nonce( 'dvfaq_dislike_it_nonce' );
    $link = admin_url('admin-ajax.php?action=dvfaq_dislike_it&post_id='. get_the_ID() .'&nonce='.$nonce);
    $dislikes = get_post_meta( get_the_ID(), '_dvfaq_dislikes', true );
    $dislikes = ( empty( $dislikes ) ) ? 0 : $dislikes;
    ?>
    <div class="dvfaq-dislike-it"><a class="dvfaq-dislike-button" href="<?php echo esc_url($link); ?>" data-id="<?php the_ID(); ?>" data-nonce="<?php echo esc_attr($nonce); ?>"><span class="dvfaqicon-thumbs-down-alt"></span></a><span class="dvfaq-dislike-count dvfaq-dislike-count-<?php the_ID(); ?>"><?php echo esc_html($dislikes); ?></span></div>
    <?php
}

$dvfaq_enable_like = dvfaq_get_option('like_button', 'disable');
$dvfaq_enable_dislike = dvfaq_get_option('dislike_button', 'disable');

if ($dvfaq_enable_like == 'enable') {
    add_action( 'wp_ajax_nopriv_dvfaq_like_it', 'dvfaq_like_it' );
    add_action( 'wp_ajax_dvfaq_like_it', 'dvfaq_like_it' );
    add_filter( 'the_content', 'dvfaq_like_it_button_html' );
    add_action('dvfaq_after_content', 'dvfaq_like_it_button', 8);
}

if ($dvfaq_enable_dislike == 'enable') {
    add_action( 'wp_ajax_nopriv_dvfaq_dislike_it', 'dvfaq_dislike_it' );
    add_action( 'wp_ajax_dvfaq_dislike_it', 'dvfaq_dislike_it' );
    add_filter( 'the_content', 'dvfaq_dislike_it_button_html' );
    add_action('dvfaq_after_content', 'dvfaq_dislike_it_button', 8);
}
?>