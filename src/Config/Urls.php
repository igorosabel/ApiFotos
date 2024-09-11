<?php declare(strict_types=1);

use Osumi\OsumiFramework\Routing\OUrl;
use Osumi\OsumiFramework\App\Module\Api\GetPhoto\GetPhotoAction;
use Osumi\OsumiFramework\App\Module\Api\GetPhotos\GetPhotosAction;
use Osumi\OsumiFramework\App\Module\Api\GetTags\GetTagsAction;
use Osumi\OsumiFramework\App\Module\Api\GetUsers\GetUsersAction;
use Osumi\OsumiFramework\App\Module\Api\Login\LoginAction;
use Osumi\OsumiFramework\App\Module\Api\SaveUser\SaveUserAction;
use Osumi\OsumiFramework\App\Module\Api\UpdateTags\UpdateTagsAction;
use Osumi\OsumiFramework\App\Module\Api\Upload\UploadAction;
use Osumi\OsumiFramework\App\Module\Home\Index\IndexAction;
use Osumi\OsumiFramework\App\Module\Home\NotFound\NotFoundAction;

use Osumi\OsumiFramework\App\Filter\LoginFilter;
use Osumi\OsumiFramework\App\Service\WebService;

$api_urls = [
  [
    'url' => '/get-photo',
    'action' => GetPhotoAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/get-photos',
    'action' => GetPhotosAction::class,
    'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/get-tags',
    'action' => GetTagsAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/get-users',
    'action' => GetUsersAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/login',
    'action' => LoginAction::class,
    'type' => 'json'
  ],
  [
    'url' => '/save-user',
    'action' => SaveUserAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/update-tags',
    'action' => UpdateTagsAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/upload',
    'action' => UploadAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
];

$home_urls = [
  [
    'url' => '/',
    'action' => IndexAction::class
  ],
  [
    'url' => '/not-found',
    'action' => NotFoundAction::class
  ],
];

$urls = [];
OUrl::addUrls($urls, $api_urls, '/api');
OUrl::addUrls($urls, $home_urls);

return $urls;
