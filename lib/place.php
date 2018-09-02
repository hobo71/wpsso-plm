<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoPlmPlace' ) ) {

	class WpssoPlmPlace {

		private $p;
		private static $mod_md_opts = array();	// get_md_options() meta data cache

		public static $place_mt = array(
			'plm_place_name'           => 'place:name',
			'plm_place_name_alt'       => 'place:name_alt',
			'plm_place_desc'           => 'place:description',
			'plm_place_street_address' => 'place:street_address',
			'plm_place_po_box_number'  => 'place:po_box_number',
			'plm_place_city'           => 'place:locality',
			'plm_place_state'          => 'place:region',
			'plm_place_zipcode'        => 'place:postal_code',
			'plm_place_country'        => 'place:country_name',
			'plm_place_phone'          => 'place:telephone',
		);

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}
		}

		public static function get_names( $schema_type = '', $add_none = false, $add_new = false, $add_custom = false ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->mark();
			}

			$first_names = array();
			$place_names = array();

			if ( $add_none ) {
				$first_names['none'] = $wpsso->cf['form']['place_select']['none'];
			}

			if ( $add_custom ) {
				$first_names['custom'] = $wpsso->cf['form']['place_select']['custom'];
			}

			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->log( 'getting multi keys for plm_place_name' );
			}

			$place_names = SucomUtil::get_multi_key_locale( 'plm_place_name', $wpsso->options, false );	// $add_none = false

			if ( ! empty( $schema_type ) && is_string( $schema_type) ) {

				if ( $wpsso->debug->enabled ) {
					$wpsso->debug->log( 'removing places not in schema type: ' . $schema_type );
				}

				$children = $wpsso->schema->get_schema_type_children( $schema_type );

				if ( ! empty( $children ) ) {	// just in case
					foreach ( $place_names as $num => $name ) {
						if ( ! empty( $wpsso->options['plm_place_schema_type_' . $num] ) && 
							in_array( $wpsso->options['plm_place_schema_type_' . $num], $children ) ) {
							continue;
						} else {
							unset( $place_names[$num] );
						}
					}
				}

			} elseif ( $wpsso->debug->enabled ) {
				$wpsso->debug->log( 'business type not provided - keeping all places' );
			}

			if ( $add_new ) {
				$next_num = SucomUtil::get_next_num( $place_names );
				$place_names[$next_num] = $wpsso->cf['form']['place_select']['new'];
			}

			if ( ! empty( $first_names ) ) {
				$place_names = $first_names + $place_names;	// combine arrays, preserving numeric key associations
			}

			return $place_names;
		}

		/**
		 * Get a specific place id. If $id is 'custom' then $mixed must be the $mod array.
		 */
		public static function get_id( $id, $mixed = 'current' ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->log_args( array( 
					'id'    => $id,
					'mixed' => $mixed,
				) );
			}

			$place_opts = array();

			if ( $id === 'custom' ) {

				if ( isset( $mixed['obj'] ) && is_object( $mixed['obj'] ) ) {

					$md_opts = self::get_md_options( $mixed );				// returns all plm options from the post

					foreach ( SucomUtil::preg_grep_keys( '/^(plm_place_.*)(#.*)?$/', 	// filter for all place options
						$md_opts, false, '$1' ) as $opt_idx => $value ) {

						$place_opts[$opt_idx] = SucomUtil::get_key_value( $opt_idx, $md_opts, $mixed );
					}
				}

			} elseif ( is_numeric( $id ) ) {

				foreach ( SucomUtil::preg_grep_keys( '/^(plm_place_.*_)' . $id . '(#.*)?$/',
					$wpsso->options, false, '$1' ) as $opt_prefix => $value ) {	// allow '[:_]' as separator

					$opt_idx = rtrim( $opt_prefix, '_' );

					$place_opts[$opt_idx] = SucomUtil::get_key_value( $opt_prefix . $id, $wpsso->options, $mixed );
				}
			}

			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->log( $place_opts );
			}

			if ( empty( $place_opts ) ) {
				return false; 
			} else {
				return array_merge( WpssoPlmConfig::$cf['form']['plm_place_opts'], $place_opts );	// complete the array
			}
		}

		/**
		 * Text value for the https://schema.org/address property.
		 */
		public static function get_address( array $place_opts ) {

			$address = '';

			foreach ( array( 
				'plm_place_street_address',
				'plm_place_po_box_number',
				'plm_place_city',
				'plm_place_state',
				'plm_place_zipcode',
				'plm_place_country',
			) as $key ) {

				if ( isset( $place_opts[$key] ) && $place_opts[$key] !== '' && $place_opts[$key] !== 'none' ) {

					switch ( $key ) {

						case 'plm_place_name':

							$place_opts[$key] = preg_replace( '/\s*,\s*/', ' ', $place_opts[$key] );	// Just in case.

							break;

						case 'plm_place_po_box_number':

							$address = rtrim( $address, ', ' ) . ' #';	// Continue street address.

							break;
					}

					$address .= $place_opts[$key] . ', ';
				}
			}

			return rtrim( $address, ', ' );
		}

		public static function get_md_options( array $mod ) {

			if ( ! is_object( $mod['obj'] ) ) {	// just in case
				return array();
			}

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->mark();
			}

			if ( ! isset( self::$mod_md_opts[$mod['name']][$mod['id']] ) ) {	// make sure a cache entry exists
				if ( $wpsso->debug->enabled ) {
					$wpsso->debug->log( 'getting new options for static array cache' );
				}
				self::$mod_md_opts[$mod['name']][$mod['id']] = array();
			} else {
				if ( $wpsso->debug->enabled ) {
					$wpsso->debug->log( 'returning options from static array cache' );
				}
				return self::$mod_md_opts[$mod['name']][$mod['id']];		// return the cache entry
			}

			$md_opts =& self::$mod_md_opts[$mod['name']][$mod['id']];		// shortcut variable
			$md_opts = $mod['obj']->get_options( $mod['id'] );			// returns empty string if no meta found

			if ( is_array( $md_opts  ) ) {

				if ( isset( $md_opts['plm_place_id'] ) && is_numeric( $md_opts['plm_place_id'] ) ) {	// allow for 0

					if ( ( $place_opts = self::get_id( $md_opts['plm_place_id'] ) ) !== false ) {

						if ( $wpsso->debug->enabled ) {
							$wpsso->debug->log( 'using place id ' . $md_opts['plm_place_id'] . ' options' );
						}

						$md_opts = array_merge( $md_opts, $place_opts );
					}
				}

				$md_opts = SucomUtil::preg_grep_keys( '/^plm_/', $md_opts );	// Only return plm options.

				if ( ! empty( $md_opts ) ) { 
					if ( empty( $md_opts['plm_place_country'] ) ) {
						$md_opts['plm_place_country'] = isset( $wpsso->options['plm_place_def_country'] ) ?
							$wpsso->options['plm_place_def_country'] : 'none';
					}
				}
			}

			return $md_opts;
		}

		public static function has_place( array $mod ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->mark();
			}

			$place_opts = false;

			if ( is_object( $mod['obj'] ) ) {	// Just in case.

				if ( ( $place_opts = self::has_md_place( $mod, 'plm_place_name' ) ) === false ) {

					if ( $wpsso->debug->enabled ) {
						$wpsso->debug->log( 'no place options from module object' );
					}
				}

			} elseif ( $wpsso->debug->enabled ) {
				$wpsso->debug->log( 'not home index and no module object' );
			}

			if ( $wpsso->debug->enabled ) {
				if ( false === $place_opts ) {
					$wpsso->debug->log( 'no place options found' );
				} else {
					$wpsso->debug->log( count( $place_opts ) . ' place options found' );
				}
			}

			return $place_opts;
		}

		public static function has_md_place( array $mod, $idx_exists = '' ) {

			$wpsso =& Wpsso::get_instance();

			if ( ! is_object( $mod['obj'] ) ) {	// just in case
				return false;
			}

			$md_opts = self::get_md_options( $mod );

			/**
			 * Check for a specific index key.
			 */
			if ( ! empty( $idx_exists ) ) {
				if ( isset( $md_opts[$idx_exists] ) ) {
					if ( ! isset( $md_opts['plm_place_id'] ) ) {
						$md_opts['plm_place_id'] = 'custom';
					}
					if ( $wpsso->debug->enabled ) {
						$wpsso->debug->log( 'returning place options - index key ' . $idx_exists . ' exists' );
						$wpsso->debug->log_arr( 'md_opts', $md_opts );
					}
					return $md_opts;
				}
			} elseif ( is_array( $md_opts  ) ) {
				foreach ( self::$place_mt as $key => $mt_name ) {
					if ( ! empty( $md_opts[$key] ) ) {
						if ( $wpsso->debug->enabled ) {
							$wpsso->debug->log( 'returning place options - one or more option keys found' );
							$wpsso->debug->log_arr( 'md_opts', $md_opts );
						}
						return $md_opts;
					}
				}
			}

			return false;
		}

		public static function has_days( array $mod ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->mark();
			}

			$place_opts = false;

			if ( is_object( $mod['obj'] ) ) {	// Just in case.
				if ( ( $place_opts = self::has_md_days( $mod ) ) === false ) {
					if ( $wpsso->debug->enabled ) {
						$wpsso->debug->log( 'no business days from module object' );
					}
				}
			} elseif ( $wpsso->debug->enabled ) {
				$wpsso->debug->log( 'not home index and no module object' );
			}

			return $place_opts;
		}

		public static function has_md_days( array $mod ) {

			if ( ! is_object( $mod['obj'] ) ) {	// just in case
				return false;
			}
			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->mark();
			}

			$md_opts = self::get_md_options( $mod );

			if ( is_array( $md_opts  ) ) {
				foreach ( $wpsso->cf['form']['weekdays'] as $day => $label ) {
					if ( ! empty( $md_opts['plm_place_day_' . $day] ) ) {
						return $md_opts;
					}
				}
			}

			return false;
		}

		public static function has_geo( array $mod ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->mark();
			}

			$place_opts = false;

			if ( is_object( $mod['obj'] ) ) {	// Just in case.

				if ( ( $place_opts = self::has_md_geo( $mod ) ) === false ) {
					if ( $wpsso->debug->enabled ) {
						$wpsso->debug->log( 'no geo coordinates from module object' );
					}
				}

			} elseif ( $wpsso->debug->enabled ) {
				$wpsso->debug->log( 'not home index and no module object' );
			}

			return $place_opts;
		}

		public static function has_md_geo( array $mod ) {

			if ( ! is_object( $mod['obj'] ) ) {	// just in case
				return false;
			}

			$md_opts = self::get_md_options( $mod );

			if ( is_array( $md_opts  ) ) {

				/**
				 * Allow for latitude and/or longitude of 0.
				 */
				if ( isset( $md_opts['plm_place_latitude'] ) && $md_opts['plm_place_latitude']!== '' && 
					isset( $md_opts['plm_place_longitude'] ) && $md_opts['plm_place_longitude'] !== '' ) {

					return $md_opts;
				}
			}

			return false;
		}
	}
}
