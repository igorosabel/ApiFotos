<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\Plugins\OImage;
use Osumi\OsumiFramework\App\Model\Photo;
use Osumi\OsumiFramework\App\Model\Tag;
use Osumi\OsumiFramework\App\Model\User;

class WebService extends OService {
	/**
	 * Función para obtener la lista de fotos de una página indicada
	 *
	 * @param int $page Número de página a obtener
	 *
	 * @return array Lista de fotos
	 */
	public function getPhotosList(int $page): array {
		$lim = ($page - 1) * $this->getConfig()->getExtra('photos_per_page');
		return Photo::all([
			'order_by' => 'when#desc',
			'limit' => $lim . "#" . $this->getConfig()->getExtra('photos_per_page')
		]);
	}

	/**
	 * Obtiene el número de páginas de resultados de las fotos
	 *
	 * @return int Número de páginas de resultados
	 */
	public function getPhotosNumPages(): int {
		return intval( ceil( Photo::count() / $this->getConfig()->getExtra('photos_per_page')) );
	}

	/**
	 * Obtiene la lista completa de tags
	 */
	public function getTagList(): array {
		return Tag::all(['order_by' => 'tag#asc']);
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
		$ruta = $dir . $id . '.' . $ext;

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

		$thumb_route  = $this->getConfig()->getExtra('thumb') . $id . '.webp';
		$photo_route = $this->getConfig()->getExtra('photo') . $id . '.webp';

		// Guardo la imagen ya modificada como WebP
		$im->save($photo_route, IMAGETYPE_WEBP);

		// Creo el thumbnail
		$im->resizeToWidth(200);
		$im->save($thumb_route, $im->getImageType());

		// Borro la imagen temporal
		unlink($ruta);
	}

	/**
	 * Actualiza una lista de fotos con las tags indicadas
	 *
	 * @param array $list Lista de ids de fotos
	 *
	 * @param string $tags Lista de tags separadas por comas
	 *
	 * @return void
	 */
	public function updateTags(array $list, string $tags): void {
		$photo_tags = [];
		$db = new ODB();

		$tag_list = explode(',', $tags);
		foreach ($tag_list as $tag_item) {
			$tag_item = trim($tag_item);

			$sql = "SELECT * FROM `tag` WHERE `slug` LIKE '%" . OTools::slugify($tag_item) . "%'";
			$db->query($sql);
			if ($res = $db->next()) {
				$tag = Tag::from($res);
			}
			else {
				$tag = Tag::create();
				$tag->tag = $tag_item;
				$tag->slug = OTools::slugify($tag_item);
				$tag->save();
			}
			$photo_tags[] = $tag;
		}

		foreach  ($list as $item) {
			$photo = Photo::findOne(['id' => $item]);
			$photo->updateTags($photo_tags);
		}
	}

	/**
	 * Rota una imagen de modo que su orientación quede correcta
	 *
	 * @param Photo $p Foto a rotar
	 *
	 * @param int $orientation Valor EXIF del campo Orientation para saber en que sentido debe rotarse
	 *
	 * @return void
	 */
	public function rotateImage(Photo $p, int $orientation): void {
		$thumb = new OImage();
		$thumb->load( $p->getThumbRoute() );
		$photo = new OImage();
		$photo->load( $p->getPhotoRoute() );

		// Orientation 6 -> Rotar 270
		if ($orientation == 6) {
			$thumb->rotate(270);
			$thumb->save($p->getThumbRoute(), $thumb->getImageType());
			$photo->rotate(270);
			$photo->save($p->getPhotoRoute(), $photo->getImageType());
		}
		// Orientation 3 -> Rotar 180
		if ($orientation == 3) {
			$thumb->rotate(180);
			$thumb->save($p->getThumbRoute(), $thumb->getImageType());
			$photo->rotate(180);
			$photo->save($p->getPhotoRoute(), $photo->getImageType());
		}
	}

	/**
	 * Obtiene la lista de usuarios
	 *
	 * @return array Lista de usuarios
	 */
	public function getUserList(): array {
		return User::all(['order_by' => 'id']);
	}
}
