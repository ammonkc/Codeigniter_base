<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * Authorize.NET AIM processing class for CodeIgniter
 *
 * A class to simplify the processing of payments using Autorize.NET AIM.
 * This does not do everything but is a good start to processing payments
 * using CodeIgniter.
 *
 * Based off a class by: Micah Carrick
 * Website:	http://www.micahcarrick.com
 * 
 * @package		Authorize.NET
 * @author		Ray (Ideal Web Solutions)
 * @email		dev@idealws.net
 * @copyright	Copyright (c) 2009, Ideal Web Solutions, LLC.
 * @link		http://idealws.com
 * @since		Version 1.0
 * @filesource
 */
class Authorize_net {
	
	var $field_string;
	var $fields = array();	
	var $response_string;
	var $response = array();
	var $debuginfo;
	var $gateway_url = "https://secure.authorize.net/gateway/transact.dll";
   
    /**
     * __construct
     * 
     * Loads the configuration settings for Authorize.NET
     * 
     * @return void
     * @author Ammon Casey
     **/
    public function __construct()
    {
		$this->CI =& get_instance();
        $this->CI->load->config('authorize_net', TRUE);
        
		if($this->CI->config->item('authorize_net_test_mode', 'authorize_net') == 'TRUE') {			
			$this->gateway_url = $this->CI->config->item('authorize_net_test_api_host', 'authorize_net');
			$this->add_x_field('x_test_request', $this->CI->config->item('authorize_net_test_mode', 'authorize_net'));
			$this->add_x_field('x_login', $this->CI->config->item('authorize_net_test_x_login', 'authorize_net'));
			$this->add_x_field('x_tran_key', $this->CI->config->item('authorize_net_test_x_tran_key', 'authorize_net'));
		}else{
			$this->gateway_url = $this->CI->config->item('authorize_net_live_api_host', 'authorize_net');
			$this->add_x_field('x_test_request', $this->CI->config->item('authorize_net_test_mode', 'authorize_net'));
			$this->add_x_field('x_login', $this->CI->config->item('authorize_net_live_x_login', 'authorize_net'));
			$this->add_x_field('x_tran_key', $this->CI->config->item('authorize_net_live_x_tran_key', 'authorize_net'));
		}
		$this->add_x_field('x_version', $this->CI->config->item('authorize_net_x_version', 'authorize_net'));
      	$this->add_x_field('x_delim_data', $this->CI->config->item('authorize_net_x_delim_data', 'authorize_net'));
      	$this->add_x_field('x_delim_char', $this->CI->config->item('authorize_net_x_delim_char', 'authorize_net'));  
      	$this->add_x_field('x_encap_char', $this->CI->config->item('authorize_net_x_encap_char', 'authorize_net')); 
      	$this->add_x_field('x_url', $this->CI->config->item('authorize_net_x_url', 'authorize_net'));
      	$this->add_x_field('x_type', $this->CI->config->item('authorize_net_x_type', 'authorize_net'));
      	$this->add_x_field('x_method', $this->CI->config->item('authorize_net_x_method', 'authorize_net'));
      	$this->add_x_field('x_relay_response', $this->CI->config->item('authorize_net_x_relay_response', 'authorize_net'));
      	$this->add_x_field('x_currency_code', $this->CI->config->item('authorize_net_x_currency_code', 'authorize_net'));
      	$this->add_x_field('x_duplicate_window', $this->CI->config->item('authorize_net_x_duplicate_window', 'authorize_net'));
	}
	
	/**
	 * Add field to query for processing
	 * 
	 * Used to add a field to send to Autorize.NET for payment processing.
	 * 
	 * @param mixed $field
	 * @param mixed $value
	 * @access	public
	 */
	function add_x_field($field, $value = '') {
	    if ( is_array($field) )
	    {
	        foreach($field as $key => $value)
	        {
	            $this->fields[$key] = $value;
	        }
	    }else{
            $this->fields[$field] = $value;
        }
    }

