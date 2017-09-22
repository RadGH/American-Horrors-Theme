<?php

if ( !defined('ABSPATH' ) ) exit;


$advertisements = acf_add_options_page(array(
	'page_title' => 'Advertisements',
	'menu_title' => 'Advertisements',
	'menu_slug'  => 'advertisements',
	'icon_url'   => 'dashicons-analytics',
	'capability' => 'manage_options',
	'autoload'   => true
));