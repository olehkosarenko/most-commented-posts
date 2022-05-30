<?php

if( ! class_exists('Most_Commented_Posts_Data') ):

class Most_Commented_Posts_Data {
	private $title;
	private $display = 'grid';
	private $post_type = 'post';
	private $posts_per_page = 10;

	/**
	 * Start logic of the class
	 */
	function __construct(){
		$this->load_default_options();
	}

	/**
	 * Load default options from the options page of the admin panel
	 */
	public function load_default_options() {
		$options = get_option( 'mostcp' );

		$title          = ! empty( $options['title'] ) ?          $options['title']          : '';
		$display        = ! empty( $options['display'] ) ?        $options['display']        : '';
		$post_type      = ! empty( $options['post_type'] ) ?      $options['post_type']      : '';
		$posts_per_page = ! empty( $options['posts_per_page'] ) ? $options['posts_per_page'] : '';

		$this->set_field( 'title',          $title );
		$this->set_field( 'display',        $display );
		$this->set_field( 'post_type',      $post_type );
		$this->set_field( 'posts_per_page', $posts_per_page );
	}

	/**
	 * Validation data of the field
	 */
	public function is_valid( $field = '', $value = '' ) {
		if( ! empty( $field ) AND !empty( $value ) ) {
			if( $field == 'display' AND ! in_array( $value, array( 'grid', 'list' ) ) ){
				return false;
			}
			if( $field == 'posts_per_page' AND intval( $value ) === 0 ) {
				return false;
			}
			return true;
		}
	}

	/**
	 * Sanitise the option fields
	 */
	public function def( $field, $value ) {
		if( $field == 'display' OR $field == 'post_type' ) {
			return sanitize_key( $value );
		} elseif( $field == 'posts_per_page' ){
			return absint( $value );
		} else {
			return sanitize_text_field( $value );
		}
	}

	/**
	 * Set a variable to the field of class
	 */
	public function set_field( $field = '', $value = '', $callback = '' ) {
		if( true === $this->is_valid( $field, $value ) ){
			if( !empty( $callback ) ) {
				$value = $callback( $value );
				if( !empty( $value ) ) {
					$this->$field = $value;
				}
			} else {
				$this->$field = $this->def( $field, $value );
			}
		}
	}


	/**
	 * Get posts for the shortcode
	 */
	public function get_posts() {
		$html = '';
		$args = array(
			'post_type'      => $this->post_type,
			'post_status'    => 'publish',
			'posts_per_page' => $this->posts_per_page,
			'comment_count'  => array(
				'value'      => 0,
				'compare'    => '>',
			),
			'orderby'        => 'comment_count',
			'order'          => 'DESC',
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			if( ! empty( $this->title ) ){
				$html .= '<div class="mostcptitle">' . $this->title . '</div>';
			}
			$html .= '<ul class="mostcp mostcp_p mostcp_frame mostcp_frame_' . $this->display . '">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				global $post;
				$image_url = MOSTCP_URL.'images/no-image.png';
				if ( has_post_thumbnail() ) {
					$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'mostcp_thumb');
					if( ! empty( $image[0] ) ) {
						$image_url = $image[0];
					}
				}
				$html .= '<li class="mostcp__item">';
					$html .= '<a href="'.get_permalink().'" class="mostcp__link">';
						$html .= '<span class="mostcp__image mostcp__image_mod">';
							$html .= '<img src="'.$image_url.'" alt="" />';
						$html .= '</span>';
						$html .= '<span class="mostcp__title">'.get_the_title().'</span>';
					$html .= '</a>';
					$html .= '<div class="mostcp__desc mostcp__desc_mod">';
						$html .= get_the_excerpt();
						$html .= '<a href="'.get_permalink().'" class="mostcp__more">'.__( 'Read more' ).'</a>';
					$html .= '</div>';
					$html .= '<div class="mostcp__meta">';
						$html .= '<div class="mostcp__data">';
							$html .= '<span class="icon icon_calendar"></span> '.get_the_date('d.m.Y').'</div>';
							$html .= '<div class="mostcp__count"><span class="icon icon_comments"></span> '.$post->comment_count.' </div>';
					$html .= '</div>';
				$html .= '</li>';
			}
			$html .= '</ul>';
		}
		wp_reset_postdata();
		return $html;
	}
}

endif;