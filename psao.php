<?php
/**
 * Plugin Name:       post search and order
 * Plugin URI:        https://wordpress.org/plugins/post-search-and-order
 * Description:       投稿の検索、ソート用ショートコード追加プラグイン。
 * Version:           1.1
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            yukimichi
 * Author URI:
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */
/*
  Copyright 2021 yukimichi

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
defined( 'ABSPATH' ) || exit;

// 定数設定!
if ( ! defined( 'POST_SEARCH_AND_ORDER_BASENAME' ) ) {
	define( 'POST_SEARCH_AND_ORDER_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'POST_SEARCH_AND_ORDER_BASEDIR' ) ) {
	define( 'POST_SEARCH_AND_ORDER_BASEDIR', plugin_dir_path( __FILE__ ) );
}

// ファイルの読み込み!
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/author.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/customfieldnum.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/customfieldrange.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/customfieldsearch.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/daterange.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/formwrap.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/option.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/order.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/orderby.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/period.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/postsnum.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/reset.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/searchform.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/taxonomy-simplecheckbox.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/taxonomy.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/shortcode/template.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/hook.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/option.php';
require_once POST_SEARCH_AND_ORDER_BASEDIR . 'lib/template-tag.php';

/*css,jsエンキュー */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_script( 'psaojs', plugins_url( 'lib/js/psao.js', __FILE__ ), array(), '1.1', true );
		wp_enqueue_style( 'psaocss', plugins_url( 'lib/css/psao.css', __FILE__ ), array(), '1.1' );

		// select2!
		wp_enqueue_script( 'select2js', plugins_url( 'lib/select2-4.0.13/dist/js/select2.min.js', __FILE__ ), array( 'jquery' ), '4.0.13', true );
		wp_enqueue_script( 'select2ja', plugins_url( 'lib/select2-4.0.13/dist/js/i18n/ja.js', __FILE__ ), array( 'jquery' ), '4.0.13', true );
		wp_enqueue_style( 'select2css', plugins_url( 'lib/select2-4.0.13/dist/css/select2.min.css', __FILE__ ), array(), '4.0.13' );
	}
);

// JavaScript遅延読み込み!
add_filter(
	'script_loader_tag',
	function ( $tag, $handle ) {
		if ( $handle === 'psaojs' || $handle === 'select2js' || $handle === 'select2ja' ) {
			return str_replace( 'src', 'async defer src', $tag );
		}
		return $tag;
	},
	10,
	2
);
// css遅延読み込み!
add_filter(
	'style_loader_tag',
	function ( $tag, $handle ) {
		if ( $handle === 'psaocss' || $handle === 'select2css' ) {
			return str_replace( 'media=\'all\'', 'media=\'print\' onload="this.media=\'all\'"', $tag );
		}
		return $tag;
	},
	10,
	2
);


add_filter( 'plugin_action_links_' . POST_SEARCH_AND_ORDER_BASENAME, 'psao_plugin_action_links', 10, 2 );
// プラグインリストページで設定ページへのリンクを表示!
function psao_plugin_action_links( $links, $file ) {
	static $this_plugin;

	if ( ! $this_plugin ) {
		$this_plugin = POST_SEARCH_AND_ORDER_BASENAME;
	}
	// var_dump($this_plugin);!
	if ( $file == $this_plugin ) {
		$settings_link = '<a href="' . get_admin_url() . 'admin.php?page=post_search_and_order_setting">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}
