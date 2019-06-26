<?php
/**
 * Plugin Name: Unclickable Featured Image
 * Plugin URI: https://github.com/zggz/unclickable-featured-image
 * Description: A wordpress plugin that forces all featured images to NOT be links
 * Version: 1.1
 * Auther: Zach Gottesman
 * Author URI: http://zachgottesman.com
 */
 
 // Wait until the theme tries to get the thumbnail
 add_filter('post_thumbnail_html', 'unlink_thumbnail', 1, 5);
 
 /**
  * Disables the link from a featured image (if it exists)
  * This is accomplished by wrapping the image in an <a> tag with no action
  * Typically $html is given as just a <img> tag pointing to the thumbnail
  * @param string $html
  * @param int $post_id
  * @param int $post_thumbnail_id
  * @param string $size
  * @param array $attr
  * @return string
  */
 function unlink_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
 	if (is_singular()) {
		$html = "<a href='javascript:void(0);' style='cursor:default'>$html</a>";
	}
 	return $html;
 }
