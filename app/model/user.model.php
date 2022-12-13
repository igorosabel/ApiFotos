<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class User extends OModel{
	/**
	 * Configures current model object based on data-base table structure
	 */
	 function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada usuario'
			),
			new OModelField(
				name: 'username',
				type: OMODEL_TEXT,
				size: 50,
				comment: 'Nombre de usuario',
				nullable: false
			),
			new OModelField(
				name: 'pass',
				type: OMODEL_TEXT,
				size: 255,
				comment: 'Contraseña cifrada del usuario',
				nullable: false
			),
			new OModelField(
				name: 'name',
				type: OMODEL_TEXT,
				size: 50,
				comment: 'Nombre real del usuario',
				nullable: false
			),
			new OModelField(
				name: 'is_admin',
				type: OMODEL_BOOL,
				comment: 'Indica si el usuario es un administrador',
				nullable: false,
				default: false
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

	/**
	 * Devuelve el nombre del usuario
	 */
	public function __toString(){
		return $this->get('name');
	}

	/**
	 * Comprueba el inicio de sesión de un usuario
	 *
	 * @param string $username Nombre de usuario
	 *
	 * @param string $pass Contraseña introducida por el usuario
	 *
	 * @return bool Comprobación de contraseña correcta o incorrecta
	 */
	public function login(string $username, string $pass): bool {
		if ($this->find(['username'=>$username])) {
			if (password_verify($pass, $this->get('pass'))) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Busca el usuario indicado y comprueba si es un admin
	 *
	 * @param int $id_user Id del usuario a comprobar
	 *
	 * @return bool Devuelve si el usuario es admin o no
	 */
	public function checkAdmin(int $id_user): bool {
		if ($this->find(['id' => $id_user])) {
			if ($this->get('is_admin')) {
				return true;
			}
		}
		return false;
	}
}
