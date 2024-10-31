<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode( 'period_psao', 'psao_pereiod' );
function psao_pereiod( $atts ) {
	$atts = shortcode_atts(
		array(
			'class' => null,
		),
		$atts
	);
	ob_start();
	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	$period = get_query_var( 'period_psao' );
	?>
<select name="period_psao" class="psao psao_select psao_pereiod<?php echo esc_attr( $class ); ?>">
	<option value="">投稿期間</option>
	<option <?php selected( $period, 'year' ); ?> value="year"><?php echo esc_html( __( '１年以内' ) ); ?></option>
	<option <?php selected( $period, '3month' ); ?> value="3month"><?php echo esc_html( __( '３ヶ月以内' ) ); ?></option>
	<option <?php selected( $period, 'month' ); ?> value="month"><?php echo esc_html( __( '１ヶ月以内' ) ); ?></option>
	<option <?php selected( $period, 'week' ); ?> value="week"><?php echo esc_html( __( '１週間以内' ) ); ?></option>
	<option <?php selected( $period, '3day' ); ?> value="3day"><?php echo esc_html( __( '３日以内' ) ); ?></option>
	<option <?php selected( $period, 'day' ); ?> value="day"><?php echo esc_html( __( '１日以内' ) ); ?></option>
</select>
	<?php
	return ob_get_clean();
}
