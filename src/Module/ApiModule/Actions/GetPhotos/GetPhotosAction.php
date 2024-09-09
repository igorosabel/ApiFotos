<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\GetPhotos;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Component\Model\PhotoList\PhotoListComponent;

#[OModuleAction(
	url: '/get-photos',
	//filters: ['Login'],
	services: ['Web']
)]
class GetPhotosAction extends OAction {
	public string $status = 'ok';
	public int $pages = 0;
	public ?PhotoListComponent $list = null;

	/**
	 * Función para obtener la lista de fotos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$page = $req->getParamInt('page');
		$this->list = new PhotoListComponent(['list' => []]);

		if (is_null($page)) {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$this->pages = $this->service['Web']->getPhotosNumPages();
			$photo_list = $this->service['Web']->getPhotosList($page);
			$this->list->setValue('list', $photo_list);
		}
	}
}
