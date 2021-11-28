<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $photo) {
	echo OTools::getComponent('api/photo_item', [ 'photo' => $photo ]);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}