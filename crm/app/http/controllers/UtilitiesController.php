<?php

/**
* UtilitiesController
*/
class UtilitiesController extends Controller
{
	public function emailLog()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$data['period']['start'] = $this->url->get('start');
		$data['period']['end'] = $this->url->get('end');
		
		$this->load->controller('commons');
		if (!empty($data['period']['start']) && !empty($data['period']['end']) && !$this->controller_commons->validateDate($data['period']['start']) && !$this->controller_commons->validateDate($data['period']['end'])) {
			$data['period']['start'] = date_format(date_create($data['period']['start'].'00:00:00'), "Y-m-d H:i:s");
			$data['period']['end'] = date_format(date_create($data['period']['end'].'23:59:59'), "Y-m-d H:i:s");
		} else {
			$data['period']['start'] = date('Y-m-d '.'00:00:00', strtotime("-1 month"));
			$data['period']['end'] = date('Y-m-d '.'23:59:59');
		}

		$this->load->model('utilities');
		$data['result'] = $this->model_utilities->getEmailLogs($data['period']);

		/*Load Language File*/
		$this->language->load('utilities', 'utilities');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_email_log');
		$data['page_active'] = 'utilities';
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('utilities/email_log', $data));
	}

	public function cronLog()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$this->load->model('utilities');
		$data['result'] = $this->model_utilities->getCronLogs();

		/*Load Language File*/
		$this->language->load('utilities', 'utilities');

		/* Set page title */
		$data['page_title'] = $this->language->get('text_cron_log');
		$data['page_active'] = 'utilities';
		
		$data['lang']= $this->language->all();
		/*Render User list view*/
		$this->response->setOutput($this->load->view('utilities/cron_log', $data));
	}

	public function databaseBackup()
	{
		/* Load common data */
		$this->load->model('commons');
		
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/*Load Language File*/
		$data['lang']['common'] = $this->language->load('common', 'common');

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}

		$data['action'] = URL.DIR_ROUTE.'dbbackup';
		$data['page_active'] = 'utilities';
		$data['page_title'] = $this->language->get('text_database_backup');

		$data['lang']= $this->language->all();
		/*Render Info view*/
		$this->response->setOutput($this->load->view('utilities/dbbackup', $data));
	}

	public function dbBackupDownload()
	{
		$this->load->model('utilities');
		$content = $this->model_utilities->DBdump();

		// Save the SQL script to a backup file
		$backup_file_name = 'CM_backup_' . time() . '.sql';
		$fileHandler = fopen($backup_file_name, 'w+');
		$number_of_lines = fwrite($fileHandler, $content);
		fclose($fileHandler); 

    	// Download the SQL backup file to the browser
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($backup_file_name));
		ob_clean();
		flush();
		readfile($backup_file_name);
		exec('rm ' . $backup_file_name);
		exit();
		die();
	}
}