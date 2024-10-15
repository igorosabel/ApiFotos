<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SaveUser;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\UserSaveDTO;
use Osumi\OsumiFramework\App\Model\User;

class SaveUserComponent extends OComponent {
	public string $status = 'ok';

	/**
	 * Función para guardar un usuario
	 *
	 * @param UserSaveDTO $data Datos del usuario a guardar
	 * @return void
	 */
	public function run(UserSaveDTO $data):void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status == 'ok') {
			$user = new User();
			if ($user->checkAdmin($data->getIdToken())) {
				$u = new User();
				if ($data->getId() != -1) {
					$u->find(['id' => $data->getId()]);
				}
				$u->set('username', $data->getUsername());
				$u->set('name', $data->getName());
				$u->set('is_admin', $data->getIsAdmin());
				if ($data->getPass() != '') {
					$u->set('pass', password_hash($data->getPass(), PASSWORD_BCRYPT));
				}
				$u->save();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
