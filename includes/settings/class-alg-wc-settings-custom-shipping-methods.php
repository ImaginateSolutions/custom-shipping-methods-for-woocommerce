<?php
/**
 * Custom Shipping Methods for WooCommerce - Settings
 *
 * @version 1.6.1
 * @since   1.0.0
 * @author  Imaginate Solutions
 * @package csm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Settings_Custom_Shipping_Methods' ) ) :

	/**
	 * Custom Shipping Methods Class.
	 */
	class Alg_WC_Settings_Custom_Shipping_Methods extends WC_Settings_Page {

		/**
		 * Constructor.
		 *
		 * @version 1.6.1
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->id    = 'alg_wc_custom_shipping_methods';
			$this->label = __( 'Custom Shipping Methods', 'custom-shipping-methods-for-woocommerce' );
			parent::__construct();
			add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'maybe_unsanitize_option' ), PHP_INT_MAX, 3 );
			// Sections.
			require_once 'class-alg-wc-custom-shipping-methods-settings-section.php';
			require_once 'class-alg-wc-custom-shipping-methods-settings-general.php';
		}

		/**
		 * Maybe unsanitize option.
		 *
		 * @param mixed $value     Value to unsanitize.
		 * @param mixed $option    Option Name.
		 * @param mixed $raw_value Raw Value.
		 * @return mixed
		 * @since   1.2.0
		 * @version 1.2.0
		 * @todo    [dev] (important) find better solution!
		 */
		public function maybe_unsanitize_option( $value, $option, $raw_value ) {
			return ( ! empty( $option['alg_wc_csm_raw'] ) ? $raw_value : $value );
		}

		/**
		 * Get settings.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		public function get_settings() {
			global $current_section;
			return array_merge(
				apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ),
				array(
					array(
						'title' => __( 'Reset Settings', 'custom-shipping-methods-for-woocommerce' ),
						'type'  => 'title',
						'id'    => $this->id . '_' . $current_section . '_reset_options',
					),
					array(
						'title'   => __( 'Reset section settings', 'custom-shipping-methods-for-woocommerce' ),
						'desc'    => '<strong>' . __( 'Reset', 'custom-shipping-methods-for-woocommerce' ) . '</strong>',
						'id'      => $this->id . '_' . $current_section . '_reset',
						'default' => 'no',
						'type'    => 'checkbox',
					),
					array(
						'type' => 'sectionend',
						'id'   => $this->id . '_' . $current_section . '_reset_options',
					),
				)
			);
		}

		/**
		 * Maybe reset settings.
		 *
		 * @version 1.3.1
		 * @since   1.0.0
		 */
		public function maybe_reset_settings() {
			global $current_section;
			if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
				foreach ( $this->get_settings() as $value ) {
					if ( isset( $value['id'] ) ) {
						$id = explode( '[', $value['id'] );
						delete_option( $id[0] );
					}
				}
				add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
			}
		}

		/**
		 * Admin notice settings reset.
		 *
		 * @version 1.3.1
		 * @since   1.3.1
		 */
		public function admin_notice_settings_reset() {
			echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
			esc_html__( 'Your settings have been reset.', 'custom-shipping-methods-for-woocommerce' ) . '</strong></p></div>';
		}

		/**
		 * Save settings.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function save() {
			parent::save();
			$this->maybe_reset_settings();
		}

	}

endif;

return new Alg_WC_Settings_Custom_Shipping_Methods();
