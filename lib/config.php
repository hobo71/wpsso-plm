<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoPlmConfig' ) ) {

	class WpssoPlmConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssoplm' => array(
					'version' => '1.5.5-rc1',		// plugin version
					'opt_version' => '8',		// increment when changing default options
					'short' => 'WPSSO PLM',		// short plugin name
					'name' => 'WPSSO Place and Location Meta (WPSSO PLM)',
					'desc' => 'WPSSO extension to provide Facebook / Open Graph "Location" and Pinterest Rich Pin / Schema "Place" meta tags.',
					'slug' => 'wpsso-plm',
					'base' => 'wpsso-plm/wpsso-plm.php',
					'update_auth' => 'tid',
					'text_domain' => 'wpsso-plm',
					'domain_path' => '/languages',
					'img' => array(
						'icon_small' => 'images/icon-128x128.png',
						'icon_medium' => 'images/icon-256x256.png',
					),
					'url' => array(
						// wordpress
						'download' => 'https://wordpress.org/plugins/wpsso-plm/',
						'review' => 'https://wordpress.org/support/view/plugin-reviews/wpsso-plm?filter=5&rate=5#postform',
						'readme' => 'https://plugins.svn.wordpress.org/wpsso-plm/trunk/readme.txt',
						'wp_support' => 'https://wordpress.org/support/plugin/wpsso-plm',
						// surniaulula
						'update' => 'http://wpsso.com/extend/plugins/wpsso-plm/update/',
						'purchase' => 'http://wpsso.com/extend/plugins/wpsso-plm/',
						'changelog' => 'http://wpsso.com/extend/plugins/wpsso-plm/changelog/',
						'codex' => 'http://wpsso.com/codex/plugins/wpsso-plm/',
						'faq' => 'http://wpsso.com/codex/plugins/wpsso-plm/faq/',
						'notes' => '',
						'feed' => 'http://wpsso.com/category/application/wordpress/wp-plugins/wpsso-plm/feed/',
						'pro_support' => 'http://wpsso-plm.support.wpsso.com/',
					),
					'lib' => array(
						// submenu items must have unique keys
						'submenu' => array (
							'plm-general' => 'Place / Location Meta',	// general settings
							'plm-contact' => 'Addresses / Contacts',
						),
						'gpl' => array(
							'admin' => array(
								'contact' => 'Contact Settings',
								'post' => 'Post Settings',
							),
						),
						'pro' => array(
							'admin' => array(
								'contact' => 'Contact Settings',
								'post' => 'Post Settings',
							),
							'head' => array(
								'place' => 'Place Meta Tags',
							),
						),
					),
				),
			),
			'form' => array(
				'plm_address' => array(
					'none' => '[None]',
					'custom' => '[Custom Address]',
					'new' => '[New Address]',
				),
				'plm_type' => array(
					'geo' => 'Geographic',
					'postal' => 'Postal Address',
				),
				'plm_md_place' => array(
					'plm_streetaddr' => '',		// Street Address
					'plm_po_box_number' => '',	// P.O. Box Number
					'plm_city' => '',		// City
					'plm_state' => '',		// State / Province
					'plm_zipcode' => '',		// Zip / Postal Code
					'plm_country' => '',		// Country
					'plm_latitude' => '',		// Latitude
					'plm_longitude' => '',		// Longitude
					'plm_altitude' => '',		// Altitude in Meters
				),
			),
		);

		public static function get_version() { 
			return self::$cf['plugin']['wpssoplm']['version'];
		}

		public static function set_constants( $plugin_filepath ) { 
			define( 'WPSSOPLM_FILEPATH', $plugin_filepath );						
			define( 'WPSSOPLM_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSOPLM_PLUGINSLUG', self::$cf['plugin']['wpssoplm']['slug'] );	// wpsso-plm
			define( 'WPSSOPLM_PLUGINBASE', self::$cf['plugin']['wpssoplm']['base'] );	// wpsso-plm/wpsso-plm.php
			define( 'WPSSOPLM_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
		}

		public static function require_libs( $plugin_filepath ) {

			require_once( WPSSOPLM_PLUGINDIR.'lib/register.php' );
			require_once( WPSSOPLM_PLUGINDIR.'lib/filters.php' );
			require_once( WPSSOPLM_PLUGINDIR.'lib/address.php' );

			add_filter( 'wpssoplm_load_lib', array( 'WpssoPlmConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {
			if ( $ret === false && ! empty( $filespec ) ) {
				$filepath = WPSSOPLM_PLUGINDIR.'lib/'.$filespec.'.php';
				if ( file_exists( $filepath ) ) {
					require_once( $filepath );
					if ( empty( $classname ) )
						return SucomUtil::sanitize_classname( 'wpssoplm'.$filespec );
					else return $classname;
				}
			}
			return $ret;
		}
	}
}

?>
