<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;

#[OModuleAction(
	url: '/update-tags',
	filters: ['login'],
	services: ['web']
)]
class updateTagsAction extends OAction {
	/**
	 * Función para actualizar las etiquetas de una serie de fotos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
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
}
