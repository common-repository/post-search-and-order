<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// https://wood-roots.com/web/wordpress/1362!
add_filter( 'query_vars', 'psao_add_meta_query_vars' );
/**
 * カスタムクエリの追加
 *
 * @param [type] $public_query_vars
 * @return void
 */
function psao_add_meta_query_vars( $public_query_vars ) {
	$public_query_vars[] = 'word_psao';
	$public_query_vars[] = 'srelation_psao';
	$public_query_vars[] = 'swhere_psao';
	$public_query_vars[] = 'sstrict_psao';
	$public_query_vars[] = 'cat_psao';
	$public_query_vars[] = 'category__and_psao';
	$public_query_vars[] = 'category__in_psao';
	$public_query_vars[] = 'tag_psao';
	$public_query_vars[] = 'tag__and_psao';
	$public_query_vars[] = 'tag__in_psao';
	$public_query_vars[] = 'date-end';
	$public_query_vars[] = 'date-start';
	$public_query_vars[] = 'period_psao';
	$public_query_vars[] = 'keynum_psao';
	$public_query_vars[] = 'keynum-start';
	$public_query_vars[] = 'keynum-end';
	$public_query_vars[] = 'keynum-name';
	$public_query_vars[] = 'order_psao';
	$public_query_vars[] = 'orderby_psao';
	$public_query_vars[] = 'postsnum_psao';
	$public_query_vars[] = 'author_psao';
	$public_query_vars[] = 'csearchkey_psao';
	$public_query_vars[] = 'csearch_psao';
	$public_query_vars[] = 'csearchoption_psao';
	$public_query_vars[] = 'option_psao';
	$public_query_vars[] = 'inkey_psao';
	$public_query_vars[] = 'poststatus_psao';

		// カスタムタクソノミ追加!
		$args       = array(
			'public'   => true,
			'_builtin' => false,
		);
		$output     = 'names';
		$taxonomies = get_taxonomies( $args, $output );
		if ( $taxonomies ) {
			foreach ( $taxonomies as $name ) {
				$public_query_vars[] = $name . '_psao_in';
				$public_query_vars[] = $name . '_psao_and';
			}
		}

		return $public_query_vars;
}

