<?php

require_once __DIR__ . '/../dao/NewsletterDao.class.php';

class NewsletterService {
    private $newsletter_dao;
    public function __construct() {
        $this->newsletter_dao = new NewsletterDao();
    }
}