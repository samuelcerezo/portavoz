<?php

defined('ABSPATH') or die();


/* Elementor Forms : Añadimos como acción enviar los datos a Salesforce */

class Salesforce_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {

	public function get_name() {
		return 'salesforce';
	}

	public function get_label() {
		return 'Salesforce Marketing Cloud';
	}

	public function register_settings_section($widget) {

		$widget->start_controls_section(
			'section_salesforce', array(
				'label' => 'Salesforce',
				'label_block' => true,
				'condition' => array(
					'submit_actions' => $this->get_name(),
				)
			)
		);

		$widget->add_control(
			'salesforce_prefix', array(
				'label' => esc_html__('Salesforce subdomain', 'portavoz'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => array(
					'active' => false
				)
			)
		);

		$widget->add_control(
			'salesforce_clientid', array(
				'label' => esc_html__('Client ID', 'portavoz'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => array(
					'active' => false
				)
			)
		);

		$widget->add_control(
			'salesforce_clientsecret', array(
				'label' => esc_html__('Client Secret', 'portavoz'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => array(
					'active' => false
				)
			)
		);

		$widget->add_control(
			'salesforce_table', array(
				'label' => esc_html__('Data extension key', 'portavoz'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'ai' => array(
					'active' => false
				)
			)
		);

		$widget->end_controls_section();

	}

	public function run($record, $ajax_handler) {

		$settings = $record->get('form_settings');

		// Comprobamos que se ha pasado toda la información para realizar la conexión

		if (empty($settings['salesforce_prefix'])) {
			return;
		}

		if (empty($settings['salesforce_clientid'])) {
			return;
		}

		if (empty($settings['salesforce_clientsecret'])) {
			return;
		}

		if (empty($settings['salesforce_table'])) {
			return;
		}

		// Obtenemos los datos del formulario
		
		$raw_fields = $record->get('fields');

		$salesforce = [];

		foreach ($settings['form_fields'] as $field) {

			if (isset($field['salesforce_migrate']) && $field['salesforce_migrate'] == 'yes') {

				$salesforce[$field['custom_id']] = $field['salesforce_key'];

			}
		
		}

		$data = [];
		
		foreach ($raw_fields as $id => $field) {

			if (isset($salesforce[$id])) {

				if (in_array($field['type'], ['acceptance'])) {

					if ($field['value'] == 'on') {
					
						$data[$salesforce[$id]] = 'true';
					
					} else {

						$data[$salesforce[$id]] = 'false';

					}

				} else {

					$data[$salesforce[$id]] = $field['value'];

				}

			}

		}

		$token = salesforce_token($settings['salesforce_prefix'], $settings['salesforce_clientid'], $settings['salesforce_clientsecret']);

		if ($token) {

			$result = salesforce_save($settings['salesforce_prefix'], $data, $settings['salesforce_table'], $token);

			$test = salesforce_check($settings['salesforce_prefix'], $result, $token);

		} else {

			$msg = 'Error conectando con Salesforce';

			$handler->add_error(0, $msg);
			$handler->add_error_message($msg);
			
			$handler->is_success = false;

		}

	}

	public function on_export($element) {

		unset(
			$element['salesforce_prefix'],
			$element['salesforce_clientid'],
			$element['salesforce_clientsecret'],
			$element['salesforce_table']
		);

		return $element;

	}

}