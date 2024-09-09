<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\Model\User;

class AddUserTask extends OTask {
	public function __toString() {
		return "addUser: Tarea para añadir usuarios";
	}

	public function run(array $options=[]): void {
		if (count($options) < 3) {
			echo "\nERROR: Debes indicar el nombre de usuario y contraseña a crear\n\n";
			echo "  php of addUser username pass name\n\n";
			exit();
		}

		$username = $options[0];
		$pass = $options[1];
		$name = $options[2];

		$user = new User();
		$user->set('username', $username);
		$user->set('pass', password_hash($pass, PASSWORD_BCRYPT));
		$user->set('name', $name);
		$user->save();

		echo "\n\n  Usuario ".$user." creado con id ".$user->get('id')."\n\n";
	}
}
