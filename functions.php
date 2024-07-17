<?php

require_once get_template_directory().'/inc/scssphp/scss.inc.php';

use ScssPhp\ScssPhp\Compiler;


// Declaramos la versión actual del tema

define('THEME_VERSION', 2.0);


// Añadimos soporte para miniaturas

add_theme_support('post-thumbnails');


// Declaramos los idiomas

add_action('after_setup_theme', function() {

	load_theme_textdomain('portavoz', get_stylesheet_directory().'/languages');

});


// Eliminamos la redimensión automática para imágenes muy grandes

add_filter('big_image_size_threshold', '__return_false');


// Creamos un menú principal por defecto

register_nav_menus(
	array(
		'primary' => 'Menú principal',
	)
);


// Directivas CSP de seguridad en el archivo .htaccess

add_action('admin_init', function() {

	if (!is_multisite()) {

		require_once(ABSPATH.'wp-admin/includes/file.php');

		$content = array(
			'# CPS Security headers rules',
			'<IfModule mod_headers.c>',
			'Header set Content-Security-Policy "default-src \'none\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' '.trim(site_url(), '/').' https://www.googletagmanager.com https://www.google.com https://www.gstatic.com https://cdnjs.cloudflare.com; connect-src \'self\' \'unsafe-inline\'  https://*.google-analytics.com https://*.mapbox.com; img-src \'self\' data: blob: https://secure.gravatar.com https://*.w.org https://library.elementor.com; style-src \'self\' \'unsafe-inline\' https://fonts.googleapis.com; worker-src \'self\' \'unsafe-inline\' blob:; font-src \'self\' data: https://fonts.gstatic.com; media-src \'self\' '.trim(site_url(), '/').'; frame-src \'self\' https://www.google.com;"',
			'</IfModule>',
			'# End of CPS Security headers rules'
		);

		insert_with_markers(get_home_path().'.htaccess', 'cps_headers', $content);

	}

});


// Función para cargar scripts PHP recursivamente

function directory_include($dir, $recursively = true) {

	if (is_dir($dir)) {

		$relative = str_replace(dirname(__FILE__).'/', '', $dir);

		$scan = scandir($dir);

		unset($scan[0], $scan[1]);

		$scan = multi_sort_array($scan, 'first');

		foreach($scan as $file) {

			if (is_dir($dir.'/'.$file) && $recursively == true) {

				directory_include($dir.'/'.$file);

			} else if (!is_dir($dir.'/'.$file)) {

				require($relative.'/'.$file);

			}

		}

	}

}


// Función para ordenar arrays multidimensionales en función de los valores

function multi_sort_array(&$array, $value) {

	if (count($array) > 0 && strpos(implode('', $array), $value) !== false) {

		$key = array_keys(preg_grep('/('.$value.')/i', $array))[0];
		
		$value = $array[$key];

		if($key) {
			unset($array[$key]);
		}

		array_unshift($array, $value);

	}

	return $array;

}


// Añadimos soporte para SVG

add_filter('wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

	global $wp_version;

	if ($wp_version !== '4.7.1') {
		return $data;
	}

	$filetype = wp_check_filetype($filename, $mimes);

	return array(
		'ext' => $filetype['ext'],
		'type' => $filetype['type'],
		'proper_filename' => $data['proper_filename']
	);

}, 10, 4 );

add_filter('upload_mimes', function($mimes) {

	$mimes['svg'] = 'image/svg+xml';

	return $mimes;

});


// Cargamos los scripts y hojas de estilo
	
