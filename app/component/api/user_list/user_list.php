<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $user) {
	echo OTools::getComponent('api/user_item', [ 'user' => $user ]);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}