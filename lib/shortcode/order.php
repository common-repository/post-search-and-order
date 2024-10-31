<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode( 'order_psao', 'psao_order' );
function psao_order( $atts ) {
	$atts = shortcode_atts(
		array(
			'class' => null,
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	$getorder_psao = get_query_var( 'order_psao' );
	?>
<select name="order_psao" class="psao psao_select psao_order<?php echo esc_attr( $class ); ?>">
	<option <?php selected( $getorder_psao, 'DESC' ); ?> value="DESC"><?php echo esc_html( __( '降順（高い順）' ) ); ?></option>
	<option <?php selected( $getorder_psao, 'ASC' ); ?> value="ASC"><?php echo esc_html( __( '昇順（低い順）' ) ); ?></option>
</select>
	<?php
	return ob_get_clean();
}
