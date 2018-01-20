<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Note extends MY_Controller{
    public function __construct(){
        parent::__construct();

        $this->load->model('note_model');
    }

    public function _remap($method, $args){
        $this->__remap($method, $args);
    }

    public function index(){
        show_404();
    }

    public function view($id = false){
        if($id === false){
            show_404();
        }

        $data = $this->note_model->get($id);
        $data['content'] = trim($data['content']);

        //Adding unit page and num information
        $unit_page = $this->input->get('unit_page');
        $unit_page = (int)($unit_page == null? 1: $unit_page);

        $unit_num = $this->input->get('unit_num');
        $unit_num = (int)($unit_num == null? 12: $unit_num);

        $data['unit_link'] = site_url_filter('unit/'.$data['unit']['id'].'/'.$unit_page.'/'.$unit_num);

        if(count($data) < 1){
            show_404();
        }

        //Loading other notes
        $data_others = $this->note_model->get_by_unit( $data['unit']['id'] , 20);

        $data['others'] = $data_others['notes'];
        $data['total_notes'] = $data_others['total'];

        //Prev and next note
        $data['nearby'] = $this->note_model->get_nearby($id);

        $this->load->view('header', array(
            'page_title'=>$data['title'],
            'current_url'=>site_url('note/'.$data['id'])
        ));
        $this->load->view('note', $data);
        $this->load->view('footer');
    }

    public function by_unit_json($unit = false, $offset = 0, $limit = 10){
        if ($unit === false)
            show_404();
        
        $offset = $offset < 0? 0: $offset;
        $limit = $limit <= 0? 10: $limit;

        $unit = (int)($unit<1?1:$unit); 
        $notes = $this->note_model->get_by_unit($unit, $limit, $offset, false);

        $this->load->view('json', array(
            'data' => $notes
        ));
    }

    public function edit($id = false){
        if (!$this->require_min_level(6)) exit;

        if($id === false){
            if (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post'){
                return $this->_edit();
            }
            else show_404();

            exit;
        }

        $note = $this->note_model->get($id, true, true);

        if(count($note) < 1){
            show_404();
        }

        return $this->load_form($note);
    }

    public function new(){
        if (!$this->require_min_level(6)) exit;

        if (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post'){
            return $this->_edit(true);
        }

        return $this->load_form(array(), true);
    }

    private function _edit($new = false){
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $content = $this->input->post('content');
        $unit = $this->input->post('unit');
        $teacher = $this->input->post('teacher');
        $date = $this->input->post('date');
        
        $date_is_auto = $this->input->post('date_is_auto');
        $date_is_auto = $date_is_auto == null? 1: 0;

        if ($new){
            $result = $this->note_model->new($title, $content, $unit, $teacher, $date, $date_is_auto);
            $id = $result;
        }
        else $result = $this->note_model->update($id, $title, $content, $unit, $teacher, $date, $date_is_auto);

        if($result){
            $msg = 'Succesfully saved the note.';
            $next = 'note/'.$id;
            $type = 'success';
        }
        else{
            $msg = 'Failed to save the note, try again.';
            $next = 'note/edit/'.$id;
            $type = 'error';
        }

        set_flashmsg($next, $msg, $type);
    }

    private function load_form($data, $new = false){
        $this->load->helper('form');

        if ($new){
            $unit = array();
            $unit['id'] = '..';
            $unit['title'] = 'Select';

            $data = array();
            $data['unit'] = $unit;
            $data['id'] = '';
            $data['teacher'] = array(
                'id' => '..',
                'name' => 'Select',
            );
            $data['title'] = '';
            $data['content'] = '';
            $data['date'] = '';
            $data['date_is_auto'] = '0';

            $page_title = 'New note';
            $data['form_type'] = 'new';
        }
        else{
            $page_title = 'Notes editor: '.$data['title'];
            $data['form_type'] = 'edit';
        }

        $this->load->view('header', array('page_title'=> $page_title));
        $this->load->view('editors/note_edit', $data);
        $this->load->view('footer');
    }
};
