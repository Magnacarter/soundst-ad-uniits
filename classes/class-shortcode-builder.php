<?php
/**
 * Class build ad shortcode.
 */
namespace Soundst\ad_shortcode;

new Ad_Shortcode();

class Ad_Shortcode {
	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', [$this, 'add_meta_box'] );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box() {
		add_meta_box(
			'ad-shortcode',
			__( 'Ad Unit Shortcode', 'textarea' ),
			[$this, 'render_ad_shortcode_metabox'],
			'ad_unit',
			'normal',
			'high'
		);
	}
	
	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_ad_shortcode_metabox( $post ) {
		global $post;
		$id = $post->ID;
		$position = get_field( 'position', $id );
		$content = sprintf('<p>[%s-adunit id=%s]</p>', $position, $id );
		echo $content;
	}
}
