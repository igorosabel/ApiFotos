<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class UserSaveDTO implements ODTO{
	public ?int $id = null;
	public ?string $username = null;
	public ?string $name = null;
	public ?string $pass = null;
	public bool $is_admin = false;
	public ?int $id_token = null;

	public function isValid(): bool {
		return (
			!is_null($this->id) &&
			!is_null($this->username) &&
			!is_null($this->name) &&
			!is_null($this->pass) &&
			!is_null($this->id_token)
		);
	}

	public function load(ORequest $req): void {
		$this->id       = $req->getParamInt('id');
		$this->username = $req->getParamString('username');
		$this->name     = $req->getParamString('name');
		$this->pass     = $req->getParamString('pass');
		$this->is_admin = $req->getParamBool('isAdmin');

		$filter = $req->getFilter('Login');
		if ($filter['status'] !== 'error') {
			$this->id_token = $filter['id'];
		}
	}
}
