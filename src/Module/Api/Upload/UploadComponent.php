<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Upload;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\User;
use Osumi\OsumiFramework\App\Model\Photo;
use Osumi\OsumiFramework\App\Service\WebService;

class UploadComponent extends OComponent {
	private ?WebService $ws = null;

	public string $status = 'ok';
	public string | int $id = 'null';

	public function __construct() {
    parent::__construct();
		$this->ws = inject(WebService::class);
	}

	/**
	 * Función para añadir una nueva foto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$photo = $req->getParam('data');
		$id_user = $req->getParamInt('id');
		$filter = $req->getFilter('Login');

		if (is_null($photo) || is_null($id_user) || $filter['status'] === 'error') {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$user = User::create();
			if ($user->checkAdmin($filter['id'])) {
				$this->getLog()->info(var_export($photo['date'], true));
				$this->getLog()->info(var_export($photo['exif'], true));
				$p = Photo::create();
				$p->id_user = $id_user;
				$p->when    = $photo['date'];
				$p->exif    = $photo['exif'];
				$p->save();

				$this->id = $p->id;

				$this->ws->saveNewImage($photo['src'], $this->id);

				if ( $photo['exif'] !== '' && $photo['exif'] !== 'false') {
					$exif_data = json_decode($photo['exif'], true);
					if (array_key_exists('Orientation', $exif_data) && $exif_data['Orientation'] !== 1) {
						$this->ws->rotateImage($p, $exif_data['Orientation']);
					}
				}
			}
			else {
				$this->status =  'error';
			}
		}
	}
}
