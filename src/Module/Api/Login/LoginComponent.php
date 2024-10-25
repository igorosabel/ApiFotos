<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Login;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Plugins\OToken;
use Osumi\OsumiFramework\App\Service\UserService;
use Osumi\OsumiFramework\App\Model\User;

class LoginComponent extends OComponent {
	private ?UserService $us = null;

	public string $status   = 'ok';
	public int    $id       = -1;
	public string $username = '';
	public string $name     = '';
	public string $token    = '';
	public string $is_admin = 'false';

	public function __construct() {
		parent::__construct();
		$this->us = inject(UserService::class);
	}

	/**
	 * Función para iniciar sesión
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->username = $req->getParamString('username');
		$pass           = $req->getParamString('pass');

		if (is_null($this->username) || is_null($pass)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$user = $this->us->login($this->username, $pass);
			if (!is_null($user)) {
				$this->id       = $user->id;
				$this->name     = $user->name;
				$this->is_admin = $user->is_admin ? 'true' : 'false';

				$tk = new OToken($this->getConfig()->getExtra('secret'));
				$tk->addParam('id', $this->id);
				$tk->addParam('username', $this->username);
				$this->token = $tk->getToken();
			}
			else {
				$this->status = 'error';
				$this->getLog()->error('login - Error al iniciar sesión (' . $this->username . ').');
			}
		}
	}
}
