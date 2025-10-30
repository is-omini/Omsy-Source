<?php
class Discord {
	// Temp ????
	private $botToken;
	private $guildId;

	private $discordAPi = "https://discord.com/api/v10/";

	function __construct($CMS) {
		$this->botToken = $CMS->Config->applications->discord->token;
	}

	public function addRole($userId, $roleId) {
		$this->submit("guilds/{$this->guildId}/members/$userId/roles/$roleId", "PUT");
	}

	public function removeRole($userId, $roleId) {
		$this->submit("guilds/{$this->guildId}/members/$userId/roles/$roleId", "DELETE");
	}

	public function getRole($userId, $roleId) {
		$r = $this->submit("guilds/{$this->guildId}/members/$userId", "GET");
		if(!isset($r->roles)) return false;
		if(in_array($roleId, $r->roles)) return true;
		return false;
	}

	public function sendMessage($channel, $message) {
		$r = $this->submit(
			"channels/$channel/messages",
			"POST",
			["Content-Type: application/json"],
			["content" => $message]
		);
		return false;
	}

	public function submit($url, $post, $head = [], $data = []) {
		$ch = curl_init($this->discordAPi.$url);
		$head = array_merge($head, ["Authorization: Bot $this->botToken"]);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $post); // "PUT" = ajouter un rôle

		if(isset($data) && is_array($data)) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response);
	}
}