<?php

defined('ABSPATH') or die();


/* Elementor Forms : Añadimos como acción enviar los datos a Salesforce */

class Data_Redirection_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {

	public function get_name() {
		return 'data_redirect';
	}

	public function get_label() {
		return 'Redirect with data';
	}

	public function register_settings_section($widget) {

		$widget->start_controls_section(
			'data_redirect', array(
				'label' => 'Redirection',
				'label_block' => true,
				'condition' => array(
					'submit_actions' => $this->get_name(),
				)
			)
		);

		$widget->add_control(
			'redirection_url', array(
				'label' => esc_html__('Url', 'portavoz'),
				'type' => \Elementor\Controls_Manager::URL,
				'options' => false,
				'label_block' => true,
			)
		);

		$widget->add_control(
			'redirection_type', array(
				'label' => esc_html__('Type of data', 'portavoz'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'post',
				'options' => array(
					'post' => esc_html__('POST', 'portavoz'),
					'get' => esc_html__('GET', 'portavoz'),
				)
			)
		);

		$widget->end_controls_section();

	}

	public function run($record, $ajax_handler) {

		$settings = $record->get('form_settings');

		// Tipo de envío de datos

		$type = $settings['redirection_type'];

		// Obtenemos los datos del formulario
		
		$raw_fields = $record->get('fields');

		$fields = array();

		foreach ($raw_fields as $id => $field) {

			$fields[$id] = $field['value'];

		}

		// Obtenemos la url de redirección

		$url = $settings['redirection_url']['url'];

		$url = preg_replace_callback("/{{([a-z]+)-([0-9]+)}}/", "dynamic_urls", $url);

		switch ($type) {
		
			case 'get':

				$url = add_query_arg($fields, $url);

				$url = esc_url_raw($url);

				if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {

					$ajax_handler->add_response_data('redirect_url', $url);

				}

				break;
			
			case 'post':

				ob_start();

				?>
				<script type="text/javascript" id="elementor-submit_actions_post-script">
					var form = document.createElement('form');
					form.method = 'post';
					form.action = '<?= $url ?>';
					<?php

					foreach ($fields as $key => $value) {

						?>
					const input = document.createElement('input'); 
                    input.type = 'hidden'; 
                    input.name = '<?= $key ?>'; 
                    input.value = '<?= $value ?>'; 
  
                    form.appendChild(input);
						<?php

					}

					?>
					document.body.appendChild(form); 
					form.submit();
					jQuery(function($) {
						$('#elementor-submit_actions_post-script').closest('.elementor-message').hide();
					});
				</script>
				<?php

				$out = ob_get_clean();

				$ajax_handler->add_error(0, $out);
				$ajax_handler->add_error_message($out);
			
				$ajax_handler->is_success = false;

				break;

		}

	}

	public function on_export($element) {

		unset(
			$element['redirection_url'],
			$element['redirection_type']
		);

		return $element;

	}

}