   /**
    * Process payment
    * 
    * Send the payment to Authorize.NET for processing. Returns the response codes
    * 1 - Approved
    * 2 - Declined
    * 3 - Transaction Error
    * There is no need to check the MD5 Hash according to Authorize.NET documentation
	* since the process is being sent and received using SSL. 
    * 
    * For your reference, you can use the following test credit card numbers when testing your connection. The expiration date must be set to the present date or later:
    * - American Express Test Card: 370000000000002
    * - Discover Test Card: 6011000000000012	
    * - Visa Test Card: 4007000000027	
    * - Second Visa Test Card: 4012888818888	
    * - JCB: 3088000000000017	
    * - Diners Club/ Carte Blanche: 38000000000006
    *
    * @access	public
    * @return	returns response code 1,2,3
    */
   function process_payment() {
		foreach( $this->fields as $key => $value ) {
			$this->field_string .= "$key=" . urlencode( $value ) . "&";
		}
		$ch = curl_init($this->gateway_url); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $this->field_string, "& " )); 
		$this->response_string = urldecode(curl_exec($ch)); 
		
		if (curl_errno($ch)) {
			$this->response['Response_Reason_Text'] = curl_error($ch);
			return 3;
		}else{
			curl_close ($ch);
		}
		$temp_values = explode($this->CI->config->item('authorize_net_x_delim_char', 'authorize_net'), $this->response_string);
		$temp_keys = array ( 
			"Response_Code", "Response_Subcode", "Response_Reason_Code", "Response_Reason_Text",
			"Approval_Code", "AVS_Result_Code", "Transaction_ID", "Invoice_Number", "Description",
			"Amount", "Method", "Transaction_Type", "Customer_ID", "Cardholder_First_Name",
			"Cardholder Last_Name", "Company", "Billing_Address", "City", "State",
			"Zip", "Country", "Phone", "Fax", "Email", "Ship_to_First_Name", "Ship_to_Last_Name",
			"Ship_to_Company", "Ship_to_Address", "Ship_to_City", "Ship_to_State",
			"Ship_to_Zip", "Ship_to_Country", "Tax_Amount", "Duty_Amount", "Freight_Amount",
			"Tax_Exempt_Flag", "PO_Number", "MD5_Hash", "Card_Code_CVV_Response Code",
			"Cardholder_Authentication_Verification_Value_CAVV_Response_Code"
		);
		for ($i=0; $i<=27; $i++) {
			array_push($temp_keys, 'Reserved_Field '.$i);
		}
		$i=0;
		while (sizeof($temp_keys) < sizeof($temp_values)) {
			array_push($temp_keys, 'Merchant_Defined_Field '.$i);
			$i++;
		}
		for ($i=0; $i<sizeof($temp_values);$i++) {
			$this->response["$temp_keys[$i]"] = $temp_values[$i];
		}
		return $this->response['Response_Code'];
   }
   
   /**
    * Get the response text.
    * 
    * Returns the response reason text for the payment processed. Must be called
    * after you have caled process_payment().
    * 
    * @access	public
    * @return	returns the response reason text
    */
   function get_response_reason_text() {
		return $this->response['Response_Reason_Text'];
   }
   
	/**
	 * Get all the codes returned
	 * 
	 * With this function you can retreive all response codes and values
	 * from your transaction. This must be called after your have called 
	 * the process_payment() function.
	 * 
	 * @access	public
	 * @return returns all codes and values in a array.
	 */
	function get_all_response_codes() {
		return $this->response;
	}

   /**
    * Dump fields sent to Authorize.NET
    * 
    * This is used for de bugging purposes. It will output the
    * field/value pairs sent to Authorize.NET to process the 
    * payment. Must be called after the process_payment() function
    * 
    * @access	public
    * @return	prints output directly to browser.
    */
   function dump_fields() {				
		echo "<h3>authorizenet_class->dump_fields() Output:</h3>";
		echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
		    <tr>
		       <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
		       <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
		    </tr>"; 
		    
		foreach ($this->fields as $key => $value) {
		 echo "<tr><td>$key</td><td>".urldecode($value)."&nbsp;</td></tr>";
		}
		
		echo "</table><br>"; 
   }

   /**
    * Dump response from Authorize.NET
    * 
    * This will return the complete output sent from Authorize.NET
    * after payment has been processed. Whether approved, declined 
    * or transaction error. Must be called after the process_payment()
    * function.
    * 
    * @access	public
    * @return	returns all the field/value pairs
    */
   function dump_response() {             
      $i = 0;
      foreach ($this->response as $key => $value) {
         $this->debuginfo .= "$key: $value\n";
         $i++;
      } 
      return $this->debuginfo;
   }
}