<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Load_Trans_Details extends CI_Controller {

	function __construct() {
        parent::__construct();
		$this->load->library('session');
		$this->load->model('files_db');
		$this->load->model('database_db');
		$this->load->helper('url');
		$this->load->library('form_validation');
    }
	
	public function index() {
		$files = new files_db();
		$database = new database_db();
		$or_no = $_GET['or_no'];
		$name = $_GET['name'];
		$amt_due = $_GET['amt'];
		$dets_total_amt = 0;
		$address = $_GET['address'];
		$contact = $_GET['contact'];
		$date = "";
		$data = false;
		$error_msg_det = "";
		$details = $database->getTransactionDetailsByOR($or_no);
		if ($details) {
			foreach ($details as $d) {
				$date = $d[0];
				$data[] = array(
					0 => $d[0],
					1 => $d[1],
					2 => $d[2],
					3 => $d[3],
					4 => $d[4],
					5 => $d[5]
				);
				$dets_total_amt += $d[5];
			}
		} else $error_msg_det = "No data found.";
		//echo json_encode($data);
		$this->mysmarty->assign('or_no', $or_no);
		$this->mysmarty->assign('date', $date);
		$this->mysmarty->assign('name', $name);
		$this->mysmarty->assign('amt_due', $amt_due);
		$this->mysmarty->assign('dets_total_amt', $dets_total_amt);
		$this->mysmarty->assign('address', $address);
		$this->mysmarty->assign('contact', $contact);
		$this->mysmarty->assign('details', $data);
		$this->mysmarty->assign('error_msg_det', $error_msg_det);
		$this->mysmarty->display('audit_trail/trans_details.tpl');
	}
	
	public function logout() {
		$account = new account();
		$account->logOut();
	}
}
?>