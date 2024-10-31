<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode( 'author_psao', 'psao_author' );
function psao_author( $atts ) {
	$atts = shortcode_atts(
		array(
			'class'       => null,
			'role'        => null,
			'excluderole' => null,
			'label'       => 'ユーザー選択',
		),
		$atts
	);
	ob_start();

	// ユーザークラス!
	$class = psao_user_cssclass( $atts );

	$getauthorsims = get_query_var( 'author_psao' );
	?>

	<select name="author_psao" class="psao psao_select psao_author<?php echo esc_attr( $class ); ?>">
		<option value=""><?php echo esc_html( $atts['label'] ); ?></option>
		<?php
		$args = array(
			'orderby' => 'display_name',
			'order'   => 'ASC',
		);

		if ( is_array( $atts['role'] ) ) {
			$atts['role'] = explode( ',', $atts['role'] );
		}
		if ( is_array( $atts['excluderole'] ) ) {
			$atts['excluderole'] = explode( ',', $atts['excluderole'] );
		} else {
			$atts['excluderole'] = array( $atts['excluderole'] );
		}

		if ( $atts['role'] ) {
			$args['role'] = $atts['role'];
		}
		if ( $atts['excluderole'] ) {
			$args['role__not_in'] = $atts['excluderole'];
		}

		$user_query = new WP_User_Query( $args );
		$userlist   = $user_query->get_results();

		foreach ( $userlist as $user ) {
			?>
			<option <?php selected( $getauthorsims, $user->ID ); ?> value="<?php echo esc_attr( $user->ID ); ?>">
			<?php echo esc_html( $user->display_name ); ?>
			</option>
			<?php
		}
		?>
</select>
	<?php
	return ob_get_clean();
}
