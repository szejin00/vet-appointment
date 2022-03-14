<?php
	require_once "stripe-php-master/init.php";
	require_once "products.php";

	$stripeDetails = array(
		"secretKey" => "sk_test_51Kb1xTDeXApwHdaGdfqdBfzBb2WygBZmVNvvYaBbwur9N5RgcIt5ub3OEwHL9iTvsboRi6lTeIfWPu5hCCMxDSQe0070XN61YW",
		"publishableKey" => "pk_test_51Kb1xTDeXApwHdaGmonQ0j5HzLMiw9qt8eKpFITgpLTjWxIcKIvquxbF4VH5Unm2bjU0uodpekQv7mtWSvRkuRJK00H3ilkhhD"
	);

	// Set your secret key: remember to change this to your live secret key in production
	// See your keys here: https://dashboard.stripe.com/account/apikeys
	\Stripe\Stripe::setApiKey($stripeDetails['secretKey']);
?>
