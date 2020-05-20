<?php
/*
	Plugin Name: Message Bar
	Plugin URI: https://github.com/VitalDevTeam/vital-message-bar/
	Description: Adds a customizable message bar to the site's header.
	Version: 1.0.1
	Requires at least: 5.2
	Requires PHP: 7.0
	Author: Vital
	Author URI: https://vtldesign.com
	Text Domain: vital
*/

if (! defined('ABSPATH')) {
	exit;
}

require 'plugin-update-checker/plugin-update-checker.php';

$vtlmb_update_checker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/VitalDevTeam/vital-message-bar/',
	__FILE__,
	'vital-message-bar'
);

class Vital_Message_Bar {

	private $plugin_path;
	private $plugin_url;
	private $plugin_version;
	private $suffix;
	private $cookie_dismissed;
	private $dismissed;

	public function __construct() {

		$this->plugin_path = plugin_dir_path(__FILE__);
		$this->plugin_url = plugin_dir_url(__FILE__);
		$this->version = '1.0.0';
		$this->suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
		$this->cookie_dismissed = 'vtlmb_dismissed';
		$this->dismissed = false;

		if (isset($_COOKIE['vtlmb_dismissed']) && $_COOKIE['vtlmb_dismissed'] === '1') {
			$this->dismissed = true;
		}

		if (function_exists('acf')) {

			require $this->plugin_path . 'admin.php';

			add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
			add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_action_link']);
			add_action('wp_head', [$this, 'inline_styles'], 100);

			if ($this->dismissed === false) {
				add_filter('body_class', [$this, 'body_class'], 10, 2);
				add_action('wp_body_open', [$this, 'add_bar']);
				add_action('vtlmb_bar', [$this, 'add_bar']);
			}
		}
	}

	/**
	 * Add link to settings on Plugins page
	 *
	 * @since 1.0.0
	 * @param array $actions An array of plugin action links.
	 * @return array Filtered array of plugin action links.
	 */
	public function add_action_link($actions) {
		$custom_link = [
			'<a href="' . admin_url('admin.php?page=vtl-message-bar') . '">Settings</a>',
		];
		return array_merge($custom_link, $actions);
	}

	/**
	 * Enqueues plugin scripts.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_scripts() {

		if (get_field('vtlmb_active', 'option')) {

			wp_enqueue_script(
				'vtlmb_js',
				$this->plugin_url . 'assets/js/vital-message-bar' . $this->suffix . '.js',
				false,
				$this->version,
				true
			);

			wp_localize_script('vtlmb_js', 'VTLMB', [
				'homeUrl'          => get_home_url(),
				'cookie_dismissed' => $this->cookie_dismissed,
				'cookie_expires'   => get_field('vtlmb_cookie_expires', 'option'),
			]);
		}
	}

	/**
	 * Enqueues plugin stylesheets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		$enqueue_css = true;
		$do_enqueue = apply_filters('vtlmb_enqueue_css', $enqueue_css);

		if (get_field('vtlmb_active', 'option')) {

			if ($do_enqueue) {

				wp_enqueue_style(
					'vtlmb_css',
					$this->plugin_url . 'assets/css/vital-message-bar' . $this->suffix . '.css',
					false,
					$this->version
				);
			}
		}
	}

	/**
	 * Adds classes to body tag.
	 *
	 * @since 1.0.0
	 * @param array $classes Body class names.
	 * @param array $class Additional class names added to the body.
	 * @return array Filtered body class names.
	 */
	public function body_class($classes, $class) {

		if (get_field('vtlmb_active', 'option')) {
			array_push($classes, 'vtlmb-message-bar');
		}

		return $classes;
	}

