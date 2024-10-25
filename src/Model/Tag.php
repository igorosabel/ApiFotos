<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;

class Tag extends OModel {
	#[OPK(
	  comment: 'Id única para cada tag'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Texto de la tag',
	  nullable: false,
	  max: 50
	)]
	public ?string $tag;

	#[OField(
	  comment: 'Slug del texto de la tag',
	  nullable: false,
	  max: 50
	)]
	public ?string $slug;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Devuelve el texto de la tag
	 */
	public function __toString() {
		return $this->tag;
	}

	/**
	 * Lista de fotos que tienen una tag
	 */
	private ?array $photos = null;

	/**
	 * Devuelve la lista de fotos que tienen una tag
	 *
	 * @return array Lista de fotos
	 */
	public function getPhotos(): array {
		if (is_null($this->photos)) {
			$this->loadPhotos();
		}
		return $this->photos;
	}

	/**
	 * Guarda la lista de fotos que tienen una tag
	 *
	 * @param array $photos Lista de fotos
	 *
	 * @return void
	 */
	public function setPhotos(array $photos): void {
		$this->photos = $photos;
	}

	/**
	 * Carga la lista de fotos que tienen una tag
	 *
	 * @return void
	 */
	public function loadPhotos(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `photo` WHERE `id` IN (SELECT `id_photo` FROM `photo_tag` WHERE `id_tag` = ?) ORDER BY `updated_at` DESC";
		$this->db->query($sql, [$this->id]);
		$list = [];

		while ($res = $this->db->next()) {
			$photo = Photo::from($res);

			$list[] = $photo;
		}

		$this->setPhotos($list);
	}
}
