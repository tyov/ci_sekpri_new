<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pump_controller extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model("pump_model", "pump");
	}
	
	function cekAppVersi(){
		$appname = @$this->input->post("app_name");
		$appver = @$this->input->post("app_ver");
		
		$ver = "1.0.3";
		if($appver==$ver){
			$response["updated"] = "0";			
			$response["message"] = "Versi Aplikasi Masih Sama";
			echo '{"response":'.json_encode($response).'}';
		}else{
			$response["updated"] = "1";
			$response["message"] = "Aplikasi Terbaru Versi ".$ver.". Anda tidak dapat melanjutkan sebelum melakukan update.";
			$response["name"] = "monitoring.v.1.0.3.apk";
			$response["link"] = "http://114.6.41.22:1003/ci_api/apk/monitoring.v.1.0.3.apk";
			echo '{"response":'.json_encode($response).'}';
		}
	}
	
	function getEmail($list){
		$device = "";
		$query = $this->db->query("select b.email FROM ScadaNetDbArchive_1.dbo.tbl_dtl_list a inner join 
								   ScadaNetDbArchive_1.dbo.tbl_groupuser b on a.nip=b.nip
								   and a.id_list='".$list."'");
		foreach($query->result() as $row){
			$device .= $row->email.',';
		}
		return substr_replace($device,"",-1);
	}
	
	function kirimEmail(){
		$query = $this->db->query("SELECT a.*, b.email FROM ScadaNetDbArchive_1.dbo.tbl_notifLog a
		left join ScadaNetDbArchive_1.dbo.tbl_distribution_list b on a.id_list = b.id_list
		where a.status = '0'");
		
		
		
		foreach($query->result() as $detail){
			$isi = $detail->content;
			//$email=$this->db->query(
			$email = str_replace(" ",",",$this->getEmail($detail->id_list));
			$this->pump->settingSMTP();
			$this->email->from('eoffice_noreply@pdamkotamalang.com', $detail->title);
			$this->email->to($email);
			$this->email->subject($detail->subject);
			$this->email->message($isi);
			$status = @$this->email->send();
			if(!$status){
				echo "Error";
				echo $this->email->print_debugger();
			}else{
				$this->db->query("update ScadaNetDbArchive_1.dbo.tbl_notifLog set status = '1' where id_log = '".$detail->id_log."'");
				echo "Sukses";
			}
		}
	}
		
	function getDeviceId(){
		$device = "";
		$query = $this->db->query("select device_id FROM ScadaNetDbArchive_1.dbo.tbl_device");
		foreach($query->result() as $row){
			$device .= $row->device_id.'~';
		}
		return explode("~",substr_replace($device,"",-1));
	}
	
	function kirimNotifikasi(){
		$url = 'https://fcm.googleapis.com/fcm/send';
		$server_key = 'AIzaSyByz11sGL0d7xevGbWgauFDMCC4LI7QTGM';
		
		$query = $this->db->query("SELECT b.userIdentification, b.archivedAlarm_id, b.stationNumber, b.stationLabel, b.infoLabel, b.infoNumber, b.alarmEvent, convert(VARCHAR(10),b.date,3) as tanggal, convert(VARCHAR(10),b.date,108) as date FROM ScadaNetDbArchive_1.dbo.tbl_logSendAlarm a
		left join ScadaNetDb.dbo.ArchivedAlarms b on a.archivedAlarm_id = b.archivedAlarm_id
		where a.status = '0' and b.infoLabel <> 'Communication status'
		order by b.archivedAlarm_id desc");
		foreach($query->result() as $rw){	
			$target = $this->getDeviceId();					
			$fields = array();
			$fields['data'] = array('post_id'=>rand(1,99),
									'userIdentification' => $rw->userIdentification,
									'archivedAlarm_id' => $rw->archivedAlarm_id,
									'stationNumber' => $rw->stationNumber,
									'stationLabel' => $rw->stationLabel,
									'infoLabel' => $rw->infoLabel,
									'infoNumber' => $rw->infoNumber,
									'alarmEvent' => $rw->alarmEvent,
									'tanggal' => $rw->tanggal,
									'date' => $rw->date);
			if(is_array($target)){
				$fields['registration_ids'] = $target;
			}else{
				$fields['to'] = $target;
			}
			
			$headers = array(
				'Content-Type:application/json',
				'Authorization:key='.$server_key
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if($result === FALSE) {
				die('FCM Send Error: ' . curl_error($ch));
			}else{
				$status = json_decode($result);
				$status = $status->success;
				if($status=='1'){
					echo "SUKSES";
					$this->db->query("update ScadaNetDbArchive_1.dbo.tbl_logSendAlarm set status = '1' where archivedAlarm_id = '".$rw->archivedAlarm_id."'");
				}
			}
			curl_close($ch);			
		}
	}
	
	function getAlarmNotif(){
		$url = 'https://fcm.googleapis.com/fcm/send';
		$server_key = 'AIzaSyByz11sGL0d7xevGbWgauFDMCC4LI7QTGM';
		$target = $this->getDeviceId();
		$qList = $this->db->query("SELECT b.userIdentification, b.archivedAlarm_id, b.stationNumber, b.stationLabel, b.infoLabel, b.infoNumber, b.alarmEvent, convert(VARCHAR(10),b.date,3) as tanggal, convert(VARCHAR(10),b.date,108) as date FROM ScadaNetDbArchive_1.dbo.tbl_logSendAlarm a
		left join ScadaNetDb.dbo.ArchivedAlarms b on a.archivedAlarm_id = b.archivedAlarm_id
		where a.status = '0' -- and b.infoLabel <> 'Communication status'
		order by b.archivedAlarm_id desc");
		$rw = $qList->row();
		$fields = array();
		$fields['data'] = array('post_id'=>rand(1,99),
								'userIdentification' => $rw->userIdentification,
								'archivedAlarm_id' => $rw->archivedAlarm_id,
								'stationNumber' => $rw->stationNumber,
								'stationLabel' => $rw->stationLabel,
								'infoLabel' => $rw->infoLabel,
								'infoNumber' => $rw->infoNumber,
								'alarmEvent' => $rw->alarmEvent,
								'tanggal' => $rw->tanggal,
								'date' => $rw->date);
		if(is_array($target)){
			$fields['registration_ids'] = $target;
		}else{
			$fields['to'] = $target;
		}
		
		$headers = array(
			'Content-Type:application/json',
			'Authorization:key='.$server_key
		);
					
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		if($result === FALSE) {
			die('FCM Send Error: ' . curl_error($ch));
		}else{
			echo $result;
		}
		curl_close($ch);
	}
	
	function UbahPassword($Value="pdam") {
		return sha1(md5('018691fbba180f0bbd33d28cdb2e0e41a7afae5d:'.$Value));
	}
	
	function logIn(){
		$username = $this->input->post("username");
		echo $username;
	}
	
	function cekLoginAndroid(){
		$username = $this->input->post("username");	
		$password = $this->UbahPassword($this->input->post("password"));
		$user = $this->pump->getUserLogin($username, $password);
		if($user<>"0"){
			$query = $this->db->query("SELECT * FROM ScadaNetDbArchive_1.dbo.tbl_groupuser where nip = '$username'");
			if($query->num_rows() > 0){
				$response["success"] = "1";		
				$response["nip"] = $user->nip;
				$response["nama_lengkap"] = $user->nama_lengkap;
				$response["keterangan"] = $user->keterangan;
				$response["nama_bagian"] = $user->nama_bagian;
				$response["tanggal_server"] = date("Y-m-d");
				$response["message"] = "Berhasil Login";
			}else{
				$response["success"] = "0";	
				$response["message"] = "Gagal Login";
			}
			echo '{"response":'.json_encode($response).'}';
		}else{
			$response["success"] = "0";		
			$response["message"] = "Gagal Login";
			echo '{"response":'.json_encode($response).'}';
		}
	}
	
	function cekHari($tanggal=""){
		$day = date('D', strtotime($tanggal));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		$hari = $dayList[$day];
		
		$month = date('m', strtotime($tanggal));
		$monthList = array("01"=>"Januari","01"=>"Januari","02"=>"Pebruari","03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember");
		$bulan = $monthList[$month];
		
		return $hari.", ".date('d', strtotime($tanggal))." ".$bulan." ".date('Y', strtotime($tanggal))." ".date('H:i:s', strtotime($tanggal));
	}
	
	function cekBulan($tanggal=""){
		
	}
	
	/*
	public function setPump(){
		$data = $this->pump->getInbox();
		foreach($data->result() as $row){
			$checkPump = $this->pump->getPump($row->SenderNumber);
			if($checkPump->num_rows() > 0){
				$this->pump->updatePump($row->SenderNumber, $row->TextDecoded);					
			}
			$this->pump->updateInbox($row->ID);
		}
	}
	*/
	
	function getStationMonitoring(){
		$username = @$this->input->post("username");
		$query = $this->pump->getStationMonitoring($username);
		
		$hasilJson = array();
		$id = "";
		foreach($query->result() as $row){
			$id .= $row->station_id."','";
			$rows['icon'] = $row->icon;
			$rows['station_id'] = $row->station_id;
			$rows['label'] = $row->label;
			$rows['type'] = $row->type;
			$rows['jenis'] = $row->jenis;
			$hasilJson[] = $rows;
		}	
		$response["dataFasilitas"] = $hasilJson;
		$response["idStationOnServer"] = "'".substr_replace($id, '', -3)."'";
		echo '{"response":'.json_encode($response).'}';
	}
	
	function getDetailMonitoring1(){
		$kode = @$this->input->post("kode");
		$username = @$this->input->post("username");
		$query = $this->db->query("select b.*, zz.edit from ScadaNetDb.dbo.Stations a left join (
		select numberInStation, CONVERT(VARCHAR(1),0)+CONVERT(VARCHAR(200),numericInformation_id) as accessId, 0 type, station_id, numericInformation_id, label, timeStamp, ROUND(value, 2) as value, unit from ScadaNetDb.dbo.NumericInformations
		UNION all
		select numberInStation, CONVERT(VARCHAR(1),1)+CONVERT(VARCHAR(200),logicalInformation_id) as accessId, 1 type, station_id, logicalInformation_id, label, timeStamp, ROUND(value, 2) as value, case when value = 1 then stateSuffix1 else stateSuffix0 end as unit from ScadaNetDb.dbo.LogicalInformations) b on a.station_id = b.station_id
		right join (select CONVERT(VARCHAR(200),c.logical)+CONVERT(VARCHAR(200),c.id_detailaccess) as accessID, c.edit from tbl_groupuser a
		left join tbl_maccess b on a.id_group = b.id_group
		left join tbl_itemaccess c on b.id_access = c.id_access
		where a.nip = '$username' and site_number = '$kode') zz on b.accessId = zz.accessID
		where a.siteNumber = '$kode'
		order by numberInStation");
		$hasilJson = array();
		$id = "";
		foreach($query->result() as $row){
			$id .= $row->numericInformation_id."','";
			$rows['numberInStation'] = $row->numberInStation;
			$rows['numericInformation_id'] = $row->numericInformation_id;
			$rows['label'] = $row->label;
			$rows['timeStamp'] = $this->cekHari($row->timeStamp);
			$rows['value'] = $row->value;
			$rows['unit'] = $row->unit;
			$rows['type'] = $row->type;
			$rows['edit'] = $row->edit;
			$hasilJson[] = $rows;
		}
		$response["detailMonitoring"] = $hasilJson;
		$response["idDetailInServer"] = "'".substr_replace($id, '', -3)."'";
		echo '{"response":'.json_encode($response).'}';
	}
	
	function getDetailMonitoring_old(){
		$kode = @$this->input->post("kode");
		$username = @$this->input->post("username");
		$query = $this->db->query("select b.*, zz.edit from ScadaNetDb.dbo.Stations a left join (
		select numberInStation, CONVERT(VARCHAR(1),0)+CONVERT(VARCHAR(200),numericInformation_id) as accessId, 0 type, station_id, numericInformation_id, label, timeStamp, ROUND(value, 2) as value, unit from ScadaNetDb.dbo.NumericInformations
		UNION all
		select numberInStation, CONVERT(VARCHAR(1),1)+CONVERT(VARCHAR(200),logicalInformation_id) as accessId, 1 type, station_id, logicalInformation_id, label, timeStamp, ROUND(value, 2) as value, case when value = 1 then stateSuffix1 else stateSuffix0 end as unit from ScadaNetDb.dbo.LogicalInformations) b on a.station_id = b.station_id
		right join (select CONVERT(VARCHAR(200),c.logical)+CONVERT(VARCHAR(200),c.id_detailaccess) as accessID, c.edit from tbl_groupuser a
		left join tbl_maccess b on a.id_group = b.id_group
		left join tbl_itemaccess c on b.id_access = c.id_access
		where a.nip = '$username' and site_number = '$kode') zz on b.accessId = zz.accessID
		where a.siteNumber = '$kode' and unit <> '°C'
		order by numberInStation");
		$hasilJson = array();
		$id = "";
		foreach($query->result() as $row){
			$id .= $row->numericInformation_id."','";
			$rows['numberInStation'] = $row->numberInStation;
			$rows['numericInformation_id'] = $row->numericInformation_id;
			$rows['label'] = $row->label;
			$rows['timeStamp'] = $this->cekHari($row->timeStamp);
			$rows['value'] = $row->value;
			$rows['unit'] = $row->unit;
			$rows['type'] = $row->type;
			$rows['edit'] = $row->edit;
			$hasilJson[] = $rows;
		}
		$response["detailMonitoring"] = $hasilJson;
		$response["idDetailInServer"] = "'".substr_replace($id, '', -3)."'";
		echo '{"response":'.json_encode($response).'}';
	}
	
	function getDetailMonitoring(){
		$kode = @$this->input->post("kode");
		$username = @$this->input->post("username");
		$query = $this->db->query("select a.numberInStation, CONVERT(VARCHAR(1),0)+CONVERT(VARCHAR(200),a.numericInformation_id) as accessId, 0 type, a.station_id, a.numericInformation_id, a.label, timeStamp, ROUND(value, 2) as value, a.unit, case when (a.min = 0 and a.max = 0) then 0 else 1 end as edit from ScadaNetDb.dbo.NumericInformations a 
		left join ScadaNetDb.dbo.Stations b on a.station_id = b.station_id
		where b.siteNumber = '$kode' and a.numberInStation <> '46'
		union all
		select a.numberInStation, CONVERT(VARCHAR(1),1)+CONVERT(VARCHAR(200),a.logicalInformation_id) as accessId, 1 type, a.station_id, a.logicalInformation_id, a.label, timeStamp, ROUND(value, 2) as value, case when value = 1 then a.stateSuffix1 else a.stateSuffix0 end as unit, a.isPLLocked as edit from ScadaNetDb.dbo.LogicalInformations a
		left join ScadaNetDb.dbo.Stations b on a.station_id = b.station_id
		where b.siteNumber = '$kode'");
		$hasilJson = array();
		$id = "";
		foreach($query->result() as $row){
			$id .= $row->numericInformation_id."','";
			$rows['numberInStation'] = $row->numberInStation;
			$rows['numericInformation_id'] = $row->numericInformation_id;
			$rows['label'] = $row->label;
			$rows['timeStamp'] = $this->cekHari($row->timeStamp);
			$rows['value'] = $row->value;
			$rows['unit'] = $row->unit;
			$rows['type'] = $row->type;
			$rows['edit'] = $row->edit;
			$hasilJson[] = $rows;
		}
		$response["detailMonitoring"] = $hasilJson;
		$response["idDetailInServer"] = "'".substr_replace($id, '', -3)."'";
		echo '{"response":'.json_encode($response).'}';
	}
	
	function getDetailItem(){
		$kode = @$this->input->post("kode");
		$username = @$this->input->post("username");
		$logical = @$this->input->post("logical");
		if($logical=="0"){
			$query = $this->db->query("select d.label, ROUND(d.value, 2) as value, d.station_id, d.numericInformation_id as detail_id from ScadaNetDbArchive_1.dbo.tbl_itemaccess a 
			left join ScadaNetDbArchive_1.dbo.tbl_maccess b on a.id_access = b.id_access
			left join ScadaNetDbArchive_1.dbo.tbl_groupuser c on b.id_group = c.id_group
			left join ScadaNetDb.dbo.NumericInformations d on a.id_detailaccess = d.numericInformation_id
			where a.id_detailaccess = '$kode' and c.nip = '$username'");
		}else{
			$query = $this->db->query("select d.label, ROUND(d.value, 2) as value, d.station_id, d.logicalInformation_id as detail_id, d.stateSuffix0, d.stateSuffix1 from ScadaNetDbArchive_1.dbo.tbl_itemaccess a 
			left join ScadaNetDbArchive_1.dbo.tbl_maccess b on a.id_access = b.id_access
			left join ScadaNetDbArchive_1.dbo.tbl_groupuser c on b.id_group = c.id_group
			left join ScadaNetDb.dbo.LogicalInformations d on a.id_detailaccess = d.logicalInformation_id
			where a.id_detailaccess = '$kode' and c.nip = '$username'");
		}
		
		$qList = $this->db->query("select TOP 20 user_id, new_value, old_value, convert(VARCHAR(10),change_date,3) as tanggal, convert(VARCHAR(10),change_date,108) as date, context from ScadaNetDbArchive_1.dbo.tbl_changelog 
		where information_id = '$kode' and logical = '$logical'
		order by change_date DESC");
		$data = $query->row();	
		$listLog = array();
		foreach($qList->result() as $rw){
			$rows['user_id'] = $rw->user_id;
			$rows['new_value'] = $rw->new_value;
			$rows['old_value'] = $rw->old_value;
			$rows['tanggal'] = $rw->tanggal;
			$rows['date'] = $rw->date;
			$rows['context'] = $rw->context;
			$listLog[] = $rows;
		}
		if($logical<>"0"){
			$response["stateSuffix0"] = $data->stateSuffix0; 
			$response["stateSuffix1"] = $data->stateSuffix1; 
		}else{
			$response["stateSuffix0"] = "0"; 
			$response["stateSuffix1"] = "1"; 
		}
		$response["listLog"] = $listLog; 
		$response["label"] = $data->label;
		$response["value"] = $data->value;
		$response["station_id"] = $data->station_id;
		$response["detail_id"] = $data->detail_id;
		$response["infoType"] = ($logical=="0")?"1":"0";
		echo '{"response":'.json_encode($response).'}';
	}
	
	function insertLog(){ 
		$user_id = $this->input->post("user_id");
		$information_id = $this->input->post("information_id");
		$logical = $this->input->post("logical");
		$new_value = $this->input->post("new_value");
		$old_value = $this->input->post("old_value");
		$change_date = date("Y-m-d H:i:s");
		$context = $this->input->post("context");
		$data = array("user_id"=>$user_id,
						"information_id"=>$information_id,
						"logical"=>$logical,
						"new_value"=>$new_value,
						"old_value"=>$old_value,
						"change_date"=>$change_date,
						"context"=>$context);
		$this->db->insert("ScadaNetDbArchive_1.dbo.tbl_changelog", $data);
		
		$qList = $this->db->query("select TOP 20 user_id, new_value, old_value, convert(VARCHAR(10),change_date,3) as tanggal, convert(VARCHAR(10),change_date,108) as date, context from ScadaNetDbArchive_1.dbo.tbl_changelog 
		where information_id = '$information_id' and logical = '$logical'
		order by change_date DESC");
		$listLog = array();
		foreach($qList->result() as $rw){
			$rows['user_id'] = $rw->user_id;
			$rows['new_value'] = $rw->new_value;
			$rows['old_value'] = $rw->old_value;
			$rows['tanggal'] = $rw->tanggal;
			$rows['date'] = $rw->date;
			$rows['context'] = $rw->context;
			$listLog[] = $rows;
		} 
		$response["success"] = "1";
		$response["message"] = "Success";
		$response["listLog"] = $listLog;
		echo '{"response":'.json_encode($response).'}';
	}
	
	function getAlarm(){		
		$qList = $this->db->query("SELECT top 200 userIdentification, archivedAlarm_id, stationNumber, stationLabel, infoLabel, infoNumber, alarmEvent, convert(VARCHAR(10),date,3) as tanggal, convert(VARCHAR(10),date,108) as date FROM ScadaNetDb.dbo.ArchivedAlarms 
		where infoLabel <> 'Communication status'
		order by archivedAlarm_id desc;");
		$listLog = array();
		foreach($qList->result() as $rw){
			$rows['userIdentification'] = $rw->userIdentification;
			$rows['archivedAlarm_id'] = $rw->archivedAlarm_id;
			$rows['stationNumber'] = $rw->stationNumber;
			$rows['stationLabel'] = $rw->stationLabel;
			$rows['infoLabel'] = $rw->infoLabel;
			$rows['infoNumber'] = $rw->infoNumber;
			$rows['alarmEvent'] = $rw->alarmEvent;
			$rows['tanggal'] = $rw->tanggal;
			$rows['date'] = $rw->date;
			$listLog[] = $rows;
		}
		$response["listAlarm"] = $listLog;
		echo '{"response":'.json_encode($response).'}';
	}
	
	function getDataGrafik(){
		$kode = @$this->input->post("kode");		
		$query = $this->db->query("select REPLACE(right(date,5),' ','-') as label, value from (select ROW_NUMBER() OVER(PARTITION BY convert(varchar(13),date,120) ORDER BY archivedNumericInformation_id desc) as urut, numericInformation_id, archivedNumericInformation_id, convert(varchar(13),date,120) as date, value from ArchivedNumericInformations 
		where numericInformation_id = '$kode' and [date] BETWEEN DATEADD(day,-1,GETDATE()) and GETDATE()) z where urut = 1");
		$listLabel = array();
		foreach($query->result() as $row){
			$rows['label'] = $row->label;
			$rows['value'] = $row->value;
			$listLabel[] = $rows;
		}
		$response["label"] = $listLabel;
		$response["yLabel"] = "25688";
		$response["xLabel"] = $query->num_rows();
		echo '{"response":'.json_encode($response).'}';
	}
}








