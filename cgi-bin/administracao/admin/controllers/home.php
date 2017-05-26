<?php 
class Home extends CI_Controller{
	
		
function index(){
	 $this->load->view(strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__))."_view");
	}



	public function bkp(){
		$this->load->dbutil();
		$this->load->helper('file');
		
		$prefs = array(
		        'ignore'      => array('estados','cidades'),
                'format'      => 'txt',             // gzip, zip, txt
                'filename'    => 'banco.sql',    // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );
		
		$backup =& $this->dbutil->backup($prefs); 
		$name_file = 'public/bkp/unos'.date('d-m-Y-H-i-s').'.sql';
        write_file($name_file, utf8_decode($backup)); 
		
		$this->load->library('email');

		$this->email->from('contato@u', 'OBJETO - BKP UNOS');
		$this->email->to('objetoco@gmail.com'); 
		$this->email->subject('us bkp '.date('d-m-Y-H-i-s').' '.date('F'));
		$this->email->message('Bkp do banco de dados unos empreendedores em '.date('d-m-Y-H-i-s'));
		$this->email->attach($name_file);	
		$this->email->send();
		exit;		
		
			
 }	
		


  		

/*
| -------------------------------------------------------------------------
| FIM DO CONTROLLER
| -------------------------------------------------------------------------
*/	
 }

?>