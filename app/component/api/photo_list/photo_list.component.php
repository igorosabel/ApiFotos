<?php declare(strict_types=1);

namespace OsumiFramework\App\Component;

use OsumiFramework\OFW\Core\OComponent;

class PhotoListComponent extends OComponent {
  public array $depends = ['api/photo_item'];
}
