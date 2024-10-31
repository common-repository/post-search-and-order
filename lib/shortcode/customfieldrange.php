<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'cnumrange_psao',
	'psao_cnumrange'
);

function psao_cnumrange( $atts ) {
	$atts = shortcode_atts(
		array(
			'class'            => null,
			'key'              => null,
			'spacer'           => '~',
			'placeholderstart' => null,
			'placeholderend'   => null,
			'enable'           => 'start,end',
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );
	?>
	<div class="psao psao_keynumrange<?php echo esc_attr( $class ); ?>">
	<?php if ( strpos( $atts['enable'], 'start' ) !== false ) { ?>
		<input type="number" placeholder="<?php echo esc_attr( $atts['placeholderstart'] ); ?>" name="keynum-start" value="<?php echo esc_attr( get_query_var( 'keynum-start' ) ); ?>">
	<?php } ?>

	<?php if ( $atts['spacer'] ) { ?>
		<span class="spacer"><?php echo esc_html( $atts['spacer'] ); ?></span>
	<?php } ?>

	<?php if ( strpos( $atts['enable'], 'end' ) !== false ) { ?>
		<input type="number" placeholder="<?php echo esc_attr( $atts['placeholderend'] ); ?>" name="keynum-end" value="<?php echo esc_attr( get_query_var( 'keynum-end' ) ); ?>">
	<?php } ?>

	<input type="hidden" name="keynum-name" value="<?php echo esc_attr( $atts['key'] ); ?>">
	</div>
	<?php
	return ob_get_clean();
}
