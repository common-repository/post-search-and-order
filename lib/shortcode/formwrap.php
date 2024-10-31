<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'formwrap_psao',
	'psao_formwrap'
);
function psao_formwrap( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'indexurl' => null,
			'class'    => null,
			'submit'   => '検索',
		),
		$atts
	);
	ob_start();

	// url設定!
	$url = psao_urlsetting_form( $atts );

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	?>
	<form action="<?php echo esc_url( $url ); ?>" method="get" class="psao_form psao_formwrap<?php echo esc_attr( $class ); ?>">
		<?php echo do_shortcode( $content ); ?>
		<button type="submit"><?php echo esc_html( $atts['submit'] ); ?></button>
	</form>
	<?php
	return ob_get_clean();
}
