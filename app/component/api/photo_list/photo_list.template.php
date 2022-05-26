<?php
use OsumiFramework\App\Component\PhotoItemComponent;

foreach ($values['list'] as $i => $photo) {
	$photo_item_component = new PhotoItemComponent([ 'photo' => $photo ]);
	echo $photo_item_component;
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
