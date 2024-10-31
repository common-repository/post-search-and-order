<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * タクソノミ系セレクトボックス生成
 *
 * @param [type] $taxname
 * @param [type] $queryname
 * @param [type] $class
 * @param [type] $label
 * @return void
 */
function psao_sort_selectbox( $taxname, $queryname, $class, $label ,$size ) {

		$args = array(
			'show_option_none'  => $label,
			'option_none_value' => '',
			'show_count'        => 1,
			'orderby'           => 'name',
			'echo'              => 0,
			'class'             => 'psao psao_select' . $class,
			'hierarchical'      => true,
			'taxonomy'          => $taxname,
			'name'              => $queryname,
		);

		if ( isset( $size ) ) {
			$select = wp_dropdown_categories( $args );

			$replace = '<select$1 size="' . $size . '">';
			$select  = preg_replace( '#<select([^>]*)>#', $replace, $select );
			$select  = str_replace( 'value="' . get_query_var( $queryname ) . '"', 'selected value="' . get_query_var( $queryname ) . '"', $select ); // 値が選択中の場合!

		} else {
			$select = wp_dropdown_categories( $args );

			$select = str_replace( 'value="' . get_query_var( $queryname ) . '"', 'selected value="' . get_query_var( $queryname ) . '"', $select ); // 値が選択中の場合!
		}

		$allowed_html = array(
			'select' => array(
				'size'  => array(),
				'name'  => array(),
				'id'    => array(),
				'class' => array(),
			),
			'option' => array(
				'value'    => array(),
				'class'    => array(),
				'selected' => array(),
			),
		);
		echo wp_kses( $select, $allowed_html );

		// https://wpdocs.osdn.jp/%E3%83%86%E3%83%B3%E3%83%97%E3%83%AC%E3%83%BC%E3%83%88%E3%82%BF%E3%82%B0/wp_dropdown_categories!
}

/**
 * チェックボックス生成
 *
 * @param [type] $taxname
 * @param [type] $queryname
 * @param [type] $indexurl
 * @param [type] $class
 * @param [type] $label
 * @return void
 */
function psao_sort_checkbox( $taxname, $queryname, $class, $label ) {
	$getquery = get_query_var( $queryname );
	// var_dump( $getquery );
	if ( empty( $getquery ) ) { // 未入力なら初期状態を閉じた状態に!
		$psao_hiddenwrap = 'psao_hiddenwrap ';
	}
	$terms = get_terms( $taxname, 'orderby=count&order=DESC' );
	?>
	<div class="psao psao_toglewrap<?php echo esc_attr( $class ); ?>">
		<div class="psao_showbutton"><?php echo esc_html( $label ); ?></div>
		<div class="<?php echo esc_attr( $psao_hiddenwrap ); ?>psao_checkbox">
			<?php
			// チェックボックス出力!
			if ( $terms ) {
				foreach ( $terms as $term ) {
					if ( is_array( $getquery ) ) {
						if ( in_array( (string) $term->term_id, $getquery, true ) ) {
							$check = 'checked';
						}
					} else {
						$check = checked( $getquery, $term->term_id, false );
					}
					echo '<label>' . esc_html( $term->name ) . '<input ' . esc_html( $check ) . ' name=' . esc_attr( $queryname ) . '[] type="checkbox" value="' . esc_attr( $term->term_id ) . '"></label>';
					$check = null;
				}
			}
			?>
		</div>
	</div>
	<?php
}

/**
 * 折りたたみなしチェックボックス
 *
 * @param [type] $taxname
 * @param [type] $queryname
 * @param [type] $indexurl
 * @param [type] $class
 * @return void
 */