add_action('wp_enqueue_scripts', function() {

	wp_enqueue_style('style', get_template_directory_uri().'/style.css', array(), uniqid());

	wp_enqueue_style('fonts', get_template_directory_uri().'/fonts/css.php', array(), uniqid());

	foreach (array_diff(scandir(get_template_directory().'/scss'), array('.', '..')) as $file) {

		$ext = end(explode('.', $file));

		if ($ext != 'scss') {
			continue;
		}

		$name = pathinfo($file, PATHINFO_FILENAME);

		$scss = get_template_directory().'/scss/'.$name.'.scss';
		$css = get_template_directory().'/css/'.$name.'.css';

		if (!file_exists($css) || filemtime($css) < filemtime($scss)) {

			$compiler = new Compiler();

			unlink($css);

			$out = fopen($css, 'w') or die('Error compilando SCSS');

			fwrite($out, $compiler->compileString(file_get_contents($scss))->getCss());

			fclose($out);

		}

	}

	foreach (array_diff(scandir(get_template_directory().'/scss'), array('.', '..')) as $file) {

		$ext = end(explode('.', $file));

		if ($ext != 'scss') {
			continue;
		}

		$name = pathinfo($file, PATHINFO_FILENAME);

		wp_enqueue_style($name, get_template_directory_uri().'/css/'.$name.'.css', array(), uniqid());

	}

	wp_enqueue_script('theme', get_template_directory_uri().'/js/theme.js', array(), uniqid(), true);

	wp_register_script('inputmask', get_template_directory_uri().'/js/inputmask.js', array('jquery'), uniqid(), true);

	wp_register_script('main', get_template_directory_uri().'/js/main.js', array('jquery', 'inputmask'), uniqid(), true);

	wp_localize_script('main', 'main', array(
		'messages' => array(
			'errors' => __('There are errors in the form. Please correct them before continuing.', 'portavoz'),
			'fill' => __('Fill in the marked fields.', 'portavoz'),
			'legal' => __('You must accept the legal notice.', 'portavoz'),
			'email' => __('Invalid email address', 'portavoz')
		)
	));

	wp_enqueue_script('main');

});


// Cargamos los scripts y estilos del área de administración

add_action('admin_enqueue_scripts', function() {

	$scss = get_template_directory().'/scss/admin.scss';

	$css = get_template_directory().'/css/admin.css';

	if (!file_exists($css) || filemtime($css) < filemtime($scss)) {

		$compiler = new Compiler();

		unlink($css);

		$out = fopen($css, 'w') or die('Error compilando SCSS');

		fwrite($out, $compiler->compileString(file_get_contents($scss))->getCss());

		fclose($out);

	}

	wp_enqueue_style('admin', get_template_directory_uri().'/css/admin.css', array(), uniqid());

	wp_enqueue_script('admin', get_template_directory_uri().'/js/admin.js', array('jquery'), uniqid(), true);

});


// Cargamos los scripts y estilos del editor de Elementor

add_action('elementor/editor/before_enqueue_scripts', function() {

	wp_enqueue_script('elementor', get_template_directory_uri().'/js/elementor.js', array('jquery'), uniqid(), true);

});


// Eliminamos archivos inncesarios

add_action('init', function() {

	$files = ['readme.html', 'wp-config-sample.php', 'licencia.txt', 'license.txt'];

	foreach ($files as $file) {

		if (file_exists(ABSPATH.$file)) {

			unlink(ABSPATH.$file);

		}

	}

});


// Modificamos el archivo style.css con la información del sitio

add_action('after_switch_theme', function() {

	if (strpos($_SERVER['SERVER_NAME'], 'portavoz.com.es') !== false) {

		update_option('blog_public', 0);

	}

	$style = strtr(file_get_contents(get_stylesheet_directory().'/style.css'), array(
		'{{NAME}}' => get_bloginfo('name'),
		'{{VERSION}}' => date('Y\.m')
	));

	if ($style != '') {
		file_put_contents(get_stylesheet_directory().'/style.css', $style);
	}

});


// Cargamos las tipografías personalizadas

add_action('elementor/controls/controls_registered', function($controls_registry) {

	$fonts = $controls_registry->get_control('font')->get_settings('options');

	foreach (scandir(get_template_directory().'/fonts') as $file) {

		if (!in_array($file, array('.', '..', 'css.php'))) {

			$fonts = array_merge(array($file => 'system'), $fonts);

		}

	}

	$controls_registry->get_control('font')->set_settings('options', $fonts);

}, 10, 1);


