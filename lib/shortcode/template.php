<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'template_psao',
	'psao_template'
);
function psao_template( $atts ) {
	$atts = shortcode_atts(
		array(
			'indexurl' => null,
			'class'    => null,
			'submit'   => '検索',
			'strict'   => null,
			'tagtype'  => 'check',
			'option'   => 'on',
			'inkey'    => null,
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
		<?php
			echo do_shortcode( '[taxonomy_psao]' );
			echo do_shortcode( '[taxonomy_psao taxname="post_tag" type="' . $atts['check'] . '"]' );
			echo do_shortcode( '[period_psao]' );
			echo do_shortcode( '[orderby_psao]' );
			echo do_shortcode( '[order_psao]' );
			echo do_shortcode( '[postsnum_psao]' );
			echo do_shortcode( '[search_psao inkey="' . $atts['inkey'] . '" option="' . $atts['option'] . '" strict="' . $atts['strict'] . '"]' );
		?>
		<input type="submit" value="<?php echo esc_attr( $atts['submit'] ); ?>">
	</form>
	<?php
		echo do_shortcode( '[reset_psao]' );
	return ob_get_clean();
}
