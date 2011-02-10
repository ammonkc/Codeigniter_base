<div class="">

    <h2 class="typeset">Login</h2>
	<h3 class="typeset">Please login with your email address and password below.</h3>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
    <?php echo secure_form_open("auth/login", array('id' => 'login-form'));?>
    	
    	<ul>
    	    <li>
    	        <label for="email">Email:</label>
    	        <?php echo form_input($email);?>
    	    </li>
    	    <li>
    	        <label for="password">Password: <small>(forgot your <?=secure_anchor('auth/forgot_password','password?', array('title' => 'Click here to reset password'))?>)</small></label>
    	        <?php echo form_input($password);?>
    	    </li>
    	    <li>
    	        <label for="remember">Remember Me:</label>
    	        <?php echo form_checkbox($remember);?>
    	    </li>
    	    <li>    	         
    	        <?php echo form_submit('submit', 'Login');?>
    	        <small>Don't have an account yet? <?=secure_anchor('auth/register', 'Register')?></small>
    	    </li>
    	</ul>
      
    <?php echo form_close();?>
    
</div>

<hr class="shadow"/>