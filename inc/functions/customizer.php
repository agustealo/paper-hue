<?php
/**
 * Paper Hue Theme Customizer
 *
 * @package Paper_Hue
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function paper_hue_customize_register( $wp_customize ) {


	/**
	* Theme setting
	*/
	$wp_customize->add_panel( 'paper_hue_theme_settings',
		array(
			 'title'	  => __( 'Theme Settings' ),
			 'priority' => 20,
		)
	);
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'paper_hue_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'paper_hue_customize_partial_blogdescription',
		) );
	}

	/**
	* Slider options
	*/
	$wp_customize->add_section( 'slider_options' , array(
		'panel' 	 => 'paper_hue_theme_settings',
    'title'    => __('Slider Options'),
    'priority' => 20,
  ) );

	// Show/hide slider
  $wp_customize->add_setting( 'paper_hue_slider' , array(
	  'default'    => true,
	  'transport'  => 'refresh',
  ) );
  $wp_customize->add_control( 'paper_hue_slider', array(
  'label' 		=> __('Show Slider'),
  'section' 	=> 'slider_options',
  'settings' 	=> 'paper_hue_slider',
  'type' 			=> 'checkbox',
) );

	// Select slider category
	$cats = array();
	foreach ( get_categories() as $categories => $category ){
	  $cats[$category->term_id] = $category->name;
	}

		$wp_customize->add_setting( 'slider_category' , array(
		    'default' 					=> 1,
			  'transport'  => 'refresh',
		)
	);

	$wp_customize->add_control( 'slider_category', array(
		'label' 		=> __('Select Category'),
		'section' 	=> 'slider_options',
		'settings'  => 'slider_category',
		'type' 			=> 'select',
		'choices'   => $cats,
		) );

	// Slide total
	$wp_customize->add_setting( 's_total' , array(
		    'default'		 => 3,
			  'transport'  => 'refresh',
		)
	);

	$wp_customize->add_control( 's_total', array(
		'label' 		=> __('Total Slides'),
		'section' 	=> 'slider_options',
		'settings'  => 's_total',
		'type' 			=> 'select',
		'choices'   => array (
									2 => 'Two',
									3 => 'Three',
									4 => 'Four',
									5 => 'Five'

		)
	) );

	// Slider display order

	$wp_customize->add_setting( 's_order' , array(
	'default'    => 'ASC',
	'transport'  => 'refresh',
	) );

	$wp_customize->add_control( 's_order', array(
	'label' 		=> __('Display Order'),
	'section' 	=> 'slider_options',
	'settings' 	=> 's_order',
	'type' 			=> 'select',
	'choices'   => array (
								'ASC' => 'First Counts',
								'DESC' => 'Last Counts'
		)
	) );

	// Order slides by

	$wp_customize->add_setting( 's_order_by' , array(
	'default'    => 'date',
	'transport'  => 'refresh',
	) );

	$wp_customize->add_control( 's_order_by', array(
	'label' 		=> __('Order By'),
	'section' 	=> 'slider_options',
	'settings' 	=> 's_order_by',
	'type' 			=> 'select',
	'choices'   => array (
												'title' 				=> 'Title',
												'ID'					  => 'ID',
												'date'				  => 'Date',
												'modified' 			=> 'Modified',
												'author' 				=> 'Author',
												'rand' 					=> 'Random Order',
												'comment_count' => 'Comment Count',

	)
	) );

	// Add Title To Banner

	$wp_customize->add_section( 'hue_banner' , array(
		'panel' 	 => 'paper_hue_theme_settings',
		'title'    => __('Banner Settings'),
		'priority' => 20,
	) );
	$wp_customize->add_setting( 'hue_header_title' , array(
		'default'    => false,
		'transport'  => 'refresh',
	) );
	$wp_customize->add_control( 'hue_header_title', array(
	'label' 		=> __('Show Title In Header'),
	'section' 	=> 'hue_banner',
	'settings' 	=> 'hue_header_title',
	'type' 			=> 'checkbox',
	) );
/////////

	/**
	* Show featured post
	*/
	$wp_customize->add_section( 'feat_post' , array(
		'panel' 	 => 'paper_hue_theme_settings',
    'title'    => __('Featured Post'),
    'priority' => 20,
  ) );
  $wp_customize->add_setting( 'show_feat_sticky' , array(
	  'default'    => true,
	  'transport'  => 'refresh',
  ) );
  $wp_customize->add_control( 'show_feat_sticky', array(
  'label' 		=> __('Feature Sticky Post'),
  'section' 	=> 'feat_post',
  'settings' 	=> 'show_feat_sticky',
  'type' 			=> 'checkbox',
	) );

	/**
	* Create Logo Setting and Upload Control
	*/
	// add a setting for the site logo
	$wp_customize->add_setting('hue_them_logo');
	// Add a control to upload the logo
	$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'hue_them_logo',
   array(
      'label' 			  => __( 'Theme Logo' ),
      'description'   => esc_html__( 'Upload your theme\'s logo ( max size 350x80 )' ),
      'section'			  => 'title_tagline',
			'settings' 			=> 'hue_them_logo',
      'flex_width' 		=> true, // Optional. Default: true
      'flex_height' 	=> true, // Optional. Default: true
      'width'			  	=> 350,  // Default: 350
      'height'			  => 80,   // Default: 80
      'button_labels' => array(
		         'select'		    => __( 'Select Image' ),
		         'change' 		  => __( 'Change Image' ),
		         'remove' 		  => __( 'Remove' ),
		         'default' 		  => __( 'Default' ),
		         'placeholder'  => __( 'No image selected' ),
		         'frame_title'  => __( 'Select Image' ),
		         'frame_button' => __( 'Choose Image' ),
		 					)
   							)
		) );

