<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {
	function __construct() {
        parent::__construct();
		$this->load->library('session');
		$this->load->model('files_db');
		$this->load->model('database_db');
		$this->load->model('messages_db');
		$this->load->model('preferences_db');
		$this->load->helper('url');
		$this->load->library('form_validation');
    }
	
	public function index() {
		if ($this->session->userdata('status') != 'authorizedUser') {
			header("location:".$this->config->item('base_url')."index.php?status=unauthorizedAccess");
		} else {
			$this->mysmarty->assign('status', $this->session->userdata('status'));
			$this->mysmarty->assign('user', $this->session->userdata('username'));
			$this->mysmarty->assign('base_url', $this->config->item('base_url'));
			$this->mysmarty->assign('nonExistentPaths', $this->checkPaths());
			$this->mysmarty->assign('nEPaths', $this->getNonExistentPaths());
			$this->mysmarty->display('header.tpl');
			$this->mysmarty->display('audit_trail/index.tpl');
			$this->mysmarty->display('footer.tpl');
		}
	}
	
	public function readFile() {
		$files = new files_db();
		$filename = $_GET['file'];
		//$filename = $this->input->post('file');
		$doc = $_GET['doc'];
		//$doc = $this->input->post('doc');
		$items = $_GET['items'];
		//$items = $this->input->post('items');
		$error_msg = false;
		$path = $files->getFilePath($filename);
		$full_path = $path."\\".$filename;
		if($path) {
			//echo $full_path;
		} else $error_msg = "No results to display. Either file is not found or no results at all.";
		
		$temp = explode("-", $filename);
		$month_in_num = $temp[1];
		$month = date("F", mktime(0, 0, 0, $temp[1], 10));
		$year = substr($temp[2], 0, strpos($temp[2], "."));
		$date = $month." ".$year;
		
		$lines = file($full_path);
		/**foreach ($lines as $line_num => $line) {
			echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
		}*/
		if ($doc == "Income Statement") {
			foreach ($lines as $line) {
				$temp = explode(",", $line);
				$size = sizeOf($temp);
				if ($size > 1) {
					$info[] = array(
						'account' => trim($temp[0]),
						'amount' => trim($temp[1])
					);
				} else {
					$info[] = array(
						'account' => trim($temp[0]),
						'amount' => ""
					);
				}
			}
			
			$size = sizeOf($info);		
			foreach ($info as $i) {
				//echo $i['account'];
				//echo $i['amount'];
			}
		}
		
		$this->mysmarty->assign('fr_kind', $doc);
		$this->mysmarty->assign('info', $info);
		$this->mysmarty->assign('source', $full_path);
		$this->mysmarty->assign('error_msg', $error_msg);
		$this->mysmarty->assign('date', $date);
		$this->mysmarty->assign('month', $month_in_num);
		$this->mysmarty->assign('year', $year);
		$this->mysmarty->assign('file', $filename);
		$this->session->set_userdata('items_of_interest', $items);
		$this->mysmarty->display('header.tpl');
		$this->mysmarty->display('audit_trail/trail_fs.tpl');
		$this->mysmarty->display('footer.tpl');
	}
	
	public function checkPaths() {
		$preferences = new preferences_db();
		$paths = $preferences->getDocPaths();
		$noOfNonExistentPaths = 0;
		foreach ($paths as $p) {
			if (!$this->pathExists($p['path']))
				$noOfNonExistentPaths++;
		}
		return $noOfNonExistentPaths;
	}
	
	public function getNonExistentPaths() {
		$preferences = new preferences_db();
		$paths = $preferences->getDocPaths();
		$nonExistentPaths = array();
		foreach ($paths as $p) {
			if (!$this->pathExists($p['path']))
				$nonExistentPaths[] = $p['path'];
		}
		return $nonExistentPaths;
	}

	public function pathExists($path) {
		//echo $path;
		if (is_dir($path)) {
			//echo "EXIST!";
			return true;
		} else {
			//echo "nope";
			return false;
		}
	}
	
	public function replyPerson() {
		$database = new database_db();
		$messages = new messages_db();
		$num = $_GET['num'];
		$content = $_GET['reply'];
		if ($content != '' && $content != ' ') {
			$temp = explode(" ", $content);
			$ref = $temp[0];
			$ans = $temp[1];

			$status = $database->checkMessageStatus($ref);
			if ($status != "confirmed") {
				if (strcasecmp($ans, "yes") == 0) {
					$reply_msg = "Thank you for your confirmation! We will call you should there be a need for further customer validation. Thank you and have a good day!";
					$database->saveReceivedMessage($ref, 'yes');
					$info = $messages->getMessageByRef($ref);
					header("location: ".$this->config->item('base_url')."messages/log_messages/writeToLog?data=".$info['date_received']." ".$info['name']."(0".$info['contact'].") replied 'yes' on Ref. No. ".$info['ref']);
				} else if (strcasecmp($ans, "no") == 0) {
					$reply_msg = "We are very sorry for bothering you. Thank you for your response, anyway. Have a good day!";
					$database->saveReceivedMessage($ref, 'no');
					$info = $messages->getMessageByRef($ref);
					header("location: ".$this->config->item('base_url')."messages/log_messages/writeToLog?data=".$info['date_received']." ".$info['name']."(0".$info['contact'].") replied 'no' on Ref. No. ".$info['ref']);
				} else $reply_msg = "Sorry, you have entered an invalid parameter. Please reply TRAIL<space><Ref No><space><Yes or No>. Thank you.";
			} else $reply_msg = "You have already sent your confirmation on this transaction with Ref. No.: ". $ref .". Thank you for your cooperation!";
			echo $reply_msg;
		} else {
			$reply_msg = "Sorry, you have entered an invalid parameter. Please reply TRAIL<space><Ref No><space><Yes or No>. Thank you.";
			echo $reply_msg;
		}
		return $reply_msg;
	}
	
	public function textPerson() {
		$database = new database_db();
		$messages = new messages_db();
		//$data['ref'] = rand(1000,9999);
		
		$data['name'] = $_GET['name'];
		$data['ref'] = $_GET['ref'];
		$data['contact'] = $_GET['contact'];
		$data['date'] = $_GET['date'];
		$data['or_no'] = $_GET['or_no'];
		$data['amt'] = $_GET['amt'];
		
		$database->storeSentMessage($data);
		$info = $messages->getMessageByRef($data['ref']);
		header("location: ".$this->config->item('base_url')."messages/log_messages/writeToLog?data=".$info['date_sent']." ".$this->session->userdata('username')." sent a confirmation message to ".$info['name']." at 0".$info['contact']." with Ref. No. ".$info['ref']." on OR No. ".$info['or_no']." amounting to Php".$info['amt']);
	}
	
	public function test() {
		$database = new database_db();
		$messages = $database->getIgnoredMessages();
		$this->mysmarty->assign('messages', $messages);
		$this->mysmarty->display('header.tpl');
		$this->mysmarty->display('messages.tpl');
		$this->mysmarty->display('footer.tpl');
	}
	
	public function checkIgnoredAndNewMessages() {
		$database = new database_db();
		$currentNo = $_GET['no'];
		$data['latest_count'] = $database->countIgnoredAndNewMessages();
		echo json_encode($data);
	}
	
	public function checkNewMessages() {
		$database = new database_db();
		$data['latest_count'] = $database->countLatestNewMessages();
		$data['all'] = $database->countIgnoredAndNewMessages();
		echo json_encode($data);
	}
	
	public function confirmMessage() {
		$database = new database_db();
		$messages = new messages_db();
		$id = $_GET['id'];
		$database->confirmMessage($id);
		$info = $messages->getMessageByID($id);
		header("location: ".$this->config->item('base_url')."messages/log_messages/writeToLog?data=".$info['date_confirmed']." ".$this->session->userdata('username')." confirmed message with Ref. No. ".$info['ref']);
	}
	
	public function ignoreMessage() {
		$database = new database_db();
		$id = $_GET['id'];
		$database->ignoreMessage($id);
	}
	
	public function logout() {
		$account = new account();
		$account->logOut();
	}
}

?>