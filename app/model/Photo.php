<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Photo extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name = 'photo';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única de cada foto'
			],
			'id_user' => [
				'type'     => OModel::NUM,
				'comment'  => 'Id del usuario que añade la foto',
				'nullable' => false,
				'ref'      => 'user.id'
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

		parent::load($table_name, $model);
	}

	/**
	 * Obtiene la ruta al thumbnail
	 *
	 * @return string URL del thumbnail de la foto
	 */
	public function getThumbUrl(): string {
		global $core;
		return $core->config->getUrl('base').'thumb/'.$this->get('id').'.webp';
	}

	/**
	 * Obtiene la ruta a la foto
	 *
	 * @return string URL de la foto
	 */
	public function getPhotoUrl(): string {
		global $core;
		return $core->config->getUrl('base').'photo/'.$this->get('id').'.webp';
	}

	/**
	 * Borra una foto de la base de datos, su thumbnail y la foto
	 *
	 * @return void
	 */
	public function deleteFull(): void{
		global $core;
		$thumb_route  = $core->config->getExtra('thumb').$this->get('id').'.webp';
		$photo_route = $core->config->getExtra('photo').$this->get('id').'.webp';

		if (file_exists($thumb_route)) {
			unlink($thumb_route);
		}
		if (file_exists($photo_route)) {
			unlink($photo_route);
		}

		$this->delete();
	}

	private ?array $tags = null;

	/**
	 * Devuelve la lista de tags de una foto
	 *
	 * @return array Lista de tags
	 */
	public function getTags(): array {
		if (is_null($this->tags)){
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
		$sql = "SELECT * FROM `tag` WHERE `id` IN (SELECT `id_tag` FROM `photo_tag` WHERE `id_photo` = ?) ORDER BY `tag` ASC";
		$this->db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$this->db->next()) {
			$tag = new Tag();
			$tag->update($res);

			array_push($list, $tag);
		}

		$this->setTags($list);
	}
}
