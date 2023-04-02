<?php
/**
 * Process Flutterwave checkout submission.
 *
 * @param array $posted_data List of posted data.
 *
 * @since  1.0.0
 */
function flutterwave_for_give_process_donation( $posted_data ) {

	// Make sure we don't have any left over errors present.
	give_clear_errors();

	// Sanitize posted data.
	$form_id  = absint( $posted_data['post_data']['give-form-id'] );
	$price_id = ! empty( $posted_data['post_data']['give-price-id'] ) ? absint( $posted_data['post_data']['give-price-id'] ) : 0;
	$amount   = ! empty( $posted_data['price'] ) ? give_sanitize_amount( $posted_data['price'] ) : 0;
	$email    = sanitize_email( $posted_data['user_email'] );
	$date     = date( 'Y-m-d H:i:s', strtotime( $posted_data['date'] ) );
	$key      = sanitize_text_field( $posted_data['purchase_key'] );

	// Any errors?
	$errors = give_get_errors();

	// No errors, proceed.
	if ( ! $errors ) {

		// Setup the payment details.
		$donation_data = array(
			'price'           => $amount,
			'give_form_title' => $posted_data['post_data']['give-form-title'],
			'give_form_id'    => $form_id,
			'give_price_id'   => $price_id,
			'date'            => $date,
			'user_email'      => $email,
			'purchase_key'    => $key,
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
				'Flutterwave Error',
				__( 'Unable to create a pending donation with Give.', 'flutterwave-for-give' )
			);

			// Send user back to checkout.
			give_send_back_to_checkout( '?payment-mode=flutterwave' );
			return;
		}

		// Do the actual payment processing using the custom payment gateway API.
		// To access the GiveWP settings, use give_get_option() as a reference,
		// this pulls the API key entered above: give_get_option('flutterwave_for_give_flutterwave_api_key')
		$api_mode    = give_get_option( 'flutterwave_for_give_api_mode' );
		$public_key  = '';
		$secret_key  = '';
		$payment_url = '';

		if ( 'live' === $api_mode ) {
			$public_key  = give_get_option( 'flutterwave_for_give_public_live_keys' );
			$secret_key  = give_get_option( 'flutterwave_for_give_secret_live_keys' );
			$payment_url = 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay';
		} else {
			$public_key = give_get_option( 'flutterwave_for_give_public_test_keys' );
			$secret_key  = give_get_option( 'flutterwave_for_give_secret_test_keys' );
			$payment_url = 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay';
			// Send user back to checkout.
			give_send_back_to_checkout( '?payment-mode=flutterwave' );
			return;
		}
			// Set up the payment data for Flutterwave.
		$payment_data = array(
			'amount' => $amount,
			'currency' => give_get_currency( $form_id ),
			'public_key' => $public_key,
			'secret_key' => $secret_key,
			'title' => $posted_data['post_data']['give-form-title'],
			'description' => $posted_data['post_data']['give-form-title'],
			'redirect_url' => give_get_success_page_uri(),
			'cancel_url' => give_get_failed_transaction_uri(),
			'callback_url' => give_get_listener_url_for_payment( $donation_id, 'flutterwave' ),
			'email' => $email,
			'first_name' => ! empty( $posted_data['user_info']['first_name'] ) ? $posted_data['user_info']['first_name'] : '',
			'last_name' => ! empty( $posted_data['user_info']['last_name'] ) ? $posted_data['user_info']['last_name'] : '',
		);

				// Use payment gateway API to process payment.
		try {
			$response = flutterwave_for_give_api_process_payment( $payment_data );

				// Record the transaction ID.
			give_insert_payment_meta( $donation_id, '_give_payment_transaction_id', $response['data']['id'] );

				// If the payment is successful, update the payment status.
			if ( 'successful' === $response['status'] ) {

				// Update payment status.
				give_update_payment_status( $donation_id, 'publish' );

				// Send email to user.
				give_trigger_donation_receipt( $donation_id );
			} elseif ( 'pending' === $response['status'] ) {
					// Update payment status.
				give_update_payment_status( $donation_id, 'pending' );

				// Add a note to the payment.
				give_insert_payment_note(
					$donation_id,
					__( 'Flutterwave payment is currently pending.', 'flutterwave-for-give' )
				);
			} else {
					// Payment failed.
				give_record_gateway_error(
					__( 'Flutterwave Error', 'flutterwave-for-give' ),
					sprintf(
						/* translators: %s Exception error message. */
						__( 'Flutterwave payment failed. Reason: %s', 'flutterwave-for-give' ),
						$response['message']
					)
				);

					// Update payment status.
				give_update_payment_status( $donation_id, 'failed' );

					// Send user back to checkout.
				give_send_back_to_checkout( '?payment-mode=flutterwave' );
			}
			
		} catch ( Exception $e ) {
				// Payment failed.
			give_record_gateway_error(
				__( 'Flutterwave Error', 'flutterwave-for-give' ),
				sprintf(
					/* translators: %s Exception error message. */
					__( 'Flutterwave payment failed. Reason: %s', 'flutterwave-for-give' ),
					$e->getMessage()
				)
			);

					// Update payment status.
			give_update_payment_status( $donation_id, 'failed' );

					// Send user back to checkout.
			give_send_back_to_checkout( '?payment-mode=flutterwave' );
		}
	}
}
add_action( 'give_gateway_flutterwave', 'flutterwave_for_give_process_donation' );