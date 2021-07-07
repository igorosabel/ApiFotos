<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class PhotoTag extends OModel{
	/**
	 * Configures current model object based on data-base table structure
	 */
	 function __construct() {
		$table_name = 'photo_tag';
		$model = [
			'id_photo' => [
				'type'    => OModel::PK,
				'comment' => 'Id de la foto'
			],
			'id_tag' => [
				'type'    => OModel::PK,
				'comment' => 'Id de la tag'
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
}
