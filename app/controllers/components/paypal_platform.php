<?php
/**
 * Party Planet
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    partyplanet
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class PaypalPlatform
{
    public $PROXY_HOST = '127.0.0.1';
    public $PROXY_PORT = '808';
    public $Env = '';
    public $API_UserName = '';
    public $API_Password = '';
    public $API_Signature = '';
    // AppID is preset for sandbox use
    //   If your application goes live, you will be assigned a value for the live environment by PayPal as part of the live onboarding process
    public $API_AppID = '';
    public $API_Endpoint = '';
    public $API_Adaptive_Endpoint = '';
    public $API_Account_Endpoint = '';
    public $USE_PROXY = false;
    public function settings($settings)
    {
        $this->Env = $settings['is_test_mode'];
        $this->API_UserName = $settings['adaptive_API_UserName'];
        $this->API_Password = $settings['adaptive_API_Password'];
        $this->API_Signature = $settings['adaptive_API_Signature'];
        $this->API_AppID = $settings['adaptive_API_AppID'];
        if (!empty($this->Env)) {
            $this->API_Adaptive_Endpoint = "https://svcs.sandbox.paypal.com/AdaptivePayments";
            $this->API_Account_Endpoint = "https://svcs.sandbox.paypal.com/AdaptiveAccounts";
            $this->API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
        } else {
            $this->API_Adaptive_Endpoint = "https://svcs.paypal.com/AdaptivePayments";
            $this->API_Account_Endpoint = "https://svcs.paypal.com/AdaptiveAccounts";
            $this->API_Endpoint = "https://api-3t.paypal.com/nvp";
        }
    }
    public function generateCharacter()
    {
        $possible = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $char = substr($possible, mt_rand(0, strlen($possible) -1) , 1);
        return $char;
    }
    public function generateTrackingID()
    {
        $GUID = $this->generateCharacter() . $this->generateCharacter() . $this->generateCharacter() . $this->generateCharacter() . $this->generateCharacter();
        $GUID.= $this->generateCharacter() . $this->generateCharacter() . $this->generateCharacter() . $this->generateCharacter();
        return $GUID;
    }
    /*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the get balance API Call.
    '
    ' Returns:
    '		The NVP Collection object of the call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function CallGetBalance()
    {
        $nvpstr = '';
        $resArray = $this->hash_api_call("GetBalance", $nvpstr);
        /* Return the response array */
        return $resArray;
    }
    /*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the create paypal account API Call.
    '    '
    '
    ' Returns:
    '		The NVP Collection object of the call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function CallCreateAccount($preferredLanguageCode, $accountType, $firstName, $lastName, $dateOfBirth, $address1, $address2, $city, $state, $zip, $countryCode, $citizenshipCountryCode, $contactPhoneNumber, $currencyCode, $emailAddress, $returnUrl, $cancelUrl, $notificationURL, $referralId)
    {
        // required fields
        $nvpstr = 'registrationType=WEB';
        if ("" != $returnUrl) {
            $nvpstr.= "&createAccountWebOptions.returnUrl=" . urlencode($returnUrl);
        }
        if ("" != $cancelUrl) {
            $nvpstr.= "&createAccountWebOptions.cancelUrl=" . urlencode($cancelUrl);
        }
        if ("" != $preferredLanguageCode) {
            $nvpstr.= "&preferredLanguageCode=" . urlencode($preferredLanguageCode);
        }
        if ("" != $accountType) {
            $nvpstr.= "&accountType=" . urlencode(strtoupper($accountType));
        }
        if ("" != $firstName) {
            $nvpstr.= "&name.firstName=" . urlencode($firstName);
        }
        if ("" != $lastName) {
            $nvpstr.= "&name.lastName=" . urlencode($lastName);
        }
        if ("" != $dateOfBirth) {
            $nvpstr.= "&dateOfBirth=" . urlencode($dateOfBirth);
        }
        if ("" != $address1) {
            $nvpstr.= "&address.line1=" . urlencode($address1);
        }
        if ("" != $address2) {
            $nvpstr.= "&address.line2=" . urlencode($address2);
        }
        if ("" != $city) {
            $nvpstr.= "&address.city=" . urlencode($city);
        }
        if ("" != $state) {
            $nvpstr.= "&address.state=" . urlencode($state);
        }
        if ("" != $zip) {
            $nvpstr.= "&address.postalCode=" . urlencode($zip);
        }
        if ("" != $countryCode) {
            $nvpstr.= "&address.countryCode=" . urlencode($countryCode);
        }
        if ("" != $citizenshipCountryCode) {
            $nvpstr.= "&citizenshipCountryCode=" . urlencode($citizenshipCountryCode);
        }
        if ("" != $contactPhoneNumber) {
            $nvpstr.= "&contactPhoneNumber=" . urlencode($contactPhoneNumber);
        }
        if ("" != $notificationURL) {
            $nvpstr.= "&notificationURL=" . urlencode($notificationURL);
        }
        if ("" != $currencyCode) {
            $nvpstr.= "&currencyCode=" . urlencode($currencyCode);
        }
        if ("" != $emailAddress) {
            $nvpstr.= "&emailAddress=" . urlencode($emailAddress);
        }
        $sandboxEmailAddress = '';
        if (!empty($this->Env)) {
            $sandboxEmailAddress = 'k.sakthivel@agriya.in';
        }
        $resArray = $this->hash_account_call("CreateAccount", $nvpstr, $sandboxEmailAddress, $referralId);
        /* Return the response array */
        return $resArray;
    }
    /*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the verify paypal account API Call.
    '    '
    ' Inputs: email , first name, last name
    '
    ' Conditionally Required:
    '		One of the following:  email , first name, last name
    ' Returns:
    '		The NVP Collection object of the call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function CallGetVerifiedStatus($email, $first_name, $last_name)
    {
        // required fields
        $nvpstr = '';
        if ("" != $email) {
            $nvpstr.= "&emailAddress=" . urlencode($email);
        }
        if ("" != $first_name) {
            $nvpstr.= "&firstName=" . urlencode($first_name);
        }
        if ("" != $last_name) {
            $nvpstr.= "&lastName=" . urlencode($last_name);
        }
        $nvpstr.= "&matchCriteria=" . urlencode('NAME');
        /* Make the PreapprovalDetails call to PayPal */
        $resArray = $this->hash_account_call("GetVerifiedStatus", $nvpstr);
        /* Return the response array */
        return $resArray;
    }
    /*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the cancel preapproval API Call.
    '
    '			a payKey of a previously successful Primary Pay call.
    ' Inputs:
    '
    ' Conditionally Required:
    '		One of the following:  payKey
    ' Returns:
    '		The NVP Collection object of the PaymentDetails call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function CallCancelPreapproval($preapprovalKey)
    {
        // required fields
        $nvpstr = "preapprovalKey=" . urlencode($preapprovalKey);
        /* Make the PreapprovalDetails call to PayPal */
        $resArray = $this->hash_call("CancelPreapproval", $nvpstr);
        /* Return the response array */
        return $resArray;
    }
    /*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the Refund API Call.
    '			The API credentials used in a Pay call can make the Refund call
    '			against a payKey, or a tracking id, or to specific receivers of a payKey or a tracking id
    '			that resulted from the Pay call
    '
    '			A receiver itself with its own API credentials can make a Refund call against the transactionId corresponding to their transaction.
    '			The API credentials used in a Pay call cannot use transactionId to issue a refund
    '			for a transaction for which they themselves were not the receiver
    '
    '			If you do specify specific receivers, keep in mind that you must provide the amounts as well
    '			If you specify a transactionId, then only the receiver of that transactionId is affected therefore
    '			the receiverEmailArray and receiverAmountArray should have 1 entry each if you do want to give a partial refund
    ' Inputs:
    '
    ' Conditionally Required:
    '		One of the following:  payKey or trackingId or trasactionId or
    '                              (payKey and receiverEmailArray and receiverAmountArray) or
    '                              (trackingId and receiverEmailArray and receiverAmountArray) or
    '                              (transactionId and receiverEmailArray and receiverAmountArray)
    ' Returns:
    '		The NVP Collection object of the Refund call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function CallRefund($payKey, $transactionId, $trackingId, $receiverEmailArray, $receiverAmountArray, $currencyCode)
    {
        /* Gather the information to make the Refund call.
        The variable nvpstr holds the name value pairs
        */
        $nvpstr = "";
        // conditionally required fields
        if ("" != $payKey) {
            $nvpstr = "payKey=" . urlencode($payKey);
            if (0 != count($receiverEmailArray)) {
                reset($receiverEmailArray);
                while (list($key, $value) = each($receiverEmailArray)) {
                    if ("" != $value) {
                        $nvpstr.= "&receiverList.receiver(" . $key . ").email=" . urlencode($value);
                    }
                }
            }
            if (0 != count($receiverAmountArray)) {
                reset($receiverAmountArray);
                while (list($key, $value) = each($receiverAmountArray)) {
                    if ("" != $value) {
                        $nvpstr.= "&receiverList.receiver(" . $key . ").amount=" . urlencode($value);
                    }
                }
            }
        } elseif ("" != $trackingId) {
            $nvpstr = "trackingId=" . urlencode($trackingId);
            if (0 != count($receiverEmailArray)) {
                reset($receiverEmailArray);
                while (list($key, $value) = each($receiverEmailArray)) {
                    if ("" != $value) {
                        $nvpstr.= "&receiverList.receiver(" . $key . ").email=" . urlencode($value);
                    }
                }
            }
            if (0 != count($receiverAmountArray)) {
                reset($receiverAmountArray);
                while (list($key, $value) = each($receiverAmountArray)) {
                    if ("" != $value) {
                        $nvpstr.= "&receiverList.receiver(" . $key . ").amount=" . urlencode($value);
                    }
                }
            }
        } elseif ("" != $transactionId) {
            $nvpstr = "transactionId=" . urlencode($transactionId);
            // the caller should only have 1 entry in the email and amount arrays
            if (0 != count($receiverEmailArray)) {
                reset($receiverEmailArray);
                while (list($key, $value) = each($receiverEmailArray)) {
                    if ("" != $value) {
                        $nvpstr.= "&receiverList.receiver(" . $key . ").email=" . urlencode($value);
                    }
                }
            }
            if (0 != count($receiverAmountArray)) {
                reset($receiverAmountArray);
                while (list($key, $value) = each($receiverAmountArray)) {
                    if ("" != $value) {
                        $nvpstr.= "&receiverList.receiver(" . $key . ").amount=" . urlencode($value);
                    }
                }
            }
        }
        $nvpstr.= "&currencyCode=" . urlencode($currencyCode);
        /* Make the Refund call to PayPal */
        $resArray = $this->hash_call("Refund", $nvpstr);
        /* Return the response array */
        return $resArray;
    }
    /*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the Execute payment API Call.
    '
    '			a payKey of a previously successful Primary Pay call.
    ' Inputs:
    '
    ' Conditionally Required:
    '		One of the following:  payKey
    ' Returns:
    '		The NVP Collection object of the PaymentDetails call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function CallExecutePayment($payKey)
    {
        $nvpstr = '';
        /* Make the Pay call to PayPal */
        if ("" != $payKey) {
            $nvpstr.= "&payKey=" . urlencode($payKey);
        }
        $resArray = $this->hash_call("ExecutePayment", $nvpstr);
        /* Return the response array */
        return $resArray;
    }
	/*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the SetPaymentOptions API Call.
    '			The SetPaymentOptions call can be made with either
    '			a payKey previously successful Pay call.
    ' Inputs:
    '
    ' Conditionally Required:
    '		One of the following:  payKey
    ' Returns:
    '		The NVP Collection object of the PaymentDetails call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
	public function CallSetPaymentOptions($payKey, $options)
    {
		$nvpstr = '';
        /* Make the Pay call to PayPal */
		$nvpstr = "actionType=" . urlencode('CREATE');
		$nvpstr .= "&payKey=" . urlencode($payKey);
		$nvpstr .= "&displayOptions.headerImageUrl=" . urlencode($options['headerImageUrl']);
		$nvpstr .= "&displayOptions.businessName=" . urlencode($options['businessName']);
		$resArray = $this->hash_call("SetPaymentOptions", $nvpstr);
	}
    /*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the PaymentDetails API Call.
    '			The PaymentDetails call can be made with either
    '			a payKey, a trackingId, or a transactionId of a previously successful Pay call.
    ' Inputs:
    '
    ' Conditionally Required:
    '		One of the following:  payKey or transactionId or trackingId
    ' Returns:
    '		The NVP Collection object of the PaymentDetails call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function CallPaymentDetails($payKey, $transactionId, $trackingId)
    {
        /* Gather the information to make the PaymentDetails call.
        The variable nvpstr holds the name value pairs
        */
        $nvpstr = "";
        // conditionally required fields
        if ("" != $payKey) {
            $nvpstr = "payKey=" . urlencode($payKey);
        } elseif ("" != $transactionId) {
            $nvpstr = "transactionId=" . urlencode($transactionId);
        } elseif ("" != $trackingId) {
            $nvpstr = "trackingId=" . urlencode($trackingId);
        }
        /* Make the PaymentDetails call to PayPal */
        $resArray = $this->hash_call("PaymentDetails", $nvpstr);
        /* Return the response array */
        return $resArray;
    }
    /*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the Pay API Call.
    ' Inputs:
    '
    ' Required:
    '
    ' Optional:
    '
    '
    ' Returns:
    '		The NVP Collection object of the Pay call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function CallPay($actionType, $cancelUrl, $returnUrl, $currencyCode, $receiverEmailArray, $receiverAmountArray, $receiverPrimaryArray, $receiverInvoiceIdArray, $feesPayer, $ipnNotificationUrl, $memo, $pin, $preapprovalKey, $reverseAllParallelPaymentsOnError, $senderEmail, $trackingId)
    {
        /* Gather the information to make the Pay call.
        The variable nvpstr holds the name value pairs
        */
        // required fields
        $nvpstr = "actionType=" . urlencode($actionType) . "&currencyCode=" . urlencode($currencyCode);
        $nvpstr.= "&returnUrl=" . urlencode($returnUrl) . "&cancelUrl=" . urlencode($cancelUrl);
        if (0 != count($receiverAmountArray)) {
            reset($receiverAmountArray);
            while (list($key, $value) = each($receiverAmountArray)) {
                if ("" != $value) {
                    $nvpstr.= "&receiverList.receiver(" . $key . ").amount=" . urlencode($value);
                }
            }
        }
        if (0 != count($receiverEmailArray)) {
            reset($receiverEmailArray);
            while (list($key, $value) = each($receiverEmailArray)) {
                if ("" != $value) {
                    $nvpstr.= "&receiverList.receiver(" . $key . ").email=" . urlencode($value);
                }
            }
        }
        if (0 != count($receiverPrimaryArray)) {
            reset($receiverPrimaryArray);
            while (list($key, $value) = each($receiverPrimaryArray)) {
                if ("" != $value) {
                    $nvpstr = $nvpstr . "&receiverList.receiver(" . $key . ").primary=" . urlencode($value);
                }
            }
        }
        if (0 != count($receiverInvoiceIdArray)) {
            reset($receiverInvoiceIdArray);
            while (list($key, $value) = each($receiverInvoiceIdArray)) {
                if ("" != $value) {
                    $nvpstr = $nvpstr . "&receiverList.receiver(" . $key . ").invoiceId=" . urlencode($value);
                }
            }
        }
        // optional fields
        if ("" != $feesPayer) {
            $nvpstr.= "&feesPayer=" . urlencode($feesPayer);
        }
        if ("" != $ipnNotificationUrl) {
            $nvpstr.= "&ipnNotificationUrl=" . urlencode($ipnNotificationUrl);
        }
        if ("" != $memo) {
            $nvpstr.= "&memo=" . urlencode($memo);
        }
        if ("" != $pin) {
            $nvpstr.= "&pin=" . urlencode($pin);
        }
        if ("" != $preapprovalKey) {
            $nvpstr.= "&preapprovalKey=" . urlencode($preapprovalKey);
        }
        if ("" != $reverseAllParallelPaymentsOnError) {
            $nvpstr.= "&reverseAllParallelPaymentsOnError=" . urlencode($reverseAllParallelPaymentsOnError);
        }
        if ("" != $senderEmail) {
            $nvpstr.= "&senderEmail=" . urlencode($senderEmail);
        }
        if ("" != $trackingId) {
            $nvpstr.= "&trackingId=" . urlencode($trackingId);
        }
        /* Make the Pay call to PayPal */
        $resArray = $this->hash_call("Pay", $nvpstr);
        /* Return the response array */
        return $resArray;
    }
    /*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the PreapprovalDetails API Call.
    ' Inputs:
    '
    ' Required:
    '		preapprovalKey:		A preapproval key that identifies the agreement resulting from a previously successful Preapproval call.
    ' Returns:
    '		The NVP Collection object of the PreapprovalDetails call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function CallPreapprovalDetails($preapprovalKey)
    {
        /* Gather the information to make the PreapprovalDetails call.
        The variable nvpstr holds the name value pairs
        */
        // required fields
        $nvpstr = "preapprovalKey=" . urlencode($preapprovalKey);
        /* Make the PreapprovalDetails call to PayPal */
        $resArray = $this->hash_call("PreapprovalDetails", $nvpstr);
        /* Return the response array */
        return $resArray;
    }
    /*
    '-------------------------------------------------------------------------------------------------------------------------------------------
    ' Purpose: 	Prepares the parameters for the Preapproval API Call.
    ' Inputs:
    '
    ' Required:
    '
    ' Optional:
    '
    '
    ' Returns:
    '		The NVP Collection object of the Preapproval call response.
    '--------------------------------------------------------------------------------------------------------------------------------------------
    */
    public function CallPreapproval($returnUrl, $cancelUrl, $currencyCode, $startingDate, $endingDate, $maxTotalAmountOfAllPayments, $senderEmail, $maxNumberOfPayments, $paymentPeriod, $dateOfMonth, $dayOfWeek, $maxAmountPerPayment, $maxNumberOfPaymentsPerPeriod, $pinType, $ipnNotificationUrl)
    {
        /* Gather the information to make the Preapproval call.
        The variable nvpstr holds the name value pairs
        */
        // required fields
        $nvpstr = "returnUrl=" . urlencode($returnUrl) . "&cancelUrl=" . urlencode($cancelUrl);
        $nvpstr.= "&currencyCode=" . urlencode($currencyCode) . "&startingDate=" . urlencode($startingDate);
        $nvpstr.= "&endingDate=" . urlencode($endingDate);
        $nvpstr.= "&maxTotalAmountOfAllPayments=" . urlencode($maxTotalAmountOfAllPayments);
        // optional fields
        if ("" != $senderEmail) {
            $nvpstr.= "&senderEmail=" . urlencode($senderEmail);
        }
        if ("" != $maxNumberOfPayments) {
            $nvpstr.= "&maxNumberOfPayments=" . urlencode($maxNumberOfPayments);
        }
        if ("" != $paymentPeriod) {
            $nvpstr.= "&paymentPeriod=" . urlencode($paymentPeriod);
        }
        if ("" != $dateOfMonth) {
            $nvpstr.= "&dateOfMonth=" . urlencode($dateOfMonth);
        }
        if ("" != $dayOfWeek) {
            $nvpstr.= "&dayOfWeek=" . urlencode($dayOfWeek);
        }
        if ("" != $maxAmountPerPayment) {
            $nvpstr.= "&maxAmountPerPayment=" . urlencode($maxAmountPerPayment);
        }
        if ("" != $maxNumberOfPaymentsPerPeriod) {
            $nvpstr.= "&maxNumberOfPaymentsPerPeriod=" . urlencode($maxNumberOfPaymentsPerPeriod);
        }
        if ("" != $pinType) {
            $nvpstr.= "&pinType=" . urlencode($pinType);
        }
        if ("" != $ipnNotificationUrl) {
            $nvpstr.= "&ipnNotificationUrl=" . urlencode($ipnNotificationUrl);
        }
		$nvpstr.= "&displayMaxTotalAmount=true";
        /* Make the Preapproval call to PayPal */
        $resArray = $this->hash_call("Preapproval", $nvpstr);
        /* Return the response array */
        return $resArray;
    }
    /**
     '-------------------------------------------------------------------------------------------------------------------------------------------
     * hash_call: Function to perform the API call to PayPal using API signature
     * @methodName is name of API method.
     * @nvpStr is nvp string.
     * returns an associative array containing the response from the server.
     '-------------------------------------------------------------------------------------------------------------------------------------------
     */
    public function hash_call($methodName, $nvpStr)
    {
        $this->API_Adaptive_Endpoint.= "/" . $methodName;
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->API_Adaptive_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        // Set the HTTP Headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-PAYPAL-REQUEST-DATA-FORMAT: NV',
            'X-PAYPAL-RESPONSE-DATA-FORMAT: NV',
            'X-PAYPAL-SECURITY-USERID: ' . $this->API_UserName,
            'X-PAYPAL-SECURITY-PASSWORD: ' . $this->API_Password,
            'X-PAYPAL-SECURITY-SIGNATURE: ' . $this->API_Signature,
            'X-PAYPAL-SERVICE-VERSION: 1.3.0',
            'X-PAYPAL-APPLICATION-ID: ' . $this->API_AppID
        ));
        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
        //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php
        if ($this->USE_PROXY) curl_setopt($ch, CURLOPT_PROXY, $this->PROXY_HOST . ":" . $this->PROXY_PORT);
        // RequestEnvelope fields
        $detailLevel = urlencode("ReturnAll"); // See DetailLevelCode in the WSDL for valid enumerations
        $errorLanguage = urlencode("en_US"); // This should be the standard RFC 3066 language identification tag, e.g., en_US
        // NVPRequest for submitting to server
        $nvpreq = "requestEnvelope.errorLanguage=$errorLanguage&requestEnvelope.detailLevel=$detailLevel";
        $nvpreq.= "&$nvpStr";
        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        //getting response from server
        $response = curl_exec($ch);
        //converting NVPResponse to an Associative Array
        $nvpResArray = $this->deformatNVP($response);
        $nvpReqArray = $this->deformatNVP($nvpreq);
        $_SESSION['nvpReqArray'] = $nvpReqArray;
        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            $_SESSION['curl_error_no'] = curl_errno($ch);
            $_SESSION['curl_error_msg'] = curl_error($ch);
            //Execute the Error handling module to display errors.

        } else {
            //closing the curl
            curl_close($ch);
        }
        return $nvpResArray;
    }
    /*'----------------------------------------------------------------------------------
    Purpose: Redirects to PayPal.com site.
    Inputs:  $cmd is the querystring
    Returns:
    ----------------------------------------------------------------------------------
    */
    function RedirectToPayPal($cmd, $embedded = false)
	    {
		$payPalURL = "";
		if (!empty($this->Env)) {
				$payPalURL = ($embedded) ? "https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay?" . $cmd : "https://www.sandbox.paypal.com/webscr?" . $cmd;
		} else {
				$payPalURL = ($embedded) ? "https://www.paypal.com/webapps/adaptivepayment/flow/pay?" . $cmd : "https://www.paypal.com/webscr?" . $cmd;
		}
		header("location: " . $payPalURL);
			exit;
	    }
    /*'----------------------------------------------------------------------------------
    * This function will take NVPString and convert it to an Associative Array and it will decode the response.
    * It is usefull to search for a particular key and displaying arrays.
    * @nvpstr is NVPString.
    * @nvpArray is Associative Array.
    ----------------------------------------------------------------------------------
    */
    public function deformatNVP($nvpstr)
    {
        $intial = 0;
        $nvpArray = array();
        while (strlen($nvpstr)) {
            //postion of Key
            $keypos = strpos($nvpstr, '=');
            //position of value
            $valuepos = strpos($nvpstr, '&') ? strpos($nvpstr, '&') : strlen($nvpstr);
            /*getting the Key and Value values and storing in a Associative Array*/
            $keyval = substr($nvpstr, $intial, $keypos);
            $valval = substr($nvpstr, $keypos+1, $valuepos-$keypos-1);
            //decoding the respose
            $nvpArray[urldecode($keyval) ] = urldecode($valval);
            $nvpstr = substr($nvpstr, $valuepos+1, strlen($nvpstr));
        }
        return $nvpArray;
    }
    public function hash_account_call($methodName, $nvpStr, $sandboxEmailAddress = '', $referralId = '')
    {
        //declaring of global variables
        $URL = $this->API_Account_Endpoint . '/' . $methodName;
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
        //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php
        if ($this->USE_PROXY) curl_setopt($ch, CURLOPT_PROXY, $this->PROXY_HOST . ":" . $this->PROXY_PORT);
        $headers_arr = array();
        $headers_arr[] = "X-PAYPAL-SECURITY-SIGNATURE: " . $this->API_Signature;
        $headers_arr[] = "X-PAYPAL-SECURITY-USERID:  " . $this->API_UserName;
        $headers_arr[] = "X-PAYPAL-SECURITY-PASSWORD: " . $this->API_Password;
        $headers_arr[] = "X-PAYPAL-APPLICATION-ID: " . $this->API_AppID;
        $headers_arr[] = "X-PAYPAL-REQUEST-DATA-FORMAT: NV";
        $headers_arr[] = "X-PAYPAL-RESPONSE-DATA-FORMAT: NV";
        $headers_arr[] = "X-PAYPAL-DEVICE-IPADDRESS: 127.0.0.1";
        if (!defined('X-PAYPAL-REQUEST-SOURCE')) {
            $headers_arr[] = "X-PAYPAL-REQUEST-SOURCE: PHP_NVP_SDK_V1.1";
        } else {
            $headers_arr[] = "X-PAYPAL-REQUEST-SOURCE: PHP_NVP_SDK_V1.1-PHP_NVP_SDK_V1.1";
        }
        if (!empty($sandboxEmailAddress)) {
            $headers_arr[] = "X-PAYPAL-SANDBOX-EMAIL-ADDRESS: " . $sandboxEmailAddress;
        }
        if (!empty($referralId)) {
            $headers_arr[] = "X-PAYPAL-MERCHANT-REFERRAL-BONUS-ID: " . $referralId;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_arr);
        curl_setopt($ch, CURLOPT_HEADER, false);
        //setting the nvpreq as POST FIELD to curl
        $detailLevel = urlencode("ReturnAll"); // See DetailLevelCode in the WSDL for valid enumerations
        $errorLanguage = urlencode("en_US"); // This should be the standard RFC 3066 language identification tag, e.g., en_US
        // NVPRequest for submitting to server
        $nvpreq = "requestEnvelope.errorLanguage=$errorLanguage&requestEnvelope.detailLevel=$detailLevel";
        $nvpreq.= "&$nvpStr";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        //getting response from server
        $response = curl_exec($ch);
        //convrting NVPResponse to an Associative Array
        $nvpResArray = $this->deformatNVP($response);
        $nvpReqArray = $this->deformatNVP($nvpreq);
        $_SESSION['nvpReqArray'] = $nvpReqArray;
        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            $_SESSION['curl_error_no'] = curl_errno($ch);
            $_SESSION['curl_error_msg'] = curl_error($ch);
        } else {
            //closing the curl
            curl_close($ch);
        }
        return $nvpResArray;
    }
    public function hash_api_call($methodName, $nvpStr = '')
    {
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        // turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $version = urlencode('51.0');
        // NVPRequest for submitting to server
        $nvpreq = 'METHOD=' . $methodName . '&VERSION=' . $version . '&PWD=' . $this->API_Password . '&USER=' . $this->API_UserName . '&SIGNATURE=' . $this->API_Signature . $nvpStr;
        // setting the nvpreq as POST FIELD to curl
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        $response = curl_exec($ch);
        //convrting NVPResponse to an Associative Array
        $nvpResArray = $this->deformatNVP($response);
        $nvpReqArray = $this->deformatNVP($nvpreq);
        $_SESSION['nvpReqArray'] = $nvpReqArray;
        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            $_SESSION['curl_error_no'] = curl_errno($ch);
            $_SESSION['curl_error_msg'] = curl_error($ch);
        } else {
            //closing the curl
            curl_close($ch);
        }
        return $nvpResArray;
    }
}
?>