<?php
/**
 * Plugin Name: Unclickable Featured Image
 * Plugin URI: https://github.com/zggz/unclickable-featured-image
 * Description: A wordpress plugin that forces all featured images to NOT be links
 * Version: 2.0
 * Author: Zach Gottesman
 * Author URI: http://zachgottesman.com
 */
 
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
 	if (is_singular() && (get_option('unclickable_options_hook'))) {
		$html = "<a href='javascript:void(0);' style='cursor:default'>" . $html . "</a>";
	}
	return $html;
}

// Wait until the theme tries to get the thumbnail
add_filter('post_thumbnail_html', 'unlink_thumbnail', 1, 5);


/**
 * Loads and sends data to a JavaScript file if needed based on plugin configuration
 */
function unlink_script() {
	// only on singular pages, and if the select option is enabled
	echo "<!-- Singular " . (is_singular() ? "Singular" : "not") . "-->";
	echo "<!-- Select   " . (get_option('unclickable_options_select') ? "select" : "not") . "-->";
	if (is_singular() && get_option('unclickable_options_select')) {
		echo "<!-- Checking thumbnail " . (has_post_thumbnail() ? "Thubmanil" : "not") . "-->";
		echo "<!-- thumb   " . (get_option('unclickable_options_thumbnail') ? "thumb" : "not") . "-->";

		// if 'Check if thumbnail exists' is unchecked or the post has a thumbnail
		if (!get_option('unclickable_options_thumbnail') || has_post_thumbnail()) {
		  echo "<!-- Loading script -->";

		  // load the script and pass it appropriate options
		  
		  wp_enqueue_script('unclickable-client', plugin_dir_url(__FILE__) . 'unclickable-client.js');
		  $jsOptions = array(
		  	// the querySelectorAll argument:
			'selector'  => get_option('unclickable_options_selector'),
			// true/nothing if we should check all or just the first
			'all'       => get_option('unclickable_options_all'),
			// the url that the thumbnail is loaded from
			'thumb'     => get_the_post_thumbnail_url(),
			// if we are checking links (based on config), give the client the link to check
			'link'      => (get_option('unclickable_options_link') ? get_permalink() : ''),
			// true/nothing of if the thumb should be checked against img src attribute
			'img'       => get_option('unclickable_options_img'),
			// true/nothing of if the background-images should be checked against background-image attribute
			'background'=> get_option('unclickable_options_background')
		  );
		  wp_localize_script('unclickable-client', 'unclickable_options', $jsOptions);
		}
	}
}
// whenever scripts are loaded, conditionally load the client side javascript
add_action('wp_enqueue_scripts', 'unlink_script');


// Everything below here is for the settings page

// registers the settings to be used by the plugin
function unclickable_register_settings() {
	add_option('unclickable_options_hook', 'true');
	register_setting(
		'unclickable_options',
		'unclickable_options_hook'
	);
	
	add_option('unclickable_options_select', 'true');
	register_setting(
		'unclickable_options',
		'unclickable_options_select'
	);
	add_option('unclickable_options_selector', 'img');
	register_setting(
		'unclickable_options',
		'unclickable_options_selector'
	);
	
	add_option('unclickable_options_all', 'true');
	register_setting(
		'unclickable_options',
		'unclickable_options_all'
	);
	
	add_option('unclickable_options_thumbnail', 'true');
	register_setting(
		'unclickable_options',
		'unclickable_options_thumbnail'
	);
	
	add_option('unclickable_options_link', 'true');
	register_setting(
		'unclickable_options',
		'unclickable_options_link'
	);
	
	add_option('unclickable_options_img', 'true');
	register_setting(
		'unclickable_options',
		'unclickable_options_img'
	);
	
	add_option('unclickable_options_background', 'true');
	register_setting(
		'unclickable_options',
		'unclickable_options_background'
	);
}
add_action('admin_init', 'unclickable_register_settings');

// setup the settings page
function unclickable_register_settings_page() {
	add_options_page(
		'Unclickable',
		'Unclickable Featured Image Settings',
		'manage_options',
		'unclickable',
		'unclickable_options_page'
	);
}
add_action('admin_menu', 'unclickable_register_settings_page');

