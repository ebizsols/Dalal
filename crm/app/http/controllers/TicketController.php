<?php

/**
* TicketController.php
*/
class TicketController extends Controller
{
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('tickets', 'tickets');
		/**
		* Get all Tickets from DB using Event model
		* According to @user_role 
		**/
		$this->load->model('ticket');

		if (!empty($this->url->get('filter')) && $data['common']['user']['role_id'] != '1') {
			if ($this->url->get('filter') == "open") {
				/* Set page title */
				$data['page_title'] = $this->language->get('text_open_tickets');
				$data['result'] = $this->model_ticket->getOpenTickets($data['common']['user']['user_id']);
			} elseif ($this->url->get('filter') == "closed") {
				/* Set page title */
				$data['page_title'] = $this->language->get('text_closed_tickets');
				$data['result'] = $this->model_ticket->getClosedTickets($data['common']['user']['user_id']);
			} else {
				/* Set page title */
				$data['page_title'] = $this->language->get('text_all_tickets');
				$data['result'] = $this->model_ticket->getTickets($data['common']['user']['user_id']);
			}
		} elseif ($data['common']['user']['role_id'] != '1') {
			/* Set page title */
			$data['page_title'] = $this->language->get('text_tickets');
			$data['result'] = $this->model_ticket->getTickets($data['common']['user']['user_id']);
		} elseif ($data['common']['user']['role_id'] == '1') {
			$data['page_title'] = $this->language->get('text_tickets');
			$data['result'] = $this->model_ticket->getTickets();
		}

		$data['ticketCount'] = $this->model_ticket->getTicketCount();

		$data['page_add'] = $this->user_agent->hasPermission('ticket/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('ticket/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('ticket/delete') ? true : false;
		$data['action_delete'] = URL.DIR_ROUTE.'ticket/delete';
		$data['page_active'] = 'ticket';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('ticket/ticket_list', $data));
	}

	public function indexAdd()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/**
		* Get all User data from DB using User model 
		**/
		$data['result'] = NULL;
		
		$this->load->model('ticket');
		if ($data['common']['user']['role_id'] != '1') {
			$data['admin'] = false;
		} else {
			$data['admin'] = true;
			$data['staff'] = $this->model_ticket->getStaff();
		}

		$data['departments'] = $this->model_ticket->getDepartments();

		/*Load Language File*/
		$this->language->load('tickets', 'tickets');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_new_ticket');
		$data['action'] = URL.DIR_ROUTE.'ticket/add';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'ticket';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('ticket/ticket_form', $data));
	}

	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Item list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('tickets');
		}
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$this->language->load('tickets', 'tickets');

		/**
		* Get all User data from DB using User model 
		**/

		$this->load->model('ticket');
		if ($data['common']['user']['role_id'] != '1') {
			$data['admin'] = false;
			$data['result'] = $this->model_ticket->getTicket($id, $data['common']['user']['user_id']);
		} else {
			$data['admin'] = true;
			$data['staff'] = $this->model_ticket->getStaff();
			$data['result'] = $this->model_ticket->getTicket($id);
		}

		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Ticket not found in Database.');
			$this->url->redirect('tickets');
		}

		$data['messages'] = $this->model_ticket->getMessages($id);

		/* Set page title */
		$data['page_title'] = $this->language->get('text_edit_ticket');
		$data['action'] = URL.DIR_ROUTE.'ticket/edit';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['page_active'] = 'ticket';

		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('ticket/ticket_form', $data));
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
			$data['attached'] = $this->uplodeFile($this->url->file('filename'));
			$data['user_id'] = $this->session->data['user_id'];
			$data['id'] = $this->url->post('id');
			$data['status'] = isset($data['status'])? $data['status']:0;
			$data['last_updated'] = date("Y-m-d h:i:s");
			$data['datetime'] = date("Y-m-d h:i:s");

			$result = $this->model_ticket->updateTicket($data);

			if ($data['status'] == '1') {
				$data['template'] = 'closeticket';
			} else {
				$data['template'] = 'ticketresponce';
			}
			$this->ticketUpdateMail($data);

			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Ticket updated successfully.');
			$this->url->redirect('ticket/edit&id='.$data['id']);
		} else {
			$data = $this->url->post('ticket');
			$data['attached'] = $this->uplodeFile($this->url->file('filename'));
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

	public function indexDelete()
	{
		$this->load->model('ticket');
		$id = $this->url->post('id');
		$data['message'] = $this->model_ticket->getMessages($id);

		foreach ($data['message'] as $key => $value) {
			if (!empty($attached = json_decode($value['attached']))) {
				foreach ($attached as $attached_key => $attached_value) {
					if (file_exists('public/uploads/ticket/'.$attached_value)) {
						unlink('public/uploads/ticket/'.$attached_value);
					}
				}
			}
		}

		$data['id'] = $id;
		$data['template'] = 'deleteticket';
		$this->ticketDelete($data);
		$result = $this->model_ticket->deleteTicket($id);
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Ticket deleted successfully.');
		$this->url->redirect('tickets');
	}

	private function uplodeFile($data)
	{
		$ds = DIRECTORY_SEPARATOR;  
		$storeFolder = DIR.'public/uploads/ticket';
		$file_name_array = array();

		foreach ($data['name'] as $key => $value) {
			$newname = time();
			$rand = rand(100, 999);
			$postfix = date('Ymd');
			
			$ext = pathinfo($value, PATHINFO_EXTENSION);
			$file_name = pathinfo($value, PATHINFO_FILENAME);
			$name = 'Attachment_'.$newname.$rand.'.'.$ext; 
			
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
			} else {
				$this->url->redirect('closetab');
				exit();
			}
		}
		$this->url->redirect('closetab');
		exit();
	}

	private function newTicketMail($data)
	{
		$this->load->controller('mail');
		$result = $this->controller_mail->getTemplate('newticket');
		
		if (empty($result['template']) || $result['template']['status'] == '0') {
			return false;
		}
		$this->load->model('ticket');
		$message = $result['template']['message'];
		$ticketurl = '<a href="'.URL_CLIENTS.DIR_ROUTE.'tickets'.'">Tickets</a>';
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

		$this->load->controller('Mail');
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

		$this->load->model('ticket');
		$ticket = $this->model_ticket->getTicket($data['id']);
		$ticketurl = '<a href="'.URL_CLIENTS.DIR_ROUTE.'tickets'.'">Tickets</a>';

		$message = $result['template']['message'];
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

	private function ticketDelete($data)
	{
		$this->load->controller('Mail');
		$result = $this->controller_mail->getTemplate($data['template']);
		
		if (empty($result['template']) || $result['template']['status'] == '0') {
			return false;
		}

		$this->load->model('ticket');
		$ticket = $this->model_ticket->getTicket($data['id']);
		$message = $result['template']['message'];
		$ticketurl = '<a href="'.URL_CLIENTS.DIR_ROUTE.'tickets'.'">Tickets</a>';
		$message = str_replace('{SUBJECT}', $ticket['subject'], $message);
		$message = str_replace('{ID}', $ticket['id'], $message);
		$message = str_replace('{TICKETURL}', $ticketurl, $message);

		$data['mail']['name'] = $ticket['name'];
		$data['mail']['email'] = $ticket['email'];
		$data['mail']['subject'] = $result['template']['subject'];
		$data['mail']['message'] = $message;
		
		$this->load->controller('Mail');
		$mail_result = $this->controller_mail->sendmail($data['mail']);

		if ($mail_result == 1) {
			return true;
		} else {
			return false;
		}
	}
}