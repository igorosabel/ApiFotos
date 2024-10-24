<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetPhoto;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Photo;
use Osumi\OsumiFramework\App\Component\Model\Photo\PhotoComponent;

class GetPhotoComponent extends OComponent {
	public string $status = 'ok';
	public ?PhotoComponent $photo = null;

	/**
	 * Función para obtener los datos de una foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');
		$this->photo = new PhotoComponent();

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$p = Photo::findOne(['id' => $id]);
			if (!is_null($p)) {
				$this->photo->photo = $p;
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
