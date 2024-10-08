<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Api\GetPhoto\GetPhotoAction;
use Osumi\OsumiFramework\App\Module\Api\GetPhotos\GetPhotosAction;
use Osumi\OsumiFramework\App\Module\Api\GetTags\GetTagsAction;
use Osumi\OsumiFramework\App\Module\Api\GetUsers\GetUsersAction;
use Osumi\OsumiFramework\App\Module\Api\Login\LoginAction;
use Osumi\OsumiFramework\App\Module\Api\SaveUser\SaveUserAction;
use Osumi\OsumiFramework\App\Module\Api\UpdateTags\UpdateTagsAction;
use Osumi\OsumiFramework\App\Module\Api\Upload\UploadAction;
use Osumi\OsumiFramework\App\Filter\LoginFilter;

ORoute::group('/api', 'json', function() {
  ORoute::post('/get-photo',   GetPhotoAction::class,   [LoginFilter::class]);
  ORoute::post('/get-photos',  GetPhotosAction::class,  [LoginFilter::class]);
  ORoute::post('/get-tags',    GetTagsAction::class,    [LoginFilter::class]);
  ORoute::post('/get-users',   GetUsersAction::class,   [LoginFilter::class]);
  ORoute::post('/login',       LoginAction::class);
  ORoute::post('/save-user',   SaveUserAction::class,   [LoginFilter::class]);
  ORoute::post('/update-tags', UpdateTagsAction::class, [LoginFilter::class]);
  ORoute::post('/upload',      UploadAction::class,     [LoginFilter::class]);
});
