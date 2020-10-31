<?php 
/* 
-------------------------------------------------------------------------------- 
| Description of Jazzcash Payment Gateway API V2.0 Library
|
| @category: Libraries
| @author Tauseef Ahmed
|
| Last Upate: 31-OCT-2020 05:25 PM
| Facebook: www.facebook.com/ahmadlogs
| Twitter: www.twitter.com/ahmadlogs
| YouTube: https://www.youtube.com/channel/UCOXYfOHgu-C-UfGyDcu5sYw/
| Blog: https://ahmadlogs.wordpress.com/
 -------------------------------------------------------------------------------- 
 */


class JazzcashApi
{ 

	private $merchant_id;
	private $password;
	private $integrity_salt;
	private $currency;
	private $language;
	private $post_url;
	
	
    function __construct()
    {
        // Set API key
        $this->merchant_id 		= JAZZCASH_MERCHANT_ID;
		$this->password 	= JAZZCASH_PASSWORD;
		$this->integrity_salt 	= JAZZCASH_INTEGERITY_SALT;
		$this->currency 	= JAZZCASH_CURRENCY_CODE;
		$this->language 	= JAZZCASH_LANGUAGE;
		$this->post_url 	= JAZZCASH_HTTP_POST_URL;
    } 
 
   
/* 
-------------------------------------------------------------------------------- 
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
| 1. This function is use to makes a transaction array 
| 2. Then it sends it to Jazz Cash Payment Gateway
| 3. Then it receives response from Payment Gateway
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
 -------------------------------------------------------------------------------- 
 */
    public function createCharge($form_data)
	{ 
		//------------------------------------------------------
		date_default_timezone_set('Asia/Karachi');
		//ini_set('max_execution_time', 60); //60 seconds = 1 minutes
		//------------------------------------------------------
		
		//------------------------------------------------------
		$DateTime 		= new DateTime();
		$pp_TxnDateTime = $DateTime->format('YmdHis');
		//------------------------------------------------------
		
		//------------------------------------------------------
		//expiry date, add 1 hour to $DateTime
		$ExpiryDateTime = $DateTime;
		$ExpiryDateTime->modify('+' . 1 . ' hours');
		$pp_TxnExpiryDateTime = $ExpiryDateTime->format('YmdHis');
		//------------------------------------------------------
		
		//------------------------------------------------------
		//transaction number
		$pp_TxnRefNo = 'T'.$pp_TxnDateTime;
		//------------------------------------------------------
		
		//------------------------------------------------------
		//standard price format i.e. 350.00
		//remove decimal point from price
		//and make it like this 35000
		$temp_amount 	= $form_data['price']*100;
		$amount_array 	= explode('.', $temp_amount);
		$pp_Amount 		= $amount_array[0];
		//------------------------------------------------------

		//Transaction Array 
		$data_array =  array(
			"pp_Language" 		=> $this->language,
			"pp_MerchantID" 	=> $this->merchant_id,
			"pp_SubMerchantID" 	=> "",
			"pp_Password" 		=> $this->password,
			"pp_BankID" 		=> "",
			"pp_ProductID" 		=> "",
			"pp_TxnRefNo" 		=> $pp_TxnRefNo,
			"pp_Amount" 		=> $pp_Amount,
			"pp_TxnCurrency" 	=> $this->currency,
			"pp_TxnDateTime" 	=> $pp_TxnDateTime,
			"pp_BillReference" 	=> "billRef",
			"pp_Description" 	=> "Description",
			"pp_TxnExpiryDateTime" 	=> $pp_TxnExpiryDateTime,
			"pp_SecureHash" 	=> "",
			"ppmpf_1" 		=> "",
			"ppmpf_2" 		=> "",
			"ppmpf_3" 		=> "",
			"ppmpf_4" 		=> "",
			"ppmpf_5" 		=> "",
			"pp_MobileNumber" 	=> $form_data['jazz_cash_no'],
			"pp_CNIC" 		=> $form_data['cnic_digits'],
		);

		$pp_SecureHash = $this->get_SecureHash($data_array);
		
		$data_array['pp_SecureHash'] = $pp_SecureHash;
		
		//sends transaction data to Jazz Cash Payment Gateway
		//and receives response
		$make_call = $this->callAPI(json_encode($data_array));
		
		$make_call = json_decode($make_call, true);
		
		return $make_call;
    }
	
	
/* 
--------------------------------------------------------------------------------
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
--------------------------------------------------------------------------------
 */
	private function get_SecureHash($data_array)
	{
		ksort($data_array);
		
		$str = '';
		foreach($data_array as $key => $value)
		{
			if(!empty($value))
			{
				$str = $str . '&' . $value;
			}
		}
		
		$str = $this->integrity_salt.$str;
		
		$pp_SecureHash = hash_hmac('sha256', $str, $this->integrity_salt);
		
		//echo '<pre>';
		//print_r($data_array);
		//echo '</pre>';
		
		return $pp_SecureHash;
	}
	
/* 
--------------------------------------------------------------------------------
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
--------------------------------------------------------------------------------
 */
	
	private function callAPI($data)
	{
		$curl = curl_init();
		//OPTIONS:
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_URL, $this->post_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		//EXECUTE:
		$result = curl_exec($curl);
		if(!$result){die("Connection Failure");}
		curl_close($curl);
		
		return $result;
	}
	
/* 
--------------------------------------------------------------------------------
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
--------------------------------------------------------------------------------
 */

}
?>
