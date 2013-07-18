<?php $this->load->view('client/userpage/content-top'); ?>
<?php $this->load->view('client/userpage/sidebar'); ?>

<div class="row-fluid">
	<ul class="nav nav-tabs">
	  <li class="active">
	    <a href="#">PayPal</a>
	  </li>
	  <li>
	 	<a href="#"><img src="<?php echo base_url();?>img/Logo/mpesa.jpg" width="70px" height="100px"></a>

	  </li>
	</ul>
  <div class="span4">
  	  <?php echo form_open('payments/deposit/paypaldeposit','class="form-horizontal"'); ?>
	  <fieldset>
	    <legend>Transfer From Paypal Account</legend>
	    <label>Enter Amount</label>
	    <input type="text" name="amount" placeholder="Amount in Dollars"/>
	   
	    <label class="checkbox">
	      <input type="checkbox"> I am sure of this step
	    </label>
	    <button type="submit" class="btn">Checkout</button>
	  </fieldset>
</div>
</div>

<?php $this->load->view('client/includes/footer'); ?>
