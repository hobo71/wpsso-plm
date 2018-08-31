<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoPlmFilters' ) ) {

	class WpssoPlmFilters {

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->p->util->add_plugin_filters( $this, array( 
				'get_defaults'                               => 1,
				'get_md_defaults'                            => 1,
				'rename_options_keys'                        => 1,
				'rename_md_options_keys'                     => 1,
				'og_type'                                    => 2,
				'og_seed'                                    => 2,
				'json_prop_https_schema_org_potentialaction' => 5,
				'json_array_schema_type_ids'                 => 2,
				'schema_meta_itemprop'                       => 4,
				'schema_noscript_array'                      => 4,
				'schema_type_id'                             => 3,
				'get_place_options'                          => 3,
				'get_event_place_id'                         => 3,
			) );

			if ( is_admin() ) {
				$this->p->util->add_plugin_filters( $this, array( 
					'save_options'           => 4,
					'option_type'            => 2,
					'post_custom_meta_tabs'  => 3,
					'messages_tooltip'       => 2,
					'messages_tooltip_post'  => 3,
					'form_cache_place_names' => 1,
				) );
				$this->p->util->add_plugin_filters( $this, array( 
					'status_gpl_features' => 4,
					'status_pro_features' => 4,
				), 10, 'wpssoplm' ); // hook into our own filters
			}
		}

		public function filter_form_cache_place_names( $mixed ) {

			$ret = WpssoPlmPlace::get_names();

			if ( is_array( $mixed ) ) {
				$ret = $mixed + $ret;
			}

			return $ret;
		}

		public function filter_get_defaults( $def_opts ) {

			/**
			 * Add options using a key prefix array and post type names.
			 */
			$def_opts = $this->p->util->add_ptns_to_opts( $def_opts, array(
				'plm_add_to' => 1,
			) );

			return $def_opts;
		}

		public function filter_get_md_defaults( $md_defs ) {

			$md_defs = array_merge( $md_defs, WpssoPlmConfig::$cf['form']['plm_place_opts'],
				array(
					'plm_place_id'      => 'custom',
					'plm_place_country' => $this->p->options['plm_place_def_country'],
				)
			);

			return $md_defs;
		}

		public function filter_rename_options_keys( $options_keys ) {

			$options_keys['wpssoplm'] = array(
				16 => array(
					'plm_addr_alt_name' => 'plm_place_name_alt',
				),
				21 => array(
					'plm_addr_delete'                   => '',
					'plm_addr_for_home'                 => 'plm_place_for_home',
					'plm_addr_def_country'              => 'plm_place_def_country',
					'plm_addr_id'                       => 'plm_place_id',
					'plm_addr_name'                     => 'plm_place_name',
					'plm_addr_name_alt'                 => 'plm_place_name_alt',
					'plm_addr_desc'                     => 'plm_place_desc',
					'plm_addr_streetaddr'               => 'plm_place_street_address',
					'plm_addr_po_box_number'            => 'plm_place_po_box_number',
					'plm_addr_city'                     => 'plm_place_city',
					'plm_addr_state'                    => 'plm_place_state',
					'plm_addr_zipcode'                  => 'plm_place_zipcode',
					'plm_addr_country'                  => 'plm_place_country',
					'plm_addr_phone'                    => 'plm_place_phone',
					'plm_addr_latitude'                 => 'plm_place_latitude',
					'plm_addr_longitude'                => 'plm_place_longitude',
					'plm_addr_altitude'                 => 'plm_place_altitude',
					'plm_addr_schema_type'              => 'plm_place_schema_type',
					'plm_addr_business_type'            => 'plm_place_schema_type',
					'plm_addr_business_phone'           => '',
					'plm_addr_img_id'                   => 'plm_place_img_id',
					'plm_addr_img_id_pre'               => 'plm_place_img_id_pre',
					'plm_addr_img_url'                  => 'plm_place_img_url',
					'plm_addr_day_sunday'               => 'plm_place_day_sunday',
					'plm_addr_day_sunday_open'          => 'plm_place_day_sunday_open',
					'plm_addr_day_sunday_close'         => 'plm_place_day_sunday_close',
					'plm_addr_day_monday'               => 'plm_place_day_monday',
					'plm_addr_day_monday_open'          => 'plm_place_day_monday_open',
					'plm_addr_day_monday_close'         => 'plm_place_day_monday_close',
					'plm_addr_day_tuesday'              => 'plm_place_day_tuesday',
					'plm_addr_day_tuesday_open'         => 'plm_place_day_tuesday_open',
					'plm_addr_day_tuesday_close'        => 'plm_place_day_tuesday_close',
					'plm_addr_day_wednesday'            => 'plm_place_day_wednesday',
					'plm_addr_day_wednesday_open'       => 'plm_place_day_wednesday_open',
					'plm_addr_day_wednesday_close'      => 'plm_place_day_wednesday_close',
					'plm_addr_day_thursday'             => 'plm_place_day_thursday',
					'plm_addr_day_thursday_open'        => 'plm_place_day_thursday_open',
					'plm_addr_day_thursday_close'       => 'plm_place_day_thursday_close',
					'plm_addr_day_friday'               => 'plm_place_day_friday',
					'plm_addr_day_friday_open'          => 'plm_place_day_friday_open',
					'plm_addr_day_friday_close'         => 'plm_place_day_friday_close',
					'plm_addr_day_saturday'             => 'plm_place_day_saturday',
					'plm_addr_day_saturday_open'        => 'plm_place_day_saturday_open',
					'plm_addr_day_saturday_close'       => 'plm_place_day_saturday_close',
					'plm_addr_day_publicholidays'       => 'plm_place_day_publicholidays',
					'plm_addr_day_publicholidays_open'  => 'plm_place_day_publicholidays_open',
					'plm_addr_day_publicholidays_close' => 'plm_place_day_publicholidays_close',
					'plm_addr_season_from_date'         => 'plm_place_season_from_date',
					'plm_addr_season_to_date'           => 'plm_place_season_to_date',
					'plm_addr_service_radius'           => 'plm_place_service_radius',
					'plm_addr_currencies_accepted'      => 'plm_place_currencies_accepted',
					'plm_addr_payment_accepted'         => 'plm_place_payment_accepted',
					'plm_addr_price_range'              => 'plm_place_price_range',
					'plm_addr_accept_res'               => 'plm_place_accept_res',
					'plm_addr_menu_url'                 => 'plm_place_menu_url',
					'plm_addr_cuisine'                  => 'plm_place_cuisine',
					'plm_addr_order_urls'               => 'plm_place_order_urls',
				),
			);

			return $options_keys;
		}

		public function filter_rename_md_options_keys( $options_keys ) {

			$options_keys['wpssoplm'] = array(
				8 => array(
					'plm_streetaddr'    => 'plm_place_street_address',
					'plm_po_box_number' => 'plm_place_po_box_number',
					'plm_city'          => 'plm_place_city',
					'plm_state'         => 'plm_place_state',
					'plm_zipcode'       => 'plm_place_zipcode',
					'plm_country'       => 'plm_place_country',
					'plm_latitude'      => 'plm_place_latitude',
					'plm_longitude'     => 'plm_place_longitude',
					'plm_altitude'      => 'plm_place_altitude',
				),
				16 => array(
					'plm_addr_alt_name' => 'plm_place_name_alt',
				),
				21 => array(
					'plm_addr_id'                       => 'plm_place_id',
					'plm_addr_name'                     => 'plm_place_name',
					'plm_addr_name_alt'                 => 'plm_place_name_alt',
					'plm_addr_desc'                     => 'plm_place_desc',
					'plm_addr_streetaddr'               => 'plm_place_street_address',
					'plm_addr_po_box_number'            => 'plm_place_po_box_number',
					'plm_addr_city'                     => 'plm_place_city',
					'plm_addr_state'                    => 'plm_place_state',
					'plm_addr_zipcode'                  => 'plm_place_zipcode',
					'plm_addr_country'                  => 'plm_place_country',
					'plm_addr_phone'                    => 'plm_place_phone',
					'plm_addr_latitude'                 => 'plm_place_latitude',
					'plm_addr_longitude'                => 'plm_place_longitude',
					'plm_addr_altitude'                 => 'plm_place_altitude',
					'plm_addr_schema_type'              => 'plm_place_schema_type',
					'plm_addr_business_type'            => 'plm_place_schema_type',
					'plm_addr_business_phone'           => '',
					'plm_addr_img_id'                   => 'plm_place_img_id',
					'plm_addr_img_id_pre'               => 'plm_place_img_id_pre',
					'plm_addr_img_url'                  => 'plm_place_img_url',
					'plm_addr_day_sunday'               => 'plm_place_day_sunday',
					'plm_addr_day_sunday_open'          => 'plm_place_day_sunday_open',
					'plm_addr_day_sunday_close'         => 'plm_place_day_sunday_close',
					'plm_addr_day_monday'               => 'plm_place_day_monday',
					'plm_addr_day_monday_open'          => 'plm_place_day_monday_open',
					'plm_addr_day_monday_close'         => 'plm_place_day_monday_close',
					'plm_addr_day_tuesday'              => 'plm_place_day_tuesday',
					'plm_addr_day_tuesday_open'         => 'plm_place_day_tuesday_open',
					'plm_addr_day_tuesday_close'        => 'plm_place_day_tuesday_close',
					'plm_addr_day_wednesday'            => 'plm_place_day_wednesday',
					'plm_addr_day_wednesday_open'       => 'plm_place_day_wednesday_open',
					'plm_addr_day_wednesday_close'      => 'plm_place_day_wednesday_close',
					'plm_addr_day_thursday'             => 'plm_place_day_thursday',
					'plm_addr_day_thursday_open'        => 'plm_place_day_thursday_open',
					'plm_addr_day_thursday_close'       => 'plm_place_day_thursday_close',
					'plm_addr_day_friday'               => 'plm_place_day_friday',
					'plm_addr_day_friday_open'          => 'plm_place_day_friday_open',
					'plm_addr_day_friday_close'         => 'plm_place_day_friday_close',
					'plm_addr_day_saturday'             => 'plm_place_day_saturday',
					'plm_addr_day_saturday_open'        => 'plm_place_day_saturday_open',
					'plm_addr_day_saturday_close'       => 'plm_place_day_saturday_close',
					'plm_addr_day_publicholidays'       => 'plm_place_day_publicholidays',
					'plm_addr_day_publicholidays_open'  => 'plm_place_day_publicholidays_open',
					'plm_addr_day_publicholidays_close' => 'plm_place_day_publicholidays_close',
					'plm_addr_season_from_date'         => 'plm_place_season_from_date',
					'plm_addr_season_to_date'           => 'plm_place_season_to_date',
					'plm_addr_service_radius'           => 'plm_place_service_radius',
					'plm_addr_currencies_accepted'      => 'plm_place_currencies_accepted',
					'plm_addr_payment_accepted'         => 'plm_place_payment_accepted',
					'plm_addr_price_range'              => 'plm_place_price_range',
					'plm_addr_accept_res'               => 'plm_place_accept_res',
					'plm_addr_menu_url'                 => 'plm_place_menu_url',
					'plm_addr_cuisine'                  => 'plm_place_cuisine',
					'plm_addr_order_urls'               => 'plm_place_order_urls',
				),
			);

			return $options_keys;
		}

		public function filter_og_type( $og_type, $mod ) {
			if ( WpssoPlmPlace::has_place( $mod ) ) {
				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'returning og_type = place' );
				}
				return 'place';
			} else {
				return $og_type;
			}
		}

		public function filter_og_seed( array $mt_og, array $mod ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ( $place_opts = WpssoPlmPlace::has_place( $mod ) ) === false ) {
				return $mt_og;     // abort
			}

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( 'returning open graph place meta tags' );
			}

			/**
			 * og:type
			 */
			$og['og:type'] = 'place';	// Pre-define to optimize.

			/**
			 * place:name
			 * place:street_address
			 * place:po_box_number
			 * place:locality
			 * place:region
			 * place:postal_code
			 * place:country_name
			 * place:telephone
			 */
			foreach ( WpssoPlmPlace::$place_mt as $key => $mt_name ) {
				$mt_og[$mt_name] = isset( $place_opts[$key] ) && $place_opts[$key] !== 'none' ? $place_opts[$key] : '';
			}

			/**
			 * og:latitude
			 * og:longitude
			 * og:altitude
			 * place:location:latitude
			 * place:location:longitude
			 * place:location:altitude
			 */
			if ( ! empty( $place_opts['plm_place_latitude'] ) && ! empty( $place_opts['plm_place_longitude'] ) ) {

				foreach( array( 'place:location', 'og' ) as $mt_prefix ) {

					$mt_og[$mt_prefix.':latitude'] = $place_opts['plm_place_latitude'];
					$mt_og[$mt_prefix.':longitude'] = $place_opts['plm_place_longitude'];

					if ( ! empty( $place_opts['plm_altitude'] ) ) {
						$mt_og[$mt_prefix.':altitude'] = $place_opts['plm_place_altitude'];
					}
				}
			}

			/**
			 * Non-standard meta tags for internal use.
			 */
			$place_defs = WpssoPlmConfig::$cf['form']['plm_place_opts'];

			foreach ( $this->p->cf['form']['weekdays'] as $day => $label ) {

				if ( ! empty( $place_opts['plm_place_day_'.$day] ) ) {

					foreach ( array( 'open', 'close' ) as $hour ) {

						$key = 'plm_place_day_'.$day.'_'.$hour;

						$mt_og['place:business:day:'.$day.':'.$hour] = isset( $place_opts[$key] ) ?
							$place_opts[$key] : $place_defs[$key];
					}
				}
			}

			foreach ( array(
				'plm_place_season_from_date'    => 'place:business:season:from_date',
				'plm_place_season_to_date'      => 'place:business:season:to_date',
				'plm_place_service_radius'      => 'place:business:service_radius',
				'plm_place_currencies_accepted' => 'place:business:currencies_accepted',
				'plm_place_payment_accepted'    => 'place:business:payment_accepted',
				'plm_place_price_range'         => 'place:business:price_range',
				'plm_place_accept_res'          => 'place:business:accepts_reservations',
				'plm_place_menu_url'            => 'place:business:menu_url',
				'plm_place_order_urls'          => 'place:business:order_url',
			) as $key => $mt_name ) {

				if ( isset( $place_opts[$key] ) ) {

					if ( $key === 'plm_place_accept_res' ) {
						$mt_og[$mt_name] = empty( $place_opts[$key] ) ? 'false' : 'true';
					} elseif ( $key === 'plm_place_order_urls' ) {
						$mt_og[$mt_name] = SucomUtil::explode_csv( $place_opts[$key] );
					} else {
						$mt_og[$mt_name] = $place_opts[$key];
					}

				} else {
					$mt_og[$mt_name] = '';
				}
			}

			return $mt_og;
		}

		public function filter_json_prop_https_schema_org_potentialaction( $action_data, $mod, $mt_og, $page_type_id, $is_main ) {

			if ( $is_main && ! empty( $mt_og['place:business:order_url'] ) ) {

				$action_data[] = array(
					'@context' => 'https://schema.org',
					'@type'    => 'OrderAction',
					'target'   => $mt_og['place:business:order_url'],
				);
			}

			return $action_data;
		}

		public function filter_json_array_schema_type_ids( $type_ids, $mod ) {

			/**
			 * Array (
			 *	[local.business] => 1
			 *	[website]        => 1
			 *	[organization]   => 1
			 *	[person]         => 1
			 * )
			 */
			if ( WpssoPlmPlace::has_place( $mod ) !== false ) {

				if ( ( $place_opts = WpssoPlmPlace::has_days( $mod ) ) !== false ) {

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'place is a local.business (has business days)' );
					}

					$place_type_id = empty( $place_opts['plm_place_schema_type'] ) ?
						'local.business' : $place_opts['plm_place_schema_type'];

					$type_ids[ $place_type_id ] = true;

				} else {

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'place is not a local.business (no business days)' );
					}

					$type_ids['place'] = true;
				}

			} elseif ( $this->p->debug->enabled ) {
				$this->p->debug->log( 'not a schema place: no place options found' );
			}

			return $type_ids;
		}

		public function filter_schema_type_id( $type_id, $mod, $is_custom ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( $is_custom ) {
				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'exiting early: custom schema type id is true' );
				}
				return $type_id;
			}

			if ( WpssoPlmPlace::has_place( $mod ) !== false ) {

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'mod is defined as a place' );
				}

				if ( ( $place_opts = WpssoPlmPlace::has_days( $mod ) ) !== false ) {
					$type_id = empty( $place_opts['plm_place_schema_type'] ) ?
						'local.business' : $place_opts['plm_place_schema_type'];
				} else {
					$type_id = 'place';
				}

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'returning schema type id '.$type_id );
				}

			} elseif ( $this->p->debug->enabled ) {
				$this->p->debug->log( 'mod is not a place (no place options found)' );
			}

			return $type_id;
		}

		public function filter_schema_meta_itemprop( $mt_schema, $mod, $mt_og, $page_type_id ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ( $place_opts = WpssoPlmPlace::has_place( $mod ) ) !== false ) {

				/**
				 * Place properties.
				 */
				$mt_schema['address'] = WpssoPlmPlace::get_address( $place_opts );

				foreach ( array(
					'plm_place_phone' => 'telephone',	// place phone number
				) as $opt_key => $mt_name ) {
					$mt_schema[$mt_name] = isset( $place_opts[$opt_key] ) ? $place_opts[$opt_key] : '';
				}

				/**
				 * Local business properties.
				 */
				if ( $this->p->schema->is_schema_type_child( $page_type_id, 'local.business' ) ) {

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'schema type is child of local.business' );
					}

					foreach ( array(
						'plm_place_currencies_accepted' => 'currenciesAccepted',
						'plm_place_payment_accepted'    => 'paymentAccepted',
						'plm_place_price_range'         => 'priceRange',
					) as $opt_key => $mt_name ) {

						$mt_schema[$mt_name] = isset( $place_opts[$opt_key] ) ? $place_opts[$opt_key] : '';
					}

				} elseif ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'schema type is not a child of local.business' );
				}

				/**
				 * Food establishment properties.
				 */
				if ( $this->p->schema->is_schema_type_child( $page_type_id, 'food.establishment' ) ) {

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'schema type is child of food.establishment' );
					}

					foreach ( array(
						'plm_place_accept_res' => 'acceptsreservations',
						'plm_place_menu_url'   => 'hasMenu',
						'plm_place_cuisine'    => 'servesCuisine',
					) as $opt_key => $mt_name ) {

						if ( $opt_key === 'plm_place_accept_res' ) {
							$mt_schema[$mt_name] = empty( $place_opts[$opt_key] ) ? 'false' : 'true';
						} else {
							$mt_schema[$mt_name] = isset( $place_opts[$opt_key] ) ? $place_opts[$opt_key] : '';
						}
					}
				}
			}

			return $mt_schema;
		}

		public function filter_schema_noscript_array( $ret, $mod, $mt_og, $page_type_id ) {

			/**
			 * Array (
			 *	[place:business:day:monday:open]          => 09:00
			 *	[place:business:day:monday:close]         => 17:00
			 *	[place:business:day:publicholidays:open]  => 09:00
			 *	[place:business:day:publicholidays:close] => 17:00
			 *	[place:business:season:from_date]         => 2016-04-01
			 *	[place:business:season:to_date]           => 2016-05-01
			 * )
			 */
			if ( $this->p->schema->is_schema_type_child( $page_type_id, 'local.business' ) ) {	// just in case

				$mt_business = SucomUtil::preg_grep_keys( '/^place:business:/', $mt_og );

				if ( ! empty( $mt_business ) ) {

					foreach ( $this->p->cf['form']['weekdays'] as $day => $label ) {

						$mt_day = array();

						if ( ! empty( $mt_business['place:business:day:'.$day.':open'] ) &&
							! empty( $mt_business['place:business:day:'.$day.':open'] ) ) {
	
							$mt_day[] = array( array( '<noscript itemprop="openingHoursSpecification" '.
								'itemscope itemtype="https://schema.org/OpeningHoursSpecification">' . "\n" ) );

							$mt_day[] = $this->p->head->get_single_mt( 'meta', 'itemprop',
								'openinghoursspecification.dayofweek', $day, '', $mod );
	
							foreach ( array(
								'place:business:day:'.$day.':open'  => 'openinghoursspecification.opens',
								'place:business:day:'.$day.':close' => 'openinghoursspecification.closes',
								'place:business:season:from_date'   => 'openinghoursspecification.validfrom',
								'place:business:season:to_date'     => 'openinghoursspecification.validthrough',
							) as $mt_key => $prop_name ) {
								if ( isset( $mt_business[$mt_key] ) ) {
									$mt_day[] = $this->p->head->get_single_mt( 'meta', 'itemprop',
										$prop_name, $mt_business[$mt_key], '', $mod );
								}
							}
	
							$mt_day[] = array( array( '</noscript>' . "\n" ) );
						}
						foreach ( $mt_day as $arr ) {
							foreach ( $arr as $val ) {
								$ret[] = $val;
							}
						}
					}
				}
			}

			return $ret;
		}

		public function filter_get_place_options( $opts, $mod, $place_id ) {

			if ( false === $opts && ( $place_id === 'custom' || is_numeric( $place_id ) ) ) {

				$place_opts = WpssoPlmPlace::get_id( $place_id, $mod );

				if ( is_array( $place_opts ) ) {	// just in xase
					return SucomUtil::preg_grep_keys( '/^plm_place_/', $place_opts, false, 'place_' );	// rename plm_place to place
				}
			}

			return $opts;
		}

		public function filter_get_event_place_id( $place_id, array $mod, $event_id ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ( $place_opts = WpssoPlmPlace::has_md_place( $mod, 'plm_place_name' ) ) !== false ) {

				if ( isset( $place_opts['plm_place_id'] ) ) {

					$place_id = $place_opts['plm_place_id'];

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'returning place id '.$place_id.' '.
							( $event_id !== false ? 'for event id '.$event_id : '(event id is false)' ) );
					}
				}
			}

			return $place_id; 
		}

		public function filter_save_options( $opts, $options_name, $network, $doing_upgrade ) {

			if ( $network ) {
				return $opts;	// Nothing to do.
			}

			$plm_place_names = SucomUtil::get_multi_key_locale( 'plm_place_name', $opts, false );	// $add_none is false.
			$plm_last_num    = SucomUtil::get_last_num( $plm_place_names );

			foreach ( $plm_place_names as $num => $name ) {

				$name = trim( $name );

				/**
				 * Remove empty "New Place".
				 */
				if ( ! empty( $opts['plm_place_delete_'.$num] ) || ( $name === '' && $num === $plm_last_num ) ) {

					if ( isset( $opts['plm_place_id'] ) && $opts['plm_place_id'] === $num ) {
						unset( $opts['plm_place_id'] );
					}

					/**
					 * Remove the place, including all localized keys.
					 */
					$opts = SucomUtil::preg_grep_keys( '/^plm_place_.*_'.$num.'(#.*)?$/', $opts, true );	// $invert is true.

					continue;	// Check the next place.
				}

				if ( $name === '' ) {	// Just in case.
					$name = sprintf( _x( 'Place #%d', 'option value', 'wpsso-plm' ), $num );
				}

				$opts['plm_place_name_'.$num] = $name;

				if ( ! empty( $opts['plm_place_img_id_'.$num] ) ) {	// Image id 0 is not valid.

					/**
					 * Remove the image url options if we have an image id.
					 */
					unset(
						$opts['plm_place_img_url_'.$num],
						$opts['plm_place_img_url:width_'.$num],
						$opts['plm_place_img_url:height_'.$num]
					);

					/**
					 * Get the location image and issue an error if the original image is too small. Only check
					 * on a manual save, not an options upgrade action (ie. when a new add-on is activated).
					 */
					if ( ! $doing_upgrade ) {
						$this->check_location_image_size( $opts, 'plm', $num );
					}
				}
			}

			return $opts;
		}

		/**
		 * Get the location image and issue an error if the original image is too small.
		 */
		private function check_location_image_size( $opts, $opt_pre, $opt_num = null ) {

			if ( $opt_pre === 'plm' ) {
				$name_transl = SucomUtil::get_key_value( $opt_pre . '_name_' . $opt_num, $opts, 'current' );
			} else {
				return;
			}

			$size_name          = $this->p->lca . '-schema';
			$opt_img_pre        = $opt_pre . '_img';
			$context_transl     = sprintf( __( 'saving place "%1$s"', 'wpsso-plm' ), $name_transl );
			$settings_page_link = $this->p->util->get_admin_url( 'plm-general' );

			$this->p->notice->set_ref( $settings_page_link, null, $context_transl );

			/**
			 * Returns an image array:
			 *
			 * array(
			 *	'og:image:url'     => null,
			 *	'og:image:width'   => null,
			 *	'og:image:height'  => null,
			 *	'og:image:cropped' => null,
			 *	'og:image:id'      => null,
			 *	'og:image:alt'     => null,
			 * );
			 */
			$og_single_image = $this->p->media->get_opts_single_image( $opts, $size_name, $opt_img_pre, $opt_num );

			$this->p->notice->unset_ref( $settings_page_link );
		}

		public function filter_option_type( $type, $base_key ) {

			if ( ! empty( $type ) ) {
				return $type;
			} elseif ( strpos( $base_key, 'plm_' ) !== 0 ) {
				return $type;
			}

			switch ( $base_key ) {

				case 'plm_place_for_home':
				case 'plm_place_def_country':	// alpha2 country code
				case 'plm_place_id':		// 'none', 'custom', or numeric (including 0)
				case 'plm_place_schema_type':
				case ( preg_match( '/^plm_place_(country|type)$/', $base_key ) ? true : false ):

					return 'not_blank';

					break;

				case ( preg_match( '/^plm_place_(name|name_alt|desc|phone|street_address|city|state|zipcode)$/', $base_key ) ? true : false ):
				case ( preg_match( '/^plm_place_(phone|price_range|cuisine)$/', $base_key ) ? true : false ):

					return 'ok_blank';	// text strings that can be blank

					break;

				case ( preg_match( '/^plm_place_(currencies_accepted|payment_accepted)$/', $base_key ) ? true : false ):

					return 'csv_blank';	// comma-delimited strings that can be blank

					break;

				case ( preg_match( '/^plm_place_(latitude|longitude|altitude|service_radius|po_box_number)$/', $base_key ) ? true : false ):

					return 'blank_num';	// must be numeric (blank or zero is ok)

					break;

				case ( preg_match( '/^plm_place_day_[a-z]+_(open|close)$/', $base_key ) ? true : false ):

					return 'time';

					break;

				case ( preg_match( '/^plm_place_season_(from|to)_date$/', $base_key ) ? true : false ):

					return 'date';

					break;

				case 'plm_place_menu_url':

					return 'url';

					break;

				case 'plm_place_order_urls':

					return 'csv_urls';

					break;

				case 'plm_place_accept_res':
				case ( preg_match( '/^plm_place_day_[a-z]+$/', $base_key ) ? true : false ):

					return 'checkbox';

					break;
			}

			return $type;
		}

		public function filter_post_custom_meta_tabs( $tabs, $mod, $metabox_id ) {

			if ( $metabox_id === $this->p->cf['meta']['id'] ) {
				if ( ! empty( $this->p->options['plm_add_to_'.$mod['post_type']] ) ) {
					SucomUtil::add_after_key( $tabs, 'media', 'plm', _x( 'Edit Place', 'metabox tab', 'wpsso-plm' ) );
				}
			}

			return $tabs;
		}

		public function filter_messages_tooltip_post( $text, $idx, $atts ) {

			if ( strpos( $idx, 'tooltip-post-plm_' ) !== 0 ) {
				return $text;
			}

			switch ( $idx ) {
				case 'tooltip-post-plm_place_id':
					$text = __( 'Select a place or enter custom place information below.', 'wpsso-plm' );
					break;
			}

			return $text;
		}

		public function filter_messages_tooltip( $text, $idx ) {

			if ( strpos( $idx, 'tooltip-plm_' ) !== 0 ) {
				return $text;
			}

			switch ( $idx ) {

				case 'tooltip-plm_place_id':

					$text = __( 'Select a place to edit. The place information is used for Open Graph meta tags and Schema markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_schema_type':	// Place Schema Type

					$text = __( 'You may optionally choose a different Schema type for this place / location (default is LocalBusiness).', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_name':

					$text = __( 'A name for this place / location (required). The place name may appear in forms and in the Schema Place "name" property.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_name_alt':

					$text = __( 'An alternate name for this place. The place alternate name may appear in the Schema Place "alternateName" property.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_desc':

					$text = __( 'A description for this place.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_street_address':

					$text = __( 'An optional street address used for Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_po_box_number':

					$text = __( 'An optional post office box number for the Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_city':

					$text = __( 'An optional city name for the Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_state':

					$text = __( 'An optional state or Province name for the Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_zipcode':

					$text = __( 'An optional zip or postal code for the Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_country':

					$text = __( 'An optional country for the Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_phone':

					$text = __( 'An optional telephone number for this place / location.', 'wpsso-plm' );

					break;
				case 'tooltip-plm_place_latitude':

					$text = __( 'The numeric decimal degrees latitude for this place (required).', 'wpsso-plm' ).' ';
					
					$text .= __( 'You may use a service like <a href="http://www.gps-coordinates.net/">Google Maps GPS Coordinates</a> (as an example), to find the approximate GPS coordinates of a street address.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_longitude':

					$text = __( 'The numeric decimal degrees longitude for this place (required).', 'wpsso-plm' ).' ';
					
					$text .= __( 'You may use a service like <a href="http://www.gps-coordinates.net/">Google Maps GPS Coordinates</a> (as an example), to find the approximate GPS coordinates of a street address.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_altitude':

					$text = __( 'An optional numeric altitude (in meters above sea level) for this place.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_img_id':	// Place Image ID

					$text = __( 'An image ID and media library selection for this place (ie. an image of the business location).', 'wpsso-plm' ) . ' ';
					
					$text .= __( 'The place image is used in the Schema LocalBusiness markup for the \'location\' Schema property.', 'wpsso-plm' ).' ';
					
					$text .= __( 'The business location image is not used when a place is added to a post, page, or custom post type.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_img_url':	// or Place Image URL

					$text = __( 'You can enter a place image URL (including the http:// prefix) instead of choosing an image ID &mdash; if a place image ID is specified, it has precedence and the image URL option is disabled.', 'wpsso-plm' ).' ';
					
					$text .= __( 'The image URL option allows you to use an image outside of a managed collection (WordPress Media Library or NextGEN Gallery), and/or a smaller logo style image.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_days':	// Open Days / Hours

					$text = __( 'Select the days and hours this place / location is open.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_season_dates':

					$text = __( 'If this place is open seasonally, select the open and close dates of the season.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_service_radius':

					$text = __( 'The geographic area where a service is provided, in meters around the location.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_currencies_accepted':

					$text = __( 'A comma-delimited list of <a href="https://en.wikipedia.org/wiki/ISO_4217">ISO 4217 currency codes</a> accepted by the local business (example: USD, CAD).', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_payment_accepted':

					$text = __( 'A comma-delimited list of payment options accepted by the local business (example: Cash, Credit Card).', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_price_range':

					$text = __( 'The price range of goods or services provided by the local business (example: $10-100).', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_accept_res':

					$text = __( 'This food establishment accepts reservations.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_menu_url':

					$text = __( 'The menu URL for this food establishment.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_cuisine':

					$text = __( 'The cuisine served by this food establishment.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_order_urls':

					$text = __( 'A comma-delimited list of website and mobile app URLs to order products.', 'wpsso-plm' ).' ';
					
					$text .= __( 'The WPSSO JSON add-on is required to add these Order Action URL(s) to the Schema potentialAction property.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_for_home':

					$metabox_title = _x( $this->p->cf['meta']['title'], 'metabox title', 'wpsso' );	// Use wpsso's text domain.

					$text = __( 'Select a place / location to include as a Schema Place in your blog (non-static) front page.', 'wpsso-plm' ).' ';
					
					$text .= sprintf( __( 'A place for a static front page can be selected in the %1$s metabox when editing the static post / page.', 'wpsso-plm' ), $metabox_title );

					break;

				case 'tooltip-plm_place_def_country':

					$text = __( 'A default country to use when creating a new place / location.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_add_to':

					$metabox_title = _x( $this->p->cf['meta']['title'], 'metabox title', 'wpsso' );	// Use wpsso's text domain.
					$metabox_tab   = _x( 'Edit Place', 'metabox tab', 'wpsso-plm' );

					$text = sprintf( __( 'A "%1$s" tab can be added to the %2$s metabox on Posts, Pages, and custom post types, allowing you to enter specific place information for that webpage content (ie. GPS coordinates and/or street address).', 'wpsso-plm' ), $metabox_tab, $metabox_title );

					break;

			}

			return $text;

		}

		public function filter_status_gpl_features( $features, $ext, $info, $pkg ) {

			$has_place_for_home = $this->p->options['plm_place_for_home'] === '' ||
				$this->p->options['plm_place_for_home'] === 'none' ? false : true;	// can be 0

			$features['(code) Place / Location for Blog Front Page'] = array(
				'status' => $has_place_for_home ? 'on' : 'off',
			);

			return $features;
		}

		public function filter_status_pro_features( $features, $ext, $info, $pkg ) {

			$features['(tool) Custom Place / Location and Local Business Meta'] = array( 
				'td_class' => $pkg['pp'] ? '' : 'blank',
				'purchase' => $pkg['purchase'],
				'status'   => $pkg['pp'] ? 'on' : 'off',
			);

			return $features;
		}
	}
}
