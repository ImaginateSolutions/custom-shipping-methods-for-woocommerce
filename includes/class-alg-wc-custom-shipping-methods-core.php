<?php
/**
 * Custom Shipping Methods for WooCommerce - Core Class
 *
 * @version 1.6.1
 * @since   1.0.0
 * @author  Imaginate Solutions
 * @package csm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Custom_Shipping_Methods_Core' ) ) :

	/**
	 * Shipping Methods Core.
	 */
	class Alg_WC_Custom_Shipping_Methods_Core {

		/**
		 * Cost Arguments
		 * 
		 * @var object Evaluate Cost Object
		 */
		public $evaluate_cost_args = '';
		
		/**
		 * Constructor.
		 *
		 * @version 1.5.2
		 * @since   1.0.0
		 */
		public function __construct() {
			if ( 'yes' === get_option( 'alg_wc_custom_shipping_methods_plugin_enabled', 'yes' ) ) {

				// Init.
				add_action( 'init', array( $this, 'init_custom_shipping' ) );

				// Evaluate shipping cost.
				add_filter( 'alg_wc_custom_shipping_methods_evaluate_cost_replace', array( $this, 'add_evaluate_cost_custom_values' ), 10, 2 );
				add_filter( 'alg_wc_custom_shipping_methods_evaluate_cost_shortcodes', array( $this, 'add_evaluate_cost_shortcodes' ) );
				add_filter( 'alg_wc_custom_shipping_methods_evaluate_cost_args_package', array( $this, 'add_evaluate_cost_custom_args_package' ), 10, 2 );
				add_filter( 'alg_wc_custom_shipping_methods_evaluate_cost_args_class', array( $this, 'add_evaluate_cost_custom_args_class' ), 10, 2 );
				add_action( 'alg_wc_custom_shipping_methods_evaluate_cost_args', array( $this, 'save_evaluate_cost_args' ) );

				// Replace zero cost.
				if ( 'yes' === get_option( 'alg_wc_custom_shipping_methods_do_replace_zero_cost', 'no' ) ) {
					add_filter( 'woocommerce_cart_shipping_method_full_label', array( $this, 'replace_zero_cost' ), PHP_INT_MAX - 1, 2 );
				}

				// Shipping icons & descriptions.
				if ( 'yes' === get_option( 'alg_wc_custom_shipping_methods_icon_desc_enabled', 'no' ) ) {
					add_filter( 'woocommerce_cart_shipping_method_full_label', array( $this, 'add_icon_and_description' ), PHP_INT_MAX, 2 );
				}

				// Trigger checkout update script.
				if ( 'yes' === get_option( 'alg_wc_custom_shipping_methods_do_trigger_checkout_update', 'no' ) ) {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_checkout_script' ) );
				}

				// Custom return URL.
				add_filter( 'woocommerce_get_return_url', array( $this, 'change_order_return_url' ), PHP_INT_MAX, 2 );

			}
		}

		/**
		 * Get order item shipping prop.
		 *
		 * @param mixed $order WC Order.
		 * @param mixed $prop  Prop.
		 * @return mixed
		 * @version 1.5.3
		 * @since   1.5.3
		 */
		public function get_order_item_shipping_prop( $order, $prop ) {
			if ( $order && is_a( $order, 'WC_Order' ) && method_exists( $order, 'get_shipping_methods' ) ) {
				foreach ( $order->get_shipping_methods() as $order_item_shipping ) {
					$instance_id = $order_item_shipping->get_instance_id();
					if (
					is_a( $order_item_shipping, 'WC_Order_Item_Shipping' ) &&
					method_exists( $order_item_shipping, 'get_method_id' ) && 'alg_wc_shipping' === $order_item_shipping->get_method_id() &&
					method_exists( $order_item_shipping, 'get_instance_id' ) && $instance_id &&
					class_exists( 'WC_Shipping_Alg_Custom' )
					) {
						$shipping = new WC_Shipping_Alg_Custom( $instance_id );
						if ( $shipping && '' != $shipping->{$prop} ) {
							return $shipping->{$prop};
						}
					}
				}
			}
			return false;
		}

		/**
		 * Change order return url.
		 *
		 * @param string $return_url Return URL.
		 * @param mixed  $order      WC Order.
		 * @return mixed
		 * @version 1.5.3
		 * @since   1.5.2
		 * @todo    [dev] (maybe) also add this to `woocommerce_get_checkout_order_received_url` filter
		 */
		public function change_order_return_url( $return_url, $order ) {
			$custom_return_url = $this->get_order_item_shipping_prop( $order, 'return_url' );
			return ( false !== $custom_return_url ? $custom_return_url : $return_url );
		}

		/**
		 * Enqueue checkout script.
		 *
		 * @version 1.4.0
		 * @since   1.4.0
		 */
		public function enqueue_checkout_script() {
			if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
				return;
			}
			wp_enqueue_script(
				'alg-wc-custom-shipping-methods-checkout',
				alg_wc_custom_shipping_methods()->plugin_url() . '/includes/js/alg-wc-custom-shipping-methods-checkout.js',
				array( 'jquery' ),
				alg_wc_custom_shipping_methods()->version,
				true
			);
		}


		/**
		 * Replace zero cost.
		 *
		 * @param string $label Label.
		 * @param string $rate  Rate.
		 * @return mixed
		 * @version 1.2.1
		 * @since   1.2.1
		 * @todo    [dev] recheck if this is still working
		 */
		public function replace_zero_cost( $label, $rate ) {
			return ( isset( $rate->method_id ) && 'alg_wc_shipping' === $rate->method_id && 0 == $rate->cost ?
			$rate->get_label() . get_option( 'alg_wc_custom_shipping_methods_replace_zero_cost_text', '' ) : $label );
		}

		/**
		 * Add icon and description.
		 *
		 * @param string $label Label.
		 * @param int    $rate  Rate.
		 * @return string
		 * @version 1.6.0
		 * @since   1.2.0
		 * @todo    [feature] add visibility options: cart / checkout
		 */
		public function add_icon_and_description( $label, $rate ) {
			return apply_filters( 'alg_wc_custom_shipping_methods_icon_and_description', $label, $rate );
		}

		/**
		 * Add evaluate cost custom values.
		 *
		 * @param array $values Values.
		 * @param array $args   Args.
		 * @return array
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_evaluate_cost_custom_values( $values, $args ) {
			$values['[weight]'] = $args['weight'];
			$values['[volume]'] = $args['volume'];
			return $values;
		}

		/**
		 * Add evaluate cost shortcodes.
		 *
		 * @param array $shortcodes Shortcodes.
		 * @return array
		 * @version 1.6.1
		 * @since   1.0.0
		 */
		public function add_evaluate_cost_shortcodes( $shortcodes ) {
			$shortcodes['costs_table'] = array( $this, 'costs_table' );
			$shortcodes['distance']    = array( $this, 'distance' );
			$shortcodes['round']       = array( $this, 'round' );
			return $shortcodes;
		}

		/**
		 * Work out rounding (shortcode).
		 *
		 * @param array  $atts    Attributes.
		 * @param string $content Content.
		 * @return string
		 * @version 1.6.1
		 * @since   1.6.1
		 */
		public function round( $atts, $content = '' ) {
			$content = do_shortcode( $content );
			$content = WC_Eval_Math::evaluate( $content );
			if ( is_numeric( $content ) ) {
				$type = ( isset( $atts['type'] ) ? $atts['type'] : 'normal' );
				switch ( $type ) {
					case 'up':
						$content = ceil( $content );
						break;
					case 'down':
						$content = floor( $content );
						break;
					default: // 'normal'
						$content = round( $content, ( isset( $atts['precision'] ) ? $atts['precision'] : 2 ) );
				}
			}
			return $content;
		}

		/**
		 * Work out distance (shortcode).
		 *
		 * @version 1.6.0
		 * @since   1.3.0
		 * @param   array $atts Attributes.
		 * @return  string
		 */
		public function distance( $atts ) {
			return apply_filters( 'alg_wc_custom_shipping_methods_distance', '', $atts );
		}

		/**
		 * Work out costs_table (shortcode).
		 *
		 * @version 1.6.0
		 * @since   1.0.0
		 * @param   array $atts Attributes.
		 * @return  string
		 */
		public function costs_table( $atts ) {
			return apply_filters( 'alg_wc_custom_shipping_methods_costs_table', '', $atts, $this );
		}

		/**
		 * Add evaluate cost custom args package.
		 *
		 * @param array $args    Args.
		 * @param mixed $package Shipping Package.
		 * @return array
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_evaluate_cost_custom_args_package( $args, $package ) {
			$args['weight'] = $this->get_package_item_weight( $package );
			$args['volume'] = $this->get_package_item_volume( $package );
			return $args;
		}

		/**
		 * Add evaluate cost custom args class.
		 *
		 * @param array $args     Args.
		 * @param mixed $products WC Products.
		 * @return array
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_evaluate_cost_custom_args_class( $args, $products ) {
			$args['weight'] = $this->get_products_weight( $products );
			$args['volume'] = $this->get_products_volume( $products );
			return $args;
		}

		/**
		 * Save evaluate cost args.
		 *
		 * @param array $args Args.
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function save_evaluate_cost_args( $args ) {
			$this->evaluate_cost_args = $args;
		}

		/**
		 * Get items volume in package.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param   array $package WC Shipping Package.
		 * @return array
		 */
		public function get_package_item_volume( $package ) {
			return $this->get_products_volume( $package['contents'] );
		}

		/**
		 * Get products volume.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 * @param   array $products WC Products.
		 * @return int
		 */
		public function get_products_volume( $products ) {
			$total_volume = 0;
			foreach ( $products as $item_id => $values ) {
				if ( $values['data']->needs_shipping() && $values['data']->get_height() && $values['data']->get_width() && $values['data']->get_length() ) {
					$total_volume += $values['data']->get_height() * $values['data']->get_width() * $values['data']->get_length() * $values['quantity'];
				}
			}
			return $total_volume;
		}

		/**
		 * Get items weight in package.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param   array $package WC Shipping Package.
		 * @return int
		 */
		public function get_package_item_weight( $package ) {
			return $this->get_products_weight( $package['contents'] );
		}

		/**
		 * Get products weight.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 * @param   array $products WC Products.
		 * @return int
		 */
		public function get_products_weight( $products ) {
			$total_weight = 0;
			foreach ( $products as $item_id => $values ) {
				if ( $values['data']->needs_shipping() && $values['data']->get_weight() ) {
					$total_weight += $values['data']->get_weight() * $values['quantity'];
				}
			}
			return $total_weight;
		}

		/**
		 * Init custom shipping.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function init_custom_shipping() {
			if ( class_exists( 'WC_Shipping_Method' ) ) {
				require_once 'class-wc-shipping-alg-custom.php';
				add_filter( 'woocommerce_shipping_methods', array( $this, 'add_custom_shipping' ) );
			}
		}

		/**
		 * Add custom shipping.
		 *
		 * @param array $methods Shipping Methods.
		 * @return array
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_custom_shipping( $methods ) {
			$methods['alg_wc_shipping'] = 'WC_Shipping_Alg_Custom';
			return $methods;
		}

	}

endif;

return new Alg_WC_Custom_Shipping_Methods_Core();
