<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Api\PhotoListComponent;

#[OModuleAction(
	url: '/get-photos',
	//filters: ['login'],
	services: ['web']
)]
class getPhotosAction extends OAction {
	/**
	 * Función para obtener la lista de fotos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$page = $req->getParamInt('page');
		$pages = 0;
		$photo_list_component = new PhotoListComponent(['list' => []]);

		if (is_null($page)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$list = $this->web_service->getPhotosList($page);
			$pages = $this->web_service->getPhotosNumPages();
			$photo_list_component->setValue('list', $list);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('pages', $pages);
		$this->getTemplate()->add('list', $photo_list_component);
	}
}
