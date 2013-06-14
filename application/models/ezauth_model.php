<?php
class EzAuth_Model extends CI_Model {

//	user defined:
var $program				= 	'default';			//	program name (for example, ezsell, ezstore, user-defined program)
var $protected_pages 		= 	array();			//	pages to protect along with access level

var $roles = array('user', 'admin');



//	other user defined variables:
var $cookie_expire			=	'86500';			//	when to expire cookies
var	$cookie_domain			=	'';					//	cookie domain

//ezauth defined:
var $user		 			=	array();	//	user info in database

	function __construct(){
		parent::__construct();		
		//session auth
		if ($this->session->userdata('ez_user')) {
			$this->user = $this->session->userdata('ez_user');
		}
		
	}
	
//	v 0.2
//	auto-login is not very secure, as if anyone can get access to the cookie in the client's browser they will be able to login with their identity. it is used for convenience.
//	do not use auto-login if you are dealing with personal information that visitors will not want anyone to see.
//	this is also a threat to people using public computers. allow the user to choose to save login data or not.
//	to enhance security, enable cookie encryption and set a very unique encryption key
//	also, encourage strong passwords
	
	function auto_login() {
		if (empty($this->user)) {
			$cookie_hash = get_cookie('ezauth_user_info', TRUE);
			if (!empty($cookie_hash)) $result = $this->login(null, null, true, $cookie_hash);
			if (!empty($result)) if ($result['authorize'] == true) return true;
			
		}
	}
	
	//	v 0.2
	function logout($redirect = '', $drop_cookie = true) {
		$this->session->sess_destroy();
		if ($drop_cookie == true) $this->drop_userinfo();
		if (!empty($redirect)) redirect($redirect);
	}
	
	//	v 0.3
	//establish user session if login information correct
	//can call login() and use default POST mobile_number and POST password or use parameters login('mobile_number here', 'password here');
	//new in 0.6 - can login from cookie hash

	function login($un = null, $pw = null, $give_new_key = false, $cookie_hash = '', $get_all_user_info = true) {
		$un = (empty($un)) ? $this->input->post('mobile_number') : $un;
		$pw = (empty($pw)) ? $this->input->post('password') : $pw;
	 	
	 	//-------This is the select statement -----------
	 	if ($get_all_user_info == false) {
			$this->db->select('ez_users.id');
		} 
		else {
			$this->db->select('ez_users.id as id, ez_users.*, ez_auth.activation_code');
		}

		// ----If we have a cookie hash,Use It else use the Mobile/password Pair -------------------
		if (empty($cookie_hash)) {
			$this->db->join('ez_auth', 'ez_auth.user_id = ez_users.id');
			$query = $this->db->get_where('ez_users', array('lower(mobile_number)' => strtolower($un), 'password' => $this->_add_salt($pw)));
		} else {
			/*This wont be executed for FlexiPay as we aren't logging in with a cookie -----*/
			$this->db->join('ez_users', 'ez_auth.user_id = ez_users.id', 'left');
			$query = $this->db->get_where('ez_auth', array('cookie_hash' => $cookie_hash));
		}
		
		if ($query->num_rows() > 0) {
			$userdata = $query->row();
			//	check to see if user's account has been activated:--> This is a Back-up Measure
			$v_code = md5(md5($userdata->mobile_number).md5($userdata->email).md5($userdata->register_date));
			if ($userdata->activation_code != $v_code) return array('authorize' => false, 'error' => 'Account not active.', 'code'	=>	'not_active');
			

			//---- This is where we are giving the user an access key for different parts of the app ----------
			$this->db->select('program, access');
			$query2 = $this->db->get_where('ez_access_keys', array('user_id' => $userdata->id));
			$userdata2 = $query2->result();
			$ez = $userdata;
			
			foreach ($userdata2 as $id => $accesskey) {
				$prg = $accesskey->program;
				$ez->access_keys->$prg = $accesskey->access;		
			}

			$this_prg = $this->program;
			$this_key = (!empty($ez->access_keys->$this_prg)) ? $ez->access_keys->$this_prg : '';

			//	if user does not have a key to specific program, but already has a membership at this site
			//	if give_new_key is set to true, give the user a new key for new program
			if (empty($this_key) && $give_new_key == true) {
				$this->new_access_key($ez->id, $this_prg, 'user');				
				$ez->access_keys->$this_prg = 'user';
			}
			
			
			/* old way of checking for active key
			if (!array_key_exists($this_key, $this->roles)) return array('authorize' => false, 'error' => 'Invalid access key for this program!', 'code'	=>	'nosuchkey');
			*/
			
			//	unset activation code
			unset($ez->activation_code);
			
			$this->update_session($ez);
			
			//print_r($ez); //Checking the session

			//Get the Balance of receiver_id
			$query=$this->db->where('user_id', $ez->id);
			$query = $this->db->get('account');		
			$result = $query->row();
			$account_balance = $result->account_balance;

			//Get the Names of the Customer to be stored in the Session
			$full_names=$this->get_entity_name($ez->id);

			$session_data= array(
				'user_id' => $ez->id,
				'full_names'=>$full_names,
				'mobile_number'=>$ez->mobile_number,
				'access_keys'=>$ez->access_keys->$prg,
				'authorize' => true,
				'account_balance' => $account_balance,
				'success'=>true
				);
			return $session_data;

		} else {
			$session_data= array(
				'authorize' => false,
				 'error' => 'Invalid mobile_number/password combination!'
				);
			return $session_data;
		}
	}
	
