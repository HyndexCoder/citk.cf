<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Branch extends MY_Controller{
    public function _remap($method, $args){
        $this->__remap($method, $args);
    }

    public function view($code = false){
        if ($code === false) show_404();

        $this->load->model('branch_model');

        $branch = $this->branch_model->get($code);

        if (empty($branch)) show_404();
        $classes = $branch['classes'];

        foreach ($classes as $i=>$class){
            $c = array();
            $c['id'] = $class;
            $c['name'] = classcode_to_name($class);
            
            if(CC_CLASS == $class)
                $c['selected'] = true;
            else
                $c['selected'] = false;

            $classes[$i] = $c;
        }
    

        $this->load->view('header', array(
            'page_title' => $branch['name'],
            'current_url' => site_url('branch/'.$branch['code'])
        ) );
        $this->load->view('branch', array(
            'branch'=>$branch,
            'classes'=>$classes,
            'selected_subject'=>$this->input->get('subject'),
            'selected_class'=>CC_CLASS
        ));
        $this->load->view('footer');
    }
};