<?php

defined('ABSPATH') or die();


/* Elementor Forms : Añadimos campos al formulario para la conexión con Salesforce */

class Salesforce_Fields extends \ElementorPro\Modules\Forms\Fields\Field_Base {

	public function get_name() {
		
	}

	public function get_type() {
		
	}

	public function update_controls($widget) {

		$elementor = \ElementorPro\Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');

		if (is_wp_error($control_data)) {
			return;
		}

		$field_controls = array(
			'salesforce_migrate' => array(
				'name' => 'salesforce_migrate',
				'label' => esc_html__('Migrate to Salesforce', 'portavoz'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => array(
					//'submit_actions' => 'salesforce',
				),
				'tab' => 'advanced',
				'inner_tab' => 'form_fields_advanced_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
			'salesforce_key' => array(
				'name' => 'salesforce_key',
				'label' => esc_html__('Salesforce column', 'portavoz'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => array(
					'salesforce_migrate' => 'yes',
				),
				'tab' => 'advanced',
				'inner_tab' => 'form_fields_advanced_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			),
		);

		$control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);

		$widget->update_control('form_fields', $control_data);

	}

	public function render($item, $item_index, $form) {
		
	}

}