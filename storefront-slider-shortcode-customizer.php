<?php
/**
 * Plugin Name: Storefront Slider Customizer
 * Inspired by and based on the "Storefront Add Slider" Plugin with a few more added Customizer options
 * Plugin URI: https://lemsmyth.com/plugins/storefront-slider-customizer
 * Description: Lets you add a slider shortcode to your Storefront theme Frontpage.
 * Author: lemsmyth
 * Author URI: http://lemsmyth.com
 * Version: 0.1
 * Text Domain: storefront-slider-customizer
 *
 *
 * Storefront Slider Customizer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Storefront Slider Customizer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'customize_register', 'lems_storefront_customize_register' );

function lems_storefront_customize_register( $wp_customize ) {

/**
 * Customizer Control For Pro Conversion
 */
class Custom_Subtitle extends WP_Customize_Control {

	public function render_content() { ?>

		<label>
		<?php if ( !empty( $this->label ) ) : ?>
			<span class="customize-control-title bellini-pro__title">
				<?php echo esc_html( $this->label ); ?>
			</span>
		<?php endif; ?>
		</label>

		<?php if ( !empty( $this->description ) ) : ?>
			<span class="description bellini-pro__description">
				<?php echo $this->description; ?>
			</span>
		<?php endif;
	}
}

	$wp_customize->add_setting('storefront_slider_shortcode_field', array(
			'type' 				=> 'theme_mod',
			'default'     => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport' 	=> 'refresh',
	) );

			$wp_customize->add_control('storefront_slider_shortcode_field',array(
				'type' 			=>'text',
        'label'     => esc_html__( 'Slider Shortcode', 'storefront' ),
        'description'	=> esc_html__( 'You can insert your Meta Slider, Smart Slider 3, Soliloquy, Revolution Slider, LayerSlider shortcode here.', 'storefront' ),
        'section'   => 'static_front_page',
        'settings'  => 'storefront_slider_shortcode_field',
			  'priority'  => 20,
		) );

	// Checkbox to remove the Home page title:

	$wp_customize->add_setting( 'storefront_hide_homepage_title', array(
			'default'			=> false,
			'type'				=> 'theme_mod',
			'sanitize_callback'	=> 'sanitize_key',
			'transport'		=> 'refresh',
		)
	);

	$wp_customize->add_control( 'storefront_hide_homepage_title', array(
			'label'				=> esc_html__( 'Hide the Home title heading', 'storefront' ),
			'section'			=> 'static_front_page',
			'settings'		=> 'storefront_hide_homepage_title',
			'priority'		=> 30,
			'type'				=> 'checkbox',

		)
	);

	// Checkbox to make the Slider full window width (@hooked storefront_before_content) rather than the container width (@hooked storefront_content_top):

	$wp_customize->add_setting( 'storefront_slider_full_width', array(
		'default'			=> false,
		'type'				=> 'theme_mod',
		'sanitize_callback'	=> 'sanitize_key',
		'transport'		=> 'refresh',
		)
	);

	$wp_customize->add_control( 'storefront_slider_full_width', array(
			'label'			=> esc_html__( 'Make Slider full window width', 'storefront' ),
			'section'		=> 'static_front_page',
			'settings'	=> 'storefront_slider_full_width',
			'priority'	=> 40,
			'type'			=> 'checkbox',
		)
	);

	// Checkbox to Show Frontpage Slider on All Pages:

	$wp_customize->add_setting( 'storefront_slider_all_pages' ,
		array(
			'default' 		=> false,
			'type' 				=> 'theme_mod',
			'sanitize_callback' => 'sanitize_key',
		  'transport' 	=> 'refresh'
		)
	);

		$wp_customize->add_control( 'storefront_slider_all_pages', array(
				'label'      => esc_html__( 'Show Frontpage Slider on All Pages', 'storefront' ),
				'section'    => 'static_front_page',
				'settings'   => 'storefront_slider_all_pages',
			  'priority'   => 50,
			  'type'       => 'checkbox',
			)
		);
 }

if ( ( ! function_exists('lems_add_slider_storefront') ) && ( ! function_exists('lems_homepage_slider_storefront') )  ) {
  if (  ( get_theme_mod('storefront_slider_all_pages')== true ) ) {
    function lems_add_slider_storefront() {
      if ( get_theme_mod('storefront_slider_shortcode_field' ) ) {
      ?>
    	<section class="hero__slider">
    	<?php
    		echo do_shortcode( html_entity_decode(get_theme_mod( 'storefront_slider_shortcode_field')) );
    	?>
    	</section><?php
      } else {
      echo esc_html_e( 'No Slider Shortcode Found!', 'storefront' );
  }
    }
		if ( get_theme_mod('storefront_slider_full_width') == true ) {
			add_action( 'storefront_before_content', 'lems_add_slider_storefront', 5);
		} else {
  	  add_action( 'storefront_content_top', 'lems_add_slider_storefront', 5);
		}
  } else {
    function lems_homepage_slider_storefront() {
        if  (is_page_template( 'template-homepage.php' ) ) {
          if ( get_theme_mod('storefront_slider_shortcode_field')) { ?>
          <section class="hero__slider"><?php
            echo do_shortcode( html_entity_decode(get_theme_mod( 'storefront_slider_shortcode_field' ) ) );
          ?>
          </section><?php
          } else {
        echo esc_html_e( 'No Slider Shortcode Found!', 'storefront' );
          }
        }
    }
			if ( get_theme_mod('storefront_slider_full_width') == true ) {
				add_action( 'storefront_before_content', 'lems_homepage_slider_storefront', 5 );
			} else {
      add_action( 'storefront_content_top', 'lems_homepage_slider_storefront', 5 );
		}
  }
}
if (! function_exists('storefront_hide_page_title')) :
	if ( (get_theme_mod('storefront_hide_homepage_title') ) == true ) {
		function storefront_hide_page_title() {
			if ( is_front_page() ) {
				remove_action( 'storefront_homepage', 'storefront_homepage_header', 10 );
			}
		}
	}
	add_action( 'wp', 'storefront_hide_page_title' );
endif;
