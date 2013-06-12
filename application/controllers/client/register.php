<?
class MyStore extends CI_Controller {
	function MyStore(){
        parent::__construct();
        $this->load->model('EzAuth_Model','ezauth');	
        $this->ezauth->program = 'mystore';

		//new in 0.6
		//if user has a cookie hash saved, ezauth can use it to login automatically
		//must have cookie helper enabled
		$this->ezauth->auto_login();

        $this->ezauth->protected_pages = array(
            'client'    	 	=>    	'user',			     //	user must be logged in to view page
            'admin'   			=>    	'admin',			//	user must be administrator to view page
            'custom_page'  		=>    	'store_manager',	//	user defined value
			'changepw'			=>		'user'
        );
    }
	
	function index() {
		$this->load->view('home');
	}
	
	//	new remap function in 0.6, method is called including arguments now.	
	//  Used authorizes transactions by redirecting all functions to one common function
	function _remap($method) {
	        $auth = $this->ezauth->authorize($method, true);
	        if ($auth['authorize'] == true) {
				//	redirect with method arguments
				//	by marlar on CodeIgniter forums
				$segments = array_slice($this->uri->segment_array(),2);
				call_user_func_array(array(&$this, $method), $segments);
	        } else {
	            // user login information incorrect, so show login screen again
	            redirect('mystore/login');
	        }
	}
	
	function client() {
		$this->load->view('client_view');
	}
	
	function admin() {
		$this->load->view('admin_view');
	}
	
	function login($data = array()) {
	        
	        // set required fields for validation
	        $rules['username'] = "required";
	        $this->validation->set_rules($rules);
	        $fields['username'] = "Username";
	        $this->validation->set_fields($fields);
			
	        // if post variables are set, run validation
	        if ($this->validation->run()) {
	            $login_ok = $this->ezauth->login();    // $login_ok is true or false depending on user login information
	            if ($login_ok['authorize'] == true) {
					$this->ezauth->remember_user();		// store cookie hash for auto-login
					redirect('mystore');    		// if user logs in successfully, redirect to main page
				} else {
					$data['error_string'] = $login_ok['error'];
				}

	        }
			$this->load->view('login_view',$data);
	}
	
	function register() {
		$data = array();
		$rules = array(
			'username'		=>	'trim|required|min_length[5]|max_length[30]',
			'email'			=>	'trim|required|valid_email',
			'password'		=>	'required|matches[password2]',
			'password2'		=>	'required',
			'first_name'	=>	'trim',
			'last_name'		=>	'trim'
		);
		$fields = array(
			'username'		=>	'Username',
			'email'			=>	'E-mail address',
			'password'		=>	'Password',
			'password2'		=>	'Password Confirmation',
			'first_name'	=>	'First Name',
			'last_name'		=>	'Last Name'
		);
		$this->validation->set_rules($rules);
		$this->validation->set_fields($fields);
		if ($this->validation->run()) {
			$inp = array(
				'ez_users'	=>	array(
					'username'		=>	$this->input->post('username'),		//	**	required field!!
					'first_name'	=>	$this->input->post('first_name'),	//	**	not a default ezauth field!
					'last_name'		=>	$this->input->post('last_name'),	//	**	not a default ezauth field!
					'email'			=>	$this->input->post('email')			//	**	only required if using verification
				),
				'ez_access_keys' => array(			//	new in 0.6	- multiple access keys can be given now during registration
					'mystore'	=>	'user',
					'ezboard'	=>	'user',
					'ezblog'	=>	'user',
				),
				'password'	=>	$this->input->post('password'),
			);
			
			$verify_yesno = ($this->input->post('verify')) ? true : false;
			$user_reg = $this->ezauth->register($inp, $verify_yesno);	//	verify parameter set to true, so verification code will be returned, which can be sent to user
			if ($user_reg['reg_ok'] == 'yes' && $verify_yesno == true) {
				$v_code = $user_reg['code'];

				//	send user e-mail with verification code.
				$message = '<p>This e-mail address was used to sign up on {My Website}. To begin using {My Website}, you must verify your e-mail
				address by clicking the link below or copying it and pasting it into your browser.</p><p>{unwrap}<a href="http://bizwidgets.biz/demos/ezauth/mystore/verify/'.$v_code.'" 
				title="Verify your e-mail address">http://bizwidgets.biz/demos/ezauth/mystore/verify/'.$v_code.'{/unwrap}</a></p>';
				
				$this->_send_mail($inp['ez_users']['email'], 'Verify your e-mail address!', $message);
				
			}
			if ($user_reg['reg_ok'] == 'yes') {
				redirect('mystore/reg_ok');
			} else {
				$data['disp_error'] = 'Error. EzAuth response:<br />' . $user_reg['error'];
			}
		}
		
		$this->load->view('register_view', $data);
	}

	
	
