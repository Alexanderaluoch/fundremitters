<div class="row-fluid">
  <div class="span4">
  	<h2>Instructions</h2>
  	<p>Deposit money to the PayPal account and it will be reflected in your account in the portal
  	</p>
  </div>
  <div class="span8">
  	<?php echo form_open('payments/deposit/paypaldeposit','class="form-horizontal"'); ?>
	  <fieldset>
	    <legend>Transfer From PesaPay Account to this Person's Mobile Account</legend>
		  <div class="control-group">
		    <label class="control-label" for="inputEmail">First Name</label>
		    <div class="controls">
		      <input type="text" id="inputEmail" placeholder="First Name" required>
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputEmail">Last Name</label>
		    <div class="controls">
		      <input type="text" id="inputEmail" placeholder="Last Name" required>
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputEmail">Phone Number</label>
		    <div class="controls">
		      <input type="text" id="inputEmail" placeholder="Safaricom Number" required>
		    </div>
		  </div>
		  <div class="control-group">
		    <div class="controls">
		      <label class="checkbox">
		        <input type="checkbox"> I am sure of this Action
		      </label>
		      <button type="submit" class="btn">Send Money</button>
		    </div>
		  </div>
	  </fieldset>
	</form>
</div>

</div>
