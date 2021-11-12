<?php

/**
* PaymentController
*/

use Omnipay\Omnipay;
use Omnipay\PayU\GatewayFactory;

require DIR_BUILDER.'vendor/autoload.php';
class PaymentController extends Controller
{
	public function index()
	{
		$id = (int)$this->url->get('invoice');
		if (empty($id)) {
			$this->url->redirect('invoices');
		}

		$this->load->model('payment');
		$invoice = $this->model_payment->getInvoice($id);

		if (empty($invoice)) {
			$this->url->redirect('invoices');
		}
		$this->load->model('commons');
		$this->load->model('payment');
		$common = $this->model_commons->getCommonData($this->session->data['user_id']);
		$credentials = $this->model_payment->getPaymentGateway();
		$credentials = json_decode($credentials, true);
		
		if (!isset($credentials['status']) || $credentials['status'] == '0') {
			$this->session->data['message'] = 'Paypal payment gateway is disabled.';
			$this->url->redirect('payment/cancel');
		}

		if ($credentials['mode'] == '0') {
			$paypalMode = true; 
		} else {
			$paypalMode = false;
		}

		$params = array(
			'cancelUrl'     => URL_CLIENTS.DIR_ROUTE.'invoice/cancelpay',
			'returnUrl'     => URL_CLIENTS.DIR_ROUTE.'invoice/successpay',
			'invoice_id'    => $invoice['id'],
			'name'      	=> 'Invoice Payment',
			'description'   => '',
			'amount'    	=> $invoice['due'],
			'currency'  	=> $invoice['currency'],
			// 'amount'    	=> '.1',
			// 'currency'  	=> 'USD',
			'custom'        => $this->token_generator(64),
			'email' 		=> $invoice['email'],
		);

		$this->session->data['params'] = $params;

		$gateway = Omnipay::create('PayPal_Express');
		$gateway->setUsername($credentials['username']);
		$gateway->setPassword($credentials['password']);
		$gateway->setSignature($credentials['signature']);
		$gateway->setTestMode($paypalMode);
		$response = $gateway->purchase($params)->send();

		if ($response->isSuccessful()) {
	        // payment was successful: update database
			exit('successful');
		} elseif ($response->isRedirect()) {
			$response->redirect();
		} else {
	        // payment failed: display message to customer
			exit($response->getMessage());
		}
	}

	public function indexCancel()
	{
		$this->load->model('payment');
		$credentials = $this->model_payment->getPaymentGateway();
		$credentials = json_decode($credentials, true);

		if ($credentials['status'] == '0') {
			$this->session->data['payment_message'] = 'Paypal payment gateway is disabled.';
			$this->url->redirect('payment/cancel');
		}

		if ($credentials['mode'] == '0') {
			$paypalMode = true; 
		} else {
			$paypalMode = false;
		}

		$params = $this->session->data['params'];
		//$this->data['set_key'] = $api_config;
		$gateway = Omnipay::create('PayPal_Express');
		$gateway->setUsername($credentials['username']);
		$gateway->setPassword($credentials['password']);
		$gateway->setSignature($credentials['signature']);
		$gateway->setTestMode($paypalMode);

		$response = $gateway->completePurchase($params)->send();
		$paypalResponse = $response->getData();
		if (isset($paypalResponse['L_SHORTMESSAGE0'])) {
			$this->session->data['payment_message'] = $paypalResponse['L_SHORTMESSAGE0'];
		} else {
			$this->session->data['payment_message'] = 'Payment canceled by user.';
		}
		unset($this->session->data['params']);
		$this->url->redirect('payment/cancel');
	}

	public function indexSuccess()
	{
		$this->load->model('payment');
		$credentials = $this->model_payment->getPaymentGateway();

		$credentials = json_decode($credentials, true);

		if ($credentials['status'] == '0') {
			$this->session->data['payment_message'] = 'Paypal payment gateway is disabled.';
			$this->url->redirect('payment/cancel');
		}

		if ($credentials['mode'] == '0') {
			$paypalMode = true; 
		} else {
			$paypalMode = false;
		}

		$params = $this->session->data['params'];

		//$this->data['set_key'] = $api_config;
		$gateway = Omnipay::create('PayPal_Express');
		$gateway->setUsername($credentials['username']);
		$gateway->setPassword($credentials['password']);
		$gateway->setSignature($credentials['signature']);
		$gateway->setTestMode($paypalMode);

		$response = $gateway->completePurchase($params)->send();
        $paypalResponse = $response->getData(); // this is the raw response object
        $purchaseId = $this->url->get('PayerID');
        
        $data['payer_id'] = $this->url->get('PayerID');
        $data['params'] = $params;
		
        if(isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {
        	$this->load->model('payment');

        	if ($purchaseId) {
        		$check_txn_id = $this->model_payment->checkTransaction($params, $paypalResponse['PAYMENTINFO_0_TRANSACTIONID']);
        		if(!$check_txn_id) {
        			$invoice = $this->model_payment->getInvoice($params['invoice_id']);
        			
        			if (!empty($invoice)) {
        				$data['txn_id'] = $paypalResponse['PAYMENTINFO_0_TRANSACTIONID'];
        				$data['amount'] = (float)$paypalResponse['PAYMENTINFO_0_AMT'];
        				if (isset($this->session->data['customer'])) {
        					$data['contact_id'] = $this->session->data['customer'];
        				} else {
        					$data['contact_id'] = 0;
        				}
        				
        				$data['datetime'] = date('Y-m-d H:i:s');

        				$invoice['paid'] = $invoice['paid'] + $paypalResponse['PAYMENTINFO_0_AMT'];
        				$invoice['due'] = $invoice['due'] - $paypalResponse['PAYMENTINFO_0_AMT'];

        				if ($invoice['due'] > '0') {
        					$invoice['status'] = 'Partially Paid';
        				} else {
        					$invoice['status'] = 'Paid';
        				}

        				$data['invoice'] = $invoice;
        				
        				$this->model_payment->createPayment($data);
        				$this->model_payment->updateInvoice($invoice);
        				$this->sendMail($data);

        				$this->session->data['payment_message'] = array('error' => false, 'message' => 'Payment Recieved');
        				$this->session->data['txn_id'] = $data['txn_id'];
        				unset($this->session->data['params']);
        				$this->url->redirect('payment/success');
        			} else {
        				// exit('Transaction ID already exist');
        				unset($this->session->data['params']);
        				$this->session->data['payment_message'] = 'Invalid invoice';
        				$this->url->redirect('payment/cancel');
        			}
        		} else {
        			// exit('Transaction ID already exist');
        			unset($this->session->data['params']);
        			$this->session->data['payment_message'] = 'Transaction ID already exist!';
        			$this->url->redirect('payment/cancel');
        		}
        	} else {
        		// exit('Payer id not found');
        		unset($this->session->data['params']);
        		$this->session->data['payment_message'] = 'Payer id not found!';
        		$this->url->redirect('payment/cancel');
        	}
        } else {
        	// exit('Payment not success');
        	unset($this->session->data['params']);
        	$this->session->data['payment_message'] = 'Payment not success!';
        	$this->url->redirect('payment/cancel');
        }
    }

    public function indexCancelShow()
    {
        $this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('payment', 'payment');
		
		if (!empty($this->session->data['payment_message'])) {
            $data['payment_message'] = $this->session->data['payment_message'];
            unset($this->session->data['payment_message']);
        }
      
        $data['page_title'] = $this->language->get('text_cancel');

		$data['lang']= $this->language->all();
        $this->response->setOutput($this->load->view('payment/cancel', $data));
    }

    public function indexSuccessShow()
    {
        $this->load->model('payment');
        if (!isset($this->session->data['txn_id'])) {
            $this->url->redirect('invoices');
        }
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('payment', 'payment');
        
        $data['page_title'] = $this->language->get('text_success');
        $data['txn_id'] = $this->session->data['txn_id'];
        unset($this->session->data['txn_id']);

		$data['lang']= $this->language->all();
        $this->response->setOutput($this->load->view('payment/success', $data));
    }


    public function payU()
    {
    	// Visa
    	// Card Number: 4012001037141112
    	// Expiry Date : 05/20
    	// CVV : 123
    	// OTP : 123456

    	// Master
    	// Card Number: 5123456789012346
		// Expiry Date : 05/20
		// CVV : 123
		// OTP : 123456

    	$merchant_key = 'eaoKLMGa';
    	$merchant_salt = '6uHwEKvGbq';

    	$dotenv = new Dotenv\Dotenv(__DIR__);
		$dotenv->load();
    }

    public function stripe()
    {
    	$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		
    	$data['lang']= $this->language->all();
    	$this->response->setOutput($this->load->view('payment/stripe', $data));
    }



    public function sendMail($data)
    {
        $result = $this->model_payment->getTemplate();
        $info = $this->model_payment->getSiteInfo();
        $info = json_decode($info, true);

        $link = '<a href="'.URL_CLIENTS.'">Click Here</a>';
        $result['message'] = str_replace('{company}', $data['invoice']['company'], $result['message']);
        $result['message'] = str_replace('{id}', $info['invoice']['prefix'].str_pad($data['invoice']['id'], 4, '0', STR_PAD_LEFT), $result['message']);
        $result['message'] = str_replace('{inv_url}', $link, $result['message']);
        $result['message'] = str_replace('{txn_id}', $data['txn_id'], $result['message']);
        $result['message'] = str_replace('{paid_amount}', $data['invoice']['abbr'].$data['invoice']['paid'], $result['message']);
        $result['message'] = str_replace('{due_amount}', $data['invoice']['abbr'].$data['invoice']['due'], $result['message']);
        $result['message'] = str_replace('{paid_date}', $data['datetime'], $result['message']);
        $result['message'] = str_replace('{business_name}', $info['name'], $result['message']);

        $mail['name'] = $data['invoice']['company'];
        $mail['email'] = $data['invoice']['email'];
        $mail['subject'] = $result['subject'];
        $mail['message'] = $result['message'];
        $this->load->controller('mail');
        $mail_result = $this->controller_mail->sendMail($mail);
        if ($mail_result == 1) {
			return true; //Success: Message sent successfully
        } else {
			return false;
        }
    }

    private function token_generator( $length = 64 )
    {
    	$token = "";
    	$charArray = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz");
    	for($i = 0; $i < $length; $i++){
    		$randItem = array_rand($charArray);
    		$token .= "".$charArray[$randItem];
    	}
    	return $token;
    }

}