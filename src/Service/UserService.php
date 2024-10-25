<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\App\Model\User;

class UserService extends OService {
  /**
   * Función para iniciar sesión
   *
   * @param string $username Nombre de usuario
   *
   * @param string $pass Contraseña del usuario
   *
   * @return ?User Devuelve el usuario si ha iniciado sesión correctamente o null en caso contrario
   */
  public function login(string $username, string $pass): ?User {
    $user = User::findOne(['username' => $username]);
    if (!is_null($user) && password_verify($pass, $user->pass)) {
			return $user;
		}
		return null;
  }
}
