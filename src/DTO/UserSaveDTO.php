<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class UserSaveDTO implements ODTO{
	private ?int $id = null;
	private ?string $username = null;
	private ?string $name = null;
	private ?string $pass = null;
	private bool $is_admin = false;
	private ?int $id_token = null;

	public function getId(): ?int {
		return $this->id;
	}
	private function setId(?int $id): void {
		$this->id = $id;
	}
	public function getUsername(): ?string {
		return $this->username;
	}
	private function setUsername(?string $username): void {
		$this->username = $username;
	}
	public function getName(): ?string {
		return $this->name;
	}
	private function setName(?string $name): void {
		$this->name = $name;
	}
	public function getPass(): ?string {
		return $this->pass;
	}
	private function setPass(?string $pass): void {
		$this->pass = $pass;
	}
	public function getIsAdmin(): bool {
		return $this->is_admin;
	}
	private function setIsAdmin(bool $is_admin): void {
		$this->is_admin = $is_admin;
	}
	public function getIdToken(): ?int {
		return $this->id_token;
	}
	private function setIdToken(?int $id_token): void {
		$this->id_token = $id_token;
	}

	public function isValid(): bool {
		return (
			!is_null($this->getId()) &&
			!is_null($this->getUsername()) &&
			!is_null($this->getName()) &&
			!is_null($this->getPass()) &&
			!is_null($this->getIdToken())
		);
	}

	public function load(ORequest $req): void {
		$this->setId($req->getParamInt('id'));
		$this->setUsername($req->getParamString('username'));
		$this->setName($req->getParamString('name'));
		$this->setPass($req->getParamString('pass'));
		$this->setIsAdmin($req->getParamBool('isAdmin'));

		$filter = $req->getFilter('Login');
		if ($filter['status'] != 'error') {
			$this->setIdToken($filter['id']);
		}
	}
}
