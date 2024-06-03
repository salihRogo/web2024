<?php

require_once __DIR__ . '/../dao/NewsletterDao.class.php';

class NewsletterService {
    private $newsletters_dao;
    public function __construct() {
        $this->newsletters_dao = new NewsletterDao();
    }

    public function add_newsletters($payload) {
        return $this->newsletters_dao->add_newsletters($payload);
    }
}