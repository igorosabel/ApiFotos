<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class User extends OModel{
	#[OPK(
	  comment: 'Id único de cada usuario'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Nombre de usuario',
	  nullable: false,
	  max: 50
	)]
	public ?string $username;

	#[OField(
	  comment: 'Contraseña cifrada del usuario',
	  nullable: false,
	  max: 255
	)]
	public ?string $pass;

	#[OField(
	  comment: 'Nombre real del usuario',
	  nullable: false,
	  max: 50
	)]
	public ?string $name;

	#[OField(
	  comment: 'Indica si el usuario es un administrador',
	  nullable: false,
	  default: false
	)]
	public ?bool $is_admin;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Devuelve el nombre del usuario
	 */
	public function __toString(){
		return $this->name;
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
		if ($this->find(['username' => $username])) {
			if (password_verify($pass, $this->pass)) {
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
		$user = User::findOne(['id' => $id_user]);
		if (!is_null($user) && $user->is_admin) {
			return true;
		}
		return false;
	}
}
