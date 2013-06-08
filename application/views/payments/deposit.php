<div class="row-fluid">
  <div class="span4">
  	<h2>Instructions</h2>
  	<p>Deposit money to the PayPal account and it will be reflected in your account in the portal
  	</p>
  </div>
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
	</form>
</div>
<div class="span4">
  	<?php echo form_open('payments/deposit/paypaldeposit','class="form-horizontal"'); ?>
	  <fieldset>
	    <legend>Transfer From Bank Account</legend>
	    <label>Enter Amount</label>
	    <input type="text" name="amount" placeholder="Amount in Dollars"/>
	   
	    <label class="checkbox">
	      <input type="checkbox"> I am sure of this step
	    </label>
	    <button type="submit" class="btn">Checkout</button>
	  </fieldset>
	</form>
</div>
</div>
