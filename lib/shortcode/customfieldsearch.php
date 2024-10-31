<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'csearch_psao',
	'psao_csearch'
);
function psao_csearch( $atts ) {
	$atts = shortcode_atts(
		array(
			'class'       => null,
			'placeholder' => null,
			'option'      => null,
			'key'         => null,
			'enable'      => null,
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	$csearch_psao       = get_query_var( 'csearch_psao' );
	$csearchoption_psao = get_query_var( 'csearchoption_psao' );

	?>
<div class="psao psao_csearch<?php echo esc_attr( $class ); ?>">
	<input class="csearch" name="csearch_psao" type="text" placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>" value="<?php echo esc_attr( $csearch_psao ); ?>">

	<?php if ( $atts['option'] !== null ) { // オプション設定! ?>
		<div class="psao_csearchoption">
			<label><input <?php checked( $csearchoption_psao, 'and' ); ?> type="radio" name="csearchoption_psao" value="and"><?php esc_html_e( 'AND検索' ); ?></label>
			<label><input <?php checked( $csearchoption_psao, 'or' ); ?> type="radio" name="csearchoption_psao" value="or"><?php esc_html_e( 'OR検索' ); ?></label>
		</div>
		<?php
	}

	// 厳格検索を有効化!
	if ( $atts['enable'] === 'strict' ) {
		?>
		<input type="hidden" name="cstrict_psao" value="strict">
		<?php
	}
	?>

	<input type="hidden" name="csearchkey_psao" value="<?php echo esc_attr( $atts['key'] ); ?>">
</div>
	<?php
	return ob_get_clean();
}
