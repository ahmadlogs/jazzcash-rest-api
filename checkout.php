<?php 
/* 
-------------------------------------------------------------------------------- 
| @author Tauseef Ahmed
| Last Upate: 31-OCT-2020 05:25 PM
| 
| Facebook: www.facebook.com/ahmadlogs
| Twitter: www.twitter.com/ahmadlogs
| YouTube: https://www.youtube.com/channel/UCOXYfOHgu-C-UfGyDcu5sYw/
| Blog: https://ahmadlogs.wordpress.com/
 -------------------------------------------------------------------------------- 
 */
 
 
include_once 'include/config.php'; 
include_once 'include/db_connect.php'; 
include_once 'include/JazzcashApi.php'; 

$title = TITLE;

include("include/header.php"); 

/* 
-------------------------------------------------------------------------------- 
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
 -------------------------------------------------------------------------------- 
 */ 
if($_POST)
{
	//echo '<pre>';
	//print_r($_POST);
	//echo '</pre>';
	//exit();
	
	$results = $db->query("SELECT * FROM product WHERE product_id = ".$_POST['product_id']); 
	$row = $results->fetch_array();
	$product_price = $row['price'];

	$data['jazz_cash_no'] =  $_POST['PHONE'];
	$data['cnic_digits']  =  $_POST['CNIC'];
	$data['price'] 		  =  $product_price;
	
	$data['paymentMethod']=  $_POST['paymentMethod'];
	$data['ccNo']		  =  $_POST['ccNo'];
	$data['expMonth']	  =  $_POST['expMonth'];
	$data['expYear']	  =  $_POST['expYear'];
	$data['cvv']		  =  $_POST['cvv'];

	$jc_api = new JazzcashApi();
	
	$response = $jc_api->createCharge($data);
	
		echo '<pre>';
		print_r($response);
		echo '</pre>';
}
/* 
-------------------------------------------------------------------------------- 
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
 -------------------------------------------------------------------------------- 
*/ 
 
/* 
-------------------------------------------------------------------------------- 
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
 -------------------------------------------------------------------------------- 
*/ 
$product_id = $_GET['product_id'];

$results = $db->query("SELECT * FROM product WHERE product_id = ".$product_id); 
$row = $results->fetch_array();

$product_name = $row['name'];
$product_price = $row['price'];
$image = $row['image'];

/* 
-------------------------------------------------------------------------------- 
| NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
 -------------------------------------------------------------------------------- 
*/ 

?>

<!-- container --> 
  <section class="showcase">
    <div class="container">
      <div class="pb-2 mt-4 mb-2 border-bottom">
        <h2><?php echo TITLE;?>  - Checkout</h2>
      </div>      
      <span id="success-msg" class="payment-errors"></span>   
      
