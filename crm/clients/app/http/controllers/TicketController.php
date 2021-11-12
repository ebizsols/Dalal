<?php

/**
* TicketController
*/
class TicketController extends Controller
{
	public function index()
	{
		$customer = $this->session->data['customer'];
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['customer']);

		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('tickets', 'tickets');
		
		$this->load->model('ticket');
		$data['result'] = $this->model_ticket->getTickets($data['common']['user']['email']);

		$data['page_title'] = $this->language->get('text_tickets');
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		/*Set action method for form submit call*/
		$data['action'] = URL_CLIENTS.DIR_ROUTE.'ticket/action';

		$data['lang']= $this->language->all();
		/*Render Info view*/
		$this->response->setOutput($this->load->view('ticket/ticket_list', $data));
	}

	public function indexAdd()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['customer']);

		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('tickets', 'tickets');
		
		$this->load->model('ticket');
		$data['departments'] = $this->model_ticket->getDepartments();

		$data['result'] = NULL;
		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_ticket');
		$data['action'] = URL_CLIENTS.DIR_ROUTE.'ticket/action';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('ticket/ticket_edit', $data));
	}

	public function indexEdit()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['customer']);
		/**
		* Check if id exist in url if not exist then redirect to Invoice list view 
		**/
		$data['id'] = (int)$this->url->get('id');
		if (empty($data['id']) || !is_int($data['id'])) {
			$this->url->redirect('tickets');
		}

		$this->load->model('ticket');
		$data['result'] = $this->model_ticket->getTicket($data);
		
		if (empty($data['result'])) {
			$this->url->redirect('tickets');
		}

		/*Load Language File*/
		$this->language->load('common', 'common');
		$this->language->load('tickets', 'tickets');

		/**
		* Get all messages from DB using Ticket model 
		**/
		$data['messages'] = $this->model_ticket->getMessages($data['result']['id']);
		
		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_ticket');
		$data['action'] = URL_CLIENTS.DIR_ROUTE.'ticket/action';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('ticket/ticket_edit', $data));
	}

	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('tickets');
		}

		$this->load->model('ticket');
		if (!empty($this->url->post('id'))) {
			$data = $this->url->post('ticket');
			$data['attached'] = $this->uplodeFile($_FILES['filename']);
			$data['user_id'] = $this->session->data['customer'];
			$data['id'] = $this->url->post('id');
			$data['status'] = isset($data['status']) ? $data['status'] : 0;
			$data['last_updated'] = date("Y-m-d h:i:s");
			$data['datetime'] = date("Y-m-d h:i:s");

			$result = $this->model_ticket->updateTicket($data);

			if ($data['status'] == '1') {
				$data['template'] = 'closeticket';
			} else {
				$data['template'] = 'ticketreply';
			}
			$this->ticketUpdateMail($data);
			
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Ticket updated successfully.');
			$this->url->redirect('ticket/edit&id='.$data['id']);
		} else {
			$data = $this->url->post('ticket');
			$data['attached'] = $this->uplodeFile($_FILES['filename']);
			$data['user_id'] = $this->session->data['user_id'];
			$data['last_updated'] = date("Y-m-d h:i:s");
			$data['datetime'] = date("Y-m-d h:i:s");
			
			$result = $this->model_ticket->createTicket($data);
			
			$data['id'] = $result;
			$this->newTicketMail($data);

			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Ticket created successfully.');
			$this->url->redirect('ticket/edit&id='.$result);
		}
	}

	private function uplodeFile($data)
	{
		$ds = DIRECTORY_SEPARATOR;  
		$storeFolder = '../public/uploads/ticket';
		$file_name_array = array();

		foreach ($data['name'] as $key => $value) {
			$newname = time();
			$rand = rand(100, 999);
			$postfix = date('Ymd');
			
			$ext = pathinfo($value, PATHINFO_EXTENSION);
			$file_name = pathinfo($value, PATHINFO_FILENAME);
			$name = 'Attachment__'.$newname.$rand.'.'.$ext; 
			
			if (!empty($file_name)) {
				$tempFile = $data['tmp_name'][$key];
				
				$targetPath = $storeFolder . $ds;
				$targetFile =  $targetPath. $name;
				move_uploaded_file($tempFile,$targetFile);
				array_push($file_name_array, $name);
				
			}
		}
		
		return json_encode($file_name_array);
	}

	public function downloadFile()
	{
		$file = $this->url->get('name');

		if (empty($file)) {
			$this->url->redirect('closetab');
			exit();
		} else {
			$filepath = DIR."public/uploads/ticket/".$file;

			if(file_exists($filepath)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($filepath));
				flush(); 
				readfile($filepath);
				$this->url->redirect('closetab');
				exit();
			} else {
				$this->url->redirect('closetab');
				exit();
			}
		}
		//$this->url->redirect('closetab');
		//exit();
	}

	private function newTicketMail($data)
	{
		$this->load->controller('Mail');
		$result = $this->controller_mail->getTemplate('newticket');
		
		if (empty($result['template']) || $result['template']['status'] == '0') {
			return false;
		}

		$this->load->model('commons');
		$info = $this->model_commons->getSiteInfo();
		
		$ticketurl = '<a href="'.URL_CLIENTS.DIR_ROUTE.'tickets'.'">Tickets</a>';
		$message = $result['template']['message'];
		$message = str_replace('{SUBJECT}', $data['subject'], $message);
		$message = str_replace('{NAME}', $data['name'], $message);
		$message = str_replace('{ID}', $data['id'], $message);
		$message = str_replace('{EMAIL}', $data['email'], $message);
		$message = str_replace('{MESSAGE}', $data['descr'], $message);
		$message = str_replace('{TICKETURL}', $ticketurl, $message);

		$data['mail']['name'] = $data['name'];
		$data['mail']['email'] = $data['email'];
		$data['mail']['subject'] = $result['template']['subject'];
		$data['mail']['message'] = $message;
		
		$mail_result = $this->controller_mail->sendmail($data['mail']);

		if ($mail_result == 1) {
			return true;
		} else {
			return false;
		}
	}

	private function ticketUpdateMail($data)
	{
		$this->load->controller('Mail');
		$result = $this->controller_mail->getTemplate($data['template']);
		
		if (empty($result['template']) || $result['template']['status'] == '0') {
			return false;
		}

		$this->load->model('commons');
		$info = $this->model_commons->getSiteInfo();
		$ticket = $this->model_ticket->getTicketView($data);

		$message = $result['template']['message'];
		$ticketurl = '<a href="'.URL_CLIENTS.DIR_ROUTE.'tickets'.'">Tickets</a>';
		$message = str_replace('{SUBJECT}', $ticket['subject'], $message);
		$message = str_replace('{NAME}', $ticket['name'], $message);
		$message = str_replace('{ID}', $ticket['id'], $message);
		$message = str_replace('{EMAIL}', $ticket['email'], $message);
		$message = str_replace('{MESSAGE}', $data['descr'], $message);
		$message = str_replace('{TICKETURL}', $ticketurl, $message);

		$data['mail']['name'] = $ticket['name'];
		$data['mail']['email'] = $ticket['email'];
		$data['mail']['subject'] = $result['template']['subject'];
		$data['mail']['message'] = $message;
		
		$mail_result = $this->controller_mail->sendmail($data['mail']);

		if ($mail_result == 1) {
			return true;
		} else {
			return false;
		}
	}
}