<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\HomeModule;

use Osumi\OsumiFramework\Routing\OModule;

#[OModule(
	actions: ['Index', 'NotFound']
)]
class HomeModule {}