	/**
	 * Adds inline styles to page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function inline_styles() {

		$styles = [];

		if ($text_color = get_field('vtlmb_text_color', 'option')) {
			$styles[] = ".vtlmb-bar-message { color: {$text_color}; }";
		}

		if ($bg_color = get_field('vtlmb_bg_color', 'option')) {
			$styles[] = ".vtlmb-bar { background-color: $bg_color; }";
		}

		if ($link_text_color = get_field('vtlmb_link_text_color', 'option')) {
			$styles[] = ".vtlmb-bar-link { color: {$link_text_color}; }";
		}

		if ($link_bg_color = get_field('vtlmb_link_bg_color', 'option')) {
			$styles[] = ".vtlmb-bar-link { background-color: {$link_bg_color}; }";
		}

		if ($link_focus_text_color = get_field('vtlmb_link_focus_text_color', 'option')) {
			$styles[] = ".vtlmb-bar-link:focus, .vtlmb-bar-link:hover { color: {$link_focus_text_color}; }";
		}

		if ($link_focus_bg_color = get_field('vtlmb_link_focus_bg_color', 'option')) {
			$styles[] = ".vtlmb-bar-link:focus, .vtlmb-bar-link:hover { background-color: {$link_focus_bg_color}; }";
		}

		if ($dismiss_color = get_field('vtlmb_dismiss_color', 'option')) {
			$styles[] = ".vtlmb-bar-dismiss-icon path { fill: {$dismiss_color}; }";
		}

		if ($dismiss_focus_color = get_field('vtlmb_dismiss_focus_color', 'option')) {
			$styles[] = ".vtlmb-bar-dismiss-icon:focus path, .vtlmb-bar-dismiss-icon:hover path { fill: {$dismiss_focus_color}; }";
		}

		$styles = implode("\n", $styles);

		printf(
			'<style type="text/css">%s</style>',
			esc_html($styles)
		);
	}

	/**
	 * Adds message bar markup to the page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_bar() {

		if (get_field('vtlmb_active', 'option')) {

			$is_sticky = (get_field('vtlmb_sticky', 'option') ? ' vtlmb-sticky' : '');

			$message = get_field('vtlmb_msg_text', 'option');

			if ($message_link = get_field('vtlmb_msg_link', 'option')) {

				$link = sprintf(
					' <span class="vtlmb-bar-link-wrapper"><a href="%s" target="%s" class="vtlmb-bar-link">%s</a></span>',
					esc_attr($message_link['url']),
					esc_attr($message_link['target']),
					esc_attr($message_link['title']),
				);
			} else {

				$link = '';
			}

			$dismiss_icon = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2"><path d="M16.55 12l6.822-6.823a2.145 2.145 0 0 0 0-3.033L21.856.628a2.145 2.145 0 0 0-3.033 0L12 7.45 5.177.628a2.145 2.145 0 0 0-3.033 0L.628 2.144a2.145 2.145 0 0 0 0 3.033L7.451 12 .628 18.823a2.145 2.145 0 0 0 0 3.033l1.516 1.516a2.145 2.145 0 0 0 3.033 0L12 16.549l6.823 6.823a2.145 2.145 0 0 0 3.033 0l1.516-1.516a2.145 2.145 0 0 0 0-3.033L16.549 12z" fill="#fff" fill-rule="nonzero"/></svg>';
			$dismiss_icon = apply_filters('vtlmb_dismiss_icon', $dismiss_icon);

			$dismiss_icon = sprintf(
				'<span class="vtlmb-bar-dismiss-icon" aria-hidden="true">%s</span>',
				$dismiss_icon
			);

			if (get_field('vtlmb_dismissable', 'option')) {

				$dismiss_button = sprintf(
					'<button type="button" class="vtlmb-bar-dismiss">%s<span class="screen-reader-text">%s</span></button>',
					$dismiss_icon,
					__('Dismiss message', 'vital')
				);
			} else {

				$dismiss_button = '';
			}

			$output = <<<EOT
<div class="vtlmb-bar{$is_sticky}">
	<div class="vtlmb-bar-container">
		<div class="vtlmb-bar-wrapper">
			<p class="vtlmb-bar-message">{$message}{$link}</p>
		</div>
		{$dismiss_button}
	</div>
</div>
EOT;

			echo apply_filters('vtlmb_message_bar', $output, $message, $message_link);
		}
	}
}

new Vital_Message_Bar();
