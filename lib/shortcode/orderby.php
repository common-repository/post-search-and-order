<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode( 'orderby_psao', 'psao_orderby' );
function psao_orderby( $atts ) {
	$atts = shortcode_atts(
		array(
			'class'  => null,
			'enable' => 'all',
			'label'  => '並べ替え選択',
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	$orderby = get_query_var( 'orderby_psao' );
	?>
	<select name="orderby_psao" class="psao psao_select psao_orderby<?php echo esc_attr( $class ); ?>">
		<option value=""><?php echo esc_html( $atts['label'] ); ?></option>

		<?php
		if ( ! empty( $atts['enable'] ) ) {
			psao_template_orderby( $atts['enable'], $orderby );
		}
		?>
	</select>
	<?php
	return ob_get_clean();
}
