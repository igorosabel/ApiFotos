<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\Photo;
use OsumiFramework\App\Model\Tag;
use OsumiFramework\OFW\Plugins\OImage;

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

	/**
	 * Obtener la extensión de una foto en formato Base64
	 *
	 * @param string $data Imagen en formato Base64
	 *
	 * @return string Extensión de la imagen
	 */
	public function getFotoExt(string $data): string {
		$arr_data = explode(';', $data);
		$arr_data = explode(':', $arr_data[0]);
		$arr_data = explode('/', $arr_data[1]);

		return $arr_data[1];
	}

	/**
	 * Guarda una imagen en Base64 en la ubicación indicada
	 *
	 * @param string $dir Ruta en la que guardar la imagen
	 *
	 * @param string $base64_string Imagen en formato Base64
	 *
	 * @param int $id Id de la imagen
	 *
	 * @param string $ext Extensión del archivo de imagen
	 *
	 * @return string Devuelve la ruta completa a la nueva imagen
	 */
	public function saveImage(string $dir, string $base64_string, int $id, string $ext): string {
		$ruta = $dir.$id.'.'.$ext;

		if (file_exists($ruta)) {
			unlink($ruta);
		}

		$ifp = fopen($ruta, "wb");
		$data = explode(',', $base64_string);
		fwrite($ifp, base64_decode($data[1]));
		fclose($ifp);

		return $ruta;
	}

	/**
	 * Guarda una imagen en Base64 para una categoría. Si no tiene formato WebP se convierte
	 *
	 * @param string $base64_string Imagen en formato Base64
	 *
	 * @param int $id Id de la imagen
	 *
	 * @return void
	 */
	public function saveNewImage(string $base64_string, int $id): void {
		$ext = $this->getFotoExt($base64_string);
		$ruta = $this->saveImage($this->getConfig()->getDir('ofw_tmp'), $base64_string, $id, $ext);
		$im = new OImage();
		$im->load($ruta);

		// Compruebo tamaño inicial
		if ($im->getWidth() > 400 || $im->getHeight() > 600) {
			$im->resizeToWidth(800);
			$im->save($ruta, $im->getImageType());
		}

		$thumb_route  = $this->getConfig()->getExtra('thumb').$id.'.webp';
		$photo_route = $this->getConfig()->getExtra('photo').$id.'.webp';

		// Guardo la imagen ya modificada como WebP
		$im->save($photo_route, IMAGETYPE_WEBP);

		// Creo el thumbnail
		$im->resizeToWidth(200);
		$im->save($thumb_route, $im->getImageType());

		// Borro la imagen temporal
		unlink($ruta);
	}
}