	function reg_ok() {
		$this->load->view('reg_ok_view');
	}
	
	function _send_mail($to, $subject, $message) {
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$config['protocol'] = 'sendmail';
		$this->email->initialize($config);
		$this->email->from('admin+noreply@bizwidgets.biz', 'Friendly BizWidgets Bot');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);	

		$this->email->send();
	}
	
	function logout() {
		$this->ezauth->logout();
		redirect('mystore');
	}
	
	function verify() {
		if ($this->ezauth->verify_email($this->uri->segment(3)) == true) {
			$this->load->view('verify_ok');
		} else {
			redirect('mystore');
		}
	}
	
	function forgotpw1() {
		$data = array();
		$fields = array(
			'username'	=>	'trim',
			'email'		=>	'trim'
		);
		$rules = array(
			'username'	=>	'User name',
			'email'		=>	'E-mail address'
		);
		$this->validation->set_rules($rules);
		$this->validation->set_fields($fields);
		if ($this->validation->run()) {
			$user = $this->ezauth->get_userid($this->input->post('username'), $this->input->post('email'));
			$usr = $this->ezauth->get_reset_code($user['user_id']);
			$message = auto_link('here is your reset code: http://bizwidgets.biz/demos/ezauth/mystore/forgotpw2/'.$usr['reset_code']);
			$this->_send_mail($usr['email'], 'Reset Code', $message);
			$data['disp_message'] = 'A reset code was sent to your e-mail address. Check your e-mail!';
		}
		$this->load->view('forgotpw1', $data);
	}
	
	function forgotpw2() {
		$reset_code = $this->uri->segment(3);
		if (empty($reset_code)) return false;
		$usr = $this->ezauth->reset_password($reset_code);
		$message = 'Username: '.$usr['username']. '. Here is your temporary password: '.$usr['temp_pw'];
		$this->_send_mail($usr['email'], 'Temporary Password', $message);
		$data['disp_message'] = 'Your temporary password was e-mailed to you. Check your e-mail!';
		$this->load->view('forgotpw2', $data);
	}
	
	function changepw() {
		$data = array();
		$un = $this->ezauth->user->username;
		if ($un == 'admin' || $un == 'client') {
			$data['disp_error'] = 'You can\'t be logged in as "admin" or "client" when trying to change an account password.';
			$this->load->view('forgotpw2',$data);
			return;
		}
		$rules = array(
			'old_password'	=>	'required',
			'new_password'	=>	'required|matches[new_password2]',
			'new_password2'	=>	'required'
		);
		$fields = array(
			'old_password'	=>	'Old Password',
			'new_password'	=>	'New Password',
			'new_password2'	=>	'Confirm New Password'
		);
		
		$this->validation->set_fields($fields);
		$this->validation->set_rules($rules);
		
		if ($this->validation->run()) {
			$result = $this->ezauth->change_pw($this->ezauth->user->id, $this->input->post('old_password'), $this->input->post('new_password'));
			if ($result) $data['disp_message'] = 'Password changed!'; else $data['disp_message'] = 'Password not changed.';
		}
		
		$this->load->view('changepw_view', $data);
	}
	
	
}