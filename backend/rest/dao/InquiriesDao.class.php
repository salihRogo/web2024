<?php

require_once __DIR__ . '/BaseDao.class.php';

class InquiriesDao extends BaseDao {
    public function __construct() {
        parent::__construct('inquiries');
    }

    public function add_inquiries($payload)
    { 
        return $this->insert("inquiries", $payload);
    }
}