// add a quick link to the settings page
function unclickable_add_settings_link($links) {
	$links[] = '<a href="' .
		admin_url('options-general.php?page=unclickable') .
		'">' . __('Settings') . '</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'unclickable_add_settings_link');

// the code that is rendered on the settings page
function unclickable_options_page()
{
  wp_enqueue_script('unclickable-admin', plugin_dir_url(__FILE__) . 'unclickable-admin.js');
  // this array describes the form that will be generated
  // I use this so that I can use a loop instead of writing completely
  // repetative HTML
  $options_array = array(
  	array(
  	  'label' => 'Use Default Thumbnail Hook (Preferred)',
  	  'option' => 'unclickable_options_hook',
  	  'type' => 'checkbox',
  	  'value' => 'true'
  	),
  	array(
  	  'label' => 'Give CSS selector string',
  	  'option' => 'unclickable_options_select',
  	  'type' => 'checkbox',
  	  'value' => 'true',
  	  'second' => array(
  	  	'label' => 'CSS Selector',
  	  	'option' => 'unclickable_options_selector'
  	  )
  	),
  	array(
  	  'label' => 'Check all matched',
  	  'option' => 'unclickable_options_all',
  	  'type' => 'checkbox',
  	  'value' => 'true'
  	),
  	array(
  	  'label' => 'Check if thumbnail exists',
  	  'option' => 'unclickable_options_thumbnail',
  	  'type' => 'checkbox',
  	  'value' => 'true'
  	),
  	array(
  	  'label' => 'Check link destination',
  	  'option' => 'unclickable_options_link',
  	  'type' => 'checkbox',
  	  'value' => 'true'
  	),
  	array(
  	  'label' => 'Check img tags',
  	  'option' => 'unclickable_options_img',
  	  'type' => 'checkbox',
  	  'value' => 'true'
  	),
  	array(
  	  'label' => 'Check for background-images',
  	  'option' => 'unclickable_options_background',
  	  'type' => 'checkbox',
  	  'value' => 'true'
  	)
  );
  
  ?>
  <div>
  <h2>Unclickable Featured Image Settings</h2>
  <form name="unclickable_options" method="post" action="options.php">
  <?php settings_fields( 'unclickable_options' ); ?>
  <h3>Help me find your featured images!</h3>
  <p>The mode you use depends on your theme and other active plugins</p>
  <table>
  
  <?php
  foreach($options_array as $option) {
  ?>
	<tr valign="top" style='text-align: right'>
		<th scope="row">
		  <label for="<?php echo $option[option]; ?>"><?php echo $option[label]; ?></label>
		</th>
		<td>
		  <input name="<?php echo $option[option]; ?>" type="<?php echo $option[type]; ?>" value="<?php echo $option[value]; ?>" <?php checked( $option[value], get_option( $option[option] ) ); ?> />
		</td>
	<?php
	if (isset($option[second])) {
	?>
		<td>
		  <label for="<?php echo $option[second][option]; ?>"><?php echo $option[second][label]; ?></label>
		</td>
		<td>
		  <input name="<?php echo $option[second][option]; ?>" value="<?php echo get_option($option[second][option]) ?>" />
		</td>
  <?php
  	}
  ?>
  	</tr>
  <?php
  }
?>
  </table>
  <?php  submit_button(); ?>
  </form>
  	<p><b>The hook option</b> (server side): uses the built in plugin hook for changing thumbnail HTML. This option is entirely server side, and if it works with your setup I would strongly recomend it.</p>
  	<p><b>The selector option</b>: uses javascript running on the user's browswer to find (or narrow down the thumbnail).
  	If your selector string creates false positives, you can use the below options to ensure the correct selection. Enter the most specific CSS selector you can. It must catch all thumbnails not caught by the default hook, and can be a comma separated list.</p>
  	<p><b>Check all matched</b>: checks all matching elements (unchecked means only the first matched element will be checked)</p>
	<p><u>The default is to have all below options checked, turning them off allows this plugin to make things that may not be the thumbnail unclickable</u></p>
  	<p><b>Check if thumbnail exists</b> (server side): uses the WordPress "has_post_thumbnail()" function, and only loads the scripts if the post has a thumbnail</p>
  	<p><b>Check link destination</b>: checks that the link being removed points to the "php_permalink()" (the current page)</p>
  	<p><b>Check img tags</b>: checks that img tags being unlinked are showing the thumbnail (checks that the WordPress "get_the_post_thumbnail_url()" matches the src attribute). If unchecked, any found img tags will be unlinked</p>
  	<p><b>Check for background-images</b>: checks that matching elements have a background image showing the thumbnail (checks that the WordPress "get_the_post_thumbnail_url()" matches the background-image url). If uncheckecd, all selector matches will be unlinked</p>
  	<p>If both "check img tags" and "check for background-images" are checked, either one will cause the element to be unlinked</p>
  </div>
<?php
} ?>