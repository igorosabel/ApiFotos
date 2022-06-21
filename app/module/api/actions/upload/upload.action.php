<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\User;
use OsumiFramework\App\Model\Photo;

#[OModuleAction(
	url: '/upload',
	filter: 'login',
	services: ['web']
)]
class uploadAction extends OAction {
	/**
	 * Función para añadir una nueva foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$photo = $req->getParam('data');
		$id_user = $req->getParamInt('id');
		$filter = $req->getFilter('login');

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

				if ( $photo['exif'] != '' && $photo['exif'] != 'false') {
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
}