// Cargamos los widgets personalizados de Elementor

add_action('after_setup_theme', function() {

	if (!did_action('elementor/loaded')) {
		return;
	}

	include('elementor/init.php');

}, 50);


// Añadimos campos personalizados para los formularios de Elementor

add_action('elementor_pro/forms/fields/register', function($fields) {

	if (did_action('elementor/loaded')) {

		include('elementor/assets/elementor.forms.fields.salesforce.php');

		$fields->register(new Salesforce_Fields());

	}

});


// Añadimos acciones personalizadas para los formularios de Elementor

add_action('elementor_pro/forms/actions/register', function($actions) {

	if (did_action('elementor/loaded')) {

		include('elementor/assets/elementor.forms.actions.redirect.php');
		include('elementor/assets/elementor.forms.actions.salesforce.php');

		$actions->register(new Data_Redirection_After_Submit());
		$actions->register(new Salesforce_Action_After_Submit());

	}

});


// Deshabilitamos los pingbacks

add_filter('pings_open', function() {

	return false;

});


// Deshabiltiamos Xmlrpc

add_filter('xmlrpc_enabled', function() {

	return false;

});


// Deshabilitamos los endpoints de la REST API

add_filter('rest_endpoints', function($endpoints) {

	if (is_user_logged_in()) {

		return $endpoints;

	}

	foreach ($endpoints as $route => $endpoint) {

		if (stripos($route, '/wp/') === 0) {

			unset($endpoints[ $route ]);

		}

	}

	return $endpoints;

});


// Deshabilitamos avisos de actualizaciones automáticas

add_filter('auto_plugin_update_send_email', function() {

	return false;

});

add_filter('auto_theme_update_send_email', function() {

	return false;

});


// Eliminamos etiquetas innecesarias de la cabecera

add_action('init', function() {

	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'start_post_rel_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
	remove_action('wp_head', 'wp_oembed_add_host_js');
	remove_action('wp_head', 'rest_output_link_wp_head');
	remove_action('template_redirect', 'rest_output_link_header', 11, 0);
	remove_action('template_redirect', 'wp_shortlink_header', 11, 0);

});


// Reemplazamos las urls dinámicas basadas en la ID

function dynamic_urls($arg) {

	if ($arg[1] == 'id') {

		if (function_exists('pll_get_post')) {

			$link = get_permalink(pll_get_post($arg[2]));

		} else {
			
			$link = get_permalink($arg[2]);
		
		}

	} else if (get_post_type($arg[2]) == 'attachment') {

		$link = wp_get_attachment_url($arg[2]);

	} else {

		if (!term_exists((int)$arg[2], $arg[1])) {
			return;
		}

		if (function_exists('pll_get_term')) {
			
			$link = get_term_link(pll_get_term((int)$arg[2]), $arg[1]);

		} else {
			
			$link = get_term_link((int)$arg[2], $arg[1]);

		}

	}

	if (!is_wp_error($link) && is_string($link)) {

		return $link;

	}

	return;

}

add_action('elementor/frontend/the_content', function($content) {

	$content = preg_replace_callback("/{{([a-z]+)-([0-9]+)}}/", "dynamic_urls", $content);
	$content = preg_replace_callback("/http:\/\/{{([a-z]+)-([0-9]+)}}/", "dynamic_urls", $content);
	$content = preg_replace_callback("/http[s]?:\/\/([a-z]+)-([0-9]+)/", "dynamic_urls", $content);

	return $content;

});


// Reemplazamos variables en el contenido

add_action('elementor/frontend/the_content', function($content) {

	$replacements = array(
		'{{year}}' => date('Y'),
		'{{cookies}}' => '<span class="cookies-link">'.__('Withdrawal', 'portavoz').'</span>'
	);

	$content = strtr($content, $replacements);

	return $content;

});


// Cargamos las funciones extras del tema

directory_include(get_stylesheet_directory().'/functions');

?>