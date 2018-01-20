<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subject_model extends CI_model{
    protected $_tablename = 'subjects';

    public function __construct(){
            parent::__construct();
            
            $this->load->database();
    }

    public function get($code){
        $subjects = $this->db->get_where($this->_tablename, array('code'=>$code))->row_array();
        return $this->set_classes($subjects);
    }

    public function get_list($unit, $limit = 10, $offset = 0){
        $this->db->select('id,unit,title,teacher');     
        return $this->db->get_where($this->_tablename, array('unit'=>$unit), $limit, $offset)->result_array();
    }

    public function get_by_class($class_id){
        $this->db->where('FIND_IN_SET( "'.$class_id.'", classes)', NULL, false);
        return $this->db->get($this->_tablename)->result_array();
    }

    public function get_all($limit = null, $offset = null){
        $subjects = $this->db->get($this->_tablename, $limit, $offset)->result_array();
        return $this->set_classes($subjects);
    }

    public function update($id, $code, $title = '', $description = '', $classes = ''){
        $this->db->where('id', $id);
        $res = $this->db->update($this->_tablename, array(
            'code' => $code,
            'title' => $title,
            'description' => $description,
            'classes' => $classes
        ));

        easy_log('subject', $id, 'Updated info', $res, $this);

        return $res;
    }

    public function new($code, $title, $description, $classes){
        $result = $this->db->insert($this->_tablename, array(
            'title' => $title,
            'code' => $code,
            'description' => $description,
            'classes' => $classes
        ));

        if($result){ 
            $id = $this->db->insert_id();

            easy_log('subject', $id, 'Created', $result, $this);

            return $id;
        }
        else return false;
    }

    /**
     * Modifies array of subjects with class included
     */
    private function set_classes($subjects){
        if (is_array($subjects)){
            if (isset($subjects['code'])){
                $subjects['classes'] = $this->explode_class($subjects['classes']);
            }
            else{
                foreach ($subjects as $i=>$subject){
                    $subjects[$i]['classes'] = $this->explode_class($subject['classes']);
                }
            }
        }

        return $subjects;
    }

    private function explode_class($class = ''){
        $class = explode(',', $class);
        
        foreach ($class as $i=>$c){
            $c = trim($c);
            if (empty($c)){
                unset($class[$i]);
            }else $class[$i] = $c;
        }

        return array_values($class);
    }
};