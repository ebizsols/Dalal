<?php

/**
* RecurringjobController
*/
class RecurringjobController extends Controller
{
	private $mail;
	public function index()
	{
		$data['code'] = $this->url->get('code');
		$this->load->model('recurringjob');
		$this->load->controller('mail');
		$this->load->model('commons');
		$this->mail = new Mail();
		$token = $this->model_recurringjob->getToken();
		if ($data['code'] != $token) {
			exit('Access Denied');
		}
		
		$count = 0;
		$inv_arr = [];
		$data['rinvoices'] = $this->model_recurringjob->getRecurringInvoices();
		$today = date('Y-m-d');
		if (!empty($data['rinvoices'])) {
			foreach ($data['rinvoices'] as $key => $value) {
				if (!empty($value['last_invoice'])) {
					$repeatdate = Date("Y-m-d", strtotime( $value['last_invoice']. ' + '. $value['repeat_every']));
					$last_date = date_format(date_create($value['last_invoice']), 'Y-m-d');
					if (strtotime($today) > strtotime($repeatdate)) {
						$this->createInvoiceFromRecurring($value);
						$count = $count + 1;
						array_push($inv_arr, $value['id']);
					}
				} elseif (empty($value['last_invoice'])) {
					$repeatdate = date_format(date_create($value['inv_date']), 'Y-m-d');
					if (strtotime($today) > strtotime($repeatdate)) {
						$this->createInvoiceFromRecurring($value);
						$count = $count + 1;
						array_push($inv_arr, $value['id']);
					}
				}
			}
		}

		$logs = json_encode(array('count' => $count, 'inv' => json_encode($inv_arr)));
		$this->model_recurringjob->createCronLog($logs);
		exit();
	}

	public function createInvoiceFromRecurring($data)
	{
		$data['status'] = "Unpaid";
		$data['paid'] = 0.00;
		$data['duedate'] = date_format(date_create(), 'Y-m-d');
		$data['paiddate'] = date_format(date_create(), 'Y-m-d');
		$data['invoicedate'] = date_format(date_create(), 'Y-m-d');
		$data['datetime'] = date_format(date_create(), 'Y-m-d');
		$data['due'] = $data['amount'];
		$data['datetime'] = date('Y-m-d H:i:s');
		$result = $this->model_recurringjob->createInvoice($data);
		$this->createPDF($result);
		$this->invoiceMail($result);
		// echo "Invoice Created Successfully". $result.'<br />';
		return true;
	}

	public function createPDF($id)
	{
		/* Load common data */
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all User data from DB using Invoice model 
		**/
		$data['result'] = $this->model_recurringjob->getInoviceView($id);
		if (empty($data['result'])) { return false; }
		$data['result']['address'] = json_decode($data['result']['address'], true);
		$data['result']['items'] = json_decode($data['result']['items'], true);
		/*Load Language File*/
		$this->language->load('invoices', 'invoices');
		if ($data['result']['status'] == "Paid") {$data['result']['status'] = $this->language->get('text_paid');}
		elseif ($data['result']['status'] == "Unpaid") {$data['result']['status'] = $this->language->get('text_unpaid');}
		elseif ($data['result']['status'] == "Pending") {$data['result']['status'] = $this->language->get('text_pending');}
		elseif ($data['result']['status'] == "In Process") {$data['result']['status'] = $this->language->get('text_in_process'); }
		elseif ($data['result']['status'] == "Cancelled") {$data['result']['status'] = $this->language->get('text_cancelled');}
		elseif ($data['result']['status'] == "Other") {$data['result']['status'] = $this->language->get('text_other');}
		elseif ($data['result']['status'] == "Partially Paid") {$data['result']['status'] = $this->language->get('text_partially_paid');}
		else {$data['result']['status'] = $this->language->get('text_unknown');}

		$data['lang']= $this->language->all();
		$html = $this->load->view('invoice/invoice_pdf_'.$data['common']['info']['invoice']['template'], $data);
		
		$html_array = array('html' => $html, 'result' => $data['result']);
		$pdf = new Pdf();
		$pdf->saveInvoicePDF($html_array);
	}
	/**
	* Invoice invoice mail method
	* This method will be called on to mail invoice details when adding new invoices
	**/
	private function invoiceMail($id)
	{
		$result = $this->model_recurringjob->getTemplate('newinvoice');

		if (empty($result['template']) || $result['template']['status'] == '0') {
			return false;
		}
		$data = $this->model_recurringjob->getInoviceView($id);

		$data['id'] = str_pad($data['id'], 4, '0', STR_PAD_LEFT);
		$site_link = '<a href="'.URL_CLIENTS.DIR_ROUTE.'invoice/view&id='.$id.'">Click Here</a>';

		$message = $result['template']['message'];
		$message = str_replace('{company}', $data['company'], $message);
		$message = str_replace('{invoice_id}', $result['common']['invoice']['prefix'].$data['id'], $message);
		$message = str_replace('{invoice_amount}', $data['currency_abbr'].$data['amount'], $message);
		$message = str_replace('{invoice_paid}', $data['currency_abbr'].$data['paid'], $message);
		$message = str_replace('{invoice_due}', $data['currency_abbr'].$data['due'], $message);
		$message = str_replace('{invoice_due_date}', $data['duedate'], $message);
		$message = str_replace('{business_name}', $result['common']['name'], $message);
		$message = str_replace('{invoice_url}', $site_link, $message);

		$data['mail']['name'] = $data['company'];
		$data['mail']['email'] = $data['email'];
		$data['mail']['subject'] = $result['template']['subject'];
		$data['mail']['message'] = $message;

		if (file_exists(DIR.'public/uploads/pdf/invoice-'.$id.'.pdf')) {
			$data['mail']['attachments'] = array('file' => DIR.'public/uploads/pdf/invoice-'.$id.'.pdf', 'name' => 'Invoice');
		}

		$mail_result = $this->sendmail($data['mail']);
		if ($mail_result == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function sendMail($data)
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getSiteInfo();
		$data['mail_info'] = $this->model_commons->getMailInfo();
		if ($data['mail_info']['status'] == '0') {
			return "Mail service is disabled. Please emable it to send mails.";
		}
		
		try {
			if ($data['mail_info']['status'] == '2') {
				$this->mail->setSmtp($data['mail_info']);
			}

			if (!empty($data['mail_info']['fromemail'])) {
				$this->mail->setFrom($data['mail_info']['fromemail'], $data['mail_info']['fromname']);
			} elseif (!empty($data['common']['mail'])) {
				$this->mail->setFrom($data['common']['mail'], $data['common']['name']);
			} else {
				return 'Please enter valid From Email Address in Email setting field!';
			}

			if (!empty($data['mail_info']['reply'])) {
				$this->mail->addReplyTo($data['mail_info']['reply'], $data['mail_info']['fromname']);
			}

			$this->mail->addAddress($data['email'], $data['name']);

			if (!empty($data['cc'])) {
				$this->mail->addCC($data['cc']);
			}
			if (!empty($data['bcc'])) {
				$this->mail->addBCC($data['bcc']);
			}
			if (!empty($data['attachments'])) {
				$this->mail->addAttachment($data['attachments']['file'], $data['attachments']['name']);
			}

			$this->mail->isHTML();
			$this->mail->setSubject($data['subject']);
			$this->mail->setMessage(html_entity_decode($data['message']));
			$this->mail->sendMail();
			$this->mail->clearAddresses();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}