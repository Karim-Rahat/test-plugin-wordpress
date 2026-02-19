jQuery(document).on( 'click', '.dvfaq-like-it', function() {
    var post_id = jQuery(this).find('.dvfaq-like-button').attr('data-id'),
        nonce = jQuery(this).find('.dvfaq-like-button').attr("data-nonce");
    jQuery.ajax({
        url : dvfaqlikeit.ajax_url,
        type : 'post',
        data : {
            action : 'dvfaq_like_it',
            post_id : post_id,
            nonce : nonce
        },
        success : function( response ) {
            jQuery('body').find('.dvfaq-like-count-'+post_id).html( response );
        }
    });    
    return false;
});
jQuery(document).on( 'click', '.dvfaq-dislike-it', function() {
    var post_id = jQuery(this).find('.dvfaq-dislike-button').attr('data-id'),
        nonce = jQuery(this).find('.dvfaq-dislike-button').attr("data-nonce");
    jQuery.ajax({
        url : dvfaqlikeit.ajax_url,
        type : 'post',
        data : {
            action : 'dvfaq_dislike_it',
            post_id : post_id,
            nonce : nonce
        },
        success : function( response ) {
            jQuery('body').find('.dvfaq-dislike-count-'+post_id).html( response );
        }
    });     
    return false;
});