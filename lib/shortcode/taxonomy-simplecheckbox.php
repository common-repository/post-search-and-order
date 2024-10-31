<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// ショートコード追加!
add_shortcode(
	'tax_simplecheck_psao',
	'psao_tax_simplecheck'
);
function psao_tax_simplecheck( $atts ) {
	$atts = shortcode_atts(
		array(
			'taxname'  => 'category',
			'class'    => null,
			'relation' => 'and',
		),
		$atts
	);

	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	// クエリ名設定!
	if ( $atts['taxname'] === 'category' ) {
		if ( $atts['relation'] === 'and' ) {
			$queryname = 'category__and_psao';
		} else {
			$queryname = 'category__in_psao';
		}
		psao_sort_checkbox_simple( $atts['taxname'], $queryname, $class );
	} elseif ( $atts['taxname'] === 'post_tag' ) {
		if ( $atts['relation'] === 'and' ) {
			$queryname = 'tag__and_psao';
		} else {
			$queryname = 'tag__in_psao';
		}
		psao_sort_checkbox_simple( $atts['taxname'], $queryname, $class );
	} elseif ( $atts['taxname'] === 'em_tag' ) {
		if ( $atts['relation'] === 'and' ) {
			psao_sort_checkbox_simple( 'admin_em_tag', 'admin_em_tag_psao_and', $class );
			psao_sort_checkbox_simple( 'em_tag', 'em_tag_psao_and', $class );
		} else {
			psao_sort_checkbox_simple( 'admin_em_tag', 'admin_em_tag_psao_in', $class );
			psao_sort_checkbox_simple( 'em_tag', 'em_tag_psao_in', $class );
		}
	} else {
		if ( $atts['relation'] === 'and' ) {
			$queryname = $atts['taxname'] . '_psao_and';
		} else {
			$queryname = $atts['taxname'] . '_psao_in';
		}
		psao_sort_checkbox_simple( $atts['taxname'], $queryname, $class );
	}

	return ob_get_clean();
}
