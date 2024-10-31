<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'taxonomy_psao',
	'psao_taxonomy'
);
function psao_taxonomy( $atts ) {
	$atts = shortcode_atts(
		array(
			'taxname'      => 'category',
			'label'        => null,
			'relation'     => 'and',
			'class'        => null,
			'adminemlabel' => '固定ボタンタグ',
			'emlabel'      => '追加ボタンタグ',
			'size'         => null,
			'multiple'     => null,
			'type'         => 'select',
		),
		$atts
	);

	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	// ラベル設定!
	if ( $atts['label'] === null ) {
		if ( $atts['taxname'] === 'category' ) {
			$atts['label'] = 'カテゴリを選択';
		} elseif ( $atts['taxname'] === 'post_tag' ) {
			$atts['label'] = 'タグを選択';
		} else {
			$ctax          = get_taxonomy( $atts['taxname'] );
			$atts['label'] = $ctax->label . 'を選択';
		}
	}

	// クエリ名・タイプ設定!
	if ( $atts['taxname'] === 'category' ) {
		if ( $atts['relation'] === 'and' ) {
			$queryname = 'category__and_psao';
		} else {
			$queryname = 'category__in_psao';
		}
		if ( $atts['type'] === 'select' ) {
			psao_sort_selectbox( $atts['taxname'], $queryname, $class, $atts['label'], $atts['size'] );
		} else {
			psao_sort_checkbox( $atts['taxname'], $queryname, $class, $atts['label'] );
		}
	} elseif ( $atts['taxname'] === 'post_tag' ) {
		if ( $atts['relation'] === 'and' ) {
			$queryname = 'tag__and_psao';
		} else {
			$queryname = 'tag__in_psao';
		}
		if ( $atts['type'] === 'select' ) {
			psao_sort_selectbox( $atts['taxname'], $queryname, $class, $atts['label'], $atts['size'] );
		} else {
			psao_sort_checkbox( $atts['taxname'], $queryname, $class, $atts['label'] );
		}
	} elseif ( $atts['taxname'] === 'em_tag' ) {
		if ( $atts['relation'] === 'and' ) {
			if ( $atts['type'] === 'select' ) {
				psao_sort_selectbox( 'em_tag', 'em_tag_psao_and', $class, $atts['emlabel'], $atts['size'] );
			} else {
				psao_sort_checkbox( 'em_tag', 'em_tag_psao_and', $class, $atts['emlabel'] );
			}
		} else {
			if ( $atts['type'] === 'select' ) {
				psao_sort_selectbox( 'em_tag', 'em_tag_psao_in', $class, $atts['emlabel'], $atts['size'] );
			} else {
				psao_sort_checkbox( 'em_tag', 'em_tag_psao_in', $class, $atts['emlabel'] );
			}
		}
	} elseif ( $atts['taxname'] === 'admin_em_tag' ) {
		if ( $atts['relation'] === 'and' ) {
			if ( $atts['type'] === 'select' ) {
				psao_sort_selectbox( 'admin_em_tag', 'admin_em_tag_psao_and', $class, $atts['adminemlabel'], $atts['size'] );
			} else {
				psao_sort_checkbox( 'admin_em_tag', 'admin_em_tag_psao_and', $class, $atts['adminemlabel'] );
			}
		} else {
			if ( $atts['type'] === 'select' ) {
				psao_sort_selectbox( 'admin_em_tag', 'admin_em_tag_psao_in', $class, $atts['adminemlabel'], $atts['size'] );
			} else {
				psao_sort_checkbox( 'admin_em_tag', 'admin_em_tag_psao_in', $class, $atts['adminemlabel'] );
			}
		}
	} else {
		if ( $atts['relation'] === 'and' ) {
			$queryname = $atts['taxname'] . '_psao_and';
		} else {
			$queryname = $atts['taxname'] . '_psao_in';
		}
		if ( $atts['type'] === 'select' ) {
			psao_sort_selectbox( $atts['taxname'], $queryname, $class, $atts['label'], $atts['size'] );
		} else {
			psao_sort_checkbox( $atts['taxname'], $queryname, $class, $atts['label'] );
		}
	}

	return ob_get_clean();
}
