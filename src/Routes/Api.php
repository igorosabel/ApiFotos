<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Api\GetPhoto\GetPhotoComponent;
use Osumi\OsumiFramework\App\Module\Api\GetPhotos\GetPhotosComponent;
use Osumi\OsumiFramework\App\Module\Api\GetTags\GetTagsComponent;
use Osumi\OsumiFramework\App\Module\Api\GetUsers\GetUsersComponent;
use Osumi\OsumiFramework\App\Module\Api\Login\LoginComponent;
use Osumi\OsumiFramework\App\Module\Api\SaveUser\SaveUserComponent;
use Osumi\OsumiFramework\App\Module\Api\UpdateTags\UpdateTagsComponent;
use Osumi\OsumiFramework\App\Module\Api\Upload\UploadComponent;
use Osumi\OsumiFramework\App\Filter\LoginFilter;

ORoute::prefix('/api', function() {
  ORoute::post('/get-photo',   GetPhotoComponent::class,   [LoginFilter::class]);
  ORoute::post('/get-photos',  GetPhotosComponent::class,  [LoginFilter::class]);
  ORoute::post('/get-tags',    GetTagsComponent::class,    [LoginFilter::class]);
  ORoute::post('/get-users',   GetUsersComponent::class,   [LoginFilter::class]);
  ORoute::post('/login',       LoginComponent::class);
  ORoute::post('/save-user',   SaveUserComponent::class,   [LoginFilter::class]);
  ORoute::post('/update-tags', UpdateTagsComponent::class, [LoginFilter::class]);
  ORoute::post('/upload',      UploadComponent::class,     [LoginFilter::class]);
});
