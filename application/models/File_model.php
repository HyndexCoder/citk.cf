<?php
defined('BASEPATH') or exit('No direct script access allowed');

class File_model extends MY_model{
    protected $_tablename = 'files';

    public function __construct(){
        parent::__construct();

        $this->load->database();
    }

    public function get_by_note($note_id){
        return $this->db->get_where($this->_tablename, array('note_id' => $note_id) )->result_array();
    }

    public function add($note_id, $file_name){
        $result = $this->db->insert($this->_tablename, array(
            'name'=>$file_name,
            'note_id'=>$note_id
        ));

        easy_log('file', $note_id.'___'.$file_name, 'Uploaded', $result, $this);

        return $result;
    }

};