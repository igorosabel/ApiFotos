<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Photo;
use OsumiFramework\App\Component\Api\PhotoItemComponent;

#[OModuleAction(
	url: '/get-photo',
	filters: ['login']
)]
class getPhotoAction extends OAction {
	/**
	 * Función para obtener los datos de una foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$photo_item_component = new PhotoItemComponent(['photo' => null]);

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$photo = new Photo();
			if ($photo->find(['id' => $id])) {
				$photo_item_component->setValue('photo', $photo);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('photo',  $photo_item_component);
	}
}