function psao_sort_checkbox_simple( $taxname, $queryname, $class ) {
	$getquery = get_query_var( $queryname );

	$terms = get_terms( $taxname, 'orderby=count&order=DESC' );
	?>

	<div class="psao psao_simplecheckbox<?php echo esc_attr( $class ); ?>">
		<?php
		if ( $terms ) {
			foreach ( $terms as $term ) {
				if ( is_array( $getquery ) ) {
					if ( in_array( (string) $term->term_id, $getquery, true ) ) {
						$check = 'checked';
					}
				} else {
					$check = checked( $getquery, $term->term_id, false );
				}
				echo '<label>' . esc_html( $term->name ) . '<input ' . esc_html( $check ) . ' name="' . esc_attr( $queryname ) . '[]" type="checkbox" value="' . esc_attr( $term->term_id ) . '"></label>';
				$check = null;
			}
		}
		?>
	</div>
	<?php
}

/**
 * 並べ替えテンプレート
 *
 * @param [type] $enable
 * @param [type] $orderby
 * @return void
 */
function psao_template_orderby( $enables, $orderby ) {
	if ( ! empty( $enables ) && $enables !== 'all' ) {
		// 入力された順番で出力 !
		$enables = explode( ',', $enables );
		foreach ( $enables as $enable ) {
			if ( strpos( $enable, 'date' ) !== false ) {
				?>
				<option <?php selected( $orderby, 'date' ); ?> value="date"><?php esc_html_e( '投稿順' ); ?></option>
				<?php
			} elseif ( strpos( $enable, 'modified' ) !== false ) {
				?>
				<option <?php selected( $orderby, 'modified' ); ?> value="modified"><?php esc_html_e( '更新順' ); ?></option>
				<?php
			} elseif ( strpos( $enable, 'title' ) !== false ) {
				?>
				<option <?php selected( $orderby, 'title' ); ?> value="title"><?php esc_html_e( 'タイトル順' ); ?></option>
				<?php
			} elseif ( strpos( $enable, 'rand' ) !== false ) {
				?>
				<option <?php selected( $orderby, 'rand' ); ?> value="rand"><?php esc_html_e( 'ランダム' ); ?></option>
				<?php
			} elseif ( strpos( $enable, 'comment_count' ) !== false ) {
				?>
				<option <?php selected( $orderby, 'comment_count' ); ?> value="comment_count"><?php esc_html_e( 'コメント数順' ); ?></option>
				<?php
			}
		}
	} else {
		// allだった場合順番に出力して終了!
		?>
		<option <?php selected( $orderby, 'date' ); ?> value="date"><?php esc_html_e( '投稿順' ); ?></option>
		<option <?php selected( $orderby, 'modified' ); ?> value="modified"><?php esc_html_e( '更新順' ); ?></option>
		<option <?php selected( $orderby, 'title' ); ?> value="title"><?php esc_html_e( 'タイトル順' ); ?></option>
		<option <?php selected( $orderby, 'rand' ); ?> value="rand"><?php esc_html_e( 'ランダム' ); ?></option>
		<option <?php selected( $orderby, 'comment_count' ); ?> value="comment_count"><?php esc_html_e( 'コメント数順' ); ?></option>
		<?php
	}
}

/**
 * Undocumented function
 *
 * @param [type] $atts
 * @return url
 */
function psao_urlsetting_form( $atts ) {
	if ( empty( $atts['indexurl'] ) ) {
		$url = home_url( '/' );
	} elseif ( $atts['indexurl'] === 'now' ) {
		$url = '';
	} else {
		$url = $atts['indexurl'];
	}
	return $url;
}

/**
 * Undocumented function
 *
 * @param [type] $atts
 * @return cssclass
 */
function psao_user_cssclass( $atts ) {
	if ( ! empty( $atts['class'] ) ) {
		$class = ' ' . $atts['class'];
	}
	return $class;
}


// https://kinocolog.com/php_now_url/
/**
 * 現在のURL取得
 *
 * @return void
 */

function psao_resetUrl() {
	$url = '';
	if ( isset( $_SERVER['HTTPS'] ) ) {
		$url .= 'https://';
	} else {
		$url .= 'http://';
	}
	$url .= $_SERVER['HTTP_HOST'] . wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	return $url;
}
