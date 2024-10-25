<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\UpdateTags;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;

class UpdateTagsComponent extends OComponent {
	private ?WebService $ws = null;

	public string $status = 'ok';

	public function __construct() {
    parent::__construct();
		$this->ws = inject(WebService::class);
	}

	/**
	 * Función para actualizar las etiquetas de una serie de fotos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$list = $req->getParam('list');
		$tags = $req->getParamString('tags');

		if (is_null($list) || is_null($tags)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->ws->updateTags($list, $tags);
		}
	}
}
