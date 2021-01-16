<?php 

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
		$this->password 		= JAZZCASH_PASSWORD;
		$this->integrity_salt 	= JAZZCASH_INTEGERITY_SALT;
		$this->currency 		= JAZZCASH_CURRENCY_CODE;
		$this->language 		= JAZZCASH_LANGUAGE;
		
    } 
 
   
    public function createCharge($form_data)
	{ 
		//------------------------------------------------------
		date_default_timezone_set('Asia/Karachi');
		//ini_set('max_execution_time', 60); //60 seconds = 1 minutes
		//------------------------------------------------------
		
		//------------------------------------------------------
		$DateTime 	= new DateTime();
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
		$temp_amount 	= $form_data['price']*100;
		$amount_array 	= explode('.', $temp_amount);
		$pp_Amount 	= $amount_array[0];
		//------------------------------------------------------

		$additional_data = array();
		$additional_data['pp_TxnDateTime'] 		 = $pp_TxnDateTime;
		$additional_data['pp_TxnExpiryDateTime'] = $pp_TxnExpiryDateTime;
		$additional_data['pp_TxnRefNo'] 		 = $pp_TxnRefNo;
		$additional_data['pp_Amount'] 			 = $pp_Amount;
		
		
		if($form_data['paymentMethod'] == "jazzcashMobile")
		{
			$this->post_url = JAZZCASH_HTTP_POST_URL;
			$data_array = $this->get_mobile_payment_array($form_data,$additional_data);
		}
		elseif($form_data['paymentMethod'] == "jazzcashCard")
		{
			$this->post_url = JAZZCASH_CARD_API_URL;
			$data_array = $this->get_card_payment_array($form_data,$additional_data);

		}
		else
		{
			return "Please elect a valid Payment Method and try again";
		}		
		


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


	private function get_mobile_payment_array($form_data,$additional_data)
	{
		//Transaction Array Mobile
		$data =  array(
			"pp_Language" 			=> $this->language,
			"pp_MerchantID" 		=> $this->merchant_id,
			"pp_SubMerchantID" 		=> "",
			"pp_Password" 			=> $this->password,
			"pp_BankID" 			=> "",
			"pp_ProductID" 			=> "",
			"pp_TxnRefNo" 			=> $additional_data['pp_TxnRefNo'],
			"pp_Amount" 			=> $additional_data['pp_Amount'],
			"pp_TxnCurrency" 		=> $this->currency,
			"pp_TxnDateTime" 		=> $additional_data['pp_TxnDateTime'],
			"pp_BillReference" 		=> "billRef",
			"pp_Description" 		=> "Description",
			"pp_TxnExpiryDateTime" 	=> $additional_data['pp_TxnExpiryDateTime'],
			"pp_SecureHash" 		=> "",
			"ppmpf_1" 				=> "",
			"ppmpf_2" 				=> "",
			"ppmpf_3" 				=> "",
			"ppmpf_4" 				=> "",
			"ppmpf_5" 				=> "",
			"pp_MobileNumber" 		=> $form_data['jazz_cash_no'],
			"pp_CNIC" 				=> $form_data['cnic_digits'],
		);
		
		
		return $data;		
	}
	
	private function get_card_payment_array($form_data,$additional_data)
	{
		//Transaction Array Card
		$data =  array(
			"pp_IsRegisteredCustomer" 		=> "No",
			"pp_ShouldTokenizeCardNumber" 	=> "No",
			"pp_CustomerID" 				=> "test",
			"pp_CustomerEmail" 				=> "test@test.com",
			"pp_CustomerMobile" 			=> "03222852628",
			"pp_TxnType" 					=> "MPAY",
			"pp_TxnRefNo" 					=> $additional_data['pp_TxnRefNo'],
			"pp_MerchantID" 				=> $this->merchant_id,
			"pp_Password" 					=> $this->password,
			"pp_Amount" 					=> $additional_data['pp_Amount'],
			"pp_TxnCurrency" 				=> $this->currency,
			"pp_TxnDateTime" 				=> $additional_data['pp_TxnDateTime'],
			"pp_C3DSecureID" 				=> "",
			"pp_TxnExpiryDateTime" 			=> $additional_data['pp_TxnExpiryDateTime'],
			"pp_BillReference" 				=> "billRef",
			"pp_Description" 				=> "Description of transaction",
			"pp_CustomerCardNumber" 		=> $form_data['ccNo'],
			"pp_CustomerCardExpiry" 		=> $form_data['expMonth'].$form_data['expYear'],
			"pp_CustomerCardCvv" 			=> $form_data['cvv'],
		);
		
		return $data;
	}	
}
?>