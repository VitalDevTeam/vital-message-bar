<?php
if (! defined('ABSPATH')) {
	exit;
}

class Vital_Message_Bar_Settings {

	public function __construct() {
		add_action('acf/init', [$this, 'settings']);
	}

	/**
	 * Adds settings page and fields.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function settings() {

		acf_add_options_page([
			'page_title'      => 'Message Bar',
			'menu_title'      => 'Message Bar',
			'menu_slug'       => 'vtl-message-bar',
			'parent_slug'     => 'options-general.php',
			'update_button'   => 'Save',
			'updated_message' => 'Settings Saved.',
			'capability'      => 'edit_posts',
			'redirect'        => false,
		]);

		acf_add_local_field_group([
			'key'                   => 'group_vtlmb_settings',
			'title'                 => 'Message Bar',
			'fields'                => [
				[
					'key'       => 'field_vtlmb_wuy83TqopJge',
					'label'     => 'Content',
					'name'      => 'vtlmb_tab1',
					'type'      => 'tab',
					'placement' => 'top',
					'endpoint'  => 0,
				],
				[
					'key'           => 'field_vtlmb_UHSBFNxFi7NI',
					'label'         => 'Active',
					'name'          => 'vtlmb_active',
					'type'          => 'true_false',
					'default_value' => 0,
					'ui'            => 1,
				],
				[
					'key'      => 'field_vtlmb_YrDYLFOGn1G5',
					'label'    => 'Message Text',
					'name'     => 'vtlmb_msg_text',
					'type'     => 'text',
					'required' => 1,
				],
				[
					'key'           => 'field_vtlmb_a8LoztxeAYk3',
					'label'         => 'Message Link',
					'name'          => 'vtlmb_msg_link',
					'type'          => 'link',
					'return_format' => 'array',
				],
				[
					'key'       => 'field_vtlmb_NkTXVtvoVfa7',
					'label'     => 'Colors',
					'name'      => 'vtlmb_tab2',
					'type'      => 'tab',
					'placement' => 'top',
					'endpoint'  => 0,
				],
				[
					'key'           => 'field_vtlmb_87uUvYh0uao6',
					'label'         => 'Text Color',
					'name'          => 'vtlmb_text_color',
					'type'          => 'color_picker',
					'default_value' => '#ffffff',
				],
				[
					'key'           => 'field_vtlmb_p6oUdVBsfBGD',
					'label'         => 'Background Color',
					'name'          => 'vtlmb_bg_color',
					'type'          => 'color_picker',
					'default_value' => '#000000',
				],
				[
					'key'           => 'field_vtlmb_aLsZN5CX3Yrt',
					'label'         => 'Link Text Color',
					'name'          => 'vtlmb_link_text_color',
					'type'          => 'color_picker',
					'default_value' => '#000000',
				],
				[
					'key'           => 'field_vtlmb_ewp2unznstTj',
					'label'         => 'Link Background Color',
					'name'          => 'vtlmb_link_bg_color',
					'type'          => 'color_picker',
					'default_value' => '#ffffff',
				],
				[
					'key'           => 'field_vtlmb_2r9DHwtfnruQ',
					'label'         => 'Link Hover/Focus Text Color',
					'name'          => 'vtlmb_link_focus_text_color',
					'type'          => 'color_picker',
					'default_value' => '#ffffff',
				],
				[
					'key'           => 'field_vtlmb_2eED3wM93qki',
					'label'         => 'Link Hover/Focus Background Color',
					'name'          => 'vtlmb_link_focus_bg_color',
					'type'          => 'color_picker',
					'default_value' => '#aaaaaa',
				],
				[
					'key'               => 'field_vtlmb_pK7BL9moCKXY',
					'label'             => 'Dismiss Button Color',
					'name'              => 'vtlmb_dismiss_color',
					'type'              => 'color_picker',
					'default_value'     => '#ffffff',
					'conditional_logic' => [
						[
							[
								'field'    => 'field_vtlmb_bXRGjXDVjHub',
								'operator' => '==',
								'value'    => 1,
							],
						],
					],
				],
				[
					'key'               => 'field_vtlmb_OTwWHHbPTAeL',
					'label'             => 'Dismiss Button Hover/Focus Color',
					'name'              => 'vtlmb_dismiss_focus_color',
					'type'              => 'color_picker',
					'default_value'     => '#aaaaaa',
					'conditional_logic' => [
						[
							[
								'field'    => 'field_vtlmb_bXRGjXDVjHub',
								'operator' => '==',
								'value'    => 1,
							],
						],
					],
				],
				[
					'key'       => 'field_vtlmb_czp6fHA6kG3T',
					'label'     => 'Settings',
					'name'      => 'vtlmb_tab3',
					'type'      => 'tab',
					'placement' => 'top',
					'endpoint'  => 0,
				],
				[
					'key'           => 'field_vtlmb_PuZcPuB3nIp7',
					'label'         => 'Make bar sticky',
					'name'          => 'vtlmb_sticky',
					'type'          => 'true_false',
					'instructions'  => 'Pins bar to the top of the page when scrolling',
					'default_value' => 0,
					'ui'            => 1,
				],
				[
					'key'           => 'field_vtlmb_bXRGjXDVjHub',
					'label'         => 'Allow bar to be dismissed?',
					'name'          => 'vtlmb_dismissable',
					'type'          => 'true_false',
					'default_value' => 1,
					'ui'            => 1,
				],
				[
					'key'               => 'field_vtlmb_tt0f6K03EFHE',
					'label'             => 'Cookie Expiration Time',
					'name'              => 'vtlmb_cookie_expires',
					'type'              => 'number',
					'instructions'      => 'Message bar dismissal cookie expiration time in seconds. Cookie will expire with browser session if left blank.',
					'default_value'     => 86400,
					'min'               => 0,
					'step'              => 1,
					'append'            => 'seconds',
					'conditional_logic' => [
						[
							[
								'field'    => 'field_vtlmb_bXRGjXDVjHub',
								'operator' => '==',
								'value'    => 1,
							],
						],
					],
				],
			],
			'location'              => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'vtl-message-bar',
					],
				],
			],
			'style'                 => 'seamless',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => 1,
		]);

	}
}

$plugin_settings_page = new Vital_Message_Bar_Settings();
