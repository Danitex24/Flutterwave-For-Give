<?php
/**
 * Process Flutterwave checkout submission.
 *
 * @param array $posted_data List of posted data.
 *
 * @since  1.0.0
 * @access public
 *
 * @return void
 */


function flutterwave_for_give_process_donation( $posted_data ) {



	// Make sure we don't have any left over errors present.
	give_clear_errors();

	// Any errors?
	$errors = give_get_errors();

	// No errors, proceed.
	if ( ! $errors ) {

		$form_id         = intval( $posted_data['post_data']['give-form-id'] );
		$price_id        = ! empty( $posted_data['post_data']['give-price-id'] ) ? $posted_data['post_data']['give-price-id'] : 0;
		$donation_amount = ! empty( $posted_data['price'] ) ? $posted_data['price'] : 0;

		// Setup the payment details.
		$donation_data = array(
			'price'           => $donation_amount,
			'give_form_title' => $posted_data['post_data']['give-form-title'],
			'give_form_id'    => $form_id,
			'give_price_id'   => $price_id,
			'date'            => $posted_data['date'],
			'user_email'      => $posted_data['user_email'],
			'purchase_key'    => $posted_data['purchase_key'],
			'currency'        => give_get_currency( $form_id ),
			'user_info'       => $posted_data['user_info'],
			'status'          => 'pending',
			'gateway'         => 'flutterwave',
		);

		// Record the pending donation.
		$donation_id = give_insert_payment( $donation_data );

		if ( ! $donation_id ) {

			// Record Gateway Error as Pending Donation in Give if not created.
			give_record_gateway_error(
				__( 'Flutterwave Error', 'flutterwave-for-give' ),
				sprintf(
					/* translators: %s Exception error message. */
					__( 'Unable to create a pending donation with Give.', 'flutterwave-for-give' )
				)
			);

			// Send user back to checkout.
			give_send_back_to_checkout( '?payment-mode=flutterwave' );
			return;
		}

		// Do the actual payment processing using the custom payment gateway API. To access the GiveWP settings, use give_get_option() 
                // as a reference, this pulls the API key entered above: give_get_option('insta_for_give_instamojo_api_key')

	} else {

		// Send user back to checkout.
		give_send_back_to_checkout( '?payment-mode=flutterwave' );
	} // End if().
}


add_action( 'give_gateway_flutterwave', 'flutterwave_for_give_process_donation' );