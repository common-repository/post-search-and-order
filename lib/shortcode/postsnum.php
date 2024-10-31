<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'postsnum_psao',
	'psao_postnum'
);
function psao_postnum( $atts ) {
	$atts = shortcode_atts(
		array(
			'class' => null,
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	$postnum = get_query_var( 'postsnum_psao', get_option( 'posts_per_page' ) );
	?>
	<div class="psao psao_postsnum<?php echo esc_attr( $class ); ?>">
		<input type="number" name="postsnum_psao" class="psao_postsnum-number" value="<?php echo esc_attr( $postnum ); ?>">
	</div>
	<?php
	return ob_get_clean();
}
