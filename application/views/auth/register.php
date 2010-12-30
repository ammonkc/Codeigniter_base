<section class="">

	<h2 class="typeset">Register</h2>
	<h3 class="typeset">Please enter the users information below.</h3>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
    <?php echo secure_form_open("auth/register", array('id' => 'register-form'));?>
    
        <ul>
            <li>
                <label for="first_name" class="inline-left"><span>First Name</span></label>
                <?php echo form_input($first_name);?>
            </li>
            <li>
                <label for="last_name" class="inline-left"><span>Last Name</span></label>
                <?php echo form_input($last_name);?>
            </li>
            <li>
                <label for="email" class="inline-left"><span>Email</span></label>
                <?php echo form_input($email);?>
            </li>
            <li>
                <label for="phone1" class="inline-left"><span>Phone Number</span></label>
                <?php echo form_input($phone1) . '<span class="dash-wrapper">-</span>' . form_input($phone2) . '<span class="dash-wrapper">-</span>' . form_input($phone3);?>
            </li>
            <li>
                <label for="password" class="inline-left"><span>Password</span></label>
                <?php echo form_input($password);?>
            </li>
            <li>
                <label for="password_confirm" class="inline-left"><span>Confirm Password</span></label>
                <?php echo form_input($password_confirm);?>
            </li>
            <li>
                <?php echo form_submit('submit', 'Register');?>
                <small>Already have an account? <?=secure_anchor('auth/login', 'login here')?></small>
            </li>
        </ul>
      
    <?php echo form_close();?>
    
</section>

<hr class="shadow"/>