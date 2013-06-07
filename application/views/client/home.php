<div class="row-fluid">
  <div class="span9" id="face">
  	<!-- Jumbotron -->
      <div class="jumbotron">
     <h1>PayPal-Mpesa</h1>
        <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
        <a class="btn btn-large btn-success" href="#">Get started today</a>
      </div>
  </div>
  <div class="span3">
  	<div class="login">
  		<form class="">
		  <div class="control-group">
		   
		    <div class="controls">
		      <input type="text" id="inputEmail" placeholder="Email Address">
		    </div>
		  </div>
		  <div class="control-group">
		
		    <div class="controls">
		      <input type="password" id="inputPassword" placeholder="Password">
		    </div>
		  </div>
		  <div class="control-group">
		    <div class="controls">
		      <label class="checkbox">
		        <input type="checkbox"> Remember me
		      </label>
		      <button type="submit" class="btn"><i class="icon-lock icon-large"></i> Sign in</button>
		      <a href="">Signup Here</a>
		    </div>
		  </div>
		</form>
  	</div>
  	<form>
  	<div class="calculator">
  		<table class="table table-hover">
		  <tbody>
		  	<tr>
		    <th>AMOUNT</th>
		    </tr>
		    <tr>
		      <td><input type="text" id="field1" name="field1"></td>
		    </tr>
		    <tr>
		      <th>RECEIVED</th>
		    </tr>
		    <tr>
		      <td><input type="text" id="field2" name="field2"></td>
		    </tr>
		    <tr>
		      <th><input type="button" value="Calculate To MPesa" name="form[Calculate]" id="Calculate" onclick="calculateText();" class="rsform-button" />	
</th>
		    </tr>
		    <tr>
		      <td><input type="text" id="Total" name="Total">
</td>
		    </tr>
		  </tbody>
		</table>
  	</div>
  	</form>
  </div>
</div>
<hr>

<script type="text/javascript">

function calculateText(){

var op1=document.getElementById('field1');

var op2=document.getElementById('field2');

var result=document.getElementById('Total');

if(op1.value=="" || op1.value!=parseFloat(op1.value)) op1.value=0;

if(op2.value=="" || op2.value!=parseFloat(op2.value)) op2.value=0;

result.value=0;

result.value=parseInt(result.value);

result.value=parseInt(result.value)+parseInt(op1.value)+parseInt(op2.value);}
</script>
      

      

      

     