	//	v 0.1
	/*
		give user another access key for specific region on website
	*/
	function new_access_key($user_id, $program, $access = 'user') {
		$inp = array(
			'user_id'	=>	$user_id,
			'program'	=>	$program,
			'access'	=>	$access,
		);
		
		$this->db->insert('ez_access_keys', $inp);
	}
	
	//	v 0.1
	/*
		update session data with user user data
	*/
	function update_session($ez_array) {
		$ez['ez_user'] = $ez_array;
		$this->session->set_userdata($ez);
		$this->user = $ez_array;
		
	}
	
	//	v 0.2
	//authorize current user for page specified
	//returns 'yes' or 'no' with error message
	function authorize($page, $give_new_key = false) {
		$pp = $this->protected_pages; //Protected Pages; Not applicable in flexi-pay
		$auth_ok = array('authorize' => true);
		$auth_not_ok = array('authorize' => false, 'error' => 'Invalid user name/password for this program.');
		$prg = $this->program;
		
		//	get user access keys
		//	check for universal key for program first, if no universal key, get program key	
		$program_key = (!empty($this->user)) ? (!empty($this->user->access_keys->all)) ? $this->user->access_keys->all : ((!empty($this->user->access_keys->$prg)) ? $this->user->access_keys->$prg : '') : '';
		
		
		//echo 'program key: ' . $program_key;
		
		if (!empty($this->user)) {
			if (empty($program_key) && $give_new_key == true) {
				$this->new_access_key($this->user->id, $prg, 'user');
			
				$this->user->access_keys->$prg = 'user';
				$program_key = 'user';
				
				// 	update session with new information
				$new_user = $this->user;
				$this->update_session($new_user);
			}
		}

		//	if page not protected, return authorize = true
		// 	else check to see that user has correct key for page
		if (!array_key_exists($page, $pp)) {
			if (!array_key_exists('all', $pp)) {
				return $auth_ok;
			} else {
				if ($program_key == 'user' || $program_key == 'admin') return $auth_ok; else return $auth_not_ok;
			}
			
		} else {
			if ($pp[$page] == 'anyone') {
				return $auth_ok;
			} else {
				if ($program_key == '') return $auth_not_ok;
				
				switch ($pp[$page]) {
					case 'user':
						if ($program_key == 'user' || $program_key == 'admin')  return $auth_ok;
					break;
					case 'admin':
						if ($program_key == 'admin') return $auth_ok;
					break;
				}
			}
		}
		return $auth_not_ok;
	}
	