<div class=" container-fluid my-5 ">
    <div class="row justify-content-center ">
        <div class="col-xl-10">
            <div class="card shadow-lg ">
                <div class="row justify-content-around">
                    <div class="col-md-5">
                        <div class="card border-0">
                            <div class="card-header pb-0">
                                <h2 class="card-title space ">Checkout</h2>
                                <p class="card-text text-muted mt-4 space">PAYMENT DETAILS</p>
                                <hr class="my-0">
                            </div>
                            <div class="card-body">
							
    <!-- ----------------------------------------------------------------------------------------- -->
	<!-- JAZZCASH payment form -->
    <!-- ----------------------------------------------------------------------------------------- -->
	<form action="<?php echo BASE_URL.'checkout.php?product_id='.$product_id;?>" method="POST" id="myCCForm">
		
		<div class="row">
		
		<div class="col-md-6">
		<div class="form-group">
		<label for="paymentMethod" class="small text-muted mb-1"><b>Pay With Mobile</b></label> 
		<input type="radio" name="paymentMethod" value="jazzcashMobile" checked="" required="" onchange="getValue(this)"> 
		<i class="fa fa-mobile" style="color:orange;"></i>
		</div>
		</div>
		
		
		<div class="col-md-6">
		<div class="form-group">
		<label for="paymentMethod" class="small text-muted mb-1"><b>Pay With Card</b></label> 
		<input type="radio" name="paymentMethod" value="jazzcashCard" checked="" required="" onchange="getValue(this)"> 
		<i class="fab fa-cc-visa" style="color:navy;"></i>
		<i class="fab fa-cc-mastercard" style="color:red;"></i>
		</div>
		</div>
		</div>
		<hr>
		
		<!-- NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN -->
		<div id="mobile_fields">
		
		<div class="form-group"> 
		<label for="PHONE" class="small text-muted mb-1">PHONE NUMBER</label> 
		<input type="text" name="PHONE" value="03123456789" class="form-control form-control-sm" > 
		</div>
		
		<div class="form-group"> 
		<label for="CNIC" class="small text-muted mb-1">LAST 6 DIGITS OF CNIC</label> 
		<input type="text" name="CNIC" value="345678" class="form-control form-control-sm" > 
		</div>
		
		</div>
		<!-- NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN -->
		
		<!-- NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN -->
		<div id="card_fields">
		
		<div class="form-group"> 
		<label for="ccNo" class="small text-muted mb-1">CARD NUMBER</label> 
		<input type="text" name="ccNo" value="5123456789012346" class="form-control form-control-sm" > 
		</div>
		
		<div class="row">
		
		<div class="col-md-4">
		<div class="form-group"> 
		<label for="expMonth" class="small text-muted mb-1">EXP MONTH</label> 
		<input type="text" name="expMonth" value="08" class="form-control form-control-sm" > 
		</div>
		</div>
		
		<div class="col-md-4">
		<div class="form-group"> 
		<label for="expYear" class="small text-muted mb-1">EXP YEAR</label> 
		<input type="text" name="expYear" value="21" class="form-control form-control-sm" > 
		</div>
		</div>
		
		<div class="col-md-4">
		<div class="form-group"> 
		<label for="cvv" class="small text-muted mb-1">CVV</label> 
		<input type="text" name="cvv" value="123" class="form-control form-control-sm" > 
		</div>
		</div>
		
		</div>
		
		</div>
		<!-- NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN -->
		
		<input type="hidden" name="product_id" value="<?php echo $product_id;?>">
		
		<div class="row mb-md-5">
			<div class="col"> 
			<button type="submit" name="" id="" class="btn btn-lg btn-block btn-primary">PURCHASE <?php echo $product_price;?> PKR</button></div>
		</div>
    </form>
	<!-- ----------------------------------------------------------------------------------------- -->
	<!-- ./JAZZCASH payment form -->
	<!-- ----------------------------------------------------------------------------------------- -->

							</div>
                        </div>
                    </div>
					
					
                    <!-- Cart -->
					<div class="col-md-5">
                        <div class="card border-0 ">
                            <div class="card-header pb-0">
                                <h2 class="card-title space ">Cart</h2>
                                <p class="card-text text-muted mt-4 space">YOUR ORDER</p>
                                <hr class="my-0">
                            </div>
							
                            <div class="card-body">
                                <div class="row justify-content-between">
                                    <div class="col-auto col-md-7">
                                        <div class="media flex-column flex-sm-row"> 
										<img class=" img-fluid" src="<?php echo BASE_URL.$image;?>" width="62" height="62">
                                            <div class="media-body my-auto">
                                                <div class="row ">
                                                    <div class="col-auto">
                                                        <p class="mb-0"><?php echo $product_name;?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" pl-0 flex-sm-col col-auto my-auto">
                                        <p class="boxed-1">1</p>
                                    </div>
                                    <div class=" pl-0 flex-sm-col col-auto my-auto ">
                                        <p><?php echo $product_price;?> PKR</p>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row ">
                                    <div class="col">
                                        <div class="row justify-content-between">
                                            <div class="col-4">
                                                <p class="mb-1"><b>Subtotal</b></p>
                                            </div>
                                            <div class="flex-sm-col col-auto">
                                                <p class="mb-1"><b><?php echo $product_price;?> PKR</b></p>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div class="col">
                                                <p class="mb-1">Shipping</p>
                                            </div>
                                            <div class="flex-sm-col col-auto">
                                                <p class="mb-1">0 PKR</p>
                                            </div>
                                        </div>
										<hr class="my-2">
                                        <div class="row justify-content-between">
                                            <div class="col-4">
                                                <p><b>Total</b></p>
                                            </div>
                                            <div class="flex-sm-col col-auto">
                                                <p class="mb-1"><b><?php echo $product_price;?> PKR</b></p>
                                            </div>
                                        </div>
                                        <hr class="my-0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<!-- ./Cart -->
					
                </div>
            </div>
        </div>
    </div>
   
</div>

   
	</div>
  </section>


<script type="text/javascript">
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//handling payment option radio button
//alert("dddd");
document.getElementById("mobile_fields").style.display = 'none';

function getValue(x) 
{
	if(x.value == 'jazzcashMobile'){
		document.getElementById("mobile_fields").style.display = 'block';
		document.getElementById("card_fields").style.display = 'none';
	}
	else if(x.value == 'jazzcashCard')
	{
		document.getElementById("mobile_fields").style.display = 'none';
		document.getElementById("card_fields").style.display = 'block';
	}
}

</script>


<?php include("include/footer.php"); ?>