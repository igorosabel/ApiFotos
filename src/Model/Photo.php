<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;

class Photo extends OModel {
	#[OPK(
	  comment: 'Id única de cada foto'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del usuario que añade la foto',
	  nullable: false,
	  ref: 'user.id'
	)]
	public ?int $id_user;

	#[OField(
	  comment: 'Fecha de la foto',
	  nullable: false,
	  type: OField::DATE
	)]
	public ?string $when;

	#[OField(
	  comment: 'Datos EXIF de la foto',
	  nullable: true,
	  type: OField::LONGTEXT
	)]
	public ?string $exif;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Obtiene la ruta al thumbnail
	 *
	 * @return string URL del thumbnail de la foto
	 */
	public function getThumbUrl(): string {
		global $core;
		return $core->config->getUrl('base') . 'thumb/' . $this->id . '.webp';
	}

	/**
	 * Obtiene la ruta a la foto
	 *
	 * @return string URL de la foto
	 */
	public function getPhotoUrl(): string {
		global $core;
		return $core->config->getUrl('base') . 'photo/' . $this->id . '.webp';
	}

	/**
	 * Obtiene la ruta al archivo físico del thumbnail
	 *
	 * @return string Ruta al archivo del thumbnail
	 */
	public function getThumbRoute(): string {
		global $core;
		return $core->config->getExtra('thumb') . $this->id . '.webp';
	}

	/**
	 * Obtiene la ruta al archivo físico de la foto
	 *
	 * @return string Ruta al archivo de la foto
	 */
	public function getPhotoRoute(): string {
		global $core;
		return $core->config->getExtra('photo') . $this->id . '.webp';
	}

	/**
	 * Borra una foto de la base de datos, su thumbnail y la foto
	 *
	 * @return void
	 */
	public function deleteFull(): void{
		$thumb_route = $this->getThumbRoute();
		$photo_route = $this->getPhotoRoute();

		if (file_exists($thumb_route)) {
			unlink($thumb_route);
		}
		if (file_exists($photo_route)) {
			unlink($photo_route);
		}

		$this->delete();
	}

	/**
	 * Lista de tags de una foto
	 */
	private ?array $tags = null;

	/**
	 * Devuelve la lista de tags de una foto
	 *
	 * @return array Lista de tags
	 */
	public function getTags(): array {
		if (is_null($this->tags)) {
			$this->loadTags();
		}
		return $this->tags;
	}

	/**
	 * Guarda la lista de tags de una foto
	 *
	 * @param array $tags Lista de tags
	 *
	 * @return void
	 */
	public function setTags(array $tags): void {
		$this->tags = $tags;
	}

	/**
	 * Carga la lista de tags de una foto
	 *
	 * @return void
	 */
	public function loadTags(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `tag` WHERE `id` IN (SELECT `id_tag` FROM `photo_tag` WHERE `id_photo` = ?) ORDER BY `tag` ASC";
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res = $db->next()) {
			$tag = Tag::from($res);

			$list[] = $tag;
		}

		$this->setTags($list);
	}

	/**
	 * Función para actualizar las tags de una foto
	 *
	 * @param array $tags Tags de la foto
	 *
	 * @return void
	 */
	public function updateTags(array $tags): void {
		$db = new ODB();
		$sql = "DELETE FROM `photo_tag` WHERE `id_photo` = ?";
		$db->query($sql, [$this->id]);

		foreach  ($tags as $tag) {
			$photo_tag = PhotoTag::create();
			$photo_tag->id_photo = $this->id;
			$photo_tag->id_tag   = $tag->id;
			$photo_tag->save();
		}
	}
}
