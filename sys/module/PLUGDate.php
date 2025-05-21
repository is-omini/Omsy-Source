<?php
class PLUGDate {
	private $CMS;

	public function __construct($CMS) {
		$this->CMS = $CMS;
	}

	public function humanizeDate($date_sql) {
        $dateN = new DateTime($date_sql);
        $date_sql = $dateN->format('Y-m-d');

        // Définition de la date actuelle et de hier
        $aujourdhui = date('Y-m-d');
        $hier = date('Y-m-d', strtotime('-1 day'));

        if ($date_sql == $aujourdhui) {
            return "Aujourd'hui";
        } elseif ($date_sql == $hier) {
            return "Hier";
        }

        // Création d'un objet DateTime
        $date = new DateTime($date_sql);

        // Création du formateur de date en français
        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'Europe/Paris',
            IntlDateFormatter::GREGORIAN,
            'dd MMMM yyyy' // Format : 02 février 2025
        );

        return ucfirst($formatter->format($date));
    }
}