<?php
class Mail {
	private $CMS, $inbox;

	private $AllBox = [];

	private $box = 'INBOX';
	private $hostname, $host, $username, $password;

	function __construct($CMS) {
		$this->CMS = $CMS;

		$this->hostname = 'mail.floagg.com';
		$this->host = '{'.$this->hostname.'/imap/ssl/novalidate-cert}';
		$this->username = 'hello@floagg.com';
		$this->password = 'Hellojulie20';
	}

	public function setBox($string) {
		$this->box = $string;
	}

	public function allMailBox() { return $this->AllBox; }

	public function closeImap() { imap_close($this->inbox); }
	public function startImap() {
		$this->inbox = imap_open($this->host.$this->box, $this->username, $this->password) or die('Impossible de se connecter : ' . imap_last_error());

		$allBox = imap_list($this->inbox, $this->host, '*');
		foreach ($allBox as $key => $value) {
			$this->AllBox[] = str_replace($this->host, '', $value);
		}
	}

	public function get_part($inbox, $mail_number, $part, $encoding) {
		$data = imap_fetchbody($inbox, $mail_number, $part);
		switch ($encoding) {
			case 3: return imap_base64($data); // BASE64
			case 4: return imap_qprint($data); // Quoted-Printable
			default: return $data;
		}
	}

	private function parseMailPart($mail_number, $structure, $part_number = null) {
		$content = [
			'text' => (string) $structure->subtype,
			'html' => "",
			'attachments' => []
		];

		// Multipart
		if ($structure->type == 1 && isset($structure->parts)) {
			foreach ($structure->parts as $i => $subpart) {
				$num = $part_number ? $part_number . '.' . ($i + 1) : ($i + 1);
				$subContent = $this->parseMailPart($mail_number, $subpart, $num);

				// Fusionner le texte et le HTML
				$content['text'] .= $subContent['text'] ?? "";
				$content['html'] .= $subContent['html'] ?? "";
				if (!empty($subContent['attachments'])) {
					$content['attachments'] = array_merge($content['attachments'], $subContent['attachments']);
				}
			}
			return $content;
		}

		// Partie simple
		$data = $this->get_part($this->inbox, $mail_number, $part_number ?: 1, $structure->encoding);
		$subtype = strtolower($structure->subtype ?? 'plain');

		switch ($structure->type) {
			case 0: // texte
				$content['text'] = htmlentities($data);
				if ($subtype === 'html') $content['html'] = ($this->CMS->Dom->sanitize_email_html($data));
				break;

			case 3: // image
			case 5: // application
				$name = $structure->ifdparameters ? $structure->dparameters[0]->value : 'unknown';
				$content['attachments'][] = [
					'name' => $name,
					'type' => $subtype,
					'data' => base64_encode($data)
				];
				break;

			default:
				break;
		}

		return $content;
	}

	public function get() {
		$mails = imap_search($this->inbox, 'ALL');
		if(!$mails) return null;

		rsort($mails);
		foreach ($mails as $mail_number) {
			$overview = imap_fetch_overview($this->inbox, $mail_number, 0);
			$s  = imap_fetchbody($this->inbox, $mail_number, 1);

			$structure = imap_fetchstructure($this->inbox, $mail_number);
			$content = $this->parseMailPart($mail_number, $structure);

			/// INSERT TO BDD
			// Date
			$dateObj = date_create($overview[0]->date);
			$dateSql = $dateObj ? $dateObj->format("Y-m-d H:i:s") : null;

			if(
				count(
					$this->CMS->DataBase->execute('SELECT * FROM plug_mailbox WHERE uid = ? AND mailbox = ?', [$overview[0]->uid, $this->box])->fetchAll()
				) > 0
			) continue;

			$this->insertMailToDB(
				[
					$overview[0]->msgno,
					$overview[0]->uid,
					$this->box,
					addslashes($overview[0]->subject),
					addslashes($overview[0]->from),
					addslashes($overview[0]->to ?? ""),
					$dateSql,
					addslashes($overview[0]->message_id ?? ""),
					htmlentities(json_encode($this->CMS->OmsyString->utf8ize($content), JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR)),
					addslashes($overview[0]->references ?? ""),
					addslashes($overview[0]->in_reply_to ?? ""),
					$overview[0]->size,
					$overview[0]->recent ?? 0,
					$overview[0]->flagged ?? 0,
					$overview[0]->answered ?? 0,
					$overview[0]->deleted ?? 0,
					$overview[0]->seen ?? 0,
					$overview[0]->draft ?? 0
				]
			);
		}
	}

