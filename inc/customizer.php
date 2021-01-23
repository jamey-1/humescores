<?php
/**
 * Humescores Theme Customizer
 *
 * @package Humescores
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function humescores_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/**
	 * Custom Customizer Customizations
	 */
	// Setting for header and footer background color
	$wp_customize->add_setting( 'theme_bg_color', array(
		'default' => '#002254',
		'transport' => 'postMessage',
		'type' => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	// Control for header and footer background color.
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'theme_bg_color',
			array(
				'label' => __( 'Header and footer background color', 'humescores' ),
				'section' => 'colors',
				'settings' => 'theme_bg_color'
			)
		)
	);


	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'humescores_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'humescores_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'humescores_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function humescores_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function humescores_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function humescores_customize_preview_js() {
	wp_enqueue_script( 'humescores-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'humescores_customize_preview_js' );


if ( ! function_exists( 'humescores_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see humescores_custom_header_setup().
	 */
	function humescores_header_style() {
		$header_text_color = get_header_textcolor();
		$header_bg_color = get_theme_mod( 'theme_bg_color' );

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( HEADER_TEXTCOLOR != $header_text_color ) {

			// If we get this far, we have custom styles. Let's do this.
			?>
			<style type="text/css">
			<?php
			// Has the text been hidden?
			if ( ! display_header_text() ) :
				?>
				.site-title,
				.site-description {
					position: absolute;
					clip: rect(1px, 1px, 1px, 1px);
					}
				<?php
				// If the user has set a custom color for the text use that.
			else :
				?>
				.site-title a,
				.site-description {
					color: #<?php echo esc_attr( $header_text_color ); ?>;
				}
			<?php endif; ?>
			</style>
			<?php
		}


		/*
		* Do we have a custom header background color?
		*/
		if ( '#002254' != $header_bg_color ) { ?>
			<style type="text/css">
				.site-header,
				.site-footer {
					background-color: <?php echo esc_attr( $header_bg_color ); ?>;
				}
			</style>
		<?php
		}


	}
endif;