jQuery( document ).ready( function(){

    /* Approve Plugin Animation */
    var offset = jQuery('#upSmart_RS').offset();
    var topPadding = 30;
    if(offset != null){
	    jQuery(window).scroll(function() {
	        if (jQuery(window).scrollTop() > (offset.top + jQuery('#sidebar').height())) {
	            jQuery('#upSmart_RS').stop().animate({
	                marginTop: jQuery(window).scrollTop() - offset.top + topPadding
	            });
	        } else {
	            jQuery('#upSmart_RS').stop().animate({
	                marginTop: jQuery('#sidebar').height()
	            });
	        };
	    });
    }
    jQuery('#upSmart_RS').css('margin-top', jQuery('#sidebar').height());
    jQuery('div.v_line').remove();
    
    if(jQuery( '.rs_img' ) == null){
    	jQuery( '.approve' ).css('height', '420');
    }
    
    /* Approve Company Mechanism */
    jQuery( '.rs_img' ).live( 'click', function() {
    
      jQuery( '.rs_img' ).addClass( 'rs_loading' );
      jQuery( '.rs_loading' ).removeClass( 'rs_img' );
    
      var link = this;
      var anchor = jQuery( link ).attr( 'href' ).split( '#' );
      var list = anchor[1].split( ',' );
      var nonce = list[0];
      var id = list[1];
      var status = list[2];
      
      var data = {
             action: 'upSmart_RS_ajax_php',
             postID: id,
             nonce: nonce,
             ap: status
            };
        
      jQuery.post( upSmart_RS_l10n.ajaxurl, data, function( r ) {
              /*alert(r); */
	      if( r.error == 1 ){
	        jQuery( 'div#ratePrompt' ).html( r.msg );
	      }
	      else if( r.error == 2 ){
	      	jQuery( 'div#ratePrompt' ).html( r.msg );
	      	jQuery( '.rs_loading' ).addClass( 'rs_img' );
	      	jQuery( '.rs_img' ).removeClass( 'rs_loading' );
	      }
	      else{
	        var rank = jQuery( 'span.rs_rank' ).html();
	        jQuery( '.approve' ).css('height', '420');
	        jQuery( 'span.rs_rank' ).html( ++rank );
	        /*jQuery( 'div#ratePrompt' ).html( 'User ID: ' + r.msg );*/
	        jQuery( '.rs_loading' ).addClass( 'rs_approved' );
	        jQuery( '.rs_approved' ).removeClass( 'rs_loading' );
	      }
    });
    return false;

  });

});