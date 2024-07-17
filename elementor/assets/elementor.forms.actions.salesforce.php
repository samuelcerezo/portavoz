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

		$widget->add_control(
			'salesforce_mapping', array(
				'label' => esc_html__('Map fields', 'portavoz'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => '',
			)
		);

		//https://stackoverflow.com/questions/71691766/populate-select2-field-with-another-select2-field-in-elementor

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

		// Normalize form data.
		$fields = [];
		foreach ($raw_fields as $id => $field) {
			$fields[ $id ] = $field['value'];
		}

		// Make sure the user entered an email (required by Sendy to subscribe users).




	}

	public function on_export($element) {

		unset(
			$element['sendy_url'],
			$element['sendy_list'],
			$element['sendy_email_field'],
			$element['sendy_name_field']
		);

		return $element;

	}

}