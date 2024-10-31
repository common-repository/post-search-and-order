<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'reset_psao',
	'psao_reset'
);
function psao_reset( $atts ) {
	$atts = shortcode_atts(
		array(
			'class'  => null,
			'submit' => 'リセット',
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	?>
<button class="psao psao_reset<?php echo esc_attr( $class ); ?>" onclick="location.href='<?php echo esc_url( psao_resetUrl() ); ?>'" name="allreset_psao"><?php echo wp_kses_post( $atts['submit'] ); ?></button>
	<?php
	return ob_get_clean();
}
