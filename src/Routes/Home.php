<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Home\Index\IndexComponent;
use Osumi\OsumiFramework\App\Module\Home\NotFound\NotFoundComponent;

ORoute::get('/',          IndexComponent::class);
ORoute::get('/not-found', NotFoundComponent::class);
