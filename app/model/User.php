<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class User extends OModel{
	/**
	 * Configures current model object based on data-base table structure
	 */
	 function __construct() {
		$table_name = 'user';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada usuario'
			],
			'username' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Nombre de usuario',
				'nullable' => false
			],
			'pass' => [
				'type'     => OModel::TEXT,
				'size'     => 255,
				'comment'  => 'Contraseña cifrada del usuario',
				'nullable' => false
			],
			'name' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Nombre real del usuario',
				'nullable' => false
			],
			'is_admin' => [
				'type'     => OModel::BOOL,
				'comment'  => 'Indica si el usuario es un administrador',
				'nullable' => false,
				'default'  => false
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
}
