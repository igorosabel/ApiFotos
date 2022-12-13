<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class PhotoTag extends OModel{
	/**
	 * Configures current model object based on data-base table structure
	 */
	 function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_photo',
				type: OMODEL_PK,
				comment: 'Id de la foto',
				incr: false
			),
			new OModelField(
				name: 'id_tag',
				type: OMODEL_PK,
				comment: 'Id de la tag',
				incr: false
			),
			new OModelField(
				name: 'created_at',
				type: OMODEL_CREATED,
				comment: 'Fecha de creación del registro'
			),
			new OModelField(
				name: 'updated_at',
				type: OMODEL_UPDATED,
				comment: 'Fecha de última modificación del registro'
			)
		);

		parent::load($model);
	}
}
