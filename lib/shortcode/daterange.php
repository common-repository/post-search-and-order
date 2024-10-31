<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'daterange_psao',
	'psao_daterange'
);

function psao_daterange( $atts ) {
	$atts = shortcode_atts(
		array(
			'class'  => null,
			'spacer' => '~',
			'enable' => 'start,end',
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	?>
<div class="psao psao_daterange<?php echo esc_attr( $class ); ?>">
	<?php if ( strpos( $atts['enable'], 'start' ) !== false ) { ?>
		<input name="date-start" type="date" value="<?php echo esc_attr( get_query_var( 'date-start' ) ); ?>"></input>
	<?php } ?>

	<?php if ( $atts['spacer'] ) { ?>
		<span class="psao_daterange-spacer"><?php echo esc_html( $atts['spacer'] ); ?></span>
	<?php } ?>

	<?php if ( strpos( $atts['enable'], 'end' ) !== false ) { ?>
		<input name="date-end" type="date" value="<?php echo esc_attr( get_query_var( 'date-end' ) ); ?>"></input>
	<?php } ?>
</div>
	<?php
	return ob_get_clean();
}
