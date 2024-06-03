<?php

require_once __DIR__ . '/BaseDao.class.php';

class NewsletterDao extends BaseDao {
    public function __construct() {
        parent::__construct('newsletters');
    }

    public function add_newsletters($payload)
    {
        return $this->insert("newsletters", $payload);
    }
}