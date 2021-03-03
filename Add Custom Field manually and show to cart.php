// Adding a custom filed (Unit) in product data
//********Add field*************// 
function custom_unit_field() {
    echo '<div class="product_custom_field">';
        woocommerce_wp_text_input( array(
            'id' => '_custom_product_text_field',
            'placeholder' => 'Unit',
            'label' => __('Unit', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );

    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'custom_unit_field', 10, 0 );

// Save Field
function woo_add_custom_general_fields_supplier_save( $product ) {
    if( isset($_POST['_custom_product_text_field']) ) {
        $product->update_meta_data( '_custom_product_text_field', esc_html( $_POST['_custom_product_text_field'] ) );
    }
}
add_action( 'woocommerce_admin_process_product_object', 'woo_add_custom_general_fields_supplier_save', 10, 1 );

// Display Fields Data to Frontend
add_action( 'woocommerce_after_shop_loop_item_title', 'custom_field_display_below_title', 2 );
add_action( 'woocommerce_single_product_summary', 'custom_field_display_below_title', 20 );
function custom_field_display_below_title(){
    global $product;

    // Get the custom field value
    $custom_field = get_post_meta( $product->get_id(), '_custom_product_text_field', true );

    // Display
    if( ! empty($custom_field) ){
        echo '<p class="my-custom-field" style="color: #98C93C; font-size: 16px; font-weight: 500; margin-bottom: 5px;">'.$custom_field.'</p>';
    }
}

// Display on cart & checkout pages
function unit_in_cart_display( $item_data, $cart_item ) {
    $unit_field = $cart_item['data']->get_meta('_custom_product_text_field');
    
    if ( ! empty( $unit_field ) ) {
        $item_data[] = array(
            'name' => __('Unit', 'woocommerce'),
            'value' => $unit_field,
        );
    }
    
    return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'unit_in_cart_display', 10, 2 );

// Display item data everywhere on orders and email notifications 
function unit_in_order_display( $item, $cart_item_key, $values, $order ) {
    $unit_field = $values['data']->get_meta('_custom_product_text_field');

    if ( ! empty( $unit_field ) ) {
        $item->update_meta_data( __( 'Unit', 'woocommerce'), $unit_field );
    }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'unit_in_order_display', 10, 4 ); 