	//	v 0.4
	//new in 0.6: activation keys are generated by user information and a timestamp, and are only enabled after a user verifies their phone_number & email
	//this can be overridden by setting verify to false
	function register($inp, $verify = true, $remember_user = false, $login_after_register = false) {
		//TODO
		if (empty($inp['ez_users']['mobile_number'])) return array('reg_ok' => 'no', 'error' => 'Mobile Number is required.');
		if (empty($inp['ez_users']['email']) && $verify == true) return array('reg_ok' => 'no', 'error' => 'E-mail required for e-mail validation.');


		$timestamp = date("Y-m-d H:i:s");
		
		//trim whitespace from mobile_number and email
		$inp['ez_users']['mobile_number'] = trim($inp['ez_users']['mobile_number']);
		$inp['ez_users']['email'] = trim($inp['ez_users']['email']);
		$inp['ez_users']['register_date'] = $timestamp;
		
		//	convert string to lower case before finding duplicates in database
		//	keeps original data as is
		$mobile = strtolower($inp['ez_users']['mobile_number']);
		$pw = $inp['password'];
		$email = strtolower($inp['ez_users']['email']);

		//	check for existing Mobile Number
		$this->db->where('trim(mobile_number)',$mobile);
		$query = $this->db->get('ez_users');
		if ($query->num_rows() > 0) 
		return array('reg_ok'=>'no','error'=>'The phone number entered already exist');

		
		///check for existing e-mail if e-mail needs validation
		if ($verify) {
			$this->db->select('ez_users.id as id, ez_auth.email_activationcode, ez_users.mobile_number, ez_users.email, ez_users.register_date');
			$this->db->join('ez_auth', 'ez_auth.user_id = ez_users.id', 'left');
			$this->db->where('trim(lower(email))', $email);
			$query = $this->db->get('ez_users');
			
			if ($query->num_rows() > 0) {
				$user = $query->row();

				$v_code = md5(md5($user->mobile_number).md5($user->email).md5($user->register_date));//Find out why this line is needed!
				return array('reg_ok' => 'no', 'error' => 'E-mail address is already in use.');
			}
		}
		
		// 1. insert into ez_users table
		$this->db->insert('ez_users', $inp['ez_users']);
		$user_id = $this->db->insert_id();


		// 2. insert into the Account Table		
		$inp2 = array(
			'user_id'		   =>	$user_id,
			'account_balance'  =>	0
		);	
		$this->db->insert('account', $inp2);
	

		//	make very unique cookie hash for auto-login
		$salt = microtime();
		$cookie_hash = $this->_add_salt($user_id.$inp['password'].$email, $salt);
		
		
		//3. insert into ez_auth table
		$inp3 = array(
			'user_id'		=>	$user_id,
			'password'		=>	$this->_add_salt($pw),
			'cookie_hash'	=>	$cookie_hash
		);
		
		
		//4. For sms & email verification
		if ($verify == true) {
			$v_code = $this->random_string(5);	//	temporary verification code that will be sent to sms for verification		
			$inp3['sms_activationcode'] = $v_code;

			//Email verification
			$v_code2 = md5(md5(microtime()).md5($this->random_string(32))); //	temporary access code that will be sent to e-mail for verification
			$md5_vcode = $this->_add_salt($v_code2);
			$inp3['email_activationcode'] = $md5_vcode;
		} 
		$this->db->insert('ez_auth', $inp3);		
		

		/*5. Access-Keys for various Pages in the site*/
		$keys_ins = 'insert into ez_access_keys (user_id, program, access) values';
		$max = count($inp['ez_access_keys']);
		$i=0;

		foreach ($inp['ez_access_keys'] as $program => $access) {		
			$i++;		
			$keys_ins .= ' (\''.$user_id.'\', \''.$program.'\', \''.$access.'\')';
			if ($i != $max) $keys_ins .= ',';
			
			//	for login after register process
			$new_access_keys->$program = $access;
		}
		$this->db->query($keys_ins);
		
		
		//6.	login user after registration
		if ($login_after_register == true && $verify == false) {
			$this->login(null, null, false, $cookie_hash);
		}

		return array('reg_ok'=>'yes','sms_code'=>$v_code, 'email_code'=>$v_code2, 'user_id' => $user_id);		
}
	
