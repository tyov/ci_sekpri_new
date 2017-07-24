<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($keyId="", $username=""){
		$this->data["keyId"] = $keyId;
		$this->data["username"] = $username;
		$this->data["listAttachment"] = $this->showAttachment($keyId);
		$this->load->view('welcome_message', $this->data);
	}
	
	function showAttachment($keyId=""){
		$query = $this->db->query("select filename, filesize, filetype from lampiranmail where key_id = '$keyId' order by uploaddate desc");
		return $query->result();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */