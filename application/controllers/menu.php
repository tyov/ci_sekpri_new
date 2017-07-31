<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo " ";
	}
	public function getData()
	{
		$menu ='[{
				    "id":1,
				    "text":"Berkas",
				    "iconCls":"icon-save",
				    "children":[{
				    	"id":"11",
				        "text":"Berkas",
				        "checked":true,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        },{
				        "id":"12",
				        "text":"Ekspedisi",
				        "checked":false,
				        "attributes":{
				            "url":"ekspedisi",
				            "view":"ekspedisi"
				        	}
				        },{
				        "id":"13",
				        "text":"Master Ekspedisi",
				        "checked":false,
				        "attributes":{
				            "url":"master_ekspedisi",
				            "view":"master_ekspedisi"
				        	}
				        }]
				 },{
				    "id":2,
				    "text":"Agenda",
				    "iconCls":"icon-save",
				    "children":[{
				    	"id":"21",
				        "text":"Agenda",
				        "checked":false,
				        "attributes":{
				            "url":"Agenda",
				            "view":"Agenda"
				        	}
				        },{
				        "id":"22",
				        "text":"Master Ruangan",
				        "checked":false,
				        "attributes":{
				            "url":"master_ruangan",
				            "view":"master_ruangan"
				        	}
				        
				        }]
				 },{
				    "id":3,
				    "text":"Laporan",
				    "iconCls":"icon-save",
				    "children":[{
				    	"id":"31",
				        "text":"Berkas",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        },{
				        "id":"32",
				        "text":"Ekspedisi",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        },{
				       	"id":"33",
				        "text":"Master Laporan",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        }]
				 }
			    ]';
		echo $menu;	
	}

	public function getContent(){
		//print_r($_POST);
		$url = isset($_POST['url'])?$_POST['url']:"";
		$view = isset($_POST['view'])?$_POST['view']:"";
		$this->load->view($view,null,true);
		//echo $view;
	}

	public function getContentMenu($view=''){
		// //print_r($_POST);
		// $url = isset($_POST['url'])?$_POST['url']:"";
		// $view = isset($_POST['view'])?$_POST['view']:"";
		$this->load->view($view);
		//echo $view;
	}

}