<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode( 'cnum_psao', 'psao_cnum' );
function psao_cnum( $atts ) {
	$atts = shortcode_atts(
		array(
			'class'    => null,
			'key'      => null,
			'label'    => null,
			'keylabel' => null,
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	$getkeynum_psao = get_query_var( 'keynum_psao' );
	?>
	<select name="keynum_psao" class="psao psao_select psao_cnum<?php echo esc_attr( $class ); ?>">
		<option value=""><?php echo esc_html( $atts['label'] ); ?></option>

		<?php
		$keys      = explode( ',', $atts['key'] );
		$i         = 0;
		$keylabels = explode( ',', $atts['keylabel'] );
		foreach ( $keys as $key ) {
			if ( ! $atts['keylabel'] ) {
				$keylabel = $key;
			} else {
				$keylabel = $keylabels[ $i ];
			}
			?>
		<option <?php selected( $getkeynum_psao, $key ); ?> value="<?php echo esc_attr( $key ); ?>">
			<?php echo esc_html( $keylabel ); ?>
		</option>
			<?php
			++$i;
		}
		?>
	</select>
	<?php

	return ob_get_clean();
}
