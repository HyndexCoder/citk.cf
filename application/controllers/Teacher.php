<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teacher extends MY_Controller{
    public function __construct(){
        parent::__construct();

        $this->load->model('teacher_model');
    }

    public function _remap($method, $args){
        $this->__remap($method, $args);
    }

    public function view($id = false, $page = 1, $num = 10){
        if($id === false){
            show_404();
        }

        if ($page < 1) show_404();
        else $page = (int)$page;

        if ($num < 1) show_404();
        else $num = (int)$num;

        $this->load->model('note_model');

        $teacher = $this->teacher_model->get($id);

        if (empty($teacher))
            show_404();

        $notes = $this->note_model->get_by_teacher($id, true, 'unit', $num, offset_manager($page, $num));

        $total_pages = ((int)($notes['total']/$num) + ($notes['total']%$num === 0? 0:1));
        $total_pages = $total_pages < 1? 1: $total_pages;

        //If page does not exists, not need to render view
        if ($page > $total_pages) show_404();

        $this->load->view('header', array(
            'page_title'=>'Prof. '.$teacher['name'].'\'s profile and notes',
            'current_url'=> site_url('teacher/'.$teacher['id'].'/'.$page.'/'.$num)
        ) );
        $this->load->view('teacher', array(
            'teacher'=>$teacher,
            'notes'=>$notes,
            'page'=>$page,
            'num'=>$num,
            'total_pages'=>$total_pages
        ));
        $this->load->view('footer');
    }

    public function all_json(){
        $teachers = $this->teacher_model->get_all('name');
        
        $this->load->view('json', array(
            'data' => $teachers
        ));
    }

    public function edit($id = false){
        if (!$this->require_min_level(6)) exit;

        if ($id === false){
            if (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post'){
                //Saving the teacher
                return $this->_edit();
            }
            else show_404();
        }
        
        $teacher = $this->teacher_model->get($id);

        if (empty($teacher))
            show_404();
        
        $this->load_form($teacher);
    }

    public function new(){
        if (!$this->require_min_level(6)) exit;

        if (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post'){
            //Creating the teacher
            return $this->_edit(true);
        }

        $this->load_form(array(), true);
    }

    private function load_form($data = array(), $new = false){
        $this->load->helper('form');

        if ($new){
            $page_title = 'Add new teacher';
            $data['id'] = '';
            $data['name'] = '';
            $data['email'] = '';
            $data['phone'] = '';
            $data['photo'] = teacher_avatar();
            $data['tenure'] = '';
            $data['designation'] = '';
        }
        else{
            $page_title = 'Teacher editor: '.$data['name'];
        }

        $data['new'] = $new;

        $this->load->view('header', array('page_title'=>$page_title));
        $this->load->view('editors/teacher', $data);
        $this->load->view('footer');
    }

    private function _edit($new = false){
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $tenure = $this->input->post('tenure');
        $designation = $this->input->post('designation');

        //If avatar link is provided, then no need to upload
        $photo = trim($this->input->post('photo_link'));
        if (empty($photo)){
            //Trying to upload avatar
            $this->load->library('upload', array(
                'upload_path' => CC_AVATAR_BASE,
                'allowed_types' => 'gif|jpg|png|bmp|svg'
            ));

            if ($this->upload->do_upload('photo')){
                $data = $this->upload->data();
                $photo = $data['file_name'];
            }
            else{
                $photo = null;
            }
        }

        if ($new){
            $result = $this->teacher_model->new($name, $email, $phone, $tenure, $designation, $photo);
            $id = $result;
        }
        else $result = $this->teacher_model->update($id, $name, $email, $phone, $tenure, $designation, $photo);

        if($result !== false){
            $msg = 'Succesfully saved the teacher.';
            $next = 'teacher/'.$id;
            $type = 'success';
        }
        else{
            $msg = 'Failed to save the teacher, try again.';
            $next = 'teacher/'. ($new? 'new':'edit/'.$id);
            $type = 'error';
        }

        set_flashmsg($next, $msg, $type);
    }
};
