<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Middleware\LoginMiddleware;
use Osumi\OsumiFramework\App\Module\Api\GetPhoto\GetPhotoComponent;
use Osumi\OsumiFramework\App\Module\Api\GetPhotos\GetPhotosComponent;
use Osumi\OsumiFramework\App\Module\Api\GetTags\GetTagsComponent;
use Osumi\OsumiFramework\App\Module\Api\GetUsers\GetUsersComponent;
use Osumi\OsumiFramework\App\Module\Api\Login\LoginComponent;
use Osumi\OsumiFramework\App\Module\Api\SaveUser\SaveUserComponent;
use Osumi\OsumiFramework\App\Module\Api\UpdateTags\UpdateTagsComponent;
use Osumi\OsumiFramework\App\Module\Api\Upload\UploadComponent;

ORoute::prefix('/api', function() {
  ORoute::post('/get-photo',   GetPhotoComponent::class,   ['before' => [LoginMiddleware::class]]);
  ORoute::post('/get-photos',  GetPhotosComponent::class,  ['before' => [LoginMiddleware::class]]);
  ORoute::post('/get-tags',    GetTagsComponent::class,    ['before' => [LoginMiddleware::class]]);
  ORoute::post('/get-users',   GetUsersComponent::class,   ['before' => [LoginMiddleware::class]]);
  ORoute::post('/login',       LoginComponent::class);
  ORoute::post('/save-user',   SaveUserComponent::class,   ['before' => [LoginMiddleware::class]]);
  ORoute::post('/update-tags', UpdateTagsComponent::class, ['before' => [LoginMiddleware::class]]);
  ORoute::post('/upload',      UploadComponent::class,     ['before' => [LoginMiddleware::class]]);
});
