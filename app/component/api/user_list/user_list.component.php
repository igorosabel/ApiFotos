<?php declare(strict_types=1);

namespace OsumiFramework\App\Component;

use OsumiFramework\OFW\Core\OComponent;

class UserListComponent extends OComponent {
  private string $depends = 'api/user_item';
}
