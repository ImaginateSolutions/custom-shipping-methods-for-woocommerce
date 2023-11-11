<?php
/**
 * Custom Shipping Methods for WooCommerce - Custom Shipping Class
 *
 * @version 1.6.2
 * @since   1.0.0
 * @author  Imaginate Solutions
 * @package csm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_Shipping_Alg_Custom' ) ) :

	/**
	 * Custom Shipping Method Class.
	 */
	class WC_Shipping_Alg_Custom extends WC_Shipping_Method {

		/**
		 * Description for the shipping method.
		 * @var string $alg_wc_csm_desc
		 */	
		public $alg_wc_csm_desc;
		/**
		* Icon for the shipping method (if applicable).
 		* @var string $alg_wc_csm_icon 
 		*/
		public $alg_wc_csm_icon;
		/**
		 * Base cost for the shipping method.
		 * @var float $cost
		 */
		public $cost;
		/**
		 * Minimum cost required for this shipping method to be available.
		 * @var float $min_cost_limit
		 */
		public $min_cost_limit;
		/**
		 * Maximum cost allowed for this shipping method to be available.
		 * @var float $max_cost_limit
		 */
		public $max_cost_limit;
		/**
		 * Minimum amount for free shipping to be available.
		 * @var float $free_shipping_min_amount
		 */
		public $free_shipping_min_amount;
		/**
		 * List of products eligible for free shipping.
		 * @var string $free_shipping_products
		 */
		public $free_shipping_products;
		/**
		 * Type of the shipping method (e.g., flat rate, weight-based, etc.).
		 * @var string $type
		 */
		public $type;
		/**
		 * Minimum cost for the shipping method.
		 * @var float $min_cost
		 */
		public $min_cost;
		/**
		 * Maximum cost for the shipping method.
		 * @var float $max_cost
		 */
		public $max_cost;
		/**
		 * Minimum weight for the shipping method.
		 * @var float $min_weight
		 */
		public $min_weight;
		/**
		 * Maximum weight for the shipping method.
		 * @var float $max_weight
		 */
		public $max_weight;
		/**
		 * Minimum volume for the shipping method.
		 * @var float $min_volume
		 */
		public $min_volume;
		/**
		 * Maximum volume for the shipping method.
		 * @var float $max_volume
		 */
		public $max_volume;
		/**
		 * Minimum quantity required for this shipping method.
		 * @var int $min_qty
		 */
		public $min_qty;
		/**
		 * Minimum distance for the shipping method.
		 * @var float $min_distance
		 */
		public $min_distance;
		/**
		 * Maximum distance for the shipping method.
		 * @var float $max_distance
		 */
		public $max_distance;
		/**
		 * Method used to calculate distance.
		 * @var string $distance_calculation
		 */
		public $distance_calculation;
		/**
		 * List of products included for this shipping method.
		 * @var string $incl_product
		 */
		public $incl_product;
		/**
		 * List of products excluded for this shipping method.
		 * @var string $excl_product
		 */
		public $excl_product;
		/**
		 * List of product categories included for this shipping method.
		 * @var string $incl_product_cat
		 */
		public $incl_product_cat;
		/**
		 * List of product categories excluded for this shipping method.
		 * @var string $excl_product_cat
		 */
		public $excl_product_cat;
		/**
		 * List of product tags included for this shipping method.
		 * @var string $incl_product_tag
		 */
		public $incl_product_tag;
		/**
		 * List of product tags excluded for this shipping method.
		 * @var string $excl_product_tag
		 */
		public $excl_product_tag;
		/**
		 * Requirement type for this shipping method.
		 * @var string $require_type
		 */
		public $require_type;
		/**
		 * Calculation method for cost/limit.
		 * @var string $limit_calc
		 */
		public $limit_calc;
		/**
		 * URL to return to after selecting this shipping method.
		 * @var string $return_url
		 */
		public $return_url;
		/**
		 * Maximum quantity allowed for this shipping method.
		 * @var int $max_qty
		 */
		public $max_qty;
		/**
		 * A general description or comment about the shipping method.
		 * @var string $desc
		 */
		public $desc;
		/**
		 * Fee Cost.
		 *
		 * @var string cost passed to [fee] shortcode
		 */
		public $fee_cost = '';

		/**
		 * Constructor.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 * @param   int $instance_id Shipping Instance ID.
		 * @todo    [feature] add free shipping **coupon** functionality
		 */
		public function __construct( $instance_id = 0 ) {
			$this->id                 = 'alg_wc_shipping';
			$this->instance_id        = absint( $instance_id );
			$this->method_title       = get_option( 'alg_wc_custom_shipping_methods_admin_title', __( 'Custom shipping', 'custom-shipping-methods-for-woocommerce' ) );
			$this->method_description = __( 'Custom shipping method.', 'custom-shipping-methods-for-woocommerce' );
			$this->supports           = array(
				'shipping-zones',
				'instance-settings',
				'instance-settings-modal',
			);
			$this->init();

			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		/**
		 * Init user set variables.
		 *
		 * @version 1.6.2
		 * @since   1.0.0
		 * @todo    [feature] customizable admin title and description (i.e. per method instance)
		 */
		public function init() {
			$this->instance_form_fields     = include 'settings/settings-custom-shipping.php';
			$this->title                    = $this->get_option( 'title' );
			$this->alg_wc_csm_icon          = $this->get_option( 'alg_wc_csm_icon' );
			$this->alg_wc_csm_desc          = $this->get_option( 'alg_wc_csm_desc' );
			$this->tax_status               = $this->get_option( 'tax_status' );
			$this->cost                     = $this->get_option( 'cost' );
			$this->min_cost_limit           = $this->get_option( 'min_cost_limit' );
			$this->max_cost_limit           = $this->get_option( 'max_cost_limit' );
			$this->free_shipping_min_amount = $this->get_option( 'free_shipping_min_amount', 0 );
			$this->free_shipping_products   = $this->get_option( 'free_shipping_products', '' );
			$this->type                     = $this->get_option( 'type', 'class' );
			$this->min_cost                 = $this->get_option( 'min_cost' );
			$this->max_cost                 = $this->get_option( 'max_cost' );
			$this->min_weight               = $this->get_option( 'min_weight' );
			$this->max_weight               = $this->get_option( 'max_weight' );
			$this->min_volume               = $this->get_option( 'min_volume' );
			$this->max_volume               = $this->get_option( 'max_volume' );
			$this->min_qty                  = $this->get_option( 'min_qty' );
			$this->max_qty                  = $this->get_option( 'max_qty' );
			$this->min_distance             = $this->get_option( 'min_distance' );
			$this->max_distance             = $this->get_option( 'max_distance' );
			$this->distance_calculation     = $this->get_option( 'distance_calculation' );
			$this->incl_product             = $this->get_option( 'incl_product' );
			$this->excl_product             = $this->get_option( 'excl_product' );
			$this->incl_product_cat         = $this->get_option( 'incl_product_cat' );
			$this->excl_product_cat         = $this->get_option( 'excl_product_cat' );
			$this->incl_product_tag         = $this->get_option( 'incl_product_tag' );
			$this->excl_product_tag         = $this->get_option( 'excl_product_tag' );
			$this->require_type             = $this->get_option( 'require_type', 'one' );
			$this->limit_calc               = $this->get_option( 'limit_calc', 'class' );
			$this->return_url               = $this->get_option( 'return_url', '' );
		}

		/**
		 * Get package products data.
		 *
		 * @param mixed  $products Products List.
		 * @param string $type     Product Type.
		 * @return array
		 * @version 1.6.0
		 * @since   1.6.0
		 */
		public function get_package_products_data( $products, $type = 'product' ) {
			// Product IDs.
			$product_ids = wp_list_pluck( $products, 'product_id' );
			if ( 'product' === $type ) {
				return $product_ids;
			}
			// Cats & Tags IDs.
			$result = array();
			foreach ( $product_ids as $product_id ) {
				$product_terms = get_the_terms( $product_id, $type );
				if ( $product_terms && ! is_wp_error( $product_terms ) ) {
					$result = array_merge( $result, wp_list_pluck( $product_terms, 'term_id' ) );
				}
			}
			return $result;
		}

		/**
		 * Is this method available?
		 *
		 * @version 1.6.2
		 * @since   1.0.0
		 * @param   array $package Shipping Package.
		 * @return  bool
		 */
		public function is_available( $package ) {
			$available = parent::is_available( $package );
			if ( $available ) {
				// Min/Max.
				$conditions = array( 'cost', 'weight', 'volume', 'qty', 'distance' );
				foreach ( $conditions as $condition ) {
					$min = 'min_' . $condition;
					$max = 'max_' . $condition;
					if ( 0 != $this->{$min} || 0 != $this->{$max} ) {
						switch ( $condition ) {
							case 'cost':
								$total = apply_filters( 'alg_wc_custom_shipping_totals', $package['contents_cost'], $package );
								break;
							case 'weight':
								$total = alg_wc_custom_shipping_methods()->core->get_package_item_weight( $package );
								break;
							case 'volume':
								$total = alg_wc_custom_shipping_methods()->core->get_package_item_volume( $package );
								break;
							case 'qty':
								$total = $this->get_package_item_qty( $package );
								break;
							case 'distance':
								add_shortcode( 'distance', array( alg_wc_custom_shipping_methods()->core, 'distance' ) );
								$total = do_shortcode( $this->distance_calculation );
								break;
							default:
								$total = 0;
						}
						if ( ( 0 != $this->{$min} && $total < $this->{$min} ) || ( 0 != $this->{$max} && $total > $this->{$max} ) ) {
							return false;
						}
					}
				}
				// Include/Exclude.
				$conditions = array( 'product', 'product_cat', 'product_tag' );
				foreach ( $conditions as $condition ) {
					$include = 'incl_' . $condition;
					$exclude = 'excl_' . $condition;
					$include = trim( $this->{$include} );
					$exclude = trim( $this->{$exclude} );
					if ( '' != $include || '' != $exclude ) {
						$package_products = $this->get_package_products_data( $package['contents'], $condition );
						//if ( ! empty( $package_products ) ) {
							$package_products   = array_unique( $package_products );
							$_include           = array_unique( array_map( 'trim', explode( ',', $include ) ) );
							$_exclude           = array_unique( array_map( 'trim', explode( ',', $exclude ) ) );
							$_include_intersect = array_intersect( $_include, $package_products );
							$_exclude_intersect = array_intersect( $_exclude, $package_products );
							if (
							( '' != $include && (
								( 'one' === $this->require_type && empty( $_include_intersect ) ) ||
								( 'one_only' === $this->require_type && count( $_include_intersect ) !== count( $package_products ) ) ||
								( 'all' === $this->require_type && count( $_include_intersect ) != count( $_include ) ) ||
								( 'all_only' === $this->require_type &&
									( count( $_include_intersect ) !== count( $_include ) || count( $_include_intersect ) !== count( $package_products ) )
								)
							) ) ||
							( '' != $exclude && ! empty( $_exclude_intersect ) )
							) {
								return false;
							}
						//}
					}
				}
			}
			return $available;
		}

		/**
		 * Evaluate a cost from a sum/string.
		 *
		 * @version 1.6.0
		 * @since   1.0.0
		 * @param   string $sum  Sum.
		 * @param   array  $args Args.
		 * @return  string
		 * @todo    [feature] (important) Free shipping by product ID: add "require all" option
		 * @todo    [feature] (important) Free shipping by product ID: add similar "Free shipping by product category/tag ID" options
		 */
		public function evaluate_cost( $sum, $args = array() ) {
			include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

			// Allow 3rd parties to process shipping cost arguments.
			$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
			$locale         = localeconv();
			$decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
			$this->fee_cost = $args['cost'];

			do_action( 'alg_wc_custom_shipping_methods_evaluate_cost_args', $args );

			// Expand shortcodes.
			add_shortcode( 'fee', array( $this, 'fee' ) );

			foreach ( apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_shortcodes', array() ) as $shortcode => $function ) {
				add_shortcode( $shortcode, $function );
			}

			$replaced_values = apply_filters(
				'alg_wc_custom_shipping_methods_evaluate_cost_replace',
				array(
					'[qty]'  => $args['qty'],
					'[cost]' => $args['cost'],
				),
				$args
			);

			$sum = do_shortcode( str_replace( array_keys( $replaced_values ), $replaced_values, $sum ) );

			remove_shortcode( 'fee', array( $this, 'fee' ) );

			foreach ( apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_shortcodes', array() ) as $shortcode => $function ) {
				remove_shortcode( $shortcode, $function );
			}

			// Remove whitespace from string.
			$sum = preg_replace( '/\s+/', '', $sum );

			// Remove locale from string.
			$sum = str_replace( $decimals, '.', $sum );

			// Trim invalid start/end characters.
			$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

			// Filter.
			$sum = apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_sum', $sum );

			// Do the math.
			if ( $sum ) {

				$sum = apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_sum_evaluated', WC_Eval_Math::evaluate( $sum ) );

				// Limits.
				if ( in_array( $this->limit_calc, array( 'class', 'all' ) ) ) {
					$sum = apply_filters( 'alg_wc_custom_shipping_methods_min_max_limits', $sum, $this );
				}

				// Final filter.
				$sum = apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_final', $sum, $this, $args );

			}

			// Return.
			return $sum ? $sum : 0;
		}

		/**
		 * Work out fee (shortcode).
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param   array $atts Shortcode Attributes.
		 * @return  string
		 */
		public function fee( $atts ) {
			$atts = shortcode_atts(
				array(
					'percent' => '',
					'min_fee' => '',
					'max_fee' => '',
				),
				$atts,
				'fee'
			);

			$calculated_fee = 0;

			if ( $atts['percent'] ) {
				$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
			}

			if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
				$calculated_fee = $atts['min_fee'];
			}

			if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
				$calculated_fee = $atts['max_fee'];
			}

			return $calculated_fee;
		}

		/**
		 * Calculate shipping function.
		 *
		 * @version 1.6.0
		 * @since   1.0.0
		 * @param   array $package (default: array()).
		 * @todo    [feature] add "Free shipping calculation" option: "per class" (i.e. per package, as it is now), "per order" (i.e. total sum) and maybe "all".
		 */
		public function calculate_shipping( $package = array() ) {
			$rate = array(
				'id'      => $this->get_rate_id(),
				'label'   => $this->title,
				'cost'    => 0,
				'package' => $package,
			);

			// Calculate the costs.
			$has_costs = false; // True when a cost is set. False if all costs are blank strings.
			$cost      = $this->get_option( 'cost' );

			if ( '' !== $cost ) {
				$has_costs    = true;
				$total_cost   = apply_filters( 'alg_wc_custom_shipping_totals', $package['contents_cost'], $package );
				$rate['cost'] = $this->evaluate_cost(
					$cost,
					apply_filters(
						'alg_wc_custom_shipping_methods_evaluate_cost_args_package',
						array(
							'qty'     => $this->get_package_item_qty( $package ),
							'cost'    => $total_cost,
							'package' => $package,
						),
						$package
					)
				);
			}

			// Add shipping class costs.
			$shipping_classes = WC()->shipping->get_shipping_classes();

			if ( ! empty( $shipping_classes ) ) {
				$found_shipping_classes = $this->find_shipping_classes( $package );
				$highest_class_cost     = 0;

				foreach ( $found_shipping_classes as $shipping_class => $products ) {
					// Also handles BW compatibility when slugs were used instead of ids.
					$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
					$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option( 'class_cost_' . $shipping_class_term->term_id, $this->get_option( 'class_cost_' . $shipping_class, '' ) ) : $this->get_option( 'no_class_cost', '' );

					if ( '' === $class_cost_string ) {
						continue;
					}

					$has_costs  = true;
					$class_cost = $this->evaluate_cost(
						$class_cost_string,
						apply_filters(
							'alg_wc_custom_shipping_methods_evaluate_cost_args_class',
							array(
								'qty'      => array_sum( wp_list_pluck( $products, 'quantity' ) ),
								'cost'     => array_sum( wp_list_pluck( $products, 'line_total' ) ),
								'products' => $products,
							),
							$products
						)
					);

					if ( 'class' === $this->type ) {
						$rate['cost'] += $class_cost;
					} else {
						$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
					}
				}

				if ( 'order' === $this->type && $highest_class_cost ) {
					$rate['cost'] += $highest_class_cost;
				}
			}

			// Limits.
			if ( in_array( $this->limit_calc, array( 'order', 'all' ) ) ) {
				$rate['cost'] = apply_filters( 'alg_wc_custom_shipping_methods_min_max_limits', $rate['cost'], $this );
			}

			// Add the rate.
			if ( $has_costs ) {
				$this->add_rate( apply_filters( 'alg_wc_custom_shipping_methods_add_rate', $rate, $package, $this ) );
			}

			/**
			 * Developers can add additional rates based on this one via this action
			 *
			 * This example shows how you can add an extra rate based on this rate via custom function:
			 *
			 *      add_action( 'woocommerce_alg_wc_shipping_shipping_add_rate', 'add_another_custom_rate', 10, 2 );
			 *
			 *      function add_another_custom_rate( $method, $rate ) {
			 *          $new_rate          = $rate;
			 *          $new_rate['id']    .= ':' . 'custom_rate_name'; // Append a custom ID.
			 *          $new_rate['label'] = 'Rushed Shipping'; // Rename to 'Rushed Shipping'.
			 *          $new_rate['cost']  += 2; // Add $2 to the cost.
			 *
			 *          // Add it to WC.
			 *          $method->add_rate( $new_rate );
			 *      }.
			 */
			do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate );
		}

		/**
		 * Get items in package.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param   array $package Shipping Package.
		 * @return  int
		 */
		public function get_package_item_qty( $package ) {
			$total_quantity = 0;
			foreach ( $package['contents'] as $item_id => $values ) {
				if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
					$total_quantity += $values['quantity'];
				}
			}
			return $total_quantity;
		}

		/**
		 * Finds and returns shipping classes and the products with said class.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param   mixed $package Shipping Package.
		 * @return  array
		 */
		public function find_shipping_classes( $package ) {
			$found_shipping_classes = array();

			foreach ( $package['contents'] as $item_id => $values ) {
				if ( $values['data']->needs_shipping() ) {
					$found_class = $values['data']->get_shipping_class();

					if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
						$found_shipping_classes[ $found_class ] = array();
					}

					$found_shipping_classes[ $found_class ][ $item_id ] = $values;
				}
			}

			return $found_shipping_classes;
		}

	}

endif;
