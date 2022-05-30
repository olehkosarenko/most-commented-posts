<?php

/**
 * Plugin Name: Most Commented Posts
 * Description: Adds a shortcode [most_comment_posts] with a list of the most commented posts. he shortcode supports the following attributes: title, display (grid or list), post_type, posts_per_page. For example: [most_comment_posts type="page" title="Most Commented Posts" display="list" post_type="post" posts_per_page="5"]
 * Author:      Oleh Kosarenko
 * Author URI:  https://www.linkedin.com/in/kosarenko/
 * Plugin URI:  https://github.com/olehkosarenko/most-commented-posts
 * Version:     1.0.0
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: mostcp
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

/** Currently plugin version. */
define( 'MOSTCP_VERSION', '1.0.0' );

/** Currently plugin name. */
define( 'MOSTCP_NAME', 'most-commented-posts' );

/** Path to a main plugin files directory */
define( 'MOSTCP_FILE', __FILE__ );

/** Path to a main plugin files directory */
define( 'MOSTCP_PATH', plugin_dir_path( __FILE__ ) );

/** Path to the class files directory of plugin. */
define( 'MOSTCP_INC_PATH', plugin_dir_path( __FILE__ ) . '/includes/' );

/** URL to the public files directory. */
define( 'MOSTCP_URL', plugins_url( 'public/', __FILE__ ) );


/**
 * The core plugin class
 */
require_once MOSTCP_INC_PATH . 'class-most-commented-posts.php';
/*
 * Begins execution of the plugin.
 */
if( ! function_exists( 'most_comented_posts_instantiate' ) ):

function most_comented_posts_instantiate() {
	global $mostmc;
	if( !isset($mostmc) ) {
		$mostmc = new Most_Commented_Posts();
		$mostmc->run();
	}
	return $mostmc;
}
most_comented_posts_instantiate();

endif;
