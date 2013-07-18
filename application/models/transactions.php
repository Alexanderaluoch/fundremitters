<?php
class Transactions extends CI_Model {

	function record_transaction($input){
		$transaction_id=1;
		$recipient_id=$input['recipient_id'];
		$amount=$input['amount'];
		$transaction=$input['transaction_type'];

		//Record the Transaction for that User
		$transaction_id = $this->random_string(32,10);
		$transaction_date = date("Y-m-d H:i:s");
		$transaction_id=strtoupper($transaction_id); //Convert to upper

		$inp = array(
			'transaction_id' => $transaction_id,
			'sender_id' => 'PAYPAL',
			'recipient_id'=>$recipient_id,
			'transaction_type'=>$transaction,
			'amount' => $amount,
			'status' =>'pending',
			'transaction_date' => $transaction_date 
			); 
		$session_data=array(
						'transaction_id'=>$transaction_id
						);
		$this->session->set_userdata($session_data);
		$this->db->insert('transaction', $inp);
	}

	function update_transaction(){
		$tid=$this->session->userdata('transaction_id');
		$this->db->where('transaction_id', $tid);
		$this->db->update('transaction', array('status' => 'Complete'));
	}

	function random_string($top=32, $length= 4) {  
	    // Generate random 4 character string
	    $string = md5(microtime());

	    // Position Limiting
	    $highest_startpoint = $top-$length;

	    // Take a random starting point in the randomly
	    // Generated String, not going any higher then $highest_startpoint

	    $randomString = substr($string,rand(0,$highest_startpoint),$length);

	    return $randomString;
	}
	
}

?>