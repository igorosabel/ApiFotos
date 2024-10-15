<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetUsers;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\User;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Component\Model\UserList\UserListComponent;

class GetUsersComponent extends OComponent {
	private ?WebService $ws = null;

	public string $status = 'ok';
	public ?UserListComponent $list = null;

	public function __construct() {
    parent::__construct();
		$this->ws = inject(WebService::class);
		$this->list = new UserListComponent();
	}

	/**
	 * Función para obtener el listado de usuario
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$filter = $req->getFilter('Login');

		if ($filter['status'] === 'error') {
			$this->status = 'error';
		}

		if ($this->status ===  'ok') {
			$user = new User();
			if ($user->checkAdmin($filter['id'])) {
				$user_list = $this->ws->getUserList();
				$this->list->list = $user_list;
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
