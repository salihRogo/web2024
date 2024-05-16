<?php

require_once __DIR__ . '/../dao/InquiriesDao.class.php';

class InquiriesService {
    private $inquiries_dao;
    public function __construct() {
        $this->inquiries_dao = new InquiriesDao();
    }

    public function add_inquiries($payload)
    {
        return $this->inquiries_dao->add_inquiries($payload);
    }
}