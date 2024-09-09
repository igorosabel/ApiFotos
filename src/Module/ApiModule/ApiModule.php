<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule;

use Osumi\OsumiFramework\Routing\OModule;

#[OModule(
	type: 'json',
	prefix: '/api',
	actions: ['GetPhoto', 'GetPhotos', 'GetTags', 'GetUsers', 'Login', 'SaveUser', 'UpdateTags', 'Upload']
)]
class ApiModule {}
