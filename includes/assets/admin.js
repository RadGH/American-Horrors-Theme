jQuery(function() {
	if ( typeof pagenow === 'undefined' ) return;

	// Add from YouTube functionality
	if ( pagenow === 'vod_page_acf-options-add-from-youtube' ) {
		init_add_from_youtube();
	}
});

function init_add_from_youtube() {
	var $url_field = jQuery('#acf-field_592b46cb98302');
	var $btn_field = jQuery('#vod-quick-add-youtube');

	var get_video_info = function(e) {
		var video_url = $url_field.val(); // 'https://www.youtube.com/watch?v=aO1K9jNZS7c';
		var video_id = video_url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);

		if ( video_id !== null ) video_id = video_id[1]; // Use video ID from matching regex pattern
		if ( !video_id ) video_id = video_url; // Fallback to assume the video URL field was actually a video ID (unlikely - no instructions to do this)

		if ( !video_id ) {
			alert('Could not determine video ID');
			return;
		}

		// Get the video info
		jQuery.getJSON({
			url: 'https://www.googleapis.com/youtube/v3/videos',
			dataType: 'jsonp',
			method: 'GET',

			data: {
				id: video_id,
				part: 'snippet',
				key: 'AIzaSyBB1XIhYYYB-m8K6C1RL_99pK6Y3CQqxqw'
			},

			success: function(data, status, xhr) {
				if ( !data ) {
					alert('No response from YouTube.');
					return;
				}

				if ( typeof data.error === 'object' && data.error ) {
					alert('Error code ' + data.error.code + ":\n\n" + data.error.message + "\n\n(See console for more information)");
					if ( typeof console.log === 'function' ) {
						console.log( 'Error code ' + data.error.code );
						console.log(data);
					}
					return;
				}

				if ( typeof data.items[0].id === 'undefined' || !data.items[0].id ) {
					alert("Error: Unexpected result:\n\nYouTube returned an unknown data type. See console for more information.");
					if ( typeof console.log === 'function' ) {
						console.log('Error: Unexpected result from YouTube');
						console.log(data);
					}
					return;
				}

				var title = data.items[0].snippet.title || "";
				var description = data.items[0].snippet.description || "";
				var image_url = data.items[0].snippet.thumbnails.default.url || "";
				var embed_code = '<iframe width="720" height="405" src="https://www.youtube.com/embed/'+ data.items[0].id +'?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>';

				// Use high res thumbnail if possible
				if ( typeof data.items[0].snippet.thumbnails.high.url !== 'string' ) image_url = data.items[0].snippet.thumbnails.high.url;

				jQuery('#acf-field_592b46e398303').val(title); // Title
				jQuery('#acf-field_592b46f898304').val(description); // Description
				jQuery('#acf-field_592b470598305').val(image_url); // Image URL
				jQuery('#acf-field_592b471398306').val(embed_code); // Embed Code
			}
		});
	};

	$btn_field.off('click', get_video_info).on('click', get_video_info);
}