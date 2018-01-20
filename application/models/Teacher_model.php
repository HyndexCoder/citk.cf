<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teacher_model extends CI_model{
    protected $_tablename = 'teachers';

    public function __construct(){
            parent::__construct();
            
            $this->load->database();
    }

    public function get($id){
        $teacher = $this->db->get_where($this->_tablename, array('id'=>$id))->row_array();
        
        return $this->avatar($teacher);
    }

    public function get_all($order_by = 'id', $order_type = 'ASC', $limit = null, $offset = null){
        $this->db->order_by($order_by, $order_type);
        $teachers = $this->db->get($this->_tablename, $limit, $offset)->result_array();

        return $this->avatar($teachers);
    }

    public function update($id, $name, $email = null, $phone = null, $tenure = null, $designation = null, $photo = null){
        $this->db->where('id', $id);

        $sql_data = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'tenure' => $tenure,
            'designation' => $designation
        );

        if (!empty($photo)) $sql_data['photo'] = make_my_url_relevent(teacher_avatar($photo));

        $res = $this->db->update($this->_tablename, $sql_data);

        easy_log('teacher', $id, 'Updated info', $res, $this);
        
        return $res;
    }

    public function new($name, $email = '', $phone = '', $tenure = '', $designation = '', $photo = ''){
        $result = $this->db->insert($this->_tablename, array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'tenure' => $tenure,
            'designation' => $designation,
            'photo' => make_my_url_relevent(teacher_avatar($photo))
        ));

        if($result){
            $id = $this->db->insert_id();

            easy_log('teacher', $id, 'Created', $result, $this);

            return $id;
        }
        else return false;
    }

    /**
     * Modifies the array of teachers with avatar included
     */
    private function avatar($teacher, $default_avatar = null){
        $default_avatar = teacher_avatar(empty($default_avatar)? CC_DEFAULT_AVATAR: $default_avatar);

        if (!empty($teacher)){
            if (!isset($teacher['id'])) {
                foreach ($teacher as $i=>$t){
                    if (empty($t['photo'])) $teacher[$i]['photo'] = $default_avatar;
                    else $teacher[$i]['photo'] = teacher_avatar($t['photo']);
                }
            }
            else{
                if (empty($teacher['photo'])) $teacher['photo'] = $default_avatar;
                else $teacher['photo'] = teacher_avatar($teacher['photo']);
            }
        }

        return $teacher;
    }
};
