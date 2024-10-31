<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'search_psao',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'class'       => null,
				'placeholder' => null,
				'option'      => 'on',
				'strict'      => null,
				'inkey'       => null,
			),
			$atts
		);
		ob_start();

		// url設定!
		$url = psao_urlsetting_form( $atts );

		// ユーザークラス!
		$class = psao_user_cssclass( $atts );

		$word_psao      = get_query_var( 'word_psao' );
		$srelation_psao = get_query_var( 'srelation_psao' );
		$swhere_psao    = get_query_var( 'swhere_psao' );

		?>
	<div class="psao psao_keywordsearch<?php echo esc_attr( $class ); ?>">
		<input class="keyword" name="word_psao" type="text" placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>" value="<?php echo esc_attr( $word_psao ); ?>">

		<?php if ( ! empty( $atts['option'] ) ) { // オプション設定! ?>
			<div class="psao_wordoption">
				<?php if ( $atts['option'] === 'on' || strpos( $atts['option'], 'relation' ) !== false ) { ?>
					<span>
						<label><input <?php checked( $srelation_psao, 'and' ); ?> type="radio" name="srelation_psao" value="and"><?php esc_html_e( 'AND検索' ); ?></label>
						<label><input <?php checked( $srelation_psao, 'or' ); ?> type="radio" name="srelation_psao" value="or"><?php esc_html_e( 'OR検索' ); ?></label>
					</span>
				<?php } ?>
				<?php if ( $atts['option'] === 'on' || strpos( $atts['option'], 'title' ) !== false ) { ?>
					<label><input <?php checked( $swhere_psao, 'title' ); ?> type="checkbox" name="swhere_psao" value="title"><?php esc_html_e( 'タイトルから検索' ); ?></label>
				<?php } ?>
			</div>
			<?php
		}

		// 厳格検索を有効化!
		if ( $atts['strict'] !== null ) {
			?>
			<input type="hidden" name="sstrict_psao" value="strict">
			<?php
		}

		// カスタムフィールドを結合!
		if ( $atts['inkey'] !== null ) {
			?>
			<input type="hidden" name="inkey_psao" value="on">
			<?php
		}
		?>
	</div>
		<?php
		return ob_get_clean();
	}
);
