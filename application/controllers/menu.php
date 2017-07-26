<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "asd";
	}
	public function getData()
	{
		$menu ='[{
				    "id":1,
				    "text":"Berkas",
				    "iconCls":"icon-save",
				    "children":[{
				        "text":"Berkas",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        },{
				        "text":"Ekspedisi",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        },{
				        "text":"Master Ekspedisi",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        }]
				 },{
				    "id":2,
				    "text":"Agenda",
				    "iconCls":"icon-save",
				    "children":[{
				        "text":"Agenda",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        },{
				        "text":"Ekspedisi",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        },{
				        "text":"Master Agenda",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        }]
				 },{
				    "id":3,
				    "text":"Laporan",
				    "iconCls":"icon-save",
				    "children":[{
				        "text":"Berkas",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        },{
				        "text":"Ekspedisi",
				        "checked":false,
				        "attributes":{
				            "url":"berkas",
				            "view":"berkas"
				        	}
				        },{
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

}