/**
* Theme default featured image
*/
$wp_customize->add_section( 'default_feat_image' , array(
	'panel' 	 => 'paper_hue_theme_settings',
  'title'    => __('Default Featured Image'),
  'priority' => 20,
) );
// add a setting for the site logo
$wp_customize->add_setting('theme_feat_image');
// Add a control to upload the logo
$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'theme_feat_image',
 array(
    'label' 			  => __( 'Default Featured Image' ),
    'description'   => esc_html__( 'Select a default thumbnail for your theme\'s featured image, this is use as a fallback for posts without a featured image ( recommended dimension: 640x440 px )' ),
    'section'			  => 'default_feat_image',
		'settings' 			=> 'theme_feat_image',
    'flex_width' 		=> true, // Optional. Default: true
    'flex_height' 	=> true, // Optional. Default: true
    'width'			  	=> 640,  // Default: 640
    'height'			  => 440,  // Default: 440
    'button_labels' => array(
	         'select'		    => __( 'Select Image' ),
	         'change' 		  => __( 'Change Image' ),
	         'remove' 		  => __( 'Remove' ),
	         'default' 		  => __( 'Default' ),
	         'placeholder'  => __( 'No image selected' ),
	         'frame_title'  => __( 'Select Image' ),
	         'frame_button' => __( 'Choose Image' ),
	 					)
 							)
	) );


	/**
	* Add pagination to front-page
	*/
	$wp_customize->add_section( 'hue_front_page' , array(
		'panel' 	 => 'paper_hue_theme_settings',
		'title'    => __('Front Page Settings'),
		'priority' => 20,
	) );
	$wp_customize->add_setting( 'hue_post_nav' , array(
		'default'    => false,
		'transport'  => 'refresh',
	) );
	$wp_customize->add_control( 'hue_post_nav', array(
	'label' 		=> __('Show Page Navigation'),
	'section' 	=> 'hue_front_page',
	'settings' 	=> 'hue_post_nav',
	'type' 			=> 'checkbox',
	) );

	/**
	* Footer Settings
	*/
	$wp_customize->add_section( 'footer_settings' , array(
		'panel' 	 => 'paper_hue_theme_settings',
    'title'    => __('Footer Settings'),
  ) );

	// Show/hide powered by WordPress
  $wp_customize->add_setting( 'hue_powered_by' , array(
	  'default'    => true,
	  'transport'  => 'refresh',
  ) );
  $wp_customize->add_control( 'hue_powered_by', array(
	  'label' 		=> __('Show "Powered by WordPress"'),
	  'section' 	=> 'footer_settings',
	  'settings' 	=> 'hue_powered_by',
	  'type' 			=> 'checkbox',
	) );

	// Show creator's link
	$wp_customize->add_setting( 'hue_credit' , array(
		'default'    => true,
		'transport'  => 'refresh',
	) );
	$wp_customize->add_control( 'hue_credit', array(
	'label' 			=> __('Credit Theme Creator'),
	'description' => esc_html__( 'Whether or not to show the creator some love for this beautiful theme.' ),
	'section' 		=> 'footer_settings',
	'settings' 		=> 'hue_credit',
	'type' 				=> 'checkbox',
	) );

	// Show text area
	$wp_customize->add_setting( 'show_copyright' , array(
		'default'    => true,
		'transport'  => 'refresh',
	) );
	$wp_customize->add_control( 'show_copyright', array(
	'label' 			=> __('Show Footer Information'),
	'description' => esc_html__( 'Display your customize information' ),
	'section' 		=> 'footer_settings',
	'settings' 		=> 'show_copyright',
	'type' 				=> 'checkbox',
	) );


	// Copyright text area
	$wp_customize->add_setting( 'hue_copyright',
	   array(
	      'default' 	=> '',
	      'transport' => 'refresh',
	   )
	);

	$wp_customize->add_control( 'hue_copyright', array(
	      'label' 			=> __( 'Footer Info' ),
	      'description' => esc_html__( 'Add things like copyright information' ),
	      'section' 		=> 'footer_settings',
	      'priority' 		=> 10, // Optional. Order priority to load the control. Default: 10
	      'type' 				=> 'textarea',
	      'capability' 	=> 'edit_theme_options', // Optional. Default: 'edit_theme_options'
	      'input_attrs' => array( // Optional.
			         'class' 		   => 'footer-info',
			         'placeholder' => __( 'Add footer information here' ),
	      ),
	   )
	);
	///
	}

add_action( 'customize_register', 'paper_hue_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function paper_hue_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function paper_hue_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function paper_hue_customize_preview_js() {
	wp_enqueue_script( 'paper-hue-customizer', get_template_directory_uri() . '/client-side/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'paper_hue_customize_preview_js' );
