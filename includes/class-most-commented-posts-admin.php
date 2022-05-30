<?php

if( ! class_exists('Most_Commented_Posts_Admin') ):

class Most_Commented_Posts_Admin {

	/**
	 * Start ailter and actions of the class
	 */
	function __construct(){
		add_filter( 'plugin_action_links_'.MOSTCP_NAME.'/'.MOSTCP_NAME.'.php', array( $this, 'settings_link' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
	}

	/**
	 * Set a link of settings on the plugin page
	 */
	function settings_link( $links ) {
		$url = esc_url( add_query_arg(
			'page',
			MOSTCP_NAME,
			get_admin_url() . 'admin.php'
		) );
		$settings_link = '<a href="' . $url . '">' . __( 'Settings' ) . '</a>';
		array_push(
			$links,
			$settings_link
		);
		return $links;
	}

	/**
	 * Add a link in the left sidebar of the admin panel
	 */
	function add_menu() {
		add_options_page(
			__( 'Most Commented Posts', 'mostcp' ),
			__( 'Most Commented Posts', 'mostcp' ),
			'manage_options',
			MOSTCP_NAME,
			array( $this, 'options_page' )
		);
	}

	/**
	 * Show an options page in the admin panel
	 */
	function options_page() {
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error( 'mostcp_messages', 'mostcp_message', __( 'Settings Saved', 'mostcp' ), 'updated' );
		}
		settings_errors( 'wporg_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'mostcp_group' );
				do_settings_sections( 'most-commented-posts' );
				submit_button( __( 'Save Settings', 'mostcp' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Add the settings sections and fields to the options page of the admin panel
	 */
	function settings_init() {
		register_setting( 'mostcp_group', 'mostcp', array( $this, 'sanitize_callback' ) );

		add_settings_section( 'mostcp_section_main', __( 'Default options', 'mostcp'), '', 'most-commented-posts' );

		add_settings_field( 'title', __( 'Title', 'mostcp' ), array( $this, 'settings_input_title' ), 'most-commented-posts', 'mostcp_section_main' );
		add_settings_field( 'display', __( 'Display as', 'mostcp' ), array( $this, 'settings_input_display' ), 'most-commented-posts', 'mostcp_section_main' );
		add_settings_field( 'post_type', __( 'Post Type', 'mostcp' ), array( $this, 'settings_input_post_type' ), 'most-commented-posts', 'mostcp_section_main' );
		add_settings_field( 'posts_per_page', __( 'Count of posts', 'mostcp' ), array( $this, 'settings_input_posts_per_page' ), 'most-commented-posts', 'mostcp_section_main' );

	}

	/**
	 * Sanitise the option fields
	 */
	function sanitize_callback( $options ){
		foreach( $options as $name => & $val ){
			$val = sanitize_text_field( $val );
		}
		return $options;
	}

	/**
	 * Show input field for title
	 */
	function settings_input_title() {
		$val = get_option('mostcp');
		$val = ! empty( $val['title'] ) ? $val['title'] : '';
		?>
		<input type="text" name="mostcp[title]" value="<?php echo esc_attr( $val ) ?>" />
		<?php
	}

	/**
	 * Show input field for display style
	 */
	function settings_input_display() {
		$val = get_option('mostcp');
		$val = ! empty( $val['display'] ) ? $val['display'] : 'grid';
		?>
		<label><input type="radio" name="mostcp[display]" value="grid" <?php if( empty( $val ) OR $val == 'grid' ){ echo 'checked'; } ?> /> <?php _e( 'Grid', 'mostcp' ); ?></label>
		<label><input type="radio" name="mostcp[display]" value="list" <?php if( $val == 'list' ){ echo 'checked'; } ?> /> <?php _e( 'List', 'mostcp' ); ?></label>
		<?php
	}

	/**
	 * Show selectbox field for post type
	 */
	function settings_input_post_type() {
		$val = get_option('mostcp');
		$val = ! empty( $val['post_type'] ) ? $val['post_type'] : 'post';
		$post_types = get_post_types( ['public'   => true ], 'objects' );
		$exclude = array( 'attachment' , );
		?>
		<select name="mostcp[post_type]">
			<?php
			foreach( $post_types as $post_type ) {
				if( !in_array( $post_type->name, $exclude )) {
					if ( isset( $post_type->name) AND isset( $post_type->labels->name) ) {
						$selected = !empty( $val ) && $post_type->name == $val ? ' selected': '';
						echo '<option value="' . $post_type->name . '"' . $selected . '>' . $post_type->labels->name . '</option>';
					}
				}
			}
			?>
		</select>
		<?php
	}


	/**
	 * Show input field for posts per page
	 */
	function settings_input_posts_per_page() {
		$val = get_option('mostcp');
		$val = ! empty( $val['posts_per_page'] ) ? $val['posts_per_page'] : '';
		?>
		<input type="number" name="mostcp[posts_per_page]" value="<?php echo esc_attr( $val ) ?>" />
		<?php
	}


}

endif;