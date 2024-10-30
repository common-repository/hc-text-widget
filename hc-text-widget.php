<?php
/*
Plugin Name: HC Text Widget
Version: 1.2.2
Plugin URI: http://wordpress.org/plugins/hc-text-widget/
Description: WYSIWYG editor inside a widget
Author: Some Web Media
Author URI: http://someweblog.com/
*/

if (!class_exists('HC_Text_Widget')) {

	// register widget
	add_action('widgets_init', '_hc_text_widget');
	function _hc_text_widget(){
		register_widget('HC_Text_Widget');
	}

	// include dinamyc tinymce
	include(dirname(__FILE__) . '/hc-tinymce.php');

	// extend wp widget class
	class HC_Text_Widget extends WP_Widget {

		public function __construct() {
			// instantiate the parent object
			parent::__construct(
				'hc_text', // Base ID
				'Visual Editor', // Name
				array('description' => 'Arbitrary text or HTML with visual editor'), // Args
				array('width' => 600, 'height' => 550)
			);
		}

		public function widget($args, $instance) {
			extract($args);

			if (!$instance['title'] && !$instance['text']) return;

			$title = apply_filters('widget_title', $instance['title']);
			$text = apply_filters('widget_text', $instance['text'], $instance);

			// our filter
			$text = apply_filters('hc_widget_text', $text, $instance);

			echo $before_widget; ?>

				<?php if ($title) echo $before_title . $title . $after_title; ?>
				<?php if ($text) : ?><div class="textwidget"><?php echo $text; ?></div><?php endif; ?>

			<?php echo $after_widget;
		}

		public function form($instance) {
			$instance = wp_parse_args((array)$instance, array('title' => '', 'text' => ''));
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'hcwp'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>">
			</p>

			<?php hc_tinymce(array(
				'id' 	=> $this->get_field_id('text'),
				'name' 	=> $this->get_field_name('text'),
				'value' => $instance['text'],
				'rows' 	=> 15
			)); ?>

		<?php
		}

		public function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['text'] = $new_instance['text'];
			return $instance;
		}
	}


	// save widget value as JSON instead of serialized array
	add_action('pre_update_option_widget_hc_text', '_hc_text_widget_filter_update');
	function _hc_text_widget_filter_update($value){
		return json_encode($value);
	}
	// decode JSON when getting widget option
	add_action('option_widget_hc_text', '_hc_text_widget_filter_option');
	function _hc_text_widget_filter_option($value){
		return is_array($value) ? $value : json_decode($value, true);
	}


	// include our javascript
	add_action('admin_print_footer_scripts', '_hc_text_widget_footer_scripts');
	function _hc_text_widget_footer_scripts() {
		?>
		<style>
			.wp-media-buttons {float: left;}
			.mceIframeContainer {background: #fff;}
			.widget-content .wp-editor-wrap {margin-bottom: 15px;}
		</style>
		<script>
			(function($){

				// parse $_GET params from url
				function getQueryParams(qs) {
					if (typeof qs == 'object') return qs;

					qs = qs.split("+").join(" ");

					var params = {},
						tokens,
						re = /[?&]?([^=]+)=([^&]*)/g;

					while (tokens = re.exec(qs)) {
						params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
					}

					return params;
				}

				// on every ajax call reinit tinyMCE
				$(document).ajaxSuccess(function(evt, request, settings) {
					if (!settings.data) return;

					var $_GET = getQueryParams(settings.data);

					// new widget added
					if ($_GET['widget-id'] && !$_GET['delete_widget']) {
						var widget_id = 'widget-' + $_GET['widget-id'] + '-text';
						hc_tinymce_init(widget_id,['blabla','lll']);
					}

					// reordering widgets
					if ($_GET.action == 'widgets-order') {
						for (var prop in $_GET) {
							if (prop.indexOf('sidebars') === 0) {
								var widgets = $_GET[prop].split(',');
								if (widgets.length > 0) {
									for (var i in widgets) {
										var widget = widgets[i].replace(/widget-\d+_/, '');
										if (widget.indexOf('hc_text-') === 0) {
											hc_tinymce_init('widget-' + widget + '-text');
										}
									}
								}
							}
						}
					}
				});

			})(jQuery);
		</script>
		<?php
	}

}

?>