add_action( 'pre_get_posts', 'psao_change_pre_posts' );
function psao_change_pre_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	// $time_start = microtime( true );
	/**
	 * プラグインで追加したパラメーターのチェック
	 */
	$paramlist = array(
		'word_psao'          => true,
		'srelation_psao'     => true,
		'swhere_psao'        => true,
		'sstrict_psao'       => true,
		'cat_psao'           => true,
		'category__and_psao' => true,
		'category__in_psao'  => true,
		'tag_psao'           => true,
		'tag__and_psao'      => true,
		'tag__in_psao'       => true,
		'date-end'           => true,
		'date-start'         => true,
		'period_psao'        => true,
		'keynum-name'        => true,
		'keynum-end'         => true,
		'keynum-start'       => true,
		'keynum_psao'        => true,
		'order_psao'         => true,
		'orderby_psao'       => true,
		'postsnum_psao'      => true,
		'author_psao'        => true,
		'csearchkey_psao'    => true,
		'csearch_psao'       => true,
		'csearchoption_psao' => true,
		'option_psao'        => true,
		'inkey_psao'         => true,
		'poststatus_psao'    => true,
	);

	$args       = array(
		'public'   => true,
		'_builtin' => false,
	);
	$output     = 'names';
	$taxonomies = get_taxonomies( $args, $output );
	if ( $taxonomies ) {
		$ctaxs  = array();
		$ctaxs2 = array();
		foreach ( $taxonomies as $name ) {
			$ctaxs[ $name . '_psao_in' ]   = true;
			$ctaxs2[ $name . '_psao_and' ] = true;
		}
		$paramlist = array_merge( $paramlist, $ctaxs, $ctaxs2 );
	}

	// wp_die(print_r(array_intersect_key($_GET, $paramlist)), 'debug');!
	if ( array_intersect_key( $_GET, $paramlist ) ) {
		// オプション設定!
		if ( get_option( 'psaosearchsticky_select' ) === 'disable' || ! get_option( 'psaosearchsticky_select' ) ) {
			$query->set( 'ignore_sticky_posts', 1 );
		}
		$typelist = get_option( 'psaosearchtype' );
		if ( $typelist ) {
			// $typelist = explode( ',', $typelist );
			$query->set( 'post_type', array_values( $typelist ) );
		}

		$poststatus = get_option( 'psaopoststatus' );
		if ( $poststatus ) {
			$query->set( 'post_status', array_values( $poststatus ) );
		}

		/**
		 * キーワード検索
		 */
		// https://meshikui.com/2019/04/01/1605/ !
		$wordpsao = get_query_var( 'word_psao' );
		if ( ! empty( $wordpsao ) ) {
			$wordpsao = wp_strip_all_tags( str_replace( '　', ' ', $wordpsao ), true );
			$query->set( 's', $wordpsao );
			/**
			* キーワード検索オプション
			*/
			add_filter(
				'posts_search',
				function ( $search ) {
					$sstrictpsao   = get_query_var( 'sstrict_psao' );
					$srelationpsao = get_query_var( 'srelation_psao' );
					$swherepsao    = get_query_var( 'swhere_psao' );

					// 厳格に検索 検索方式を「LIKE」から「LIKE BINARY」へ変更するコード https://mycus-tom.com/posts/30 !
					if ( ! empty( $sstrictpsao ) && $sstrictpsao === 'strict' ) {
						$search = str_replace( 'LIKE', 'LIKE BINARY', $search );
					}
					// or検索に変更 https://inafukukazuya.com/archives/6684 !
					if ( ! empty( $srelationpsao ) && $srelationpsao === 'or' ) {
						$search = str_replace( ')) AND ((', ')) OR ((', $search );
					}
					// title検索に変更 https://mycus-tom.com/posts/31 !
					if ( ! empty( $swherepsao ) && $swherepsao === 'title' ) {
						$search = str_replace(
							array( 'excerpt', 'content' ),
							array( 'title', 'title' ),
							$search
						);

					}
					// wp_die( $search );
					return $search;
				}
			);

			/**
			 * カスタムフィールドを検索対象に
			 */
			// https://www.scitech.co.jp/include-customfields-search-target !
			$inkey_psao = get_query_var( 'inkey_psao' );
			if ( ! empty( $inkey_psao ) ) {
				$swherepsao = get_query_var( 'swhere_psao' );
				if ( empty( $swherepsao ) ) {

					function psao_cf_search_join( $join ) {
						global $wpdb;

						$join .= ' LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';

						return $join;
					}
					add_filter( 'posts_join', 'psao_cf_search_join' );

					function psao_cf_search_where( $where ) {
						global $wpdb;
						$sstrictpsao = get_query_var( 'sstrict_psao' );
						if ( ! empty( $sstrictpsao ) && $sstrictpsao === 'strict' ) {
							$like = 'LIKE BINARY';
						} else {
							$like = 'LIKE';
						}

						$where = preg_replace(
							'/\(\s*' . $wpdb->posts . '.post_title\s+' . $like . '\s*(\'[^\']+\')\s*\)/',
							'(' . $wpdb->posts . '.post_title ' . $like . ' $1) OR (' . $wpdb->postmeta . '.meta_value ' . $like . ' $1)',
							$where
						);

						// 特定のカスタムフィールドを検索対象から外す（※１）
						// $where .= ' AND (' . $wpdb->postmeta . ".meta_key NOT LIKE 'number')";
						// $where .= ' AND (' . $wpdb->postmeta . ".meta_key NOT LIKE 'zip')";
						// $where .= ' AND (' . $wpdb->postmeta . ".meta_key NOT LIKE 'access')";

						// wp_die( $where );
						return $where;
					}
					add_filter( 'posts_where', 'psao_cf_search_where' );

					function psao_cf_search_distinct() {
						return 'DISTINCT';
					}
					add_filter( 'posts_distinct', 'psao_cf_search_distinct' );
				}
			}
		}

		/**
		 * 表示数
		 */
		$postnum = get_query_var( 'postsnum_psao' );
		if ( ! empty( $postnum ) ) {
			$query->set( 'posts_per_page', $postnum );
		}

		/**
		 * 投稿状態
		 */
		$poststatus_psao = get_query_var( 'poststatus_psao' );
		if ( ! empty( $postnum ) ) {
			$query->set( 'post_status', $poststatus_psao );
		}

		/**
		 * ソート用カテゴリ
		 */
		$catpsao = get_query_var( 'cat_psao' );
		if ( ! empty( $catpsao ) ) {
			$query->set( 'cat', $catpsao );
		}
		// アンド!
		$categoryand_psao = get_query_var( 'category__and_psao' );
		if ( ! empty( $categoryand_psao ) ) {
			$query->set( 'category__and', $categoryand_psao );
		}
		// オア!
		$categoryin_psao = get_query_var( 'category__in_psao' );
		if ( ! empty( $categoryin_psao ) ) {
			$query->set( 'category__in', $categoryin_psao );
		}

		/**
		 * ソート用タグ
		 */
		// 単体!
		$tagpsao = get_query_var( 'tag_psao' );
		if ( ! empty( $tagpsao ) ) {
			$query->set( 'tag_id', $tagpsao );
		}
		// アンド!
		$tagand_psao = get_query_var( 'tag__and_psao' );
		if ( ! empty( $tagand_psao ) ) {
			$query->set( 'tag__and', $tagand_psao );
		}
		// オア!
		$tagin_psao = get_query_var( 'tag__in_psao' );
		if ( ! empty( $tagin_psao ) ) {
			$query->set( 'tag__in', $tagin_psao );
		}

		/**
		 * ソート用カスタムタクソノミ
		 */
		// OR!
		foreach ( $taxonomies as $name ) {
			$ctax_psao_in = get_query_var( $name . '_psao_in' );
			if ( ! empty( $ctax_psao_in ) ) {

				$query->set(
					'tax_query',
					array(
						array(
							'taxonomy' => $name,
							'field'    => 'term_id',
							'terms'    => $ctax_psao_in,
							'operator' => 'OR',
						),
					)
				);
			}
		}
		// アンド!
		foreach ( $taxonomies as $name ) {
			$ctax_psao_and = get_query_var( $name . '_psao_and' );
			if ( ! empty( $ctax_psao_and ) ) {

				$query->set(
					'tax_query',
					array(
						array(
							'taxonomy' => $name,
							'field'    => 'term_id',
							'terms'    => $ctax_psao_and,
							'operator' => 'AND',
						),
					)
				);
			}
		}

		/**
		 * 期間指定
		 */
		$peropdpsao = get_query_var( 'period_psao' );
		$datedstart = get_query_var( 'date-start' );
		$datedend   = get_query_var( 'date-end' );
		// 期間入力!
		if ( ! empty( $datedstart ) || ! empty( $datedend ) ) {

			$args = array(
				'inclusive' => true,
			);

			if ( ( $datedstart ) && ( $datedend ) ) {
				$args['after']   = $datedstart . ' 23:59:59';
				$args['before']  = $datedend . ' 23:59:59';
				$args['compare'] = 'BETWEEN';
			} elseif ( ( $datedstart ) ) {
				$args['after']   = $datedstart . ' 23:59:59';
				$args['compare'] = '>=';
			} elseif ( ( $datedend ) ) {
				$args['before']  = $datedend . ' 23:59:59';
				$args['compare'] = '<=';
			}

			$query->set(
				'date_query',
				array( $args )
			);

			// 期間定形!
		} elseif ( ! empty( $peropdpsao ) ) {
			if ( $peropdpsao === 'day' ) {
				$query->set(
					'date_query',
					array(
						'after' => '1 day ago',
					)
				);
			}
			if ( $peropdpsao === '3day' ) {
				$query->set(
					'date_query',
					array(
						'after' => '3 day ago',
					)
				);
			}
			if ( $peropdpsao === 'week' ) {
				$query->set(
					'date_query',
					array(
						'after' => '1 week ago',
					)
				);
			}
			if ( $peropdpsao === 'month' ) {
				$query->set(
					'date_query',
					array(
						'after' => '1 month ago',
					)
				);
			}
			if ( $peropdpsao === '3month' ) {
				$query->set(
					'date_query',
					array(
						'after' => '3 month ago',
					)
				);
			}
			if ( $peropdpsao === 'year' ) {
				$query->set(
					'date_query',
					array(
						'after' => '1 year ago',
					)
				);
			}
		}

		/**
		 * カスタムフィールド
		 */
		// 数字!
		$keynum_psao = get_query_var( 'keynum_psao', false );
		if ( $keynum_psao ) {
				$args = array(
					'key'     => $keynum_psao,
					'type'    => 'NUMERIC',
					'compare' => 'EXISTS',
				);
				$query->set(
					'meta_query',
					array( $args )
				);
		}

		// 数字間隔!
		$keynumstart = get_query_var( 'keynum-start', false );
		$keynumend   = get_query_var( 'keynum-end', false );
		$keynumname  = get_query_var( 'keynum-name', false );
		if ( $keynumname ) {
			if ( is_numeric( $keynumstart ) && is_numeric( $keynumend ) ) {
				$args = array(
					'key'     => $keynumname,
					'type'    => 'NUMERIC',
					'value'   => array( $keynumstart, $keynumend ),
					'compare' => 'BETWEEN',
				);
				$query->set(
					'meta_query',
					array( $args )
				);
			} elseif ( is_numeric( $keynumstart ) ) {
				$args = array(
					'key'     => $keynumname,
					'type'    => 'NUMERIC',
					'value'   => $keynumstart,
					'compare' => '>=',
				);
				$query->set(
					'meta_query',
					array( $args )
				);
			} elseif ( is_numeric( $keynumend ) ) {
				$args = array(
					'key'     => $keynumname,
					'type'    => 'NUMERIC',
					'value'   => $keynumend,
					'compare' => '<=',
				);
				$query->set(
					'meta_query',
					array( $args )
				);
			}
		}

		// 文字検索!
		// https://www-creators.com/archives/5332 !
		$csearchkey_psao    = get_query_var( 'csearchkey_psao' );
		$csearch_psao       = get_query_var( 'csearch_psao' );
		$csearchoption_psao = get_query_var( 'csearchoption_psao' );
		if ( ! empty( $csearchkey_psao ) && ! empty( $csearch_psao ) ) {
			$args         = array(
				'key'  => $csearchkey_psao,
				'type' => 'CHAR',
			);
			$csearch_psao = wp_strip_all_tags( str_replace( '　', ' ', $csearch_psao ), true );

			if ( $csearchoption_psao === 'or' ) {
				// https://ja.wordpress.org/support/topic/meta_query%E3%82%92%E4%BD%BF%E3%81%A3%E3%81%9F%E7%B5%9E%E3%82%8A%E8%BE%BC%E3%81%BF%E3%81%AB%E3%81%A4%E3%81%84%E3%81%A6/ !
				$csearch_psao    = str_replace( ' ', '|', $csearch_psao );
				$args['value']   = $csearch_psao;
				$args['compare'] = 'RLIKE';
			} else {
				// https://www-creators.com/archives/5332 !
				// $csearch_psao    = str_replace( ' ', ')(?=.*', $csearch_psao );
				// $args['value']   = '(?=.*' . $csearch_psao . ')';
				// $args['compare'] = 'RLIKE';
				$csearch_psao    = str_replace( ' ', '|', $csearch_psao );
				$args['value']   = $csearch_psao;
				$args['compare'] = 'RLIKE';
			}

			$query->set(
				'meta_query',
				array( $args )
			);

		}

		/**
		 * 投稿者
		 */
		$authorpsao = get_query_var( 'author_psao' );
		if ( ! empty( $authorpsao ) ) {
			$query->set( 'author', $authorpsao );
		}

		/**
		 * 並べ替え条件
		 */
		$orderpsao = get_query_var( 'order_psao', 'DESC' );
		$query->set( 'order', $orderpsao );

		// ordeby設定!
		$orderbypsao = get_query_var( 'orderby_psao' );
		if ( ! empty( $keynumstart ) || ! empty( $keynumend ) || ! empty( $keynum_psao ) || ! empty( $orderbypsao ) ) {
			$orderby = array();

			if ( is_numeric( $keynumstart ) || is_numeric( $keynumend ) ) {
				$orderby[ $keynumname ] = $orderpsao;
			}

			if ( ! empty( $keynum_psao ) ) {
				$orderby[ $keynum_psao ] = $orderpsao;
			}

			if ( ! empty( $orderbypsao ) ) {
				$orderby[ $orderbypsao ] = $orderpsao;
			}

			$query->set( 'orderby', $orderby );
		}

		/**
		 * 追加オプション
		 */
		$option_psao = get_query_var( 'option_psao' );
		if ( ! empty( $option_psao ) ) {
			$option_psao = implode( ',', $option_psao );
			if ( strpos( $option_psao, 'password' ) !== false ) {
				$query->set( 'has_password', true );
			}
			if ( strpos( $option_psao, 'comments' ) !== false ) {
				$query->set(
					'comment_count',
					array(
						'value'   => 0,
						'compare' => '>',
					)
				);
			}
		}

		// add_filter( 'posts_where', 'add_my_query' );
		// function add_my_query( $where ) {
		// wp_die( $where );
		// };

		// $time = microtime( true ) - $time_start;
		// wp_die( "{$time} 秒" );
	}
}


// add_filter( 'posts_search', 'add_my_query' );
// function add_my_query( $where ) {
// wp_die( $where );
// };
