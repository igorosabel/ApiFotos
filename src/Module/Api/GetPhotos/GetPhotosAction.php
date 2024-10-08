<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetPhotos;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Component\Model\PhotoList\PhotoListComponent;

class GetPhotosAction extends OAction {
	private ?WebService $ws = null;

	public string $status = 'ok';
	public int $pages = 0;
	public ?PhotoListComponent $list = null;

	public function __construct() {
		$this->ws = inject(WebService);
		$this->list = new PhotoListComponent(['list' => []]);
	}

	/**
	 * Función para obtener la lista de fotos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$page = $req->getParamInt('page');

		if (is_null($page)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->pages = $this->ws->getPhotosNumPages();
			$photo_list = $this->ws->getPhotosList($page);
			$this->list->setValue('list', $photo_list);
		}
	}
}
