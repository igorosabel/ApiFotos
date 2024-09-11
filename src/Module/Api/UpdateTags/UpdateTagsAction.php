<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\UpdateTags;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;

class UpdateTagsAction extends OAction {
	public string $status = 'ok';

	/**
	 * Función para actualizar las etiquetas de una serie de fotos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$list = $req->getParam('list');
		$tags = $req->getParamString('tags');

		if (is_null($list) || is_null($tags)) {
			$this->status = 'error';
		}

		if ($this->status == 'ok') {
			$this->service['Web']->updateTags($list, $tags);
		}
	}
}
