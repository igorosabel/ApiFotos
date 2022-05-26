<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: 'getPhoto, getPhotos, getTags, getUsers, login, saveUser, updateTags, upload',
	type: 'json',
	prefix: '/api'
)]
class apiModule {}
