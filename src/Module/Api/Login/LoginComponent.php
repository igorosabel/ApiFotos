<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Login;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Plugins\OToken;
use Osumi\OsumiFramework\App\Model\User;

class LoginComponent extends OComponent {
	public string $status   = 'ok';
	public int    $id       = -1;
	public string $username = '';
	public string $name     = '';
	public string $token    = '';
	public string $is_admin = 'false';

	/**
	 * Función para iniciar sesión
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$this->username = $req->getParamString('username');
		$pass     = $req->getParamString('pass');

		if (is_null($this->username) || is_null($pass)) {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$user = new User();
			if ($user->login($this->username, $pass)) {
				$this->id = $user->get('id');
				$this->name = $user->get('name');
				$this->is_admin = $user->get('is_admin') ? 'true' : 'false';

				$tk = new OToken($this->getConfig()->getExtra('secret'));
				$tk->addParam('id', $this->id);
				$tk->addParam('username', $this->username);
				$this->token = $tk->getToken();
			}
			else {
				$this->status = 'error';
				$this->getLog()->error('login - Error al iniciar sesión ('.$this->username.').');
			}
		}
	}
}
