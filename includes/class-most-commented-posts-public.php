<?php

if( ! class_exists('Most_Commented_Posts_Public') ):

class Most_Commented_Posts_Public {

	private $posts;

	/**
	 * Start actions of the class
	 */
	function __construct(){
		add_action( 'after_setup_theme', function(){
			add_theme_support( 'post-thumbnails' );
		} );
		add_action( 'plugin_loaded', array( $this, 'add_image_size' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_shortcode( 'most_comment_posts', array( $this, 'add_shortcode' ) );
	}

	/**
	 * Add new image size for posts of shortcode
	 */
	function add_image_size() {
		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'mostcp_thumb', 200, 100, true );
		}
	}

	/**
	 * Add style and font
	 */
	function wp_enqueue_scripts() {
		wp_enqueue_style( 'most_comment_posts', plugins_url( 'public/css/style.css' , MOSTCP_FILE ), array(), time() );
		wp_enqueue_style( 'font-family-roboto', 'https://fonts.googleapis.com/css2?family=Roboto&display=swap', array(), null );
	}

	/**
	 * Create a shortcode
	 */
	function add_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'          => '',
			'display'        => '',
			'post_type'      => '',
			'posts_per_page' => '',
		), $atts ) );

		$data = new Most_Commented_Posts_Data();
		$data->set_field( 'title',          $title );
		$data->set_field( 'display',        $display );
		$data->set_field( 'post_type',      $post_type );
		$data->set_field( 'posts_per_page', $posts_per_page );

		return $data->get_posts();
	}

}

endif;