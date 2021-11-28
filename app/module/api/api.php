<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Service\webService;
use OsumiFramework\OFW\Plugins\OToken;
use OsumiFramework\App\Model\User;
use OsumiFramework\App\Model\Photo;
use OsumiFramework\App\DTO\UserSaveDTO;

#[ORoute(
	type: 'json',
	prefix: '/api'
)]
class api extends OModule {
	private ?webService $web_service = null;

	function __construct() {
		$this->web_service = new webService();
	}

	/**
	 * Función para obtener la lista de fotos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/get-photos',
		filter: 'loginFilter'
	)]
	public function getPhotos(ORequest $req): void {
		$status = 'ok';
		$page = $req->getParamint('page');

		$list = [];
		$pages = 0;

		if (is_null($page)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$list = $this->web_service->getPhotosList($page);
			$pages = $this->web_service->getPhotosNumPages();
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('pages', $pages);
		$this->getTemplate()->addComponent('list', 'api/photo_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para obtener la lista completa de tags
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/get-tags',
		filter: 'loginFilter'
	)]
	public function getTags(ORequest $req): void {
		$status = 'ok';
		$list = [];

		if ($status=='ok') {
			$list = $this->web_service->getTagList();
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'api/tag_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para iniciar sesión
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/login')]
	public function login(ORequest $req): void {
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

	/**
	 * Función para añadir una nueva foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/upload',
		filter: 'loginFilter'
	)]
	public function upload(ORequest $req): void {
		$status = 'ok';
		$photo = $req->getParam('data');
		$id_user = $req->getParamInt('id');
		$filter = $req->getFilter('loginFilter');

		$id = null;

		if (is_null($photo) || is_null($id_user) || $filter['status'] == 'error') {
			$status = 'error';
		}

		if ($status == 'ok') {
			$user = new User();
			if ($user->checkAdmin($filter['id'])) {
				$this->getLog()->info(var_export($photo['date'], true));
				$this->getLog()->info(var_export($photo['exif'], true));
				$p = new Photo();
				$p->set('id_user', $id_user);
				$p->set('when', $photo['date']);
				$p->set('exif', $photo['exif']);
				$p->save();

				$id = $p->get('id');

				$this->web_service->saveNewImage($photo['src'], $id);

				if ( $photo['exif'] != '' ) {
					$exif_data = json_decode($photo['exif'], true);
					if (array_key_exists('Orientation', $exif_data) && $exif_data['Orientation'] != 1) {
						$this->web_service->rotateImage($p, $exif_data['Orientation']);
					}
				}
			}
			else {
				$status =  'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', $id);
	}

	/**
	 * Función para actualizar las etiquetas de una serie de fotos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/update-tags',
		filter: 'loginFilter'
	)]
	public function updateTags(ORequest $req): void {
		$status = 'ok';
		$list = $req->getParam('list');
		$tags = $req->getParamString('tags');

		if (is_null($list) || is_null($tags)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$this->web_service->updateTags($list, $tags);
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para obtener los datos de una foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/get-photo',
		filter: 'loginFilter'
	)]
	public function getPhoto(ORequest $req): void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$photo = null;

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$photo = new Photo();
			if (!$photo->find(['id' => $id])) {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('photo', 'api/photo_item', ['photo' => $photo, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para obtener el listado de usuario
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/get-users',
		filter: 'loginFilter'
	)]
	public function getUsers(ORequest $req): void {
		$status = 'ok';
		$filter = $req->getFilter('loginFilter');
		$list = [];

		if ($filter['status'] == 'error') {
			$status = 'error';
		}

		if ($status ==  'ok') {
			$user = new User();
			if ($user->checkAdmin($filter['id'])) {
				$list = $this->web_service->getUserList();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'api/user_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para guardar un usuario
	 *
	 * @param UserSaveDTO $data Datos del usuario a guardar
	 * @return void
	 */
	#[ORoute(
		'/save-user',
		filter: 'loginFilter'
	)]
	public function saveUser(UserSaveDTO $data): void {
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
