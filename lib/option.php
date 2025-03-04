<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// 設定ページ追加!
add_action( 'admin_menu', 'psao_addoptionpage' );
function psao_addoptionpage() {
	add_options_page( 'post search and order', 'post search and order', 'manage_options', 'post_search_and_order_setting', 'psao_option_content' );
}

// オプション更新処理!
add_action(
	'init',
	function () {
		if ( isset( $_POST['psao_name'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['psao_name'] ) ), 'psao_action' ) ) {

			if ( isset( $_POST['psaosearchtype'] ) ) {
				$psaosearchtype = array_map( 'sanitize_text_field', wp_unslash( $_POST['psaosearchtype'] ) );
				update_option( 'psaosearchtype', $psaosearchtype );
			} else {
				update_option( 'psaosearchtype', false );
			}

			if ( isset( $_POST['psaosearchsticky_select'] ) ) {
				update_option( 'psaosearchsticky_select', sanitize_text_field( wp_unslash( $_POST['psaosearchsticky_select'] ) ) );
			} else {
				update_option( 'psaosearchsticky_select', false );
			}

			if ( isset( $_POST['psaopoststatus'] ) ) {
				$psaopoststatus = array_map( 'sanitize_text_field', wp_unslash( $_POST['psaopoststatus'] ) );
				update_option( 'psaopoststatus', $psaopoststatus );
			} else {
				update_option( 'psaopoststatus', false );
			}
		}
	}
);

function psao_option_content() {
	?>
	<style>
		label{
			margin-right: 11px;
		}
	</style>

	<div class="wrap">
		<h1>プラグインオプション</h1>
		<form method="post">
			<?php
			wp_nonce_field( 'psao_action', 'psao_name' );  // nonceフィールド設置!
			?>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<td>
						<p class="description">検索、ソート結果で表示する投稿タイプの指定</p>
							<?php
							$psaosearchtype = get_option( 'psaosearchtype' );
							$args       = array(
								'public' => true,
							);
							$posttypes  = get_post_types( $args );
							foreach ( $posttypes as $name ) {
								echo '<label><input ' . checked( isset( $psaosearchtype[ $name ] ), true, false ) . ' name="psaosearchtype[' . esc_attr( $name ) . ']" value="' . esc_attr( $name ) . '" type="checkbox" class="regular-text">' . esc_html( $name ) . '</label>';
							}
							?>
						</td>
					</tr>
					<tr>
						<td>
						<p class="description">検索、ソート結果で先頭固定ページを表示するか</p>
							<?php
							echo '<label><input ' . checked( get_option( 'psaosearchsticky_select' ), 'enable', false ) . ' name="psaosearchsticky_select" value="enable" type="radio" class="regular-text">先頭に表示する</label>';
							echo '<label><input ' . checked( get_option( 'psaosearchsticky_select' ), 'disable', false ) . ' name="psaosearchsticky_select" value="disable" type="radio" class="regular-text">先頭に表示しない</label>';
							?>
						</td>
					</tr>
					<tr>
						<td>
						<p class="description">検索、ソート結果で表示する投稿ステータスの指定</p>
							<?php
							$psaopoststatus = get_option( 'psaopoststatus' );
							$statuslist = array(
								'publish',
								'pending',
								'draft',
								'auto-draft',
								'future',
								'private',
								'inherit',
								'trash',
								'any',
							);
							foreach ( $statuslist as $status ) {
								echo '<label><input ' . checked( isset( $psaopoststatus[ $status ] ), true, false ) . ' name="psaopoststatus[' . esc_attr( $status ) . ']" value="' . esc_attr( $status ) . '" type="checkbox" class="regular-text">' . esc_html( $status ) . '</label>';
							}
							?>
							<p class="description">
							<br>※投稿タイプでattachmentを追加している場合は、inheritを追加で指定するか、anyを選択してください
							<br>※publish以外を選択したい場合、投稿表示数の変更（postsnum_psaoショートコード）と同時に使用することはできません
							</p>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" class="button button-primary" name="psaosubmit" value="変更を保存">
			</p>
		</form>
	</div>
	<?php
}
