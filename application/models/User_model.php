<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Community Auth - Examples Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2017, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class User_model extends MY_Model {

    protected $_tablename = 'users';

    public function __construct(){
            parent::__construct();
            
            $this->load->database();
    }

    public function get($id){
        return $this->db->get_where($this->db_table('user_table'), array('user_id'=>$id))->row_array();
    }

    public function get_by_username($username){
        $this->db->select('user_id,username,email,auth_level,banned');
        return $this->db->get_where($this->db_table('user_table'), array('username'=>$username))->row_array();
    }

    public function new($user_data = array()){
        $user_data['passwd']     = $this->authentication->hash_passwd($user_data['passwd']);
        $user_data['user_id']    = $this->user_model->get_unused_id();
        $user_data['created_at'] = date('Y-m-d H:i:s');

        $this->db->set($user_data)
				->insert($this->db_table('user_table'));

        if( $this->db->affected_rows() == 1 ){
			easy_log('user', $user_data['id'], 'Created', true, $this);
            return $user_data['user_id'];
        }
        else return false;
	}
	
	public function validate_username($username){
		return preg_match('/^[A-Za-z0-9_]+$/', $username)? true: false;
	}

	/**
     * Compared the given password with logged in user's password
     * Returns true or false
     */
    public function _check_passwd_match($passwd){
        $auth_model = $this->authentication->auth_model;
        $auth_data = $this->{$auth_model}->get_auth_data( $this->auth_username );

        if (!$auth_data) return false;

        $hash_real_passwd = $auth_data->passwd;

        
        return $this->authentication->check_passwd($auth_data->passwd, $passwd);
    }

	/**
	 * Update a user record with data not from POST
	 *
	 * @param  int     the user ID to update
	 * @param  array   the data to update in the user table
	 * @return bool
	 */
	public function update_user_raw_data( $the_user, $user_data = [] )
	{
		//Modified
	
		$res = $this->db->where('user_id', $the_user)
			->update( $this->db_table('user_table'), $user_data );

		easy_log('user', $the_user, 'Updated: '.json_encode($user_data), $res, $this);

		return $res;
	}

	// --------------------------------------------------------------

	/**
	 * Get data for a recovery
	 * 
	 * @param   string  the email address
	 * @return  mixed   either query data or FALSE
	 */
	public function get_recovery_data( $email )
	{
		$query = $this->db->select( 'u.user_id, u.email, u.banned' )
			->from( $this->db_table('user_table') . ' u' )
			->where( 'LOWER( u.email ) =', strtolower( $email ) )
			->limit(1)
			->get();

		if( $query->num_rows() == 1 )
			return $query->row();

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Get the user name, user salt, and hashed recovery code,
	 * but only if the recovery code hasn't expired.
	 *
	 * @param  int  the user ID
	 */
	public function get_recovery_verification_data( $user_id )
	{
		$recovery_code_expiration = date('Y-m-d H:i:s', time() - config_item('recovery_code_expiration') );

		$query = $this->db->select( 'username, passwd_recovery_code' )
			->from( $this->db_table('user_table') )
			->where( 'user_id', $user_id )
			->where( 'passwd_recovery_date >', $recovery_code_expiration )
			->limit(1)
			->get();

		if ( $query->num_rows() == 1 )
			return $query->row();
		
		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Validation and processing for password change during account recovery
	 */
	public function recovery_password_change()
	{
		$this->load->library('form_validation');

		// Load form validation rules
		$this->load->model('examples/validation_callables');
		$this->form_validation->set_rules([
			[
				'field' => 'passwd',
				'label' => 'NEW PASSWORD',
				'rules' => [
					'trim',
					'required',
					'matches[passwd_confirm]',
					[ 
						'_check_password_strength', 
						[$this->validation_callables, '_check_password_strength'] 
					]
				]
			],
			[
				'field' => 'passwd_confirm',
				'label' => 'CONFIRM NEW PASSWORD',
				'rules' => 'trim|required'
			],
			[
				'field' => 'recovery_code'
			],
			[
				'field' => 'user_identification'
			]
		]);

		if( $this->form_validation->run() !== FALSE )
		{
			$this->load->vars( ['validation_passed' => 1] );

			$this->_change_password(
				set_value('passwd'),
				set_value('passwd_confirm'),
				set_value('user_identification'),
				set_value('recovery_code')
			);
		}
		else
		{
			$this->load->vars( ['validation_errors' => validation_errors()] );
		}
	}

	// --------------------------------------------------------------

	/**
	 * Change a user's password
	 * 
	 * @param  string  the new password
	 * @param  string  the new password confirmed
	 * @param  string  the user ID
	 * @param  string  the password recovery code
	 */
	protected function _change_password( $password, $password2, $user_id, $recovery_code )
	{
		// User ID check
		if( isset( $user_id ) && $user_id !== FALSE )
		{
			$query = $this->db->select( 'user_id' )
				->from( $this->db_table('user_table') )
				->where( 'user_id', $user_id )
				->where( 'passwd_recovery_code', $recovery_code )
				->get();

			// If above query indicates a match, change the password
			if( $query->num_rows() == 1 )
			{
				$user_data = $query->row();

				$this->db->where( 'user_id', $user_data->user_id )
					->update( 
						$this->db_table('user_table'), 
						[
							'passwd' => $this->authentication->hash_passwd( $password ),
							'passwd_recovery_code' => NULL,
							'passwd_recovery_date' => NULL
						] 
					);
			}
		}
	}

	// --------------------------------------------------------------

	/**
     * Get an unused ID for user creation
     *
     * @return  int between 1200 and 4294967295
     */
    public function get_unused_id()
    {
        // Create a random user id between 1200 and 4294967295
        $random_unique_int = 2147483648 + mt_rand( -2147482448, 2147483647 );

        // Make sure the random user_id isn't already in use
        $query = $this->db->where( 'user_id', $random_unique_int )
            ->get_where( $this->db_table('user_table') );

        if( $query->num_rows() > 0 )
        {
            $query->free_result();

            // If the random user_id is already in use, try again
            return $this->get_unused_id();
        }

        return $random_unique_int;
    }

    // --------------------------------------------------------------

}