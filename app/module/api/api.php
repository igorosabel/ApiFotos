<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Service\webService;
use OsumiFramework\OFW\Plugins\OToken;
use OsumiFramework\App\Model\User;
use OsumiFramework\App\Model\Photo;

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
	#[ORoute('/get-photos')]
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
	#[ORoute('/get-tags')]
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
	#[ORoute('/upload')]
	public function upload(ORequest $req): void {
		$status = 'ok';
		$photo = $req->getParam('data');
		$id_user = $req->getParamInt('id');

		$id = null;

		if (is_null($photo) || is_null($id_user)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$p = new Photo();
			$p->set('id_user', $id_user);
			$p->set('when', $photo['date']);
			$p->save();

			$id = $p->get('id');

			$this->web_service->saveNewImage($photo['src'], $id);
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
	#[ORoute('/update-tags')]
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
	#[ORoute('/get-photo')]
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
}
