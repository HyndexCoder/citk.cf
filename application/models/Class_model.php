<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Class_model extends CI_Model{
    public $classes;

    public function __construct(){
        parent::__construct();

        $this->classes = array(
            '111','112','121','122','131','132','141','142', //Degree
            '211','212','221','222','231','232'              //Diploma
        );
    }

    public function get_all(){
        $classes = array();

        foreach ($this->classes as $class){
            $classes[] = array(
                'id'=>$class,
                'name'=>classcode_to_name($class),
                'selected'=>false
            );
        }

        return $classes;
    }
};