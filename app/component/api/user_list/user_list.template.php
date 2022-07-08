<?php
use OsumiFramework\App\Component\Api\UserItemComponent;

foreach ($values['list'] as $i => $user) {
	$user_item_component = new UserItemComponent([ 'user' => $user ]);
	echo $user_item_component;
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
