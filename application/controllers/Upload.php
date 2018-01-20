<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Upload extends MY_Controller{
    private $_upload_dir = 'uploads/';

    public function __construct(){
        parent::__construct();

        if (!$this->require_min_level(6)) exit;
        
        $this->load->helper('form');
    }

    public function form($note_id = false){
        $imageMimeTypes = array(
            'image/png',
            'image/gif',
            'image/jpeg');

        if($note_id === false){
            die('<h3>Save the note to upload files.</h3>');
        }

        $this->load->model('file_model');
        $files = $this->file_model->get_by_note($note_id);

        foreach ($files as $i=>$file){
            $files[$i]['link'] = base_url($this->_upload_dir.$file['name']);
            $files[$i]['is_image'] = in_array(mime_content_type($this->_upload_dir.$file['name']), $imageMimeTypes)? true: false;
        }

        $this->load->view('editors/file_upload', array('note_id' => $note_id, 'files' => $files));
    }

    public function do(){
        $response = new stdClass;
        $response->success = false;
        $response->url = null;
        $response->error = null;

        $note_id = $this->input->post('note_id');

        if (is_null($note_id) || $note_id === false){
            $response->error = 'No note id specified';
        }
        else {
            $this->load->library('upload', array(
                'upload_path' => $this->_upload_dir,
                'allowed_types' => 'gif|jpg|png|bmp|pdf|doc|docx|txt|rtf'
            ));
            
    
            if ( ! $this->upload->do_upload('file'))
            {
                $response->error = $this->upload->display_errors();
            }
            else
            {
                $data = $this->upload->data();
    
                //Loading file model
                $this->load->model('file_model');
    
                $response->success = $this->file_model->add($note_id, $data['file_name']);
                $response->url = base_url($this->_upload_dir.$data['file_name']);
            }
        }

        $this->load->view('json', array(
            'data' => $response
        ));
    }
};