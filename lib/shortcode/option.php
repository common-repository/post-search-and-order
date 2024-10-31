<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'checkpass_psao',
	'psao_checkpass'
);
function psao_checkpass( $atts ) {
	$atts = shortcode_atts(
		array(
			'class' => null,
			'label' => 'パスワードが設定されている投稿のみ表示する',
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	$getoption_psao = get_query_var( 'option_psao' );

	echo '<div class="psao">';
	if ( is_array( $getoption_psao ) ) {
		if ( in_array( (string) 'password', $getoption_psao, true ) ) {
			$check = 'checked';
		}
	} else {
		$check = checked( $getoption_psao, 'password', true, false );
	}
	echo '<label class="checkpass_psao' . esc_attr( $class ) . '">' . esc_html( $atts['label'] ) . '<input ' . esc_html( $check ) . ' name="option_psao[]" type="checkbox" value="' . esc_attr( 'password' ) . '"></label>';

	echo '</div>';
	return ob_get_clean();
}

add_shortcode(
	'checkcom_psao',
	'psao_checkcom'
);
function psao_checkcom( $atts ) {
	$atts = shortcode_atts(
		array(
			'class' => null,
			'label' => 'コメントの存在する投稿のみ表示する',
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	$getoption_psao = get_query_var( 'option_psao' );

	echo '<div class="psao">';
	if ( is_array( $getoption_psao ) ) {
		if ( in_array( (string) 'comments', $getoption_psao, true ) ) {
			$check = 'checked';
		}
	} else {
		$check = checked( $getoption_psao, 'comments', true, false );
	}
	echo '<label class="checkcom_psao' . esc_attr( $class ) . '">' . esc_html( $atts['label'] ) . '<input ' . esc_html( $check ) . ' name="option_psao[]" type="checkbox" value="' . esc_attr( 'comments' ) . '"></label>';
	echo '</div>';

	return ob_get_clean();
}
