<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\GetPhoto;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Photo;
use Osumi\OsumiFramework\App\Component\Model\Photo\PhotoComponent;

#[OModuleAction(
	url: '/get-photo',
	filters: ['Login']
)]
class GetPhotoAction extends OAction {
	public string $status = 'ok';
	public ?PhotoComponent $photo = null;

	/**
	 * Función para obtener los datos de una foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$id = $req->getParamInt('id');
		$this->photo = new PhotoComponent(['Photo' => null]);

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status == 'ok') {
			$p = new Photo();
			if ($p->find(['id' => $id])) {
				$this->photo->setValue('Photo', $p);
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