	public function stringClear($text) {
		$text = strip_tags($text);
		$text = preg_replace('/\s+/', ' ', $text);
		$text = preg_replace('/\{[^}]*\}/', '', $text);
		$text = trim($text);
		return $text;
	}
	public function stringConvert($string) {
		$decoded = imap_mime_header_decode($string);
		$final = "";
		foreach ($decoded as $part) $final .= $part->text;

		return $final;
	}


	//
	public function insertMailToDB($CONTENT) {
		echo 'insert';
		$this->CMS->DataBase->execute(
			'INSERT INTO plug_mailbox(msgno, uid, mailbox, subject, sender, recipient, mail_date, message_id, message_content, references_field, in_reply_to, size, recent, flagged, answered, deleted, seen, draft) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
			$CONTENT
		);
	}

	public function getAllMailDB() {
		return $this->CMS->DataBase->execute(
			'SELECT * FROM plug_mailbox WHERE mailbox = ? ORDER BY mail_date DESC LIMIT 12',
			[$this->box]
		)->fetchAll();
	}

	public function getMailDB($id) {
		return $this->CMS->DataBase->execute('SELECT * FROM plug_mailbox WHERE uid = ? AND mailbox = ?', [$id, $this->box])->fetchAll()[0] ?? null;
	}







	public function sendSmtpMail($to, $subject, $body, $from) {
		$newline = "\r\n";
		$timeout = 30;

		// Connexion en clair (port 25 recommandé)
		$smtp = fsockopen($this->hostname, 25, $errno, $errstr, $timeout);
		if (!$smtp) {
			die("Erreur connexion SMTP: $errstr ($errno)");
		}

		$response = fgets($smtp, 512);
		//echo "<<< $response";
		if (strpos($response, '220') !== 0) {
			die("Erreur SMTP (connexion): $response");
		}

		// Fonction helper
		$send = function($cmd, $expectedCodes = ['250']) use ($smtp, $newline) {
			fwrite($smtp, $cmd . $newline);
			//echo ">>> $cmd\n";

			$response = '';
			while ($line = fgets($smtp, 512)) {
				//echo "<<< $line";
				$response .= $line;
				// Fin de réponse quand ce n’est plus un "code-" (ex: "250-")
				if (preg_match('/^\d{3} /', $line)) {
					break;
				}
			}

			$code = substr($response, 0, 3);
			if (!in_array($code, $expectedCodes)) {
				die("Erreur SMTP après '$cmd': $response");
			}
			return $response;
		};


		// EHLO
		$send("EHLO localhost");

		// Auth en clair
		$send("AUTH LOGIN", ['334']);
		$send(base64_encode($this->username), ['334']);
		$send(base64_encode($this->password), ['235']);

		// MAIL FROM
		$send("MAIL FROM:<$from>");

		// RCPT TO
		//$send("RCPT TO:<$to>", ['250','251']);
		// On découpe les adresses séparées par virgule
		$recipients = array_map('trim', explode(',', $to));

		foreach ($recipients as $rcpt) {
		    $send("RCPT TO:<$rcpt>", ['250','251']);
		}

		// DATA
		$send("DATA", ['354']);

		// Entêtes
		$headers  = "From: <$from>" . $newline;
		$headers .= "To: <$to>" . $newline;
		$headers .= "Subject: $subject" . $newline;
		$headers .= "MIME-Version: 1.0" . $newline;
		$headers .= "Content-Type: text/html; charset=UTF-8" . $newline;
		$headers .= "Content-Transfer-Encoding: 8bit" . $newline;

		// Message complet
		$message = $headers . $newline . $body . $newline . "." . $newline;
		fwrite($smtp, $message);

		// Réponse après DATA
		$response = fgets($smtp, 512);
		//echo "<<< $response";
		if (strpos($response, '250') !== 0) {
			die("Erreur SMTP après DATA: $response");
		}

		// QUIT
		$send("QUIT", ['221']);

		fclose($smtp);

		imap_append($this->inbox, $this->host.'Sent', $message);
	}


}