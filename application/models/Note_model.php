<?php
defined('BASEPATH') or exit('No direct script access allowed');

//Notes
class Note_model extends MY_Model{
    protected $_tablename = 'notes';

    public function __construct(){
        parent::__construct();
        
        $this->load->database();
    }

    public function get($id, $teacher = true, $unit = true){
        $this->db->cache_on();
        $note = $this->db->get_where($this->_tablename, array('id'=>$id))->row_array();
        $this->db->cache_off();

        if (!empty($note)){
            //Loading teacher
            if ($teacher){
                $this->load->model('teacher_model');

                $note['teacher'] = $this->teacher_model->get($note['teacher']);
            }
            
            //Loading unit
            if ($unit){
                $this->load->model('unit_model');

                $note['unit'] = $this->unit_model->get($note['unit']);
            }

            $note['date_string'] = format_date($note['date']);
        }

        return $note;
    }

    public function get_by_unit($unit, $limit = 10, $offset = 0, $teacher = true, $order_by = 'ASC'){
        $data = array();        
        $where = array('unit'=>$unit);

        $this->db->limit($limit, $offset);
        $this->db->order_by('date', $order_by);

        $this->db->cache_on();
        $data['notes'] = $this->db->get_where($this->_tablename, $where)->result_array();
        $this->db->cache_off();

        $data['total'] = $this->count($where);

        //Loading teacher
        if($teacher){
            $this->load->model('teacher_model');

            foreach ($data['notes'] as $i=>$note){
                if(!empty($note)){
                    $data['notes'][$i]['teacher'] = $this->teacher_model->get($note['teacher']);
                    $data['notes'][$i]['date_string'] = format_date($note['date']);
                }
            }
        }

        return $data;
    }

    public function get_nearby($id){
        $note = $this->get($id, false, false);

        if (!$note) return false;

        $notes = $this->get_by_unit($note['unit'], null, null, false);

        $capture_next = false;
        $prev = $next = null;

        foreach ($notes['notes'] as $n){
            if ($capture_next){
                $next = $n;
                break;
            }

            if ($n['id'] == $note['id']){
                $capture_next = true;
            }
            else $prev = $n;
        }


        return array(
            'prev' => $prev,
            'next' => $next
        );
    }

    public function get_by_teacher($teacher, $unit = false, $order_by ='date', $limit = null, $offset = null, $order = 'ASC'){

        $this->db->select('id,unit,title,date,date_is_auto');
        $this->db->order_by($order_by, $order);
        $where = array('teacher'=>$teacher);

        $data = array();

        $this->db->cache_on();
        $data['notes'] = $this->db->get_where($this->_tablename, $where, $limit, $offset)->result_array();
        $this->db->cache_off();

        $data['total'] = $this->count($where);

        if ($unit){
            $this->load->model('unit_model');

            foreach ($data['notes'] as $i=>$note){
                $data['notes'][$i]['unit'] = $this->unit_model->get($note['unit']);
            }
        }

        return $data;
    }

    public function update($id, $title, $content, $unit, $teacher, $date = null, $date_is_auto = 1){
        $this->db->where('id', $id);
        $result = $this->db->update($this->_tablename, array(
            'title' => $title,
            'content' => $content,
            'unit' => $unit,
            'teacher' => $teacher,
            'date' => $date,
            'date_is_auto' => $date_is_auto
        ));

        easy_log('note', $id, 'Updated info', $result, $this);

        //Deleting cache
        $this->db->cache_delete_all();

        return $result;
    }

    public function new($title, $content, $unit, $teacher, $date, $date_is_auto = 1){
        $result = $this->db->insert($this->_tablename, array(
            'title' => $title,
            'content' => $content,
            'unit' => $unit,
            'teacher' => $teacher,
            'date' => $date,
            'date_is_auto' => $date_is_auto
        ));

        if ($result){
            $id = $this->db->insert_id();

            easy_log('unit', $id, 'Created', $result, $this);

            //Deleting cache
            $this->db->cache_delete_all();

            return $id;
        }
        else return false;
    }
};
