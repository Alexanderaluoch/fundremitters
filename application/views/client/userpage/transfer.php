<?php $this->load->view('client/userpage/content-top'); ?>
<?php $this->load->view('client/userpage/sidebar'); ?>

<div class="span8">
  <div class="span4">
  	<h2>Instructions</h2>
  	<p>Deposit money to the PayPal account and it will be reflected in your account in the portal
  		
  	</p>
  	<p>M-Pesa Number: 0718640103</p>
  </div>
  <div class="span8">
  	<?php echo form_open('','class="form-horizontal"'); ?>
	  <fieldset>
	    <legend>Withdraw Money From PayPesa Account</legend>
	    <label>Enter Amount</label>
	    <input type="text" name="amount" placeholder="Amount in Dollars"/>
	   
	    <label class="checkbox">
	      <input type="checkbox"> I am sure of this step
	    </label>
	    <button type="submit" class="btn">Withdraw</button>
	  </fieldset>
	</form>
</div>
</div>
<?php $this->load->view('client/includes/footer'); ?>
