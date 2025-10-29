<?php
class Dom {
	private $doc;

	public function __construct() {}

	private function clearImage() {
		$imgs = $this->doc->getElementsByTagName('img');
		// itération arrière pour suppression possible
		for ($i = $imgs->length - 1; $i >= 0; $i--) {
			$img = $imgs->item($i);
			if (!$img) continue;

			$src = $img->getAttribute('src');
			$width = strtolower($img->getAttribute('width'));
			$height = strtolower($img->getAttribute('height'));
			$style = strtolower($img->getAttribute('style'));

			// détecter tracking pixel : width=1 & height=1 ou style contenant 1px
			$isPixel = false;
			if (($width !== "" && intval($width) === 1 && $height !== "" && intval($height) === 1)
				|| strpos($style, 'width:1px') !== false
				|| strpos($style, 'height:1px') !== false) {
				$isPixel = true;
			}

			// supprimer les images pixel
			if ($isPixel) {
				$img->parentNode->removeChild($img);
				continue;
			}

			// neutraliser le src distant : le déplacer dans data-original-src (pas de chargement)
			if ($src) {
				// garder les data:, base64 inline si tu veux autoriser — ici on neutralise tout ce qui commence par http
				if (preg_match('#^\s*https?://#i', $src)) {
					$img->setAttribute('data-original-src', $src);
					$img->removeAttribute('src');
					// ajouter un placeholder visuel (optionnel)
					$img->setAttribute('alt', $img->getAttribute('alt') ?: 'Image distante (chargement désactivé)');
				} else {
					// si src est data:base64, tu peux laisser ou enlever selon ta politique ; ici on laisse
				}
			}
		}
	}

	private function clearLink() {
		// 4) sécuriser les liens
		$links = $this->doc->getElementsByTagName('a');
		for ($i = $links->length - 1; $i >= 0; $i--) {
			$a = $links->item($i);
			if (!$a) continue;
			// ajouter rel+target pour éviter phishing/JS injections via opener
			$a->setAttribute('rel', 'noopener noreferrer nofollow');
			$a->setAttribute('target', '_blank');

			// neutraliser javascript: dans href si resté
			$href = $a->getAttribute('href');
			if ($href && preg_match('#^\s*(javascript|vbscript):#i', $href)) {
				$a->removeAttribute('href');
			}
		}
	}

	private function clearOn_() {
		// 2) nettoyer les attributs "on*" et javascript: dans href/src, et supprimer style
		$xpath = new DOMXPath($this->doc);
		foreach ($xpath->query('//*') as $node) {
			if (!$node->hasAttributes()) continue;
			$attrsToRemove = [];
			foreach (iterator_to_array($node->attributes) as $attr) {
				$name = strtolower($attr->nodeName);
				$val  = $attr->nodeValue;

				// supprimer attributs on*
				if (strpos($name, 'on') === 0) {
					$attrsToRemove[] = $name;
					continue;
				}

				// neutraliser javascript: et data URIs suspects dans href/src
				if (in_array($name, ['href','src','xlink:href'])) {
					$lower = trim(strtolower($val));
					if (strpos($lower, 'javascript:') === 0 || strpos($lower, 'vbscript:') === 0) {
						$attrsToRemove[] = $name;
						continue;
					}
					// si src/href externe -> keep but we'll neutraliser les images plus bas
				}

				// Option simple : supprimer style (évite CSS malveillant / tracking via width/height dans style)
				if ($name === 'style') {
					//$attrsToRemove[] = $name;
					continue;
				}
			}
			foreach ($attrsToRemove as $a) {
				$node->removeAttribute($a);
			}
		}
	}

	public function sanitize_email_html(string $html): string {
		// Préparer DOM
		libxml_use_internal_errors(true);
		$this->doc = new DOMDocument();
		// Encodage pour garder les caractères spéciaux
		$this->doc->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

		// 1) supprimer les balises dangereuses
		$dangerTags = ['script','iframe','object','embed','form','link','meta','style'];
		foreach ($dangerTags as $tag) {
			$nodes = $this->doc->getElementsByTagName($tag);
			// Comme getElementsByTagName est "live", on boucle en arrière
			for ($i = $nodes->length - 1; $i >= 0; $i--) {
				$node = $nodes->item($i);
				if ($node) $node->parentNode->removeChild($node);
			}
		}
		$this->clearOn_();

		// 3) traiter les images : neutraliser chargement automatique et supprimer petits pixels
		//$this->clearImage();

		// 4) sécuriser les liens
		$this->clearLink();

		// 5) retourner HTML nettoyé
		$body = '';
		foreach ($this->doc->getElementsByTagName('body') as $b) {
			$body = '';
			foreach ($b->childNodes as $child) {
				$body .= $this->doc->saveHTML($child);
			}
			break;
		}

		libxml_clear_errors();
		return $body ?: htmlentities($html);
	}
}