<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subject extends MY_Controller{

    public function __construct(){
        parent::__construct();

        $this->load->model('subject_model');
    }
    
    public function load_json($class_id = false){
        if($class_id == false)
            show_404();

        $subjects = $this->load_by_class($class_id);     

        $this->load->view('json', array(
            'data' => $subjects
        ));
    }

    private function load_by_class($class_id){
        return $this->subject_model->get_by_class($class_id);
    }

    public function all_json($limit = null, $offset = null){
        $subjects = $this->subject_model->get_all($limit, $offset);
        
        $this->load->view('json', array(
            'data' => $subjects
        ));
    }

    public function edit($code = false){
        if (!$this->require_min_level(6)) exit;

        if ($code === false){
            if (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post'){
                //Saving the Unit
                return $this->_edit();
            }
            else show_404();
        }

        $subject = $this->subject_model->get($code);

        if (!is_array($subject))
            show_404();

        

        return $this->load_form($subject);
    }

    public function new(){
        if (!$this->require_min_level(6)) exit;

        if (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post'){
            //Creating the Unit
            
            return $this->_edit(true);

            exit;
        }

        $this->load_form(array(), true);
    }

    private function load_form($data, $new = false){
        //Loading the form
        $this->load->helper('form');

        $this->load->model('class_model');

        $data['new'] = $new;

        if ($new){
            $data['id'] = '..';
            $data['title'] = '';
            $data['code'] = '';
            $data['description'] = '';
            $data['classes'] = array();
        }
        

        $data['all_classes'] = $this->class_model->get_all();

        $this->load->view('header', array(
            'page_title' => $new? 'Add new subject':'Subject editor: '.htmlspecialchars('('.$data['code'].') '.$data['title'])
        ));
        $this->load->view('editors/subject', $data);
        $this->load->view('footer');
    }

    private function _edit($new = false){
        $id = $this->input->post('id');
        $code = $this->input->post('code');
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $classes = array();

        if (!empty($code)){
            $code = strtoupper($code);

            $this->load->model('class_model');
            $db_classes = $this->class_model->get_all();

            foreach ($db_classes as $class){
                $c = $this->input->post('class-'.$class['id']);
                if($c != null) $classes[] = $class['id'];
            }
            $classes = implode(',', $classes);

            if ($new){
                $result = $this->subject_model->new($code, $title, $description, $classes);
            }
            else $result = $this->subject_model->update($id, $code, $title, $description, $classes);
        }else $result = false;

        if($result){
            $msg = 'Succesfully saved the subject. <a href="'.site_url('subject/new').'">Add another</a> or <a href="'.site_url('subject/edit/'.$code).'">Edit again</a>.';
            $next = '/';
            $type = 'success';
        }
        else{
            $msg = 'Failed to save the subject, try again.';
            $next = 'subject/edit/'.$id;
            $type = 'error';
        }

        set_flashmsg($next, $msg, $type);
    }
};