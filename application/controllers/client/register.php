<?php
/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Register
 * @category	Controller
 * @author		Tom Kimani adopted from Ezauth
 * @link		http://tomkimani.wordpress.com/
*/

require APPPATH.'/libraries/AfricasTalkingGateway.php';

class Register extends CI_Controller {
	function Register(){
        parent::__construct();
        $this->load->model('EzAuth_Model','ezauth');	
    }
		
	function index() {
		$data = array();
		
		$inp = array(
			'ez_users'	=>	array(
				'first_name'	=>	$this->input->post('first_name'),	//	**	not a default ezauth field!
				'last_name'		=>	$this->input->post('last_name'),	//	**	not a default ezauth field!
				'email'			=>	$this->input->post('email'),		//	**	only required if using verification
       		    'mobile_number' =>  $this->input->post('mobile_number'), //  **  only required if using verification
       		    'country_code'		=>  $this->input->post('countries') 	//  
			),
			'ez_access_keys' => array(			//	new in 0.6	- multiple access keys can be given now during registration
				'userhome'	=>	'user',
			),
			'password2'	=>	$this->input->post('password2'),
		);
			
		$verify_yesno=true;
		
		$user_reg = $this->ezauth->register($inp, $verify_yesno);	//	verify parameter set to true, so verification code will be returned, which can be sent to user
		
		if ($user_reg['reg_ok'] == 'yes' && $verify_yesno == true) {
			
			/*---------------EMAIL CODE ------------------------------------------*/
			$v_code = $user_reg['email_code'];
			//	send user e-mail with verification code.
			$message_email = '<p>This e-mail address was used to sign up on PesaPay. To begin using PesaPay, you must verify your e-mail
			address by clicking the link below or copying it and pasting it into your browser.</p><p>{unwrap}<a href="http://localhost/register/verify/'.$v_code.'" 
			title="Verify your e-mail address">http://localhost/register/verify/'.$v_code.'{/unwrap}</a></p>';
			
			//$this->_send_mail($inp['ez_users']['email'], 'Verify your e-mail address!', $message);

			/*-----------------SMS CODE ************************************/
			$v_code= $user_reg['sms_code'];
			//	send sms to user with the verification code.
			$message_sms = 'Your PesaPay verification code is '.strtoupper($v_code).'.Enter this code on the verification screen provided. Thank-you for registering with us.';           
            echo $message_sms;
            //$this->_send_sms($inp['ez_users']['mobile_number'],  $message_sms); 			
		}
			if ($user_reg['reg_ok'] == 'yes') {
			$this->load->view('client/verify');
		} else if($user_reg['reg_ok'] == 'no'){
			$data['disp_error'] = 'Correct the following errors to continue:<br/>' .$user_reg['error'];
			$data['main_content']='client/register';		
			// Loading the Country model drop down list 
			$this->load->model('countries','countries');
			$data['countries'] = $this->countries->get_dropdownlist();
			$this->load->view('client/includes/template',$data);
		}else{	   
		$data['main_content']='client/register';		
		// Loading the Country model drop down list 
		$this->load->model('countries','countries');
		$data['countries'] = $this->countries->get_dropdownlist();
		$this->load->view('client/includes/template',$data);
		}
	}

	function reg_ok() {
		$this->load->view('reg_ok_view');
	}
	
	function _send_mail($to, $subject, $message) {
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$config['protocol'] = 'sendmail';
		$this->email->initialize($config);
		$this->email->from('admin+noreply@pesapay.com', 'PesaPay Admin');	
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);	

		$this->email->send();
	}

	//----------Function to send sms-------------------
    function _send_sms($recipient,$message){
        // Specify your login credentials
        $username    = "TomKim";
        $apiKey      = "1473c117e56c4f2df393c36dda15138a57b277f5683943288c189b966aae83b4"; 

        // Create a new instance of our awesome gateway class
        $gateway  = new AfricaStalkingGateway($username, $apiKey);

        // Thats it, hit send and we'll take care of the rest
        $results  = $gateway->sendMessage($recipient, $message);
        if ( count($results) ) {
          // These are the results if the request is well formed
          foreach($results as $result) {
         /* 
         	echo " Number: " .$result->number;
            echo " Status: " .$result->status;
            echo " Cost: "   .$result->cost."\n";
          */
          }
        } else {
            // We only get here if we cannot process your request at all
            // (usually due to wrong username/apikey combinations)
            echo "Oops, No messages were sent. ErrorMessage: ".$gateway->getErrorMessage();
        }
        // DONE!!!
    }

		
	function verify_email() {
		if ($this->ezauth->verify_email($this->uri->segment(3))==true) {
			$this->load->view('verify_ok');
		} else {
			redirect('mystore');
		}
	}

	function verify_sms() {
		if ($this->ezauth->verify_sms($this->input->post('verify_sms')) == true) {
			redirect('client/company/userpage');
		} else {
			$data['disp_error']='Incorrect verification code Entered.Retry';
			$data['main_content']='client/verify';
			$this->load->view('client/includes/template',$data);
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