	//	v 0.3
	/*
	when user clicks link in e-mail, database will change user authorization_code to a md5 hash that will be matched on login
	** new login after verification setting, GIVE NEW KEY IS SET TO FALSE SO A KEY MUST BE ALREADY SET FOR CURRENT PROGRAM IF AUTO-LOGIN IS SET TO TRUE
	*/
	function verify_phone($code, $login_after_verify = false) {
		$code = trim($code);
		if (empty($code)) return false;
		//$salty_code = $this->_add_salt($code); //My Code is not being salted

		//get data for updating tables and auto-login if desired
		$this->db->select('ez_users.id, ez_users.mobile_number, ez_users.email, ez_users.register_date, ez_auth.cookie_hash');
		$this->db->join('ez_users', 'ez_users.id = ez_auth.user_id', 'left');
		$query = $this->db->get_where('ez_auth', array('activation_code' => $code));
		if ($query->num_rows() == 1) {
			$user = $query->row();			
			$v_code = md5(md5($user->mobile_number).md5($user->email).md5($user->register_date));
			
			//	update ez_auth table with new hash code
			$this->db->where('activation_code', $code);
			$this->db->update('ez_auth', array('activation_code' => $v_code));
			
			if ($login_after_verify)
				return $this->login(null, null, false, $user->cookie_hash);		//	login by cookie hash
			else
				return true;
		} else {
			return false;
		}
	}
	
	
	//	v 0.2
	// used for generating random authorization codes and temporary passwords
	function random_string($length= 4) {  
	    // Generate random 4 character string
	    $string = md5(microtime());

	    // Position Limiting
	    $highest_startpoint = 32-$length;

	    // Take a random starting point in the randomly
	    // Generated String, not going any higher then $highest_startpoint
	    $randomString = substr($string,rand(0,$highest_startpoint),$length);

	    return $randomString;
	}
	

	//forgot pw: 2 steps
	//	v 0.1
	//step 1 adds reset code for user to be e-mailed to user to verify e-mail.
	function get_reset_code($user_id) {
		if (empty($user_id)) return false;
		
		$reset_code = $this->_add_salt($this->random_string());
		
			$this->db->where('user_id', $user_id);
			$this->db->update('ez_auth', array('reset_code' => $reset_code));
			
			$this->db->select('email');
			$query = $this->db->get_where('ez_users', array('id' => $user_id));
			$eml = $query->row();
			$email = $eml->email;
			return array('reset_code' => $reset_code, 'email' => $email);
	}
	

	
	//	v 0.1
	//step 2 confirms e-mail address and sets temporary password that can be e-mailed to user.
	function reset_password($code) {
		if (empty($code)) return false;
		
		//	get user email to send new pw
		
		$this->db->select('ez_users.email, ez_users.mobile_number');
		$this->db->join('ez_users', 'ez_users.id = ez_auth.user_id', 'left');
		$query = $this->db->get_where('ez_auth', array('reset_code' => $code));
		$result = $query->row();
		if (empty($result)) return false;
		
		$email = $result->email;
		$un = $result->mobile_number;
		
		$temp_pw = $this->random_string(10);
		$md5pw = $this->_add_salt($temp_pw);
		$this->db->where('reset_code', $code);
		$this->db->update('ez_auth', array('password' => $md5pw, 'reset_code' => ''));
		
		return array('temp_pw' => $temp_pw, 'mobile_number' => $un, 'email' => $email);
	}

	
	//	v 0.2
	function _add_salt($pw, $salt = 'shakeit') {
		$md5_string = '';
		if (is_array($pw)) {
			foreach ($pw as $word) {
				$md5_string .= md5($word);
			}
		} else {
			$md5_string = $pw;
		}
		return md5(md5($salt) .md5($md5_string));
	}
	
	//	v 0.3
	function change_pw($user_id, $new_pw) {
		$this->db->where('user_id', $user_id);
		$this->db->update('ez_auth', array('password' => $this->_add_salt($new_pw)));
		return $this->db->affected_rows();
	}
	
