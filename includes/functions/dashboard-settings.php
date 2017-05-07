<?php

// "FROM" EMAIL SETTINGS

add_action( 'admin_init', 'register_rssfeed_settings' );

function register_from_email_settings() {
	add_settings_field(
		'from_email',         // id
		'"From" Email',       // setting title
		'from_email_display', // display callback
		'general',            // settings page
		'default'             // settings section
	);
	register_setting(
		'general',            // option page
		'from_email',         // option name
		'sanitize_from_email' // validation callback
	);
}

add_action( 'admin_init', 'register_from_email_settings' );

function sanitize_from_email($value) {
	// sanitizes from_email as if it were admin_email
	return sanitize_option( 'admin_email', $value );
}

function from_email_display() {
	echo '<div id="from_email"><input name="from_email" type="email" class="regular-text ltr" value="';
	echo get_option( 'from_email' ) ? : get_option( 'admin_email' );
	echo '" /><p class="description">This address sends email notifications to users.</p></div>';
	?>
	<script type="text/javascript">
		var tbody = document.querySelector('tbody');
		tbody.insertBefore(  document.getElementById('from_email').parentNode.parentNode, tbody.children[5] );
		document.querySelector('label[for="admin_email"]').textContent = "Admin Email";
		document.getElementById('admin-email-description').textContent = "This address receives email notifications from the website.";
	</script>
	<?php
}

// Change the default "from" name
function rs_tweaks_default_from_name( $name ) {
	if ( empty($name) || $name == "WordPress" ) return get_bloginfo('title');
	return $name;
}
add_filter( 'wp_mail_from_name', 'rs_tweaks_default_from_name', 11 );

// If sending from admin email, send from the custom "From email" in Settings > General instead
function rs_tweaks_default_from_email( $email ) {
	$admin_email = get_option('admin_email');
	$from_email = get_option('from_email');

	if ( $from_email && (empty($email) || $email == $admin_email) ) return $from_email;
	return $email;
}
add_filter( 'wp_mail_from', 'rs_tweaks_default_from_email', 11 );

// Make Gravity Forms default to "from email" set in Settings > General
// gf_apply_filters( array( 'gform_notification', $form['id'] ), $notification, $form, $lead );
function gf_override_default_from_address( $notification, $form, $lead ) {
	if ( $notification['from'] == "{admin_email}" ) $notification['from'] = get_option('admin_email');

	$notification['from'] = apply_filters( 'wp_mail_from', $notification['from'] );
	$notification['fromName'] = apply_filters( 'wp_mail_from_name', $notification['fromName'] );

	return $notification;
}
add_filter( 'gform_notification', 'gf_override_default_from_address', 20, 3 );



// CROPPED THUMBNAIL IMAGE SIZE SETTINGS

function register_cropped_thumbnail_settings() {
	add_settings_field(
		'cropped_thumbnail',      // id
		'Cropped thumbnail size', // setting title
		'crop_thumb_display',     // display callback
		'media',                  // settings page
		'default'                 // settings section
	);
	register_setting(
		'media',                  // option page
		'crop_thumb_w',           // option name
		'intval'                  // validation callback
	);
	register_setting(
		'media',                  // option page
		'crop_thumb_h',           // option name
		'intval'                  // validation callback
	);
}
add_action( 'admin_init', 'register_cropped_thumbnail_settings' );

function crop_thumb_display() {
	echo '<div id="crop_thumb">Width <input type="number" class="small-text" step="1" min="0" name="crop_thumb_w" value="';
	echo get_option( "crop_thumb_w",180);
	echo '" /> ';
	echo 'Height <input type="number" class="small-text" step="1" min="0" name="crop_thumb_h" value="';
	echo get_option( "crop_thumb_h",180);
	echo '" /></div>';
	?>
	<script type="text/javascript">
		var table = document.getElementById("crop_thumb").parentNode.parentNode.parentNode;
		table.insertBefore(  document.getElementById('crop_thumb').parentNode.parentNode, table.childNodes[1] );
		document.getElementById('thumbnail_size_w').previousSibling.previousSibling.textContent = "Max Width";
		document.getElementById('thumbnail_size_h').previousSibling.previousSibling.textContent = "Max Height";
	</script>
	<?php
}

// adds new images sizes using given dimensions, with fallbacks

$width = get_option( "crop_thumb_w",180);
$height = get_option( "crop_thumb_h",180);
add_image_size( 'thumbnail-cropped', $width, $height, true );




// RSS FEED IMAGE SIZE SETTINGS

function register_rssfeed_settings() {

	add_settings_field(
		'rssfeed',             // id
		'RSS feed image size', // setting title
		'rssfeed_display',     // display callback
		'media',               // settings page
		'default'              // settings section
	);
	register_setting(
		'media',               // option page
		'rssfeed_w',           // option name
		'intval'               // validation callback
	);
	register_setting(
		'media',               // option page
		'rssfeed_h',           // option name
		'intval'               // validation callback
	);

}

function rssfeed_display() {
	echo 'Width <input type="number" class="small-text" step="1" min="0" name="rssfeed_w" value="';
	echo get_option( "rssfeed_w",560);
	echo '" /> ';
	echo 'Height <input type="number" class="small-text" step="1" min="0" name="rssfeed_h" value="';
	echo get_option( "rssfeed_h",280 );
	echo '" />';
}

// adds new images sizes using given dimensions, with fallbacks
$width = get_option( "rssfeed_w",560);
$height = get_option( "rssfeed_h",280);
add_image_size( 'rssfeed', $width, $height, true );
