<?php
defined('BASEPATH') or exit('No direct access to script allowed');

$data = isset($data)? $data: array();

echo json_encode($data);