<?php

header("Content-type: text/css; charset: UTF-8");

require_once('../../../../wp-load.php');

$fonts = array();

foreach (scandir(get_template_directory().'/fonts') as $family) {

	if (!in_array($family, array('.', '..', 'css.php'))) {

		$fonts[$family] = array();

		foreach (scandir(get_template_directory().'/fonts/'.$family) as $variant) {

			if (is_dir($variant)) {
				continue;
			}

			if (!in_array($variant, array('.', '..'))) {

				$ext = pathinfo($variant)['extension'];

				if ($ext != 'woff2') {

					unlink(get_template_directory().'/fonts/'.$family.'/'.$variant);

				}

				$italic = false;

				if (strpos($variant, 'i')) {
					$italic = true;
				}

				$weight = intval(pathinfo($variant)['filename']);

				$fonts[$family][$weight.($italic ? 'i' : '')][$ext] = $variant;

			}

		}


	}

}

ob_start();

?>
@charset 'UTF-8';
<?php

foreach ($fonts as $family => $variant) {

	foreach ($variant as $weight => $variant) {

		$italic = false;

		if (strpos($weight, 'i')) {

			$italic = true;

			$weight = intval($weight);

		}

?>

@font-face {
	font-family: '<?= $family ?>';
	font-display: swap;
	src: url('../fonts/<?= $family ?>/<?= $weight ?>.woff2') format('woff2');
	font-weight: <?= $weight ?>;
	font-style: <?= ($italic ? 'italic' : 'normal') ?>;
}
<?php

	}

}

echo ob_get_clean();

?>