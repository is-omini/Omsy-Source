<?php
class PLUGDate {
	private $CMS;

	public function __construct($CMS) {
		$this->CMS = $CMS;
	}

	public function humanizeDate($date_sql) {
        $dateN = new DateTime($date_sql);
        $date_sql = $dateN->format('Y-m-d');

        $aujourdhui = date('Y-m-d');
        $hier = date('Y-m-d', strtotime('-1 day'));
        $avantthier = date('Y-m-d', strtotime('-2 day'));

        if ($date_sql == $aujourdhui) {
            return "Aujourd'hui";
        } elseif ($date_sql == $hier) {
            return "Hier";
        } elseif ($date_sql == $avantthier) {
            return "Avant-Hier";
        }

        // Fallback sans Intl
        $mois = [
            1 => "janvier", 2 => "février", 3 => "mars", 4 => "avril",
            5 => "mai", 6 => "juin", 7 => "juillet", 8 => "août",
            9 => "septembre", 10 => "octobre", 11 => "novembre", 12 => "décembre"
        ];

        $jour = $dateN->format("d");
        $moisTexte = $mois[(int)$dateN->format("m")];
        $moisNumber = $dateN->format("m");
        $annee = $dateN->format("Y");

        return ucfirst("$jour $moisTexte");
    }

}