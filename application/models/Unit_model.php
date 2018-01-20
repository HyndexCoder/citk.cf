<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit_model extends MY_model{
    protected $_tablename = 'units';

    public function __construct(){
        parent::__construct();

        $this->load->database();
    }

    public function get($id, $notes = false, $limit = 10, $offset = 0, $parse_branch = true){
        $unit =  $this->db->get_where($this->_tablename, array('id'=>$id))->row_array();

        if(count($unit) < 1){//If unit doesn't exists
            return $unit;
        }

        if(!$notes){
            return $unit;
        }

        $this->load->model('note_model');
        
        $data = $this->note_model->get_by_unit($id, $limit, $offset);

        $unit['notes'] = $data['notes'];
        $unit['total_notes'] = $data['total'];
        
        if($parse_branch){
            $this->load->model('branch_model');
            $unit['branches'] = $this->branch_model->get_many($unit['branch']);
            unset($unit['branch']);
        }

        return $unit;
    }

    public function get_all($order_by = 'id', $order_type = 'ASC', $limit = null, $offset = null, $parse_branch = true){
        $this->db->order_by($order_by, $order_type);
        $units = $this->db->get($this->_tablename, $limit, $offset)->result_array();

        if($parse_branch){
            $this->load->model('branch_model');

            foreach ($units as $i=>$unit){
                $units[$i]['branches'] = $this->branch_model->get_many($unit['branch']);
                unset($units[$i]['branch']);
            }
        }

        return $units;
    }

    public function get_by_subject($subject_code, $limit = null, $offset = null){
        return $this->db->get_where($this->_tablename, array('subject'=>$subject_code), $limit, $offset)->result_array();
    }

    public function get_random($limit = 20){
        //SELECT col1 FROM tbl ORDER BY RAND() LIMIT 10;
        
    }

    public function update($id, $title, $subject, $class, $branch){
        $this->db->where('id', $id);
        $res = $this->db->update($this->_tablename, array(
            'title' => $title,
            'subject' => $subject,
            'class' => $class,
            'branch' => $branch
        ));

        easy_log('unit', $id, 'Updated info', $res, $this);

        return $res;
    }

    public function new($title, $subject, $class, $branch){
        $result = $this->db->insert($this->_tablename, array(
            'title' => $title,
            'subject' => $subject,
            'class' => $class,
            'branch' => $branch
        ));

        if($result){ 
            $id = $this->db->insert_id();

            easy_log('unit', $id, 'Created', $result, $this);
            
            return $id;
        }
        else return false;
    }
};