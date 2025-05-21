<?php
class omsyBlog {
    private $app_id = null;
    private $monday = [
        ['num' => 1, 'name' => "janvier", 'day' => 31],
        ['num' => 2, 'name' => "février", 'day' => 31],
        ['num' => 3, 'name' => "mars", 'day' => 31],
        ['num' => 4, 'name' => "avril", 'day' => 31],
        ['num' => 5, 'name' => "mai", 'day' => 31],
        ['num' => 6, 'name' => "juin", 'day' => 31],
        ['num' => 7, 'name' => "juillet", 'day' => 31],
        ['num' => 8, 'name' => "août", 'day' => 31],
        ['num' => 9, 'name' => "septembre", 'day' => 31],
        ['num' => 10, 'name' => "octobre", 'day' => 31],
        ['num' => 11, 'name' => "novembre", 'day' => 31],
        ['num' => 12, 'name' => "décembre", 'day' => 31]
    ];

    function __construct() {
        $this->app_id = $_GET['app_id'];
    }

    public function markdown_parse($string) {
        return nl2br($string);
    }

    public function getAllPost($int = 12) {
        $blogs = CMS->DataBase->execute('SELECT * FROM exemple_posts ORDER BY ID DESC LIMIT '.$int)->fetchAll();
        $blogsGroup = CMS->DataBase->execute('SELECT * FROM exemple_groups')->fetchAll();

        foreach ($blogs as $key => $value) {
            $blogs[$key]['tag'] = $blogsGroup[$value['group_id']-1]['slug_name'];
            $blogs[$key]['date_string'] = $value['date_time'];
            $blogs[$key]['link'] = '/blog/'.$blogs[$key]['tag'].'/'.$value['slug_name'];
        }
        return $blogs;
    }

    public function getAllCategorie() {
        $blogs = CMS->DataBase->execute('SELECT * FROM exemple_groups WHERE home_page = 1 ORDER BY ID DESC')->fetchAll();

        foreach ($blogs as $key => $value) {
            $blogs[$key]['link'] = '/blog/'.$blogs[$key]['slug_name'].'/';
        }
        return $blogs;
    }

    public function getCategorie($ID) {
        $blogs = CMS->DataBase->execute('SELECT * FROM exemple_groups WHERE ID = ?', [$ID])->fetch();

        return $blogs;
    }

    public function getCategoriPost($string, $limite = 12) {
        $blogs = CMS->DataBase->execute('SELECT * FROM exemple_posts WHERE group_id = ? ORDER BY ID DESC LIMIT '.intval($limite), [$string])->fetchAll();
        $blogsGroup = CMS->DataBase->execute('SELECT * FROM exemple_groups')->fetchAll();

        foreach ($blogs as $key => $value) {
            $blogs[$key]['tag'] = $blogsGroup[$value['group_id']-1]['slug_name'];
            $blogs[$key]['date_string'] = $value['date_time'];
            $blogs[$key]['link'] = '/blog/'.$blogs[$key]['tag'].'/'.$value['slug_name'];
        }
        return $blogs;
    }

    public function getPost() {
        $db = CMS->DataBase->execute('SELECT * FROM exemple_groups WHERE slug_name = ?', [$groups])->fetch();
        $db2 = CMS->DataBase->execute('SELECT * FROM exemple_posts WHERE slug_name = ? AND group_id = ?', [$postName, $db->ID])->fetch();
    }

    public function convertirDateSQL($date_sql) {
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

    public function getPlaylistByName(string $id) {
        $getInfo = CMS->DataBase->execute('SELECT * FROM blog_playlist WHERE name = ?', [$id])->fetchAll()[0];
        $getAllPost  = CMS->DataBase->execute('SELECT * FROM blog_playlist_content WHERE playlist_id = ?', [$getInfo['ID']])->fetchAll();
        $buff = [];
        foreach($getAllPost as $value) {
            $buff[] = CMS->DataBase->execute('SELECT * FROM blog_single WHERE ID = ?', [$value['post_id']])->fetchAll()[0];
        }

        return $buff;
    }

    public function getPostByTerm(string $term, string $val) {
        return CMS->DataBase->execute("SELECT * FROM blog_single WHERE $term = ?", [$val])->fetchAll();
    }

    public function allGroup() {
        $get = CMS->DataBase->execute('SELECT * FROM blog_group WHERE parent_id = 0')->fetchAll();

        foreach($get as $key => $parent) {
            $get[$key] = $parent;
            $get[$key]['kids'] = CMS->DataBase->execute('SELECT * FROM blog_group WHERE parent_id = ?', [$parent['ID']])->fetchAll();
        }

        return $get;
    }
}
