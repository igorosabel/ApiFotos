<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Plugins\OToken;
use OsumiFramework\App\Model\User;

#[OModuleAction(
	url: '/login'
)]
class loginAction extends OAction {
	/**
	 * Función para iniciar sesión
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$username = $req->getParamString('username');
		$pass     = $req->getParamString('pass');

		$id       = -1;
		$name     = '';
		$token    = '';
		$is_admin = 'false';

		if (is_null($username) || is_null($pass)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$user = new User();
			if ($user->login($username, $pass)) {
				$id = $user->get('id');
				$name = $user->get('name');
				$is_admin = $user->get('is_admin') ? 'true' : 'false';

				$tk = new OToken($this->getConfig()->getExtra('secret'));
				$tk->addParam('id', $id);
				$tk->addParam('username', $username);
				$token = $tk->getToken();
			}
			else {
				$status = 'error';
				$this->getLog()->error('login - Error al iniciar sesión ('.$username.').');
			}
		}

		$this->getTemplate()->add('status',   $status);
		$this->getTemplate()->add('id',       $id);
		$this->getTemplate()->add('username', $username);
		$this->getTemplate()->add('name',     $name);
		$this->getTemplate()->add('token',    $token);
		$this->getTemplate()->add('is_admin', $is_admin);
	}
}
