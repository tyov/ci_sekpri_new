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
			        "text":"Books",
			        "state":"open",
			        "attributes":{
			            "url":"/demo/book/abc",
			            "view":100
			        },
			        "children":[{
			            "text":"PhotoShop",
			            "checked":true
			        },{
			            "id": 8,
			            "text":"Sub Bookds",
			            "state":"closed"
			        }]
			    }]
			},{
			    "text":"Languages",
			    "state":"closed",
			    "children":[{
			        "text":"Java"
			    },{
			        "text":"C#"
			    }]
			}]';
		echo $menu;	
	}

}