<?php
/**
 * Plugin Name: Custom Shipping Methods for WooCommerce
 * Plugin URI: https://imaginate-solutions.com/downloads/custom-shipping-methods-for-woocommerce/
 * Description: Add custom shipping methods to WooCommerce.
 * Version: 1.9.1
 * Author: Imaginate Solutions
 * Author URI: https://imaginate-solutions.com
 * Text Domain: custom-shipping-methods-for-woocommerce
 * Domain Path: /langs
 * Copyright: © 2023 Imaginate Solutions
 * WC tested up to: 8.2
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package csm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Custom_Shipping_Methods' ) ) :

	/**
	 * Main Alg_WC_Custom_Shipping_Methods Class
	 *
	 * @class   Alg_WC_Custom_Shipping_Methods
	 * @version 1.9.0
	 * @since   1.0.0
	 */
	final class Alg_WC_Custom_Shipping_Methods {

		/**
		 * Plugin version.
		 *
		 * @var   string
		 * @since 1.0.0
		 */
		public $version = '1.9.1';

		/**
		 * Single instance of class.
		 *
		 * @var   Alg_WC_Custom_Shipping_Methods The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
 		* Core instance for custom shipping methods functionality.
 		* @var object $core
 		*/
		public $core = null;

		/**
		 * Main Alg_WC_Custom_Shipping_Methods Instance
		 *
		 * Ensures only one instance of Alg_WC_Custom_Shipping_Methods is loaded or can be loaded.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @static
		 * @return  Alg_WC_Custom_Shipping_Methods - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Alg_WC_Custom_Shipping_Methods Constructor.
		 *
		 * @version 1.5.2
		 * @since   1.0.0
		 * @access  public
		 */
		public function __construct() {

			// Check for active plugins.
			if (
			! $this->is_plugin_active( 'woocommerce/woocommerce.php' ) ||
			( 'custom-shipping-methods-for-woocommerce.php' === basename( __FILE__ ) && $this->is_plugin_active( 'custom-shipping-methods-for-woocommerce-pro/custom-shipping-methods-for-woocommerce-pro.php' ) )
			) {
				return;
			}

			// Set up localisation.
			load_plugin_textdomain( 'custom-shipping-methods-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

			// Pro.
			if ( 'custom-shipping-methods-for-woocommerce-pro.php' === basename( __FILE__ ) ) {
				require_once 'includes/pro/class-alg-wc-custom-shipping-methods-pro.php';
			}

			// Include required files.
			$this->includes();

			// Admin.
			if ( is_admin() ) {
				$this->admin();
			}
		}

		/**
		 * Is plugin active.
		 *
		 * @param array $plugin Plugin Array.
		 * @return bool
		 * @version 1.5.2
		 * @since   1.5.2
		 */
		public function is_plugin_active( $plugin ) {
			return ( function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin ) :
			(
				in_array( $plugin, apply_filters( 'active_plugins', (array) get_option( 'active_plugins', array() ) ), true ) ||
				( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
			)
			);
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		public function includes() {
			// Core.
			$this->core = require_once 'includes/class-alg-wc-custom-shipping-methods-core.php';
		}

		/**
		 * Admin.
		 *
		 * @version 1.6.1
		 * @since   1.9.0
		 */
		public function admin() {

			//HPOS compatibility
			add_action( 'before_woocommerce_init', array( $this, 'csm_declare_hpos_compatibility' ) );
			//Action links.
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
			// Settings.
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			// Version update.
			if ( get_option( 'alg_wc_custom_shipping_methods_version', '' ) !== $this->version ) {
				add_action( 'admin_init', array( $this, 'version_updated' ) );
			}
		}

		/**
		 * Declare compatibility with HPOS
		 *
		 * @return void
		 */
		public function csm_declare_hpos_compatibility() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );		
			}
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 * @param mixed $links Links array.
		 * @return array
		 */
		public function action_links( $links ) {
			$custom_links   = array();
			$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_custom_shipping_methods' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
			if ( 'custom-shipping-methods-for-woocommerce.php' === basename( __FILE__ ) ) {
				$custom_links[] = '<a target="_blank" href="https://imaginate-solutions.com/downloads/custom-shipping-methods-for-woocommerce/?utm_source=wporg&utm_medium=unlock&utm_campaign=unlock">' .
				__( 'Unlock All', 'custom-shipping-methods-for-woocommerce' ) . '</a>';
			}
			return array_merge( $custom_links, $links );
		}

		/**
		 * Add Custom Shipping Methods settings tab to WooCommerce settings.
		 *
		 * @param array $settings Settings Array.
		 * @return array
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		public function add_woocommerce_settings_tab( $settings ) {
			$settings[] = require_once 'includes/settings/class-alg-wc-settings-custom-shipping-methods.php';
			return $settings;
		}

		/**
		 * Version updated.
		 *
		 * @version 1.2.1
		 * @since   1.1.0
		 */
		public function version_updated() {
			update_option( 'alg_wc_custom_shipping_methods_version', $this->version );
		}

		/**
		 * Get the plugin url.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return  string
		 */
		public function plugin_url() {
			return untrailingslashit( plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return  string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

	}

endif;

if ( ! function_exists( 'alg_wc_custom_shipping_methods' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Custom_Shipping_Methods to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_Custom_Shipping_Methods
	 * @todo    [dev] `plugins_loaded`
	 */
	function alg_wc_custom_shipping_methods() {
		return Alg_WC_Custom_Shipping_Methods::instance();
	}
}

alg_wc_custom_shipping_methods();
