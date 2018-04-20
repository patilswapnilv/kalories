<?php

/**
 * Provides the markup for any textarea field
 *
 * @link       http://slushman.com
 * @since      1.0.0
 *
 * @package    Kalories
 * @subpackage Kalories/admin/partials
 */

if (!empty($atts['label'])) {

	?><label for="<?php echo esc_attr($atts['id']); ?>"><?php esc_html_e($atts['label'], 'kalories'); ?>: </label><?php

}

?><textarea
	class="<?php echo esc_attr($atts['class']); ?>"
	cols="<?php echo esc_attr($atts['cols']); ?>"
	id="<?php echo esc_attr($atts['id']); ?>"
	name="<?php echo esc_attr($atts['name']); ?>"
	rows="<?php echo esc_attr($atts['rows']); ?>"><?php

	echo esc_textarea($atts['value']);

?></textarea>
<span class="description"><?php esc_html_e($atts['description'], 'kalories'); ?></span>
