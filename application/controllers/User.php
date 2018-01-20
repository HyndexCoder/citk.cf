<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller{

    protected $main_method = 'profile';

    //Rules holders
    private $username_rules;
    private $username_errors;
    private $email_rules;
    private $email_errors;
    private $password_rules;
    private $password_errors;
    private $auth_level_rules;
    private $auth_level_errors;

    public function __construct(){
        parent::__construct();

        $this->load->model('user_model');
        $this->load->helper('auth');
        $this->load->model('examples/validation_callables');

        //Defining rules for form validation
        $this->username_rules = [
            'required',
            'trim',
            'min_length[5]',
            'max_length[12]',
            ['allowed_characters', [$this->user_model, 'validate_username']],
            'is_unique['.db_table('user_table').'.username]'
        ];
        $this->username_errors = array(
            'is_unique'=>'This %s is already in use.',
            'allowed_characters'=>'%s can contain only a-z, A-Z, 0-9 and _ (underscore) characters.'
        );

        $this->email_rules = 'trim|strtolower|required|valid_email|is_unique['.db_table('user_table').'.email]';
        $this->email_errors = array('is_unique'=>'This %s is already in use.');

        $this->password_rules = [
            'required',
            [ 
                '_check_password_strength', 
                [ $this->validation_callables, '_check_password_strength' ] 
            ]
        ];

        $this->auth_level_rules = 'trim|required|integer|in_list[1,6,9]';
        $this->auth_level_errors = array(
            'in_list'=>'%s must be one of the provided value(s).'
        );
    }

    public function _remap($method, $args){
        $this->__remap($method, $args);
    }

    public function profile($username = FALSE){
        if($username === FALSE){
            show_404();
        }

        $user = $this->user_model->get_by_username($username);

        if(empty($user)){
            show_404();
        }

        //Check if this profile is editable
        $editable = false;
        if (isset($this->auth_user_id)){
            if ($this->is_role('Admin') ||
             $this->auth_user_id == $user['user_id']) 
                    $editable = true;
        }

        $this->load->helper('form');

        $this->load->view('header', array(
            'page_title'=>$user['username'].'\'s profile',
            'current_url'=>site_url('user/'.$user['username'])
        ) );
        $this->load->view('profile', array(
            'user' => $user,
            'editable' => $editable
        ));
        $this->load->view('footer');
    }

    public function profile_id($id = false){
        if($id === FALSE){
            show_404();
        }

        $user = $this->user_model->get($id);

        if(empty($user)){
            show_404();
        }

        redirect('user/'.$user['username']);
    }

    public function login(){
        $show_login_form = TRUE;

        if($this->is_logged_in()){
            show_404();
        }
        elseif( $this->uri->uri_string() != LOGIN_PAGE){
            show_404();
        }
        elseif( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){

            $this->auth_data = $this->authentication->user_status( 0 );

            // Set user variables if successful login
			if( $this->auth_data )
                $this->_set_user_variables();

            // Call the post auth hook
            $this->post_auth_hook();
            $show_login_form = FALSE;

            // Login attempt not successful
            if (!$this->auth_data)
            {
                $show_login_form = TRUE;

                $this->tokens->name = 'login_token';

                $on_hold = ( 
                    $this->authentication->on_hold === TRUE OR 
                    $this->authentication->current_hold_status()
                )
                ? 1 : 0;
            }
        }
        
        if($show_login_form){
            $this->load->helper('form');
            $this->setup_login_form();
            
            $this->load->view('header', array('page_title'=>'Login'));
            $this->load->view('login');
            $this->load->view('footer');
        }
        
    }

    public function logout()
    {
        $this->authentication->logout();
     
        // Set redirect protocol
        $redirect_protocol = USE_SSL ? 'https' : NULL;
     
        redirect( site_url( LOGIN_PAGE . '?logout=1', $redirect_protocol ) );
    }

    public function new(){
        if (!$this->require_role('Admin')) exit;

        if (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post'){
            //Creating the user
            
            // Load resources
            $this->load->library('form_validation');
            
            //Setting rules for form validation
            $this->form_validation->set_rules('username', 'Username', $this->username_rules, $this->username_errors);

            $this->form_validation->set_rules('password', 'Password', $this->password_rules);

            $this->form_validation->set_rules('email', 'Email address', $this->email_rules, $this->email_errors);

            $this->form_validation->set_rules('auth_level', 'User level', $this->auth_level_rules, $this->auth_level_errors);

            $this->form_validation->set_error_delimiters('<li>','</li>');

            if( $this->form_validation->run() )
            {
                // Customize this array for your user
                $user_data = [
                    'username'   => $this->input->post('username'),
                    'passwd'     => $this->input->post('password'),
                    'email'      => $this->input->post('email'),
                    'auth_level' => $this->input->post('auth_level'), // 9 if you want to login @ examples/index.
                ];

                $result = $this->user_model->new($user_data);
                $id = $result;
                
                if($result !== false){
                    $msg = 'Succesfully created the user.';
                    $type = 'success';
                    $next = 'user/'.$user_data['username'];
                }
                else{
                    $msg = 'Failed to create the user, try again.</a>';
                    $type = 'error';
                    $next = 'user/new';
                }

                set_flashmsg($next, $msg, $type);
            }
        }

        $this->load_form(array(), true);
    }

    public function edit($type = false, $user_id = false){
        if ($type === false){
            show_404();
        }

        if (!$user = $this->verify_edit_permission($user_id)){
            show_404();
        }

        // Load resources
        $this->load->helper('auth');
        $this->load->library('form_validation');
        $this->load->model('examples/validation_callables');

        $this->form_validation->set_error_delimiters('<li>','</li>');

        //Is the request is to save or show form
        $save_req = (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post');

        //Data to send to views
        $data = array(
            'save_req' => $save_req,
            'user_id' => $user['user_id'],
            'username' => $user['username']
        );

        $type = strtolower($type);

        //Setting validation rules
        switch($type){
            /**
             * Editing the username
             */
            case  'username':
                if ($save_req){
                    $this->form_validation->set_rules('username', 'Username', $this->username_rules, $this->username_errors);
                }
                else{
                    //$data['username'] = $user['username'];
                }
                break;
            /**
             * Editing the email
             */
            case 'email':
                if ($save_req){
                    $this->form_validation->set_rules('email', 'Email address', $this->email_rules, $this->email_errors);
                }
                else{
                    $data['email'] = $user['email'];
                }
                break;
            /**
             * Editing the password
             */
            case 'password':
                if ($save_req){
                    //Checks if current password is correct
                    $this->form_validation->set_rules('c_password', 'Your current password',
                        array('required', ['_check_passwd_match', [$this->user_model, '_check_passwd_match']]), array(
                            '_check_passwd_match' => '%s is incorrect.'
                    ));
                    $this->form_validation->set_rules('password', 'New password', $this->password_rules);
                    $this->form_validation->set_rules('re_password', 'Confirmation password', 'matches[password]|required', array('matches'=>'Both the passwords should match each other.'));
                }
                break;
            case 'level':
                /**
                 * Only Admin should be able to change user levels.
                 */
                if (!$this->is_role("Admin")) show_404();

                if ($save_req){
                    $this->form_validation->set_rules('auth_level', 'User level', $this->auth_level_rules, $this->auth_level_errors);
                }
                else{
                    $data['auth_level'] = $user['auth_level'];
                }
                break;
            case 'ban':
                /**
                 * Only Admin should be able to ban and un-ban.
                 */
                if (!$this->is_role("Admin")) show_404();

                if ($save_req){
                    $this->form_validation->set_rules('banned', 'Ban', 'trim|required|integer|in_list[0,1]', array(
                        'integer'=>'%s can only be a provided value.',
                        'in_list'=>'Please choose %s from the given list only.'
                    ));
                }
                else{
                    /**
                     * There is no seperate page to ban and un-ban users.
                     * The profile page is enough.
                     */
                    show_404();
                }
                break;
            default: show_404();
        }

        if ($this->form_validation->run()){
            $result = call_user_func(array($this, '_edit_'.$type), $data['user_id']);

            //Redirecting to profile page if changing ban
            if ($type == 'ban'){
                redirect('user/'.$user['username']);
                exit;
            }

            if($result){
                $msg = 'Succesfully changed the '.$type.'.';

                $next = 'user/profile_id/'.$user['user_id'];
                $type = 'success';
            }
            else{
                $msg = 'Failed to save, <a href="'.site_url().'">edit again</a>';

                $next = 'user/edit/'.$type.'/'.$user['user_id'];
                $type = 'error';
            }

            set_flashmsg($next, $msg, $type);
        }
        else{
            //Redirecting to profile page if changing ban
            if ($type == 'ban'){
                redirect('user/'.$user['username']);
                exit;
            }
        }

        $this->load->view('header', array('page_title'=>'Edit '.ucfirst($type) ) );
        $this->load->view('editors/user/'.$type, $data);
        $this->load->view('footer');
    }

    /**
     * Check's all conditions when updating profile is possible
     * Only the profile owner and admin can update a profile
     * Returns false when not possible or user data when possible.
     * Redirects to login page if not at all logged in.
     */
    private function verify_edit_permission($user_id = false){
        if (! $this->verify_min_level(1)){
            //Must be logged in
            redirect(add_url_query(site_url(LOGIN_PAGE), array('redirect'=>uri_string())));
            exit;
        }

        if (strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post'){
            //Request to update the profile
            $user_id = $this->input->post('user_id');

            if ($user_id == $this->auth_user_id || $this->is_role('Admin')){
                //Only profile owner and admin can edit profile

            }
            else {
                return false;
            }

            $user = $this->user_model->get($user_id);

            if (empty($user)){
                //User doesn't exists
                return false;
            }

            //Load the form
            return $user;
        }
        else{
            //Request to show form
            //Only the owner of the and the admin can edit profile

            if ($user_id === false){
                //Then edit own's profile
                $user_id = $this->auth_user_id; 
            }
            elseif ($this->is_role('Admin')){
                //Admin can edit anyone's profile
                
            }
            elseif ($this->auth_user_id == $user_id){
                //The account owner can edit this
            }
            else{
                //For any other case, nothing is allowed
                return false;
            }

            $user = $this->user_model->get($user_id);

            if (empty($user)){
                //User doesn't exists
                return false;
            }

            //Load the form
            return $user;
        }
    }

    private function load_form($data = array(), $new = false){
        $this->load->helper('form');

        if ($new){
            $page_title = 'Create new user';
            $data['user_id'] = '';
            $data['username'] = '';
            $data['email'] = '';
        }
        else{
            $page_title = 'Edit user';
        }
        $data['new'] = $new;

        $this->load->view('header', array('page_title'=>$page_title));
        $this->load->view('editors/user', $data);
        $this->load->view('footer');
    }

    private function _edit_username($user_id){
        $username = $this->input->post('username');

        if (empty($username)) return false;

        return $this->user_model->update_user_raw_data($user_id, array('username'=>$username));
    }
    
    private function _edit_email($user_id){
        $email = $this->input->post('email');

        if (empty($email)) return false;

        return $this->user_model->update_user_raw_data($user_id, array('email'=>$email));
    }
    
    private function _edit_password($user_id){
        $password = $this->authentication->hash_passwd($this->input->post('password'));

        if (empty($password)) return false;

        return $this->user_model->update_user_raw_data($user_id, array('passwd'=>$password));
    }
    
    private function _edit_level($user_id){
        $auth_level = $this->input->post('auth_level');

        if (empty($auth_level)) return false;

        return $this->user_model->update_user_raw_data($user_id, array('auth_level'=>$auth_level));
    }
    
    private function _edit_ban($user_id){
        $banned = $this->input->post('banned');

        if (is_null($banned)) return false;

        $banned = $banned?'1':'0';

        return $this->user_model->update_user_raw_data($user_id, array('banned'=>$banned));
    }
};