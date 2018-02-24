<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoPlmConfig' ) ) {

	class WpssoPlmConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssoplm' => array(			// Plugin acronym.
					'version' => '2.5.0-rc.3',		// Plugin version.
					'opt_version' => '16',		// Increment when changing default option values.
					'short' => 'WPSSO PLM',		// Short plugin name.
					'name' => 'WPSSO Place / Location and Local Business Meta',
					'desc' => 'WPSSO Core extension to provide Pinterest Place, Facebook / Open Graph Location, Schema Local Business, and Local SEO meta tags.',
					'slug' => 'wpsso-plm',
					'base' => 'wpsso-plm/wpsso-plm.php',
					'update_auth' => 'tid',
					'text_domain' => 'wpsso-plm',
					'domain_path' => '/languages',
					'req' => array(
						'short' => 'WPSSO Core',
						'name' => 'WPSSO Core',
						'min_version' => '3.54.0-rc.3',
					),
					'img' => array(
						'icons' => array(
							'low' => 'images/icon-128x128.png',
							'high' => 'images/icon-256x256.png',
						),
					),
					'lib' => array(
						'submenu' => array(	// Note that submenu elements must have unique keys.
							'plm-general' => 'Place / Location',
						),
						'gpl' => array(
							'admin' => array(
								'post' => 'Post Settings',
							),
						),
						'pro' => array(
							'admin' => array(
								'post' => 'Post Settings',
							),
						),
					),
				),
			),
			'form' => array(
				'plm_addr_opts' => array(
					'plm_addr_name' => '',				// Name
					'plm_addr_name_alt' => '',			// Altername Name
					'plm_addr_desc' => '',				// Description
					'plm_addr_streetaddr' => '',			// Street Address
					'plm_addr_po_box_number' => '',			// P.O. Box Number
					'plm_addr_city' => '',				// City
					'plm_addr_state' => '',				// State / Province
					'plm_addr_zipcode' => '',			// Zip / Postal Code
					'plm_addr_country' => '',			// Country
					'plm_addr_latitude' => '',			// Latitude
					'plm_addr_longitude' => '',			// Longitude
					'plm_addr_altitude' => '',			// Altitude
					'plm_addr_business_type' => 'local.business',
					'plm_addr_phone' => '',
					'plm_addr_day_sunday' => 0,
					'plm_addr_day_sunday_open' => '09:00',
					'plm_addr_day_sunday_close' => '17:00',
					'plm_addr_day_monday' => 0,
					'plm_addr_day_monday_open' => '09:00',
					'plm_addr_day_monday_close' => '17:00',
					'plm_addr_day_tuesday' => 0,
					'plm_addr_day_tuesday_open' => '09:00',
					'plm_addr_day_tuesday_close' => '17:00',
					'plm_addr_day_wednesday' => 0,
					'plm_addr_day_wednesday_open' => '09:00',
					'plm_addr_day_wednesday_close' => '17:00',
					'plm_addr_day_thursday' => 0,
					'plm_addr_day_thursday_open' => '09:00',
					'plm_addr_day_thursday_close' => '17:00',
					'plm_addr_day_friday' => 0,
					'plm_addr_day_friday_open' => '09:00',
					'plm_addr_day_friday_close' => '17:00',
					'plm_addr_day_saturday' => 0,
					'plm_addr_day_saturday_open' => '09:00',
					'plm_addr_day_saturday_close' => '17:00',
					'plm_addr_day_publicholidays' => 0,
					'plm_addr_day_publicholidays_open' => '09:00',
					'plm_addr_day_publicholidays_close' => '17:00',
					'plm_addr_service_radius' => '',
					'plm_addr_currencies_accepted' => '',
					'plm_addr_payment_accepted' => '',
					'plm_addr_price_range' => '',
					'plm_addr_accept_res' => 0,
					'plm_addr_menu_url' => '',
					'plm_addr_cuisine' => '',
					'plm_addr_order_urls' => '',
				),
			),
		);

		public static function get_version( $add_slug = false ) {
			$ext = 'wpssoplm';
			$info =& self::$cf['plugin'][$ext];
			return $add_slug ? $info['slug'].'-'.$info['version'] : $info['version'];
		}

		public static function set_constants( $plugin_filepath ) { 
			if ( defined( 'WPSSOPLM_VERSION' ) ) {			// execute and define constants only once
				return;
			}
			define( 'WPSSOPLM_VERSION', self::$cf['plugin']['wpssoplm']['version'] );						
			define( 'WPSSOPLM_FILEPATH', $plugin_filepath );						
			define( 'WPSSOPLM_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSOPLM_PLUGINSLUG', self::$cf['plugin']['wpssoplm']['slug'] );	// wpsso-plm
			define( 'WPSSOPLM_PLUGINBASE', self::$cf['plugin']['wpssoplm']['base'] );	// wpsso-plm/wpsso-plm.php
			define( 'WPSSOPLM_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
		}

		public static function require_libs( $plugin_filepath ) {

			require_once WPSSOPLM_PLUGINDIR.'lib/register.php';
			require_once WPSSOPLM_PLUGINDIR.'lib/filters.php';
			require_once WPSSOPLM_PLUGINDIR.'lib/address.php';

			add_filter( 'wpssoplm_load_lib', array( 'WpssoPlmConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {
			if ( false === $ret && ! empty( $filespec ) ) {
				$filepath = WPSSOPLM_PLUGINDIR.'lib/'.$filespec.'.php';
				if ( file_exists( $filepath ) ) {
					require_once $filepath;
					if ( empty( $classname ) ) {
						return SucomUtil::sanitize_classname( 'wpssoplm'.$filespec, false );	// $underscore = false
					} else {
						return $classname;
					}
				}
			}
			return $ret;
		}
	}
}

