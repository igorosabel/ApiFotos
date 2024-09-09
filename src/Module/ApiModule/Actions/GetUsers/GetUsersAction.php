<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\GetUsers;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\User;
use Osumi\OsumiFramework\App\Component\Model\UserList\UserListComponent;

#[OModuleAction(
	url: '/get-users',
	filters: ['Login'],
	services: ['Web']
)]
class GetUsersAction extends OAction {
	public string $status = 'ok';
	public ?UserListComponent $list = null;

	/**
	 * Función para obtener el listado de usuario
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$filter = $req->getFilter('Login');
		$this->list = new UserListComponent(['list' => []]);

		if ($filter['status'] == 'error') {
			$this->status = 'error';
		}

		if ($this->status ==  'ok') {
			$user = new User();
			if ($user->checkAdmin($filter['id'])) {
				$user_list = $this->service['Web']->getUserList();
				$this->list->setValue('list', $user_list);
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
