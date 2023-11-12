<?php
/**
 * Custom Shipping Methods for WooCommerce - Custom Shipping Settings
 *
 * @version 1.6.2
 * @since   1.0.0
 * @author  Imaginate Solutions
 * @todo    [dev] (maybe) select dropdown for "Include/Exclude Cats/Tags"
 * @package csm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$availability_extra_desc_zero  = ' ' . __( 'Ignored if set to zero.', 'custom-shipping-methods-for-woocommerce' );
$availability_extra_desc_blank = ' ' . __( 'Ignored if empty.', 'custom-shipping-methods-for-woocommerce' );
$pro_link                      = '<a target="_blank" href="https://imaginate-solutions.com/downloads/custom-shipping-methods-for-woocommerce/?utm_source=wporg&utm_medium=unlock&utm_campaign=settings">Custom Shipping Methods for WooCommerce Pro</a>';
$pro_desc_short                = sprintf( '.<br><em>This shortcode is available in %s plugin only</em>', $pro_link );
$pro_desc                      = sprintf( 'This option is available in %s plugin only.', $pro_link );

$available_shortcodes = apply_filters(
	'alg_wc_custom_shipping_methods_shortcodes_desc',
	array(
		'[qty]'                                           => __( 'number of items', 'custom-shipping-methods-for-woocommerce' ),
		'[cost]'                                          => __( 'total cost of items', 'custom-shipping-methods-for-woocommerce' ),
		'[weight]'                                        => __( 'total weight of items', 'custom-shipping-methods-for-woocommerce' ),
		'[volume]'                                        => __( 'total volume of items', 'custom-shipping-methods-for-woocommerce' ),
		'[fee percent="10" min_fee="20" max_fee=""]'      => __( 'percentage based fees', 'custom-shipping-methods-for-woocommerce' ),
		'[round]'                                         => __( 'rounding', 'custom-shipping-methods-for-woocommerce' ) .
		sprintf(
			// translators: %s Round Shortcode Example.
			' (' . __( 'check examples %s', 'custom-shipping-methods-for-woocommerce' ) . ')',
			'<a target="_blank" href="https://imaginate-solutions.com/docs/docs/understanding-the-functionality-of-the-cost-table/">' .
			__( 'here', 'custom-shipping-methods-for-woocommerce' ) . '</a>'
		),
		'[costs_table prop="qty" table="1-10|10-5|20-0"]' => __( 'costs table', 'custom-shipping-methods-for-woocommerce' ) .
		sprintf(
			// translators: %s Costs Table Example.
			' (' . __( 'check examples %s', 'custom-shipping-methods-for-woocommerce' ) . ')',
			'<a target="_blank" href="https://imaginate-solutions.com/docs/docs/understanding-the-functionality-of-the-cost-table/">' .
			__( 'here', 'custom-shipping-methods-for-woocommerce' ) . '</a>'
		) .
			apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc_short ),
		'[distance]'                                      => __( 'distance', 'custom-shipping-methods-for-woocommerce' ) .
		sprintf(
			// translators: %s Distance Shortcode Example.
			' (' . __( 'check examples %s', 'custom-shipping-methods-for-woocommerce' ) . ')',
			'<a target="_blank" href="https://imaginate-solutions.com/docs/docs/understanding-the-functionality-of-the-cost-table/">' .
			__( 'here', 'custom-shipping-methods-for-woocommerce' ) . '</a>'
		) .
			apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc_short ),
	)
);
$cost_desc            = '';
$cost_desc           .= '<ul>';
foreach ( $available_shortcodes as $shortcode => $shortcode_desc ) {
	$cost_desc .= "<li>* <code>{$shortcode}</code> - {$shortcode_desc}.</li>";
}
$cost_desc .= '</ul>';

// General settings.
$settings = array(
	'title' => array(
		'title'       => __( 'Method title', 'custom-shipping-methods-for-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'custom-shipping-methods-for-woocommerce' ),
		'default'     => __( 'Custom shipping', 'custom-shipping-methods-for-woocommerce' ),
		'desc_tip'    => true,
		'css'         => 'width:100%',
	),
);
if ( 'yes' === get_option( 'alg_wc_custom_shipping_methods_icon_desc_enabled', 'no' ) ) {
	$settings = array_merge(
		$settings,
		array(
			'alg_wc_csm_icon' => array(
				'title'       => __( 'Method icon (optional)', 'custom-shipping-methods-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Frontend icon (URL).', 'custom-shipping-methods-for-woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,
				'css'         => 'width:100%',
			),
			'alg_wc_csm_desc' => array(
				'title'       => __( 'Method description (optional)', 'custom-shipping-methods-for-woocommerce' ),
				'type'        => 'textarea',
				'description' => __( 'Frontend description.', 'custom-shipping-methods-for-woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,
				'css'         => 'width:100%',
			),
		)
	);
}
$settings = array_merge(
	$settings,
	array(
		'tax_status'               => array(
			'title'   => __( 'Tax status', 'custom-shipping-methods-for-woocommerce' ),
			'type'    => 'select',
			'class'   => 'wc-enhanced-select',
			'default' => 'taxable',
			'options' => array(
				'taxable' => __( 'Taxable', 'custom-shipping-methods-for-woocommerce' ),
				'none'    => _x( 'None', 'Tax status', 'custom-shipping-methods-for-woocommerce' ),
			),
			'css'     => 'width:100%',
		),
		'cost'                     => array(
			'title'       => __( 'Cost', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'text',
			'placeholder' => '',
			'description' => $cost_desc,
			'default'     => '0',
			'desc_tip'    => __( 'Enter a cost (excl. tax) or sum, e.g. <code>10.00 * [qty]</code>.', 'custom-shipping-methods-for-woocommerce' ),
			'css'         => 'width:100%',
		),
		'min_cost_limit'           => array(
			'title'             => __( 'Min cost limit', 'custom-shipping-methods-for-woocommerce' ),
			'desc_tip'          => __( 'Ignored if set to zero.', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'placeholder'       => '',
			'default'           => '0',
			'css'               => 'width:100%',
			'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ), 'cost' ),
			'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
		),
		'max_cost_limit'           => array(
			'title'             => __( 'Max cost limit', 'custom-shipping-methods-for-woocommerce' ),
			'desc_tip'          => __( 'Ignored if set to zero.', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'placeholder'       => '',
			'default'           => '0',
			'css'               => 'width:100%',
			'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ), 'cost' ),
			'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
		),
		'free_shipping_min_amount' => array(
			'title'             => __( 'Free shipping min amount', 'custom-shipping-methods-for-woocommerce' ),
			'desc_tip'          => __( 'Free shipping minimum order amount.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Calculated per package / shipping class.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Ignored if set to zero.', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'placeholder'       => '',
			'default'           => '0',
			'css'               => 'width:100%',
			'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ), 'cost' ),
			'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
		),
		'free_shipping_products'   => array(
			'title'             => __( 'Free shipping products', 'custom-shipping-methods-for-woocommerce' ),
			'desc_tip'          => __( 'Products that grant free shipping when added to the cart.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Set as comma separated product IDs.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Calculated per package / shipping class.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Ignored if empty.', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'text',
			'placeholder'       => '',
			'default'           => '',
			'css'               => 'width:100%',
			'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ) ),
			'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
		),
	)
);

// Availability settings.
$settings = array_merge(
	$settings,
	array(
		'availability'         => array(
			'title'       => __( 'Availability', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'title',
			'default'     => '',
			'description' => __( 'Method availability.', 'custom-shipping-methods-for-woocommerce' ),
		),
		'min_cost'             => array(
			'title'             => __( 'Min cart cost', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'description'       => __( 'Minimum total cart cost.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_zero,
			'default'           => 0,
			'desc_tip'          => true,
			'custom_attributes' => array(
				'step' => '0.0000000001',
				'min'  => '0',
			),
			'css'               => 'width:100%',
		),
		'max_cost'             => array(
			'title'             => __( 'Max cart cost', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'description'       => __( 'Maximum total cart cost.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_zero,
			'default'           => 0,
			'desc_tip'          => true,
			'custom_attributes' => array(
				'step' => '0.0000000001',
				'min'  => '0',
			),
			'css'               => 'width:100%',
		),
		'min_weight'           => array(
			'title'             => __( 'Min cart weight', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'description'       => __( 'Minimum total cart weight.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_zero,
			'default'           => 0,
			'desc_tip'          => true,
			'custom_attributes' => array(
				'step' => '0.0000000001',
				'min'  => '0',
			),
			'css'               => 'width:100%',
		),
		'max_weight'           => array(
			'title'             => __( 'Max cart weight', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'description'       => __( 'Maximum total cart weight.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_zero,
			'default'           => 0,
			'desc_tip'          => true,
			'custom_attributes' => array(
				'step' => '0.0000000001',
				'min'  => '0',
			),
			'css'               => 'width:100%',
		),
		'min_volume'           => array(
			'title'             => __( 'Min cart volume', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'description'       => __( 'Minimum total cart volume.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_zero,
			'default'           => 0,
			'desc_tip'          => true,
			'custom_attributes' => array(
				'step' => '0.0000000001',
				'min'  => '0',
			),
			'css'               => 'width:100%',
		),
		'max_volume'           => array(
			'title'             => __( 'Max cart volume', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'description'       => __( 'Maximum total cart volume.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_zero,
			'default'           => 0,
			'desc_tip'          => true,
			'custom_attributes' => array(
				'step' => '0.0000000001',
				'min'  => '0',
			),
			'css'               => 'width:100%',
		),
		'min_qty'              => array(
			'title'             => __( 'Min cart quantity', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'description'       => __( 'Minimum total cart quantity.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_zero,
			'default'           => 0,
			'desc_tip'          => true,
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '0',
			),
			'css'               => 'width:100%',
		),
		'max_qty'              => array(
			'title'             => __( 'Max cart quantity', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'description'       => __( 'Maximum total cart quantity.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_zero,
			'default'           => 0,
			'desc_tip'          => true,
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '0',
			),
			'css'               => 'width:100%',
		),
		'min_distance'         => array(
			'title'             => __( 'Min distance', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'desc_tip'          => __( 'Minimum distance.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_zero . ' ' .
				__( '"Distance calculation" option must be filled in.', 'custom-shipping-methods-for-woocommerce' ),
			'default'           => 0,
			'css'               => 'width:100%',
			'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ), 'distance' ),
			'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
		),
		'max_distance'         => array(
			'title'             => __( 'Max distance', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'number',
			'desc_tip'          => __( 'Maximum distance.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_zero . ' ' .
				__( '"Distance calculation" option must be filled in.', 'custom-shipping-methods-for-woocommerce' ),
			'default'           => 0,
			'css'               => 'width:100%',
			'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ), 'distance' ),
			'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
		),
		'distance_calculation' => array(
			'title'             => __( 'Distance calculation', 'custom-shipping-methods-for-woocommerce' ),
			'type'              => 'text',
			'desc_tip'          => __( 'Used for "Min distance" and "Max distance" options.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				// translators: %s Distance Shortcode.
				sprintf( __( 'You should use %s shortcode here.', 'custom-shipping-methods-for-woocommerce' ), '<strong>[distance]</strong>' ),
			'default'           => '',
			'css'               => 'width:100%',
			'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ) ),
			'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
		),
		'incl_product'         => array(
			'title'       => __( 'Required products', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Selected products must be in cart for shipping method to be available.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Set as comma separated product IDs.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_blank,
			'default'     => '',
			'desc_tip'    => true,
			'css'         => 'width:100%',
		),
		'excl_product'         => array(
			'title'       => __( 'Excluded products', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'None of the selected products must be in cart for shipping method to be available.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Set as comma separated product IDs.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_blank,
			'default'     => '',
			'desc_tip'    => true,
			'css'         => 'width:100%',
		),
		'incl_product_cat'     => array(
			'title'       => __( 'Required product categories', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Selected product categories must be in cart for shipping method to be available.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Set as comma separated product category IDs.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_blank,
			'default'     => '',
			'desc_tip'    => true,
			'css'         => 'width:100%',
		),
		'excl_product_cat'     => array(
			'title'       => __( 'Excluded product categories', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'None of the selected product categories must be in cart for shipping method to be available.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Set as comma separated product category IDs.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_blank,
			'default'     => '',
			'desc_tip'    => true,
			'css'         => 'width:100%',
		),
		'incl_product_tag'     => array(
			'title'       => __( 'Required product tags', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Selected product tags must be in cart for shipping method to be available.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Set as comma separated product tag IDs.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_blank,
			'default'     => '',
			'desc_tip'    => true,
			'css'         => 'width:100%',
		),
		'excl_product_tag'     => array(
			'title'       => __( 'Excluded product tags', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'None of the selected product tags must be in cart for shipping method to be available.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
				__( 'Set as comma separated product tag IDs.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_blank,
			'default'     => '',
			'desc_tip'    => true,
			'css'         => 'width:100%',
		),
		'require_type'         => array(
			'title'       => __( 'Require type', 'custom-shipping-methods-for-woocommerce' ),
			'description' => __( 'Affects "Required products", "Required product categories" and "Required product tags" options.', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'select',
			'default'     => 'one',
			'options'     => array(
				'one'      => __( 'At least one of the required products has to be in cart', 'custom-shipping-methods-for-woocommerce' ),
				'one_only' => __( 'Only the required products have to be in cart', 'custom-shipping-methods-for-woocommerce' ),
				'all'      => __( 'All of the required products have to be in cart', 'custom-shipping-methods-for-woocommerce' ),
				'all_only' => __( 'All and only the required products have to be in cart', 'custom-shipping-methods-for-woocommerce' ),
			),
			'desc_tip'    => true,
			'css'         => 'width:100%',
		),
	)
);

// Shipping class settings.
$shipping_classes = WC()->shipping->get_shipping_classes();
if ( ! empty( $shipping_classes ) ) {
	$settings['class_costs'] = array(
		'title'       => __( 'Shipping class costs', 'custom-shipping-methods-for-woocommerce' ),
		'type'        => 'title',
		'default'     => '',
		'description' => sprintf(
			// translators: %s Settings URL.
			__( 'These costs can optionally be added based on the <a href="%s">product shipping class</a>.', 'custom-shipping-methods-for-woocommerce' ),
			admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' )
		),
	);
	foreach ( $shipping_classes as $shipping_class ) {
		if ( ! isset( $shipping_class->term_id ) ) {
			continue;
		}
		$settings[ 'class_cost_' . $shipping_class->term_id ] = array(
			/* translators: %s: shipping class name */
			'title'       => sprintf( __( '"%s" shipping class cost', 'custom-shipping-methods-for-woocommerce' ), esc_html( $shipping_class->name ) ),
			'type'        => 'text',
			'placeholder' => __( 'N/A', 'custom-shipping-methods-for-woocommerce' ),
			'description' => __( 'See "Cost" option description above for available options.', 'custom-shipping-methods-for-woocommerce' ),
			'default'     => $this->get_option( 'class_cost_' . $shipping_class->slug ),
			'desc_tip'    => true,
			'css'         => 'width:100%',
		);
	}
	$settings['no_class_cost'] = array(
		'title'       => __( 'No shipping class cost', 'custom-shipping-methods-for-woocommerce' ),
		'type'        => 'text',
		'placeholder' => __( 'N/A', 'custom-shipping-methods-for-woocommerce' ),
		'description' => __( 'See "Cost" option description above for available options.', 'custom-shipping-methods-for-woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'css'         => 'width:100%',
	);
	$settings['type']          = array(
		'title'   => __( 'Calculation type', 'custom-shipping-methods-for-woocommerce' ),
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'default' => 'class',
		'options' => array(
			'class' => __( 'Per class: Charge shipping for each shipping class individually', 'custom-shipping-methods-for-woocommerce' ),
			'order' => __( 'Per order: Charge shipping for the most expensive shipping class', 'custom-shipping-methods-for-woocommerce' ),
		),
		'css'     => 'width:100%',
	);
	$settings['limit_calc']    = array(
		'title'    => __( 'Limits calculation', 'custom-shipping-methods-for-woocommerce' ),
		'desc_tip' => __( 'For "Min cost limit" and "Max cost limit" options.', 'custom-shipping-methods-for-woocommerce' ),
		'type'     => 'select',
		'class'    => 'wc-enhanced-select',
		'default'  => 'class',
		'options'  => array(
			'class' => __( 'Per class: Check limits for each shipping class individually', 'custom-shipping-methods-for-woocommerce' ),
			'order' => __( 'Per order: Check limits for final cost only', 'custom-shipping-methods-for-woocommerce' ),
			'all'   => __( 'All: Check limits for each shipping class individually and then for final cost', 'custom-shipping-methods-for-woocommerce' ),
		),
		'css'      => 'width:100%',
	);
}

// Advanced settings.
$settings = array_merge(
	$settings,
	array(
		'advanced'   => array(
			'title'       => __( 'Advanced', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'title',
			'default'     => '',
			'description' => __( 'Advanced settings.', 'custom-shipping-methods-for-woocommerce' ),
		),
		'return_url' => array(
			'title'       => __( 'Custom return URL', 'custom-shipping-methods-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Will be used instead of the standard "Order received" page.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc_blank,
			'default'     => '',
			'desc_tip'    => true,
			'css'         => 'width:100%',
		),
	)
);

return $settings;
