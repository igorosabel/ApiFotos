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
	public function run(UserSaveDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$user = User::create();
			if ($user->checkAdmin($data->getIdToken())) {
				$u = User::create();
				if ($data->getId() !== -1) {
					$u = User::findOne(['id' => $data->getId()]);
				}
				$u->username = $data->getUsername();
				$u->name     = $data->getName();
				$u->is_admin = $data->getIsAdmin();
				if ($data->getPass() !== '') {
					$u->pass = password_hash($data->getPass(), PASSWORD_BCRYPT);
				}
				$u->save();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}