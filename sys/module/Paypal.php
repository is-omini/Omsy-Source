<?php
/**
 * 
 */
class Paypal
{
	private $client, $secret, $token;

	function __construct($CMS)
	{
		$this->client = $CMS->Config->applications->paypal->client;
		$this->secret = $CMS->Config->applications->paypal->secret;
	}

	public function scripts() {
		return '<script src="https://www.paypal.com/sdk/js?client-id=Af-hvjx-2GpWK6P_H5lW_vU2-kn4Gd2WGMyOZep98VL9zMpc3xeHnxeA_Udg82VBniWWQ6TV-HDJP-aX&currency=EUR"></script>'.'<script src="/share/lib/paypal/paypal.js"></script>';
	}

	private function obtPaypalToken() {
		// Obtenir un token
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->client . ":" . $this->secret);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		curl_setopt($ch, CURLOPT_POST, true);

		$response = curl_exec($ch);
		$this->token = json_decode($response)->access_token;
		curl_close($ch);
	}

	public function capture($orderId) {
		if (!$orderId) {
			http_response_code(400);
			echo json_encode(["error" => "Order ID manquant"]);
			exit;
		}

		// Obtenir un token
		$this->obtPaypalToken();
		/*
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $secret);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		curl_setopt($ch, CURLOPT_POST, true);

		$response = curl_exec($ch);
		$token = json_decode($response)->access_token;
		curl_close($ch);
		*/

		// Capturer le paiement
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderId/capture");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
		    "Content-Type: application/json",
		    "Authorization: Bearer $this->token"
		]);

		$response = curl_exec($ch);
		curl_close($ch);

		header('Content-Type: application/json');
		echo $response;
	}

	public function checkout() {
		// Obtenir un access token
		$this->obtPaypalToken();
		/*
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->client . ":" . $this->secret);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		curl_setopt($ch, CURLOPT_POST, true);

		$response = curl_exec($ch);
		$token = json_decode($response)->access_token;
		curl_close($ch);
		*/

		// Créer une commande
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Content-Type: application/json",
			"Authorization: Bearer $this->token"
		]);

		$data = [
			"intent" => "CAPTURE",
			"purchase_units" => [[
				"amount" => [
					"currency_code" => "EUR",
					"value" => $_GET['shoppingCart']
				]
			]]
		];

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$response = curl_exec($ch);
		curl_close($ch);

		header('Content-Type: application/json');
		echo $response;
	}

	public function success($orderId) {
		if (!$orderId) {
			die("Erreur : aucun orderId reçu");
		}

		// 1. Obtenir un token
		$this->obtPaypalToken();
		/* $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->client . ":" . $this->secret);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		curl_setopt($ch, CURLOPT_POST, true);

		$response = curl_exec($ch);
		$token = json_decode($response)->access_token;
		curl_close($ch); */


		// 2. Capturer le paiement
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderId/capture");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Content-Type: application/json",
			"Authorization: Bearer $this->token"
		]);

		$response = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($response, true);


		// Vérification
		echo json_encode($result);
		/* if (!empty($result['status']) && $result['status'] === "COMPLETED") {
			print_r($result);
		} else {
			echo "<h1>Erreur paiement ❌</h1>";
			print_r($result);
		} */

	}
}