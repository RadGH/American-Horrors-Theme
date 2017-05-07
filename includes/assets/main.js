jQuery(function () {
	init_mobile_button();

	init_roku_player();

	init_age_verification();
});

function init_mobile_button() {
	var $body = jQuery('body');

	var mobile_open = false;

	var rate_limit = false;

	var toggleMenuStart = function() {
		$body.addClass('mmenu-fading');

		if ( typeof requestAnimationFrame === 'function' ) {
			requestAnimationFrame(function() {
				$body
					.toggleClass('mmenu-open', mobile_open)
					.toggleClass('mmenu-close', !mobile_open);
			});
		}else{
			$body
				.toggleClass('mmenu-open', mobile_open)
				.toggleClass('mmenu-close', !mobile_open);
		}
	};

	var toggleMenuFinish = function() {
		rate_limit = false;
		$body.removeClass('mmenu-fading');
	};

	$body.on('click', '.mobile-button', function(e) {
		if ( rate_limit !== false ) {
			clearTimeout(rate_limit);
			toggleMenuFinish();
		}

		mobile_open = !mobile_open;

		toggleMenuStart();

		rate_limit = setTimeout(toggleMenuFinish, 300);
	});

	toggleMenuStart();
	toggleMenuFinish();
}

function init_roku_player() {
	var $roku = jQuery('#roku-player');
	if ( $roku.length < 1 ) return;

	$f( "roku-player", flowplayer_swf, {

		// server: rtmp://199.189.86.11:1935/live
		// application: live
		// stream: Stream1

		clip: {
			url: 'Stream1',
			// scaling: 'fit',
			// configure clip to use hddn as our provider, referring to our rtmp plugin
			provider: 'rtmp'
		},

		// streaming plugins are configured under the plugins node
		plugins: {

			// here is our rtmp plugin configuration
			rtmp: {
				url: "flowplayer.rtmp-3.2.13.swf",

				// netConnectionUrl defines where the streams are found
				netConnectionUrl: 'rtmp://199.189.86.11:1935/live'
			}
		},
		canvas: {
			backgroundGradient: 'none'
		}
	});
}

function init_age_verification() {
	var has_verified_age = getCookie('age');
	if ( has_verified_age ) return;

	var $body = jQuery('body');

	var $age_frame = jQuery('<div>', {class: 'age-frame'}).css('display', 'none');

	var $age_verify = jQuery('<a>', {class: 'age-verify button'}).attr('href', '#');
	var $age_cancel = jQuery('<a>', {class: 'age-cancel button'}).attr('href', '#');

	var $origin_target = false;

	$age_frame.append(
		jQuery('<div>', {class: 'age-inner'}).append(
			jQuery('<div>', {class: 'age-modal'}).append(
				jQuery('<p>').text('Please verify that you are age 18 or older.')
			).append(
				$age_verify.text('Yes, I am 18 or older')
			).append(
				$age_cancel.text('No, I am under 18')
			)
		)
	);

	$body.append( $age_frame );

	var $intercept_elements = jQuery('#roku-player, #filmon-player, .vod-embed-code');
	var $intercept_wrap = jQuery('<div>', {class: 'age-intercept-wrap'});
	var $intercept_wall = jQuery('<a>', {class: 'age-intercept-wall'}).attr('href', '#');

	$intercept_elements.each(function() {
		var $wrap_clone = $intercept_wrap.clone();
		$wrap_clone.data('age-target', this);

		jQuery(this)
			.wrap( $wrap_clone )
			.before( $intercept_wall.clone() );
	});

	$body.on('click', '.age-intercept-wall', function(e) {
		$origin_target = jQuery( jQuery(this).closest('.age-intercept-wrap').data('age-target') );
		$age_frame.css('display', 'block');
		return false;
	});

	// Clicking cancel hides the frame, you can't continue until you agree
	$age_cancel.add($age_frame).click(function() {
		$age_frame.css('display', 'none');
		$origin_target = false;
		return false;
	});

	// Clicking OK stores a cookie and permits you to continue, and will attempt to click the original target you clicked on.
	$age_verify.click(function() {
		// Remember age with a cookie
		setCookie( 'age', '18+', 1 );

		// Remove the popup, and all of the walls, while keeping the content in the wall.
		$age_frame.remove();

		jQuery('.age-intercept-wrap').children().each(function() {
			jQuery(this).parent().after(this);
		}).end().remove();

		jQuery('.age-intercept-wall').remove();

		// Click on the origin target, is possible (the element that was identified with $intercept_elements).
		if ( $origin_target && $origin_target.length > 0 ) {
			$origin_target.click();
			$origin_target.find('a').click();
		}

		return false;
	});
}

function setCookie(name,value,days) {
	var expires = "";
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days*24*60*60*1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + value + expires + "; path=/";
}

function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function deleteCookie(name) {
	setCookie(name,"",-1);
}
