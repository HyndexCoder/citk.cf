<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit extends MY_Controller{
    public function __construct(){
        parent::__construct();

        $this->load->model('unit_model');
    }

    public function _remap($method, $args){
        $this->__remap($method, $args);
    }

    public function index(){
        show_404();
    }

    public function view($id = false, $page = 1, $num = 12){
        if($id === false){
            show_404();
        }

        $this->load->model('subject_model');

        if ($page < 1) show_404();
        else $page = (int)$page;

        if ($num < 1) show_404();
        else $num = (int)$num;

        $unit = $this->unit_model->get($id, true, $num, offset_manager($page, $num));

        $unit['total_pages'] = ((int)($unit['total_notes']/$num) + ($unit['total_notes']%$num === 0? 0:1));
        $unit['total_pages'] = $unit['total_pages'] < 1? 1: $unit['total_pages'];

        if($unit['total_pages'] < $page){
            show_404();
        }

        if(!isset($unit['id'])){
            show_404();
        }


        $unit['class_name'] = classcode_to_name($unit['class']);
        $unit['subject'] = $this->subject_model->get($unit['subject']);
        $unit['page'] = $page;
        $unit['num'] = $num;

        //Loading other units in the same subject
        $unit['others'] = $this->unit_model->get_by_subject($unit['subject']['code']);

        $this->load->view('header', array(
            'page_title'=> 'Unit: '.$unit['title'],
            'current_url'=> site_url('unit/'.$unit['id'].'/'.$page.'/'.$num)
        ));
        $this->load->view('unit', $unit);
        $this->load->view('footer');
    }

    public function all_json(){
        $units = $this->unit_model->get_all('title', 'asc', null, null, false);

        $this->load->view('json', array(
            'data' => $units
        ));
    }

    public function by_subject_json($subject_code = false){
        if ($subject_code === false)
            show_404();

        $units = $this->unit_model->get_by_subject($subject_code);

        //Now loading subject model for subject description
        $this->load->model('subject_model');
        $subject = $this->subject_model->get($subject_code);

        $this->load->view('json', array(
            'data' => array(
                'units' => $units,
                'subject' => $subject
            )
        ));
    }

    public function edit($id = false){
        if (!$this->require_min_level(6)) exit;

        if($id === false){
            if (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post'){
                //Saving the Unit
                return $this->_edit();
            }
            else show_404();
        }    

        $unit = $this->unit_model->get($id, true, 10, 0, false);

        return $this->load_form($unit);
    }

    public function new(){
        if (!$this->require_min_level(6)) exit;

        if (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post'){
            //Creating the Unit
            $this->_edit(true);
        }
        else{
            return $this->load_form(array(), true);
        }
    }

    //
    //
    //
    private function _edit($new = false){
        $this->load->model('branch_model');
        
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $subject = $this->input->post('subject');
        $class = $this->input->post('class');
        
        $all_branches = $this->branch_model->get_all();
        $branches = array();

        foreach ($all_branches as $branch){
            $b = $this->input->post('branch-'.$branch['code']);
            if($b != null) $branches[] = strtoupper($branch['code']);
        }
        $branches = implode(',', $branches);

        if($new){
            $result = $this->unit_model->new($title, $subject, $class, $branches);
            $id = $result;
        }
        else $result = $this->unit_model->update($id, $title, $subject, $class, $branches);
        
        if($result){
            $msg = 'Succesfully saved the unit.';
            $next = 'unit/'.$id;
            $type = 'success';
        }
        else{
            $msg = 'Failed to save the unit, try again.';
            $next = 'unit/edit/'.$id;
            $type = 'error';
        }

        set_flashmsg($next, $msg, $type);
    }

    private function load_form($data, $new = false){
        $this->load->helper('form');
        $this->load->model('subject_model');
        $this->load->model('class_model');
        $this->load->model('branch_model');

        $data['classes'] = $this->class_model->get_all();
        $data['subjects'] = $this->subject_model->get_all();
        $data['all_branches'] = $this->branch_model->get_all();

        if ($new){
            $page_title = 'New unit';
            $data['id'] = '..';//So that clicking the cancel link takes to homepage
            $data['title'] = '';
            $data['class'] = '';
            $data['subject'] = array('code'=>'');
            $data['branch'] = array();
            $data['form_type'] = 'new';
        }
        else{
            $data['subject'] = $this->subject_model->get($data['subject']);
            $data['branch'] = explode(',', $data['branch']);

            foreach ($data['branch'] as $i=>$b){
                $b = trim($b);
                if (!empty($b)){
                    $data['branch'][$i] = strtoupper($b);
                }
            }

            if(count($data) < 1){
                show_404();
            }

            $page_title = 'Unit editor: '.$data['title'];
            $data['form_type'] = 'edit';
        }

        $this->load->view('header', array('page_title'=> $page_title));
        $this->load->view('editors/unit_edit', $data);
        $this->load->view('footer');
    }
};
