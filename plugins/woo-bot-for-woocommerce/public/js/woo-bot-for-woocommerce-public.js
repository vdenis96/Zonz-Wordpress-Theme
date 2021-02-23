jQuery(document).ready( function(){


	// popup on exit intent
	jQuery.exitIntent('enable');
	jQuery(document).bind('exitintent', function() {
		jQuery('#woo-bot-messenger').addClass('woo-shake');
		if( jQuery('#woo-bot-exit-intent-hidden-message').length > 0 ) {
			if( ! jQuery('#woo-bot-message-box .scroll-content > div:last').hasClass('woo-bot-exit-message') ){
				var wc_msg_timeout = 0;
				var exit_message = jQuery('#woo-bot-exit-intent-hidden-message').html();
				if( jQuery('#woo-bot-message-box .scroll-content > div').length == 1 && jQuery('#woo-bot-messenger').is(":hidden") ){
					jQuery(this).ajaxUpdateChatContent('welcome_message');
					wc_msg_timeout = 2000;
				}
				jQuery('#woo-bot-message-box .scroll-content').append('<div class="woo-bot-chat-to woo-bot-exit-message">'+ exit_message +'</div>');
				if( jQuery('#woo-bot-message-box .scroll-content > div').length > 0 ){
					setTimeout(function(){ 
						jQuery(this).ajaxUpdateChatContent('exit_intent_message');
					}, parseInt(wc_msg_timeout) );
				}
			}
			if( jQuery('#woo-bot-messenger').is(":hidden") ){
				jQuery('#woo-bot-opener').trigger('click');
			}
			jQuery('#woo-bot-message-box .scroll-content').animate({ scrollTop: jQuery('#woo-bot-message-box .scroll-content')[0].scrollHeight }, 1000);
		}
		setTimeout(function(){ 
			jQuery('#woo-bot-messenger').removeClass('woo-shake');
		}, 1000 )
	});

	// enable chat slick scroll bar
	jQuery('.wb-scrollbar-inner').scrollbar();

	jQuery('#woo-bot-message-box .scroll-content').animate({ scrollTop: jQuery('#woo-bot-message-box .scroll-content')[0].scrollHeight }, 1000);

	jQuery("form#woo-bot-message-form").on("submit", function (event) {
		event.preventDefault();

		var chat_msg = jQuery('#woo-bot-message-input').val();
		chat_msg = chat_msg.trim();

		if( '' == chat_msg ){
			jQuery('#woo-bot-message-input').val('').attr('placeholder', 'Please enter your message').addClass('woo-bot-message-input-error');
		}else{

			jQuery('#woo-bot-message-box .scroll-content').append('<div class="woo-bot-chat-from">'+chat_msg+'</div>');

			var formDataVar = jQuery(this).serialize();
			jQuery.ajax({
				url: woobot_ajaxurl,
				type: "POST",
				data: formDataVar,
				beforeSend: function() {
					jQuery('#woo-bot-message-box .scroll-content').append('<div class="woo-bot-chat-to empty"><div class="dot-pulse"></div></div>');
				},
				success: function(response) {
					if( response.answer_type ){
						jQuery('#woo-bot-answer-type').val( response.answer_type );
					}
					if( response.reply ){
						//jQuery('#woo-bot-message-box .scroll-content').append('<div class="woo-bot-chat-to">'+response.reply+'</div>');
						jQuery('#woo-bot-message-box .scroll-content .woo-bot-chat-to.empty').html(response.reply).removeClass('empty');
						jQuery('#woo-bot-message-box .scroll-content').animate({ scrollTop: jQuery('#woo-bot-message-box .scroll-content')[0].scrollHeight }, 1000);

						jQuery('#woo-bot-message-input').focus();
					}
				}
			});

			jQuery('#woo-bot-message-input').val('').attr('placeholder', 'Send your message').removeClass('woo-bot-message-input-error');

		}

		jQuery('#woo-bot-message-box .scroll-content').animate({ scrollTop: jQuery('#woo-bot-message-box .scroll-content')[0].scrollHeight }, 1000);

		return false;

	});

	jQuery(document).on('click', '.wb_chat_option', function(){
		var thisText = jQuery(this).text();
		jQuery('#woo-bot-message-input').val(thisText);
		jQuery('#woo-bot-message-form').submit();
	});

	jQuery(document).on('click', '#woo-bot-opener', function(){
		var chat_icon = jQuery(this).attr('data-chat-icon');
		var real_icon = 'dashicons dashicons-'+chat_icon;
		var close_icon = 'dashicons dashicons-no-alt';
		if( jQuery('#woo-bot-messenger').is(":visible") ){
			jQuery('#woo-bot-messenger').slideUp();
			jQuery('#woo-bot-opener > span').attr('class',real_icon);
			//jQuery('#woo-bot-opener').addClass('bounce-opener');
		}else{
			if( jQuery('#woo-bot-message-box .scroll-content > div').length == 1 ){
				jQuery(this).ajaxUpdateChatContent('welcome_message');
			}
			jQuery('#woo-bot-messenger').slideDown();
			jQuery('#woo-bot-message-input').focus();
			jQuery('#woo-bot-opener > span').attr('class',close_icon);
			//jQuery('#woo-bot-opener').removeClass('bounce-opener');
		}
		jQuery('#woo-bot-message-box .scroll-content').animate({ scrollTop: jQuery('#woo-bot-message-box .scroll-content')[0].scrollHeight  }, 1000);
	});
	
	jQuery.fn.ajaxUpdateChatContent = function( msgtype = 'welcome_message' ) {
		jQuery.ajax({
			url: woobot_ajaxurl,
			type: "POST",
			data: {msg_type: msgtype, action: 'woo_bot_chat_content_data_update'},
			success: function(response) {
				//console.log( response );
			}
		});
	}
	
});



(function ($) {
	'use strict';

	var timer;

	function trackLeave(ev) {
		if (ev.clientY > 0) {
			//console.log(ev.clientY);
			return;
		}

		if (timer) {
			clearTimeout(timer);
		}

		if ($.exitIntent.settings.sensitivity <= 0) {
			$.event.trigger('exitintent');
			return;
		}

		timer = setTimeout(
			function () {
				timer = null;
				$.event.trigger('exitintent');
			}, $.exitIntent.settings.sensitivity);
	}

	function trackEnter() {
		if (timer) {
			clearTimeout(timer);
			timer = null;
		}
	}

	$.exitIntent = function (enable, options) {
		$.exitIntent.settings = $.extend($.exitIntent.settings, options);

		if (enable == 'enable') {
			$(window).mouseleave(trackLeave);
			$(window).mouseenter(trackEnter);
		} else if (enable == 'disable') {
			trackEnter(); // Turn off any outstanding timer
			$(window).unbind('mouseleave', trackLeave);
			$(window).unbind('mouseenter', trackEnter);
		} else {
			throw "Invalid parameter to jQuery.exitIntent -- should be 'enable'/'disable'";
		}
	}

	$.exitIntent.settings = {
		'sensitivity': 300
	};

})(jQuery);
