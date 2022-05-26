<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\UserSaveDTO;
use OsumiFramework\App\Model\User;

#[OModuleAction(
	url: '/save-user',
	filter: 'login'
)]
class saveUserAction extends OAction {
	/**
	 * Función para guardar un usuario
	 *
	 * @param UserSaveDTO $data Datos del usuario a guardar
	 * @return void
	 */
	public function run(UserSaveDTO $data):void {
		$status = 'ok';
		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status == 'ok') {
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
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
