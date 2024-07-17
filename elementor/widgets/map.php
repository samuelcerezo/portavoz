<?php

namespace Portavoz\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

if (! defined('ABSPATH')) {
	exit;
}

class Mapbox_Map extends Widget_Base {

	public function get_name() {
		return 'Mapbox_Map';
	}

	public function get_title() {
		return 'Mapbox';
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_categories() {
		return ['portavoz'];
	}

	public function get_script_depends() {
		return [];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => __('Configuration', 'portavoz'),
			]
		);

		$this->add_control(
			'autocenter',
			[
				'label' => __('Auto center', 'portavoz'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'returned_value' => 'yes'
			]
		);

		$this->add_control(
			'center',
			[
				'label' => __('Center', 'elementor'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'conditions' => [
					'terms' => [
						[
							'name' => 'autocenter',
							'operator' => '!=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'zoom',
			[
				'label' => __('Zoom Level', 'portavoz'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20
					]
				]
			]
		);

		$this->add_control(
			'pitch',
			[
				'label' => __('Pitch', 'portavoz'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360
					]
				]
			]
		);

		$this->add_control(
			'bearing',
			[
				'label' => __('Bearing', 'portavoz'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 90
					]
				]
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __('Height', 'portavoz'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 300,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1000,
						'step' => 10
					],
					'vh' => [
						'min' => 10,
						'max' => 1000,
						'step' => 10
					],
					'vw' => [
						'min' => 10,
						'max' => 1000,
						'step' => 10
					]
				],
				'size_units' => ['px', 'vh', 'vw', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .map-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'scroll',
			[
				'label' => __('Prevent Scroll', 'portavoz'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'returned_value' => 'yes'
			]
		);

		$this->add_control(
			'poi',
			[
				'label' => __('Show POIs', 'portavoz'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'returned_value' => 'yes'
			]
		);

		$this->add_control(
			'zoom_control',
			[
				'label' => __('Show zoom control', 'portavoz'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'returned_value' => 'yes'
			]
		);

		$this->add_control(
			'zoom_click',
			[
				'label' => __('Zoom when double click', 'portavoz'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'returned_value' => 'yes'
			]
		);

		$this->add_control(
			'dragging',
			[
				'label' => __('Available drag the map', 'portavoz'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'returned_value' => 'yes'
			]
		);

		$this->add_control(
			'token',
			[
				'label' => __('Token', 'portavoz'),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __('Style', 'portavoz').'. <a href="//www.mapbox.com/studio" target="_blank">'.__('Get styles', 'portavoz').'</a>',
				'type' => Controls_Manager::TEXT,
				'placeholder' => __('Url', 'portavoz')
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_markers',
			[
				'label' => __('Markers', 'portavoz'),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'cluster',
			[
				'label' => __('Group markers', 'portavoz'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'returned_value' => 'yes'
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'address',
			[
				'label' => __('Coordinates', 'elementor'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label' => __('Choose icon', 'portavoz'),
				'type' => Controls_Manager::MEDIA
			]
		);

		$repeater->add_responsive_control(
			'size',
			[
				'label' => __('Size', 'portavoz'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 40,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1000,
						'step' => 1
					],
				],
				'size_units' => ['px'],
			]
		);

		$repeater->add_control(
			'position',
			[
				'label' => __('Position', 'portavoz'),
				'type' => Controls_Manager::SELECT,
				'default' => 'bc',
				'options' => [
					'top-left' => __('Top left', 'portavoz'),
					'top' => __('Top center', 'portavoz'),
					'top-right' => __('Top right', 'portavoz'),
					'left' => __('Center left', 'portavoz'),
					'center' => __('Center', 'portavoz'),
					'right' => __('Center right', 'portavoz'),
					'bottom-left' => __('Bottom left', 'portavoz'),
					'bottom' => __('Bottom center', 'portavoz'),
					'bottom-right' => __('Bottom right', 'portavoz'),
				]
			]
		);

		$repeater->add_control(
			'info',
			[
				'label' => __('HTML content', 'portavoz'),
				'type' => Controls_Manager::TEXTAREA,
			]
		);

		$this->add_control(
			'markers',
			[
				'label' => __('Markers', 'portavoz'),
				'type' => Controls_Manager::REPEATER,
				'show_label' => true,
				'fields' => array_values($repeater->get_controls()),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings();

		$center = null;

		ob_start();

		if (is_numeric(str_replace(array(',', '.', '-', ' '), '', $settings['center']))) {
			$center = array('lat' => trim(explode(',', $settings['center'])[1]), 'lng' => trim(explode(',', $settings['center'])[0]));
		}

		wp_enqueue_script('mapbox', get_template_directory_uri().'/js/mapbox.js', array('jquery'), uniqid(), true);

		wp_enqueue_style('mapbox', get_template_directory_uri().'/css/mapbox.css', array());

		$markers = $bounds = array();

		foreach ($settings['markers'] as $index => $marker) {

			$markers[] = array(
				'type' => 'FeatureCollection',
				'features' => array(
					array(
						'type' => 'Feature',
						'properties' => array(
							'id' => $index,
							'html' => '<div class="marker-wrapper" data-id="'.$index.'">'.$marker['info'].'</div>',
							'anchor' => $marker['position']
						),
						'geometry' => array(
							'type' => 'Point',
							'coordinates' => array(
								(float)trim(explode(',', $marker['address'])[1]),
								(float)trim(explode(',', $marker['address'])[0]),
								0.0
							)
						)
					)
				)
			);

			$bounds[] = array((float)trim(explode(',', $marker['address'])[1]), (float)trim(explode(',', $marker['address'])[0]));

		}

		?>
		<div class="map-wapper" id="map-<?= $this->get_id() ?>" style="display: block;"></div>
		<script type="text/javascript">
			jQuery(window).on('load', function($) {
				mapboxgl.accessToken = '<?= $settings['token'] ?>';
				const map_<?= $this->get_id() ?> = new mapboxgl.Map({
					container: 'map-<?= $this->get_id() ?>',
					style: '<?= ($settings['style'] != '' ? $settings['style'] : 'mapbox://styles/mapbox/streets-v9') ?>',
					zoom: <?= $settings['zoom']['size'] ?>,
					pitch: <?= $settings['pitch']['size'] ?>,
					bearing: '<?= $settings['bearing']['size'] ?>',
					showZoom: <?= ($settings['zoom_control'] == 'yes' ? true : false) ?>,
					doubleClickZoom: <?= ($settings['zoom_click'] == 'yes' ? true : false) ?>,
					scrollZoom: <?= ($settings['scroll'] == 'yes' ? true : false) ?>,
					<?php 

					if (!is_null($center) && ($center['lat'] != '' && $center['lng'] != '')) {

						?>center: [<?= $center['lat'] ?>, <?= $center['lng'] ?>],<?php

					}

					?>
				});
				map_<?= $this->get_id() ?>.on('load', () => {
					map_<?= $this->get_id() ?>.addSource('markers', {
						type: 'geojson',
						data: json_encode($markers),
						cluster: <?= ($settings['cluster'] == 'yes' ? true : false) ?>,
						clusterMaxZoom: 18,
						clusterRadius: 50
					});
					var bounds = new mapboxgl.LngLatBounds(),
						markers = <?= json_encode($bounds) ?>;
					markers.forEach(function(marker) {
						bounds.extend(marker);
					});
					map_<?= $this->get_id() ?>.fitBounds(bounds, {
						padding: {
							top: 50,
							left: 50,
							right: 50,
							bottom: 50
						},
						animate: false
					});
					<?php if ($settings['cluster'] == 'yes') { ?>
					map_<?= $this->get_id() ?>.addLayer({
						id: 'points',
						type: 'circle',
						source: 'markers',
						filter: ['!', ['has', 'point_count']],
						paint: {
							'circle-color': '#000',
							'circle-radius': 6
						}
					});
					<?php } ?>
					map_<?= $this->get_id() ?>.on('click', 'points', (e) => {
						var properties = e.features[0].properties,
							coordinates = e.features[0].geometry.coordinates;
						var popup = new mapboxgl.Popup({
							focusAfterOpen: false,
							anchor: properties.anchor
						}).setLngLat(coordinates.slice()).setHTML(properties.html).addTo(map_<?= $this->get_id() ?>);
						map_<?= $this->get_id() ?>.easeTo({
							center: [coordinates.slice()[0], coordinates.slice()[1]],
							zoom: 9
						});
						map_668d233444276.on('zoomstart', function() {
							popup.remove();
						});
						map_668d233444276.on('movestart', function() {
							popup.remove();
						});
					});
					map_<?= $this->get_id() ?>.addControl(new mapboxgl.NavigationControl(), 'top-right');
					<?php if ($settings['autocenter'] == 'yes' && count($settings['markers']) > 0) { ?>
					map_668d233444276..fitBounds(b, {
						animate: false,
						padding: 50
					});
					<?php } ?>
				});
			});
		</script>
		<?php

		echo ob_get_clean();

	}

	protected function content_template() {

	}
}
