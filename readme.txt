=== WPSSO Place / Location and Local Business Meta - aka Local SEO for Facebook, Google, and Pinterest ===
Plugin Name: WPSSO Place / Location and Local Business Meta
Plugin Slug: wpsso-plm
Text Domain: wpsso-plm
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-plm/assets/
Tags: local seo, local business, knowledge graph, location, place, address, venue, restaurant, business hours, telephone, coordinates, meta tags
Contributors: jsmoriss
Requires PHP: 5.4
Requires At Least: 3.8
Tested Up To: 4.9.8
Stable Tag: 3.0.0

WPSSO Core add-on to provide Pinterest Place, Facebook / Open Graph Location, Schema Local Business, and Local SEO meta tags.

== Description ==

<p style="margin:0;"><img class="readme-icon" src="https://surniaulula.github.io/wpsso-plm/assets/icon-256x256.png"></p>

**Let Pinterest, Facebook and Google know about your location(s):**

Include Pinterest Rich Pin *Place*, Facebook / Open Graph *Location*, and Google *Local Business / Local SEO* meta tags in your webpages.

**The WPSSO Place / Location and Local Business Meta (aka WPSSO PLM) add-on can be used in two different ways:**

* To provide location information for the webpage content (ie. the content subject is about a specific physical place / location).

* To provide location information for an Organization, which in turn may be related to the content (ie. the content Publisher, event Organizer, etc.).

The Free version of WPSSO PLM can provide location information for the [WPSSO Organization Markup](https://wordpress.org/plugins/wpsso-organization/) (aka WPSSO ORG) add-on. The WPSSO ORG add-on is required to select a place / location for an organization / local business.

<h3>WPSSO PLM Free / Standard Features</h3>

* Extends the features of the WPSSO Core Free or Pro plugin.

* Manage multiple place / location information:

	* Place Schema Type
	* Place Name
	* Place Alternate Name
	* Place Description
	* Street Address
	* P.O. Box Number
	* City
	* State / Province
	* Zip / Postal Code
	* Country
	* Telephone
	* Place Latitude
	* Place Longitude
	* Place Altitude
	* Place Image ID
	* or Place Image URL
	* Open Days / Hours
	* Open Dates (Seasonal)
	* Local Business:
		* Service Radius
		* Currencies Accepted
		* Payment Accepted
		* Price Range
	* Food Establishment:
		* Accepts Reservations
		* Serves Cuisine
		* Food Menu URL
		* Order Action URL(s)

* Download the Free version from [GitHub](https://surniaulula.github.io/wpsso-plm/) or [WordPress.org](https://wordpress.org/plugins/wpsso-plm/).

<h3>WPSSO PLM Pro / Additional Features</h3>

* Extends the features of WPSSO Core Pro (requires an active and licensed <a href="https://wpsso.com/">WPSSO Core Pro plugin</a>).

* Add a Place / Location settings tab to Posts, Pages, and custom post types and optionally select an existing address, or enter custom address information, for the content.

<h3>Markup Examples</h3>

* [Markup Example for a Restaurant](http://wpsso.com/docs/plugins/wpsso-schema-json-ld/notes/markup-examples/markup-example-for-a-restaurant/) using the WPSSO PLM add-on to manage the Place / Location information (address, geo coordinates, business hours – daily and seasonal, restaurant menu URL, and accepts reservation values).

<h3>WPSSO Core Plugin Prerequisite</h3>

WPSSO Place / Location and Local Business Meta (aka WPSSO PLM) is an add-on for the [WPSSO Core Plugin](https://wordpress.org/plugins/wpsso/) (Free or Pro version). The [WPSSO PLM Pro add-on](https://wpsso.com/extend/plugins/wpsso-plm/) uses WPSSO Core Pro features and requires an active and licensed [WPSSO Core Pro plugin](https://wpsso.com/).

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO PLM Add-on](https://wpsso.com/docs/plugins/wpsso-plm/installation/install-the-plugin/)
* [Uninstall the WPSSO PLM Add-on](https://wpsso.com/docs/plugins/wpsso-plm/installation/uninstall-the-plugin/)

== Frequently Asked Questions ==

== Screenshots ==

01. WPSSO PLM settings page includes options to manage location addresses, geo location, business hours, service radius, price and currency information, restaurant menu URL, and more.
02. WPSSO PLM tab in the Document SSO metabox provides options to manage custom location addresses, geo location, business hours, service radius, price and currency information, restaurant menu URL, and more (Pro version).
03. WPSSO PLM meta tag example for the Schema https://schema.org/Restaurant type shown in Google's Structured Data Testing Tool (only a section of the complete Schema markup is shown).

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Free / Standard Version Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-plm/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-plm/)

<h3>Changelog / Release Notes</h3>

**Version 3.0.1-dev.4 (2018/09/13)**

* *New Features*
	* None.
* *Improvements*
	* Added a static local cache to the WpssoOrgOrganization::get_id() method.
* *Bugfixes*
	* Fixed setting place Open Graph and Schema type when reading post options.
* *Developer Notes*
	* Added a new WpssoPlmFilters::set_post_options() private method called by both the 'save_post_options' and 'get_post_options' filter hooks.

**Version 3.0.0 (2018/09/09)**

* *New Features*
	* Extended the Schema type selection for places / locations from LocalBusiness to Place (which includes LocalBusiness).
* *Improvements*
	* Refactored the Place / Location settings page and tab in the Document SSO metabox.
* *Bugfixes*
	* None.
* *Developer Notes*
	* None.

== Upgrade Notice ==

= 3.0.1-dev.4 =

(2018/09/13) Added a static local cache to the WpssoOrgOrganization::get_id() method.

= 3.0.0 =

(2018/09/09) Extended the Schema type selection for places / locations from LocalBusiness to Place. Refactored the settings page and tab in the Document SSO metabox.

