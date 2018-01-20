<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Branch_model extends CI_model{
    protected $_tablename = 'branches';

    public function __construct(){
            parent::__construct();

            $this->load->database();
    }

    public function get($code){
        $branch = $this->db->get_where($this->_tablename, array('code'=>$code) )->row_array();
        return $this->set_classes($branch);
    }
	
	//$codes, branch codes seperated by comma. White spaces ignored.
    public function get_many($codes, $limit = null, $offset = null){
        if (trim($codes) != '*'){
            $codes = explode(',',$codes);
            
            foreach($codes as $code){
                $this->db->or_where('code', strtoupper(trim($code)) );
            }
        }

        $branches = $this->db->get($this->_tablename, $limit, $offset)->result_array();
        return $this->set_classes($branches);
    }

    public function get_all($limit = null, $offset = null){  
        return $this->get_many('*');
    }

    private function set_classes($branches){
        if (isset($branches['code'])){
            $branches['classes'] = $this->explode_class($branches['classes']);
        }
        elseif (is_array($branches)){
            foreach ($branches as $i=>$b){
                if (!empty($branches[$i])) $branches[$i]['classes'] = $this->explode_class($b['classes']);
            }
        }

        return $branches;
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