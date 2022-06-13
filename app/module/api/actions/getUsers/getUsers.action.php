<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\User;
use OsumiFramework\App\Component\UserListComponent;

#[OModuleAction(
	url: '/get-users',
	filter: 'login',
	services: 'web',
	components: 'api/user_list'
)]
class getUsersAction extends OAction {
	/**
	 * Función para obtener el listado de usuario
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$filter = $req->getFilter('login');
		$user_list_component = new UserListComponent(['list' => [], 'extra' => 'nourlencode']);

		if ($filter['status'] == 'error') {
			$status = 'error';
		}

		if ($status ==  'ok') {
			$user = new User();
			if ($user->checkAdmin($filter['id'])) {
				$list = $this->web_service->getUserList();
				$user_list_component = new UserListComponent(['list' => $list, 'extra' => 'nourlencode']);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $user_list_component);
	}
}