<?php
/**
* Phpmailer Class
*/
require_once DIR_LIBRARY.'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{
	public $dompdf;
	public function __construct()
	{
		// instantiate and use the dompdf class
		// $this->dompdf = new Dompdf(); 
		// global $_dompdf_warnings;
		// $_dompdf_warnings = array();
		// global $_dompdf_show_warnings;
		// $_dompdf_show_warnings = true;
		$options = new Options();
		$options->setIsRemoteEnabled(true);
		$this->dompdf = new Dompdf($options);
	}

	public function createPDF($data)
	{
		$this->dompdf->loadHtml($data['html']);
		$this->dompdf->setPaper('letter');
		$this->dompdf->render();
		// Output the generated PDF to Browser
		$this->dompdf->stream("Document.pdf", array("Attachment" => false));
	}

	public function saveInvoicePDF($data)
	{
		$this->dompdf->loadHtml($data['html']);
		$this->dompdf->setPaper('letter');
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents(DIR.'public/uploads/pdf/invoice-'.$data['result']['id'].'.pdf', $output);
	}

	public function saveQuotePDF($data)
	{
		$this->dompdf->loadHtml($data['html']);
		$this->dompdf->setPaper('letter');
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents(DIR.'public/uploads/pdf/quote-'.$data['result']['id'].'.pdf', $output);
	}
}