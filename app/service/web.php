<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\Photo;
use OsumiFramework\App\Model\Tag;

class webService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Función para obtener la lista de fotos de una página indicada
	 *
	 * @param int $page Número de página a obtener
	 *
	 * @return array Lista de fotos
	 */
	public function getPhotosList(int $page): array {
		$ret = [];
		$db = new ODB();
		$lim = ($page - 1) * $this->getConfig()->getExtra('photos_per_page');

		$sql = "SELECT * FROM `photo` ORDER BY `updated_at` DESC LIMIT ".$lim.",".$this->getConfig()->getExtra('photos_per_page');
		$db->query($sql);
		$ret = [];

		while ($res = $db->next()) {
			$photo = new Photo();
			$photo->update($res);

			array_push($ret, $photo);
		}

		return $ret;
	}

	/**
	 * Obtiene el número de páginas de resultados de las fotos
	 *
	 * @return int Número de páginas de resultados
	 */
	public function getPhotosNumPages(): int {
		$db = new ODB();
		$c  = $this->getConfig();

		$sql = "SELECT COUNT(*) AS `num` FROM `photo`";
		$db->query($sql);
		$res = $db->next();

		return intval( ceil( (int)$res['num'] / $this->getConfig()->getExtra('photos_per_page')) );
	}

	/**
	 * Obtiene la lista completa de tags
	 */
	public function getTagList(): array {
		$ret = [];
		$db = new ODB();

		$sql = "SELECT * FROM `tag` ORDER BY `tag` ASC";
		$db->query($sql);
		$ret = [];

		while ($res = $db->next()) {
			$tag = new Tag();
			$tag->update($res);

			array_push($ret, $tag);
		}

		return $ret;
	}

}
