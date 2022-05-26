<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Tag extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única para cada tag'
			],
			'tag' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Texto de la tag',
				'nullable' => false
			],
			'slug' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Slug del texto de la tag',
				'nullable' => false
			],
			'created_at' => [
				'type'    => OModel::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OModel::UPDATED,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($model);
	}

	/**
	 * Devuelve el texto de la tag
	 */
	public function __toString() {
		return $this->get('tag');
	}

	private ?array $photos = null;

	/**
	 * Devuelve la lista de fotos que tienen una tag
	 *
	 * @return array Lista de fotos
	 */
	public function getPhotos(): array {
		if (is_null($this->photos)){
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
		$sql = "SELECT * FROM `photo` WHERE `id` IN (SELECT `id_photo` FROM `photo_tag` WHERE `id_tag` = ?) ORDER BY `updated_at` DESC";
		$this->db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$this->db->next()) {
			$photo = new Photo();
			$photo->update($res);

			array_push($list, $photo);
		}

		$this->setPhotos($list);
	}
}
