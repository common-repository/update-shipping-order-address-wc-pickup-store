<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/h8ps1tm
 * @since      1.0.0
 *
 * @package    Update_Shipping_Order_Address_Wcps
 * @subpackage Update_Shipping_Order_Address_Wcps/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Update_Shipping_Order_Address_Wcps
 * @subpackage Update_Shipping_Order_Address_Wcps/admin
 * @author     Tiago Mano <tiago@hellodev.us>
 */
class Update_Shipping_Order_Address_Wcps_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Update_Shipping_Order_Address_Wcps_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Update_Shipping_Order_Address_Wcps_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/update-shipping-order-address-wcps-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Update_Shipping_Order_Address_Wcps_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Update_Shipping_Order_Address_Wcps_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/update-shipping-order-address-wcps-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Change the Shipping Address to the Local Pickup store chosen
	 *
	 * @since    1.0.0
	 */
	function change_shipping_address_to_local_pickup( $order_id ) {

		$pickup_store = get_post_meta( $order_id, '_shipping_pickup_stores', true );

		if ( $pickup_store ) {

			// Get store details
			$store_id 		= wps_get_store_id_by_name( $pickup_store );
			$city     		= get_post_meta( $store_id, 'city', true );

			if ( wps_check_countries_count() )
				$store_country = get_post_meta( $store_id, 'store_country', true );
			$store_country = ( !empty( $store_country ) ) ? $store_country : wps_get_wc_default_country();
			
			$address  		= get_post_meta( $store_id, '_wpcs_address_1', true );
			$postcode 		= get_post_meta( $store_id, '_wpcs_postcode', true );
			
			// Update Billing details
			update_post_meta( $order_id, '_shipping_address_1', $address );
			update_post_meta( $order_id, '_shipping_city', $city );
			update_post_meta( $order_id, '_shipping_country', $store_country );
			update_post_meta( $order_id, '_shipping_postcode', $postcode );

		}
		
	}
	
	/**
	 * Add a Meta Box to WC Pickup Store plugin with additional fields
	 * necessary to work has the details to Shipping address
	 *
	 */
	function wcps_meta_box() {

		add_meta_box(
			'wpcs-additional-fields',
			__( 'Additional Fields', $this->plugin_name ),
			array( $this, 'wpcs_meta_box_callback' ),
			'store',
			'normal',
			'high'
		);

	}
	
	/**
	 * Add the fields to the Meta Box - WC Pickup Store
	 *
	 */
	function wpcs_meta_box_callback( $post ) {
	
		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'wpcs_notice_nonce', 'wpcs_notice_nonce' );
	
		$postcode  		= get_post_meta( $post->ID, '_wpcs_postcode', true );
		$address_1 		= get_post_meta( $post->ID, '_wpcs_address_1', true );
		
		?>
		<p><?php _e( 'Additional fields to the WC Pickup Store plugin', $this->plugin_name ); ?>.</p>
		<table style="width:100%;border-spacing: 0 8px;">
			<tr>
				<th align="left"><?php _e( 'Shipping Address 1', $this->plugin_name ); ?></th>
			</tr>
			<tr>
				<td>
					<input type="text" name="wpcs_address_1" style="width:100%" value="<?php echo esc_attr( $address_1 ); ?>">
				</td>
			</tr>
			<tr>
				<th align="left"><?php _e( 'Postcode', $this->plugin_name ); ?></th>
			</tr>
			<tr>
				<td>
					<input type="text" name="wpcs_postcode" style="width:100%" value="<?php echo esc_attr( $postcode ); ?>">
				</td>
			</tr>
		</table>
		<?php

	}
	
	/**
	 * When the sotre post is saved, saves our custom data.
	 *
	 * @param int $post_id
	 */
	function save_wpcs_meta_box_data( $post_id ) {
	
		// Check if our nonce is set.
		if ( ! isset( $_POST['wpcs_notice_nonce'] ) ) {
			return;
		}
	
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['wpcs_notice_nonce'], 'wpcs_notice_nonce' ) ) {
			return;
		}
	
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
	
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
	
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
	
		}
		else {
	
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
	
		// Update the meta field in the database.
		update_post_meta( $post_id, '_wpcs_postcode', sanitize_text_field( $_POST['wpcs_postcode'] ) );
		update_post_meta( $post_id, '_wpcs_address_1', sanitize_text_field( $_POST['wpcs_address_1'] ) ); 
	}
	
	/**
	 * Check if WC Pickup Store is activated and if not show a notice message
	 *
	 * @since    1.0.0
	*/
	public function wpcs_wc_admin_notice() {
		
		if ( ! function_exists( 'wps_check_countries_count' ) && current_user_can( 'activate_plugins' ) ) :
			?>
			<div class="notice notice-error is-dismissible">
				<p>
					<?php
					printf(
						__('To use our plugin %1$s you need to activate or install the %2$sWC Pickup Store%3$s.', $this->plugin_name ),
						'<strong>Update Shipping Order Address - WC Pickup Store</strong>',
						'<a href="https://wordpress.org/plugins/wc-pickup-store/" target="_blank" >',
						'</a>'
					);
					?>
				</p>
			</div>		
			<?php
		endif;
		
	}

}
