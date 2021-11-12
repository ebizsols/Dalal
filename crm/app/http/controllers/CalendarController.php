<?php

/**
* CalendarController
*/
class CalendarController extends Controller
{
	/**
	* Calendar index method
	* This method will be called on Calendar view
	**/
	public function index()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all Event data from DB using Event model
		* According to user_role 
		**/		
		$this->load->model('calendar');

		/*Load Language File*/
		$this->language->load('calendar', 'calendar');

		$data['month_names'] = json_encode(array($this->language->get('text_january'), $this->language->get('text_february'), $this->language->get('text_march'), $this->language->get('text_april'), $this->language->get('text_may'), $this->language->get('text_june'), $this->language->get('text_july'), $this->language->get('text_august'), $this->language->get('text_september'), $this->language->get('text_october'), $this->language->get('text_november'), $this->language->get('text_december')));

		$data['month_names_short'] = json_encode(array($this->language->get('text_jan'), $this->language->get('text_feb'), $this->language->get('text_mar'), $this->language->get('text_apr'), $this->language->get('text_may'), $this->language->get('text_jun'), $this->language->get('text_jul'), $this->language->get('text_aug'), $this->language->get('text_sep'), $this->language->get('text_oct'), $this->language->get('text_nov'), $this->language->get('text_dec')));

		$data['day_names'] = json_encode(array($this->language->get('text_sunday'), $this->language->get('text_monday'), $this->language->get('text_tuesday'), $this->language->get('text_wednesday'), $this->language->get('text_thursday'), $this->language->get('text_friday'), $this->language->get('text_saturday')));

		$data['day_names_short'] = json_encode(array($this->language->get('text_sun'), $this->language->get('text_mon'), $this->language->get('text_tue'), $this->language->get('text_wed'), $this->language->get('text_thu'), $this->language->get('text_fri'), $this->language->get('text_sat')));

		$data['buttons'] = json_encode(array('today' => $this->language->get('text_today'),
			'month' => $this->language->get('text_month'),
			'week' => $this->language->get('text_week'),
			'day' => $this->language->get('text_day'),
		));

		/* Set Page title and action */
		$data['page_title'] = $this->language->get('text_events');
		$data['page_add'] = $this->user_agent->hasPermission('calendar/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('calendar/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('calendar/delete') ? true : false;
		$data['action'] = URL.DIR_ROUTE.'calendar/edit';
		$data['action_delete'] = URL.DIR_ROUTE.'calendar/delete';
		$data['page_active'] = 'calendar';
		
		$data['lang']= $this->language->all();
		/*Render Calendar list view*/
		$this->response->setOutput($this->load->view('calendar/calendar', $data));
	}

	public function indexEvent()
	{
		/* Load common data */
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$request = $this->url->get;
		$this->load->model('calendar');
		if ($data['common']['user']['role_id'] == "1") {
			$result = json_encode($this->model_calendar->getEvents($request));
		} else {
			$result = json_encode($this->model_calendar->getUserEvents($request, $this->session->data['user_id']));
		}

		echo $result;
	}
	/**
	* Calendar index Action method
	* This method will be called on Event add or Update.
	**/
	public function indexAction()
	{
		/**
		* Check if form is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('calendar');
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to Calendar view 
		**/
		$data = $this->url->post('event');
		$data['allday'] = isset($data['allday']) ? $data['allday'] : 0;
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();

		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data, $data['info']['date_format'].' H:i A')) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('calendar');
		}
		$data['start'] = DateTime::createFromFormat($data['info']['date_format'].' H:i A', $data['start']);
		$data['start_date'] = $data['start']->format('Y-m-d');
		$data['start_time'] = $data['start']->format('H:i:s');

		if ($data['allday'] == '0') {
			$data['end'] = DateTime::createFromFormat($data['info']['date_format'].' H:i A', $data['end']);
			$data['end_date'] = $data['end']->format('Y-m-d');
			$data['end_time'] = $data['end']->format('H:i:s');
		} else {
			$date = $data['start'];
			$date->setTime(23,59);
			$data['end_date'] = date_format($date, 'Y-m-d');
			$data['end_time'] = date_format($date, 'H:i:s');
		}
		
		$data['user_id'] = $this->session->data['user_id'];
		$data['id'] = $this->url->post('id');
		
		$this->load->model('calendar');
		if (!empty($data['id'])) {
			$result = $this->model_calendar->updateEvent($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Calendar Event updated successfully.');

		} else {
			$result = $this->model_calendar->createEvent($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Calendar Event created successfully.');
		}
		$this->url->redirect('calendar');
	}
	/**
	* Calendar index Delete method
	* This method will be called on Event delete
	**/
	public function indexDelete()
	{
		$this->load->model('calendar');
		$result = $this->model_calendar->deleteEvent($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Event deleted successfully.');
		$this->url->redirect('calendar');
	}

	/*Validate Event Input Filed*/
	public function validateField($data, $format)
	{
		$error = [];
		$error_flag = false;

		if ($this->controller_commons->validateText($data['title'])) {
			$error_flag = true;
			$error['name'] = 'Event Name!';
		}
		if ($this->validateDate($data['start'], $format)) {
			$error_flag = true;
			$error['startdate'] = 'Start Date!';
		}
		if ($data['allday'] == '0') {
			if ($this->validateDate($data['end'], $format)) {
				$error_flag = true;
				$error['end'] = 'End Date!';
			}
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	public function validateDate($date, $format)
	{	
		$date = DateTime::createFromFormat($format, $date);
		$date_errors = DateTime::getLastErrors();
		
		if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
			return true;
		} else {
			return false;
		}

	}
	/**
	* Calendar index Drop method
	* This method will be called on cjhange event date
	**/
	public function indexDrop()
	{
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to Calendar view 
		**/
		$data = $this->url->post('event');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		$data['allday'] = isset($data['allday']) ? $data['allday'] : 0;

		$this->load->controller('commons');
		if ($validate_field = $this->validateField($data, $data['info']['date_format'].' H:i:s')) {
			echo json_encode(array('error' => true, 'message' => 'Please enter valid '.implode(", ",$validate_field).'!'));
			exit();
		}
		$data['start'] = DateTime::createFromFormat($data['info']['date_format'].' H:i:s', $data['start']);
		$data['start_date'] = $data['start']->format('Y-m-d');
		$data['start_time'] = $data['start']->format('H:i:s');

		if ($data['allday'] == '0') {
			$data['end'] = DateTime::createFromFormat($data['info']['date_format'].' H:i:s', $data['end']);
			$data['end_date'] = $data['end']->format('Y-m-d');
			$data['end_time'] = $data['end']->format('H:i:s');
		} else {
			$date = $data['start'];
			$date->setTime(23,59);
			$data['end_date'] = date_format($date, 'Y-m-d');
			$data['end_time'] = date_format($date, 'H:i:s');
		}
		
		$data['user_id'] = $this->session->data['user_id'];
		
		$this->load->model('calendar');
		$this->model_calendar->updateEvent($data);
		echo json_encode(array('error' => false, 'message' => 'Page updated successfully.'));
	}
}