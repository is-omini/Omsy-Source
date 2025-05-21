<?php
class omsyRole {
	private $rolesString = [
		0=>'Visiteur',
		1=>'Redactor',
		2=>'Moderator',
		3=>'Administrator',
	];
	public function numberToString($int) {
		return $this->rolesString[$int] ?? $int;
	}
}