	//	v 0.5
	//	gets user id from email address or mobile_number and activation status
	//	new in 0.6 - check if activated user option
	function get_userid($mobile_number = '', $email = '') {
		if (empty($mobile_number) && empty($email)) return false;
		$un_added = false;
		if (!empty($mobile_number)) {
			$this->db->where('mobile_number', $mobile_number);
			$un_added = true;
		}
		if (!empty($email))
			if ($un_added) $this->db->orwhere('email', $email); else $this->db->where('email', $email);
			
		$this->db->select('ez_users.id as id, ez_auth.activation_code, ez_users.mobile_number, ez_users.email, ez_users.register_date');
		
		//	check if active user
		$this->db->join('ez_auth', 'ez_users.id = ez_auth.user_id', 'left');

		$query = $this->db->get('ez_users');
		$userdata = $query->row();

		//	active user or not
		$v_code = md5(md5($userdata->mobile_number).md5($userdata->email).md5($userdata->register_date));
				
		$active = ($userdata->activation_code == $v_code) ? true : false;
		
		if (!empty($userdata)) $user = array('user_id' => $userdata->id, 'active' => $active); else $user = false;
		
		return $user;
	}
	
	//	functions to remember user when loggin in
	
	//	v 0.3
	//	if you use functions that remember the user, you must have CodeIgniter's cookie helper enabled!!!!
	//	remember to enable cookie encryption and set a very unique encryption KEY
	
	function remember_user($user = null, $reset_cookie = false) {
		$user = (empty($user)) ? $this->user : $user;
		if (empty($user)) return false;
		
		$this->db->select('ez_users.id as id, ez_users.*, ez_auth.cookie_hash, ez_auth.password');
		$this->db->join('ez_users', 'ez_users.id = ez_auth.user_id', 'left');
		$this->db->limit(1);
		$query = $this->db->get_where('ez_auth', array('user_id' => $user->id));
		$db_user = $query->row();
		
		//	stopping point if no user found
		if (empty($db_user)) return false;
		
		//	no cookie hash found, so make new one and save
		//	or if reset cookie is specified
		if (empty($db_user->cookie_hash) || $reset_cookie == true) {
			//	make very unique cookie hash for auto-login
			$salt = microtime();
			$db_user->cookie_hash = $this->_add_salt($db_user->mobile_number.$db_user->id.$db_user->password.$db_user->email, $salt);
			$this->db->where('user_id', $db_user->id);
			$this->db->limit(1);
			$this->db->update('ez_auth', array('cookie_hash' => $db_user->cookie_hash));
		}
		
		//	set cookie with very unique cookie hash
		$cookie = array(
		                   'name'   => 'user_info',
		                   'value'  => $db_user->cookie_hash,
		                   'expire' => $this->cookie_expire,
		                   'domain' => $this->cookie_domain,
		                   'path'   => '/',
		                   'prefix' => 'ezauth_'
		);
		set_cookie($cookie);
		
		return true;
	}
	
	//	v 0.2
	//	gets all user info in ez_users table based on hash saved in cookie
	//	runs cookie through XSS filter
	function fetch_userinfo() {
		$cookie_hash = get_cookie('ezauth_user_info', TRUE);
		
		if (empty($cookie_hash)) return false;
		
		$this->db->select('ez_users.*');
		$this->db->join('ez_users', 'ez_users.id = ez_auth.user_id', 'left');
		$query = $this->db->get_where('ez_auth', array('cookie_hash' => $cookie_hash));
		return $query->row();
	}
	
	//	v 0.2
	function drop_userinfo() {
		delete_cookie('ezauth_user_info');
	}
	
	//	v 0.1
	//	cleans up pending registrations that have not been verified after x days (default is 30)
	function cleanup_pending_registrations($days = '30') {
		
		$query = "DELETE ez_users.*, ez_auth.*, ez_access_keys.* FROM ez_users, ez_auth, ez_access_keys WHERE ez_users.register_date < date_sub(now(), interval ".$days." day) AND ez_users.id = ez_auth.user_id AND ez_access_keys.user_id = ez_users.id AND ez_auth.activation_code != md5(concat(md5(ez_users.mobile_number), md5(ez_users.email), md5(ez_users.register_date)))";
		$query = $this->db->query($query);
		return $this->db->affected_rows();
	}



	//------------------ FLEXIPAY CUSTOM FUNCTIONS ----------------------
	function transaction($input){
		$sender_id= $input['sender_id'];
		$receiver_id= $input['recepient_id'];
		$amount = $input['amount'];
		$transaction = $input['transaction_type'];

		//Get the Balance of sender_id
		$query=$this->db->where('user_id', $sender_id);
		$query = $this->db->get('account');		
		$result = $query->row();
		$sender_balance = $result->account_balance;

		//Get the Balance of receiver_id
		$query=$this->db->where('user_id', $receiver_id);
		$query = $this->db->get('account');		
		$result = $query->row();
		$receiver_balance = $result->account_balance;


	//Transaction Type 1 ---> Payments & Deposit Transactions
				  //	----> Deposits are Between Agent to Customer  
				  //	----> Deposits are Between Merchant to Customer   
				  //	----> Deposits are Between Customer to Customer   
	
	//Transaction Type 2 ---> Withdrawals 
					//	----> May be Between Customer and Agent 
				   //	----> Withdrawals can be Between Customer to Customer 

	//Transaction Type 3 ---> Payments 
					//	----> May be Between Customer and Merchant 
				   //	----> Payments can be Between Customer to Customer 
				   //	----> Payments can be Between Customer to Customer 

		//Does the Account have sufficient Balance 
		if($sender_balance>0 && $sender_balance >= $amount){
		//Perform the Transaction
		$new_receiver_balance = $receiver_balance + $amount;
		$new_sender_balance = $sender_balance - $amount;


		//Update the New Records
		$this->db->where('user_id', $sender_id);
		$this->db->update('account', array('account_balance' => $new_sender_balance));

		$this->db->where('user_id', $receiver_id);		
		$this->db->update('account', array('account_balance' => $new_receiver_balance));

		//Record the Transaction for that User
		$transaction_id = $this->random_string(10);
		$transaction_date = date("Y-m-d H:i:s");
		$transaction_id=strtoupper($transaction_id); //Convert to upper

		$inp = array(
			'transaction_id' => $transaction_id,
			'sender_id' => $sender_id,
			'recipient_id'=>$receiver_id,
			'transaction_type'=>$transaction,
			'amount' => $amount,
			'transaction_date' => $transaction_date 
			); 
		
		$this->db->insert('transaction', $inp);

		//Get Name if Customer Get Names, If Agent/Merchant Produce the Business Name
	 	$inp['sender_id'] = $this->get_entity_name($sender_id);
	 	$inp['recipient_id'] = $this->get_entity_name($receiver_id);
	 	$inp['sender_balance'] = $new_sender_balance;
	 	$inp['success']=true;

		return $inp;		
		}else{
		return array('success'=>false,'message'=>"Failed. Insufficient Funds to be moved from One Account to the Other!");		
		}
	}	

	function get_transaction($user_id){
		$where ='sender_id=\''.$user_id. ' OR recipient_id='.$user_id;
		$this->db->where($where);
		$query = $this->db->get('account');		
		$result = $query->row();

		return $data;		
	}

	//Gets the Names of the Sender and Receiver
	function get_entity_name($query_id){
		//Get the Account Type-Id
		$query=$this->db->where('user_id', $query_id);
		$query = $this->db->get('account');		
		$result = $query->row();
		$account_type_id = $result->account_type_id;

		//Merchant
		if($account_type_id == 1){
			$query=$this->db->where('user_id', $query_id);
			$query = $this->db->get('merchant_description');		
			$result = $query->row();
			$merchant_name = $result->business_name;	
			return $merchant_name;
		//Agent
		}else if ($account_type_id == 2){
			$query=$this->db->where('user_id', $query_id);
			$query = $this->db->get('agent_description');		
			$result = $query->row();
			$agent_name = $result->business_name;
			return $agent_name;	
		//Customer
		}else{
			$query=$this->db->where('id', $query_id);
			$query = $this->db->get('ez_users');		
			$result = $query->row();
			$first_name = $result->first_name;	
			$last_name = $result->last_name;

			$full_names= $first_name . ' ' .$last_name;
			return $full_names;	
		}
	}
}	
?>