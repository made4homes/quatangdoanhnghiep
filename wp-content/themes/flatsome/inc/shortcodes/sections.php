<?php

function ux_section( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'_id'              => 'section_' . rand(),
		'class'            => '',
		'label'            => '',
		'visibility'       => '',
		'sticky'           => '',
		// Background.
		'bg'               => '',
		'bg_size'          => '',
		'bg_color'         => '',
		'bg_overlay'       => '',
		'bg_overlay__sm'   => '',
		'bg_overlay__md'   => '',
		'bg_pos'           => '',
		'parallax'         => '',
		'effect'           => '',
		// Video.
		'video_mp4'        => '',
		'video_ogg'        => '',
		'video_webm'       => '',
		'video_sound'      => 'false',
		'video_loop'       => 'true',
		'youtube'          => '',
		'video_visibility' => 'hide-for-medium',
		// Layout.
		'dark'             => 'false',
		'mask'             => '',
		'padding'          => '30px',
		'padding__sm'      => '',
		'padding__md'      => '',
		'height'           => '',
		'height__sm'       => '',
		'height__md'       => '',
		'margin'           => '',
		'loading'          => '',
		'scroll_for_more'  => '',
		// Shape divider.
		'divider_top'            => '',
		'divider_top_height'     => '150px',
		'divider_top_height__sm' => null,
		'divider_top_height__md' => null,
		'divider_top_width'      => '100',
		'divider_top_width__sm'  => null,
		'divider_top_width__md'  => null,
		'divider_top_fill'       => '',
		'divider_top_flip'       => 'false',
		'divider_top_to_front'   => 'false',
		'divider'                => '',
		'divider_height'         => '150px',
		'divider_height__sm'     => null,
		'divider_height__md'     => null,
		'divider_width'          => '100',
		'divider_width__sm'      => null,
		'divider_width__md'      => null,
		'divider_fill'           => '',
		'divider_flip'           => 'false',
		'divider_to_front'       => 'false',
		// Border Control.
		'border'           => '',
		'border_hover'     => '',
		'border_color'     => '',
		'border_margin'    => '',
		'border_radius'    => '',
		'border_style'     => '',
	), $atts );

	extract( $atts );

	// Hide if visibility is hidden.
	if ( $visibility === 'hidden' ) {
		return;
	}

	ob_start();

	$classes = array( 'section' );

	$classes_bg = array( 'section-bg', 'fill' );

	// Fix old.
	if ( strpos( $bg, '#' ) !== false ) {
		$atts['bg_color'] = $bg;
		$atts['bg']       = false;
	}

	// Add Custom Classes.
	if ( $class ) {
		$classes[] = $class;
	}

	// Add Dark text.
	if ( $dark === 'true' ) {
		$classes[] = 'dark';
	}

	// If sticky section.
	if ( $sticky ) {
		$classes[] = 'sticky-section';
	}

	// Add Mask.
	if ( $mask ) {
		$classes[] = 'has-mask mask-' . $mask;
	}

	// Add visibility class.
	if ( $visibility ) {
		$classes[] = $visibility;
	}

	// Add Parallax.
	if ( $parallax ) {
		$classes[] = 'has-parallax';
		$parallax  = 'data-parallax-container=".section" data-parallax-background data-parallax="-' . esc_attr( $parallax ) . '"';
	}

	// Background effects.
	if ( $effect ) {
		wp_enqueue_style( 'flatsome-effects' );
	}

	// Add Full Height Class.
	if ( $height === '100vh' ) {
		$classes[] = 'is-full-height';
	}

	if ( $border_hover ) {
		$classes[] = 'has-hover';
	}

	$classes    = implode( ' ', $classes );
	$classes_bg = implode( ' ', $classes_bg );
	?>

	<section class="<?php echo esc_attr( $classes ); ?>" id="<?php echo esc_attr( $_id ); ?>">
		<div class="<?php echo esc_attr( $classes_bg ); ?>" <?php echo $parallax; ?>>
			<?php if ( ! empty( $atts['bg'] ) ) echo wp_get_attachment_image( $atts['bg'], $atts['bg_size'], false, [ 'class' => 'bg' ] ); ?>

			<?php require( __DIR__ . '/commons/video.php' ); ?>

			<?php
			if ( $bg_overlay ) {
				echo '<div class="section-bg-overlay absolute fill"></div>';
			}
			if ( $loading ) {
				echo '<div class="loading-spin centered"></div>';
			}
			if ( $scroll_for_more ) {
				echo '<button class="scroll-for-more z-5 icon absolute bottom h-center" aria-label="' . esc_attr__( 'Scroll for more', 'flatsome' ) . '">' . get_flatsome_icon( 'icon-angle-down', '42px' ) . '</button>';
			}
			if ( $effect ) {
				echo '<div class="effect-' . esc_attr( $effect ) . ' bg-effect fill no-click"></div>';
			}
			?>

			<?php require( __DIR__ . '/commons/border.php' ); ?>

		</div>

		<?php require __DIR__ . '/commons/shape-divider.php'; ?>

		<div class="section-content relative">
			<?php echo $content; ?>
		</div>

		<?php
		// Get custom CSS.
		$args = array(
			'padding'    => array(
				'selector' => '',
				'property' => 'padding-top, padding-bottom',
			),
			'margin'     => array(
				'selector' => '',
				'property' => 'margin-bottom',
			),
			'height'     => array(
				'selector' => '',
				'property' => 'min-height',
			),
			'bg_color'   => array(
				'selector' => '',
				'property' => 'background-color',
			),
			'bg_overlay' => array(
				'selector' => '.section-bg-overlay',
				'property' => 'background-color',
			),
			'bg_pos'     => array(
				'selector' => '.bg',
				'property' => 'object-position',
			),
		);

		if ( $divider_top ) {
			$args = array_merge( $args, array(
				'divider_top_height' => array(
					'selector' => '.ux-shape-divider--top svg',
					'property' => 'height',
				),
				'divider_top_width'  => array(
					'selector' => '.ux-shape-divider--top svg',
					'property' => '--divider-top-width',
					'unit'     => '%',
				),
				'divider_top_fill'   => array(
					'selector' => '.ux-shape-divider--top .ux-shape-fill',
					'property' => 'fill',
				),
			) );
		}

		if ( $divider ) {
			$args = array_merge( $args, array(
				'divider_height' => array(
					'selector' => '.ux-shape-divider--bottom svg',
					'property' => 'height',
				),
				'divider_width'  => array(
					'selector' => '.ux-shape-divider--bottom svg',
					'property' => '--divider-width',
					'unit'     => '%',
				),
				'divider_fill'   => array(
					'selector' => '.ux-shape-divider--bottom .ux-shape-fill',
					'property' => 'fill',
				),
			) );
		}
		echo ux_builder_element_style_tag( $_id, $args, $atts );
		?>
	</section>
	<?php
	$content = ob_get_contents();
	ob_end_clean();

	return do_shortcode( $content );
}
