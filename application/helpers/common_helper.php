<?php

    if (! function_exists('StripArray')) {
        function StripArray($Array, $FieldName = array()) {
            $ArrayResult = array();
            foreach($Array as $Key => $Element) {
                if (in_array($Key, $FieldName) && in_array($Element, array('0', '0000-00-00', '0000-00-00 00:00:00'))) {
                    $ArrayResult[$Key] = null;
                    } else {
                    $ArrayResult[$Key] = stripslashes($Element);
                }
            }
            return $ArrayResult;
        }
    }
    
    if (! function_exists('EscapeString')) {
        function EscapeString($Array) {
            $ArrayResult = array();
            foreach($Array as $Key => $Element) {
                $ArrayResult[$Key] = pg_escape_string($Element);
            }
            return $ArrayResult;
        }
    }
    
    if (! function_exists('GetOption')) {
        function GetOption($OptAll, $ArrayOption, $Selected) {
            $temp = ($Selected == 0) ? 'selected' : '';
            $Content = ($OptAll) ? '<option value="0" '.$temp.'>All<option>' : '';
            foreach ($ArrayOption as $Value => $Title) {
                $temp = ($Selected == $Value) ? 'selected' : '';
                $Content .= '<option value="'.$Value.'" '.$temp.'>'.$Title.'</option>';
            }
            return $Content;
        }
    }
    
    if (! function_exists('ShowOption')) {
        function ShowOption($Param) {
            $Param['OptAll'] = (isset($Param['OptAll'])) ? $Param['OptAll'] : false;
            $Param['ArrayID'] = (isset($Param['ArrayID'])) ? $Param['ArrayID'] : 'id';
            $Param['WithEmptySelect'] = (isset($Param['WithEmptySelect'])) ? $Param['WithEmptySelect'] : 1;
            
            $Param['ArrayTitle'] = (isset($Param['ArrayTitle'])) ? $Param['ArrayTitle'] : 'title';
            $Param['Selected'] = (isset($Param['Selected'])) ? $Param['Selected'] : '';
            
            if ($Param['WithEmptySelect'] == 1) {
                $Content = '<option value="">-</option>';
                } else {
                $Content = '';
            }
            
            $Selected = '';
            if ($Param['OptAll']) {
                $Selected = ($Param['Selected'] == '0') ? 'selected' : '';
                $Content .= '<option value="0" ' . $Selected . '>Semua</option>';
            }
            
            foreach ($Param['Array'] as $Array) {
                $Selected = ($Param['Selected'] == $Array[$Param['ArrayID']]) ? 'selected' : '';
                $Content .= '<option value="'.$Array[$Param['ArrayID']].'" '.$Selected.'>'.$Array[$Param['ArrayTitle']].'</option>';
            }
            
            return $Content;
        }
    }
    
    if (! function_exists('ArrayToJSON')) {
        function ArrayToJSON($Array) {
            $Result = '';
            foreach ($Array as $Key => $Element) {
                $Element = pg_escape_string($Element);
                $Result .= (empty($Result)) ? "'$Key': '$Element'" : ",'$Key':'$Element'";
            }
            $Result = '{' . $Result . '}';
            return $Result;
        }
    }
    
    if (! function_exists('ConvertToUnixTime')) {
        function ConvertToUnixTime($String) {
            preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/i', $String, $Match);
            $UnixTime = mktime ($Match[4], $Match[5], $Match[6], $Match[2], $Match[3], $Match[1]);
            $UnixTime = 'new Date('.$UnixTime.')';
            return $UnixTime;
        }
    }
    
    if (! function_exists('ConvertDateToString')) {
        function ConvertDateToString($String) {
            preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/i', $String, $Match);
            return date("d F Y", mktime (0, 0, 0, $Match[2], $Match[3], $Match[1]));
        }
    }
    
    if (! function_exists('ConvertDateToQuery')) {
        function ConvertDateToQuery($String) {
            preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/i', $String, $Match);
            if (isset($Match[0]) && !empty($Match[0])) {
                $Result = $Match[0];
                return $Result;
            }
            
            $Array = explode('/', $String);
            $Result = $Array[2] . '-' . $Array[0] . '-' . $Array[1];
            
            return $Result;
        }
    }
    
    if (! function_exists('MoneyFormat')) {
        function MoneyFormat($Value) {
            return number_format($Value, 2, ',', '.');
        }
    }
	
    if (! function_exists('show_price')) {
        function show_price($Value) {
            return 'Rp. '.number_format($Value, 2, ',', '.');
        }
    }
    
    if (! function_exists('Upload')) {
        function Upload($InputName, $PathDir = 'User', $Param = array()) {
            $Param['AllowedExtention'] = (isset($Param['AllowedExtention'])) ? $Param['AllowedExtention'] : array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'xls', 'csv');
            
            $ArrayResult = array('Result' => '0', 'FileDirName' => '');
            if (isset($_FILES[$InputName]) && is_array($_FILES[$InputName]) && is_array($_FILES[$InputName]['name'])) {
                $FileCount = count($_FILES[$InputName]['name']);
                for ($i = 0; $i < $FileCount; $i++) {
                    if ($_FILES[$InputName]['error'][$i] == '0') {
                        $Extention = F_GetExtention($_FILES[$InputName]['name'][$i]);
                        $FileName = date("Ymd_His").'_'.rand(1000,9999).'.'.$Extention;
                        
                        @mkdir(IMGS_DIR.'/'.date("Y"));
                        @mkdir(IMGS_DIR.'/'.date("Y").'/'.date("m"));
                        @mkdir(IMGS_DIR.'/'.date("Y").'/'.date("m").'/'.date("d"));
                        $FileLocation = IMGS_DIR.'/'.date("Y").'/'.date("m").'/'.date("d").'/'.basename($FileName);
                        $FileRequest = date("Y").'/'.date("m").'/'.date("d").'/'.basename($FileName);
                        
                        if (move_uploaded_file($_FILES[$InputName]['tmp_name'][$i], $FileLocation)) {
                            $ParamImage = array(
							'FileSource' => $FileLocation,
							'Width' => 456,
							'Height' => 320,
                            );
                            F_Resize($ParamImage);
                            $ArrayResult['Result'] = '1';
                            $ArrayResult['ArrayImage'][] = $FileRequest;
                        }
                    }
                }
            }
            else if (isset($_FILES[$InputName]) && !empty($_FILES[$InputName]) && !empty($_FILES[$InputName]['name'])) {
                $Extention = GetExtention($_FILES[$InputName]['name']);
                $ArrayResult['Message'] = 'There was an error uploading the file, please try again!';
                $ArrayResult['FileDirName'] = '';
                
                if (! in_array($Extention, $Param['AllowedExtention'])) {
                    $ArrayResult['Message'] = 'Hanya file bertipe jpg, jpeg, gif, png, bmp dan xls yang dapat di upload.';
                    } else if ($_FILES[$InputName]['error'] == '0') {
                    $DirYear = date("Y");
                    $DirMonth = date("m");
                    $DirDay = date("d");
                    
                    @mkdir($PathDir.'/'.$DirYear);
                    @mkdir($PathDir.'/'.$DirYear.'/'.$DirMonth);
                    @mkdir($PathDir.'/'.$DirYear.'/'.$DirMonth.'/'.$DirDay);
                    
                    $FileName = date("Ymd_His").'_'.rand(1000,9999).'.'.$Extention;
                    $FileDirectory = $PathDir;
                    $FileLocation = $FileDirectory.'/'.$DirYear.'/'.$DirMonth.'/'.$DirDay.'/'.basename($FileName);
                    
                    if (move_uploaded_file($_FILES[$InputName]['tmp_name'], $FileLocation)) {
                        $ArrayResult['Result'] = '1';
                        $ArrayResult['Message'] = 'Upload file berhasil.';
                        $ArrayResult['FileDirName'] = $DirYear.'/'.$DirMonth.'/'.$DirDay.'/'.$FileName;
                    }
                }
            }
            
            return $ArrayResult;
        }
    }
    
    if (! function_exists('UploadFtp')) {
        /*
            $ParamUpload = array(
            'UploadFtp' => 1,
            'WithCreateDir' => 1,
            'Name' => date('YmdHis') . '_' . rand(1000,9999),
            'Extention' => GetExtention($_FILES['document']['name']),
            'UploadPathFtp' => SFTP_PATH,
            'UploadPathLocal' => $this->config->item('base_path') . '/static/images/_temp'
            );
            $FileUpload = UploadFtp($ParamUpload, 'document');
        /*	*/
        function UploadFtp($File, $Name = 'Image') {
            $File['WithCreateDir'] = (isset($File['WithCreateDir'])) ? $File['WithCreateDir'] : 0;
            $File['UploadFtp'] = (isset($File['UploadFtp'])) ? $File['UploadFtp'] : 0;
            $ArrayResult = array('Message' => '', 'Status' => 1);
            
            if (isset($_FILES[$Name]) && !empty($_FILES[$Name])) {
                $FileName = basename($File['Name'] . '.' . $File['Extention']);
                $PathFileName = $File['UploadPathLocal'] . '/'. $FileName;
                @unlink($PathFileName);
                if (! move_uploaded_file($_FILES[$Name]['tmp_name'], $PathFileName)) {
                    $ArrayResult['Status'] = 0;
                    $ArrayResult['Message'] = '<span class="red">There was an error uploading the file, please try again!</span>';
                    } else {
                    ImageResize($PathFileName, $PathFileName, PHOTO_WIDTH, PHOTO_HEIGHT, 1);
                }
                
                if ($ArrayResult['Status'] == 1 && $File['UploadFtp'] == 1) {
                    $FtpConnection = ftp_connect(SFTP_HOST);
                    $FtpResource = ftp_login($FtpConnection, SFTP_USER, SFTP_PASS);
                    ftp_pasv($FtpConnection, (bool)true);
                    
                    // Create Date Directory
                    $DateDir = '';
                    if ($File['WithCreateDir'] == 1) {
                        $DirFile = array(date("Y"), date("m"), date("d"));
                        foreach ($DirFile as $Directory) {
                            $DateDir .= $Directory . '/';
                            $File['UploadPathFtp'] .= '/' . $Directory;
                            @ftp_mkdir($FtpConnection, $File['UploadPathFtp']);
                        }
                    }
                    
                    // Delete File with same name
                    $FileNameDelete = $File['UploadPathFtp'] . '/' . basename($File['Name']);
                    foreach (array('jpg', 'png', 'jpeg', 'pdf') as $Value) {
                        @ftp_delete($FtpConnection, $FileNameDelete . '.' . $Value);
                    }
                    
                    ftp_chdir($FtpConnection, $File['UploadPathFtp']);
                    $FtpUpload = ftp_put($FtpConnection, $FileName, $PathFileName, FTP_BINARY);
                    
                    $ArrayResult['FileName'] = $DateDir . $File['Name'] . '.' . $File['Extention'];
                }
            }
            
            return $ArrayResult;
        }
    }
    
    if (! function_exists('GetExtention')) {
        function GetExtention($FileName) {
			$ext = pathinfo($FileName, PATHINFO_EXTENSION);
			$ext = strtolower($ext);
			return $ext;
			
			/*
                $FileName = strtolower(trim($FileName));
                if (empty($FileName)) {
                return '';
                }
                
                $ArrayString = explode('.', $FileName);
                return $ArrayString[count($ArrayString) - 1];
            /*	*/
        }
    }
    
    if (! function_exists('Write')) {
        function Write($FileLocation, $FileContent) {
            $Handle = @fopen($FileLocation, 'wb+');
            if ($Handle) {
                fputs($Handle, $FileContent);
                fclose($Handle);
            }
        }
    }
    
    if (! function_exists('GetStringFilter')) {
        // $Param = array('filter' => '[{"type":"numeric","comparison":"eq","value":"' . $company_id . '","field":"company_id"},{"type":"numeric","comparison":"eq","value":1,"field":"menu_company_active"}]');
		
		/*
			// overwrite field name
			$param['field_replace']['id'] = 'Nota.id';
			$param['field_replace']['nota_currency_total'] = 'Nota.nota_total';
			$param['field_replace']['status_nota_name'] = 'StatusNota.name';
        /*	*/
		
        function GetStringFilter($Param, $ReplaceField = array(), $string_default = '') {
		
            $StringFilter = '';
            
            if (isset($Param['sSearch'])) {
				$field_replace = (isset($Param['field_replace'])) ? $Param['field_replace'] : array();
				
                $StringFilter = "";
                if ( $Param['sSearch'] != "" ) {
                    $StringFilter = "AND (";
                    $aWords = preg_split('/\s+/', $Param['sSearch']);
                    for ($j = 0; $j < count($aWords); $j++) {
                        if ( $aWords[$j] != "" ) {
                            $StringFilter .= "(";
                            for ($i = 0; $i < count($ReplaceField); $i++) {
								$field_name = (isset($field_replace[$ReplaceField[$i]])) ? $field_replace[$ReplaceField[$i]] : $ReplaceField[$i];
								if (empty($field_name)) {
									continue;
                                }
								
                                $StringFilter .= $field_name." ILIKE '%".pg_escape_string( $aWords[$j] )."%' OR ";
                            }
                            $StringFilter = substr_replace( $StringFilter, "", -3 );
                            $StringFilter .= ") AND ";
                        }
                    }
                    
					
					
                    $StringFilter = substr_replace( $StringFilter, "", -5 );
                    $StringFilter .= ")";
                }
                
                /* Individual column filtering */
                $sColumnWhere = "";
                for ( $i=0 ; $i<count($ReplaceField) ; $i++ ) {  
                    if ( isset($Param['sSearch_'.$i]) && !empty($Param['sSearch_'.$i]) ) {
                        $aWords = preg_split('/\s+/', $Param['sSearch_'.$i]);
                        $sColumnWhere .= "(";
                        for ( $j=0 ; $j<count($aWords) ; $j++ )
                        {
                            if ( $aWords[$j] != "" )
                            {
                                $sColumnWhere .= $ReplaceField[$i]." ILIKE '%".pg_escape_string( $aWords[$j] )."%' OR ";
                            }
                        }
                        $sColumnWhere = substr_replace( $sColumnWhere, "", -3 );
                        $sColumnWhere .= ") AND ";
                    }
                }
                if ( $sColumnWhere != "" ) {
                    $sColumnWhere = substr_replace( $sColumnWhere, "", -5 );
                    $StringFilter .= " AND ".$sColumnWhere;
                }
            }
            else if (isset($Param['filter']) && !empty($Param['filter'])) {
                $Filter = json_decode($Param['filter']);
                
                foreach ($Filter as $Array) {
                    $Field = (isset($ReplaceField[$Array->field])) ? $ReplaceField[$Array->field] : $Array->field;
                    
                    if (isset($Array->field) && isset($Array->value)) {
                        if ($Array->type == 'numeric') {
                            if ($Array->comparison == 'eq') {
                                $StringFilter .= "AND " . $Field." = '".$Array->value."' ";
                                } else if ($Array->comparison == 'lt') {
                                $StringFilter .= "AND " . $Field." < '".$Array->value."' ";
                                } else if ($Array->comparison == 'gt') {
                                $StringFilter .= "AND " . $Field." > '".$Array->value."' ";
                                } else if ($Array->comparison == 'not') {
                                $StringFilter .= "AND " . $Field." != '".$Array->value."' ";
                                } else if ($Array->comparison == 'eq_can_empty' && !empty($Array->value)) {
								$StringFilter .= "AND " . $Field." = '".$Array->value."' ";
                                } else if ($Array->comparison == 'in') {
								$StringFilter .= "AND " . $Field." IN (".$Array->value.") ";
                            }
                            } else if ($Array->type == 'date') {
                            if ($Array->comparison == 'eq') {
                                $StringFilter .= "AND " . $Field." = '".ConvertDateToQuery($Array->value)."' ";
                                } else if ($Array->comparison == 'lt') {
                                $StringFilter .= "AND " . $Field." <= '".ConvertDateToQuery($Array->value)."' ";
                                } else if ($Array->comparison == 'gt') {
                                $StringFilter .= "AND " . $Field." >= '".ConvertDateToQuery($Array->value)."' ";
                            }
                            } else if ($Array->type == 'list') {
                            $Array->field = $Field;
                            $StringFilter .= GetStringFromList($Array);
                            } else if ($Array->type == 'custom') {
                            $StringFilter .= "AND " . $Array->field . ' ';
                            } else {
                            $StringFilter .= "AND " . $Field." ILIKE '".$Array->value."%' ";
                        }
                    }
                }
            }
			/*-- default filter ---*/
			if($string_default != '')
			{
				$StringFilter = " AND ( ".$string_default .")";
			}
            return $StringFilter;
        }
    }
    
    if (! function_exists('GetStringFromList')) {
        function GetStringFromList($Param) {
            $ArrayFieldYesNo = array('supplier_active', 'agent_active', 'car_active', 'customer_active', 'driver_active', 'guide_active');
            $ArrayEmptyOrExist = array('driver_photo');
            
            $StringResult = '';
            if (in_array($Param->field, $ArrayFieldYesNo)) {
                foreach ($Param->value as $Value) {
                    if ($Value == 'Yes') {
                        $StringResult .= (empty($StringResult)) ? "'1'" : ", '1'";
                        } else if ($Value == 'No') {
                        $StringResult .= (empty($StringResult)) ? "'0'" : ", '0'";
                    }
                }
                $StringResult = (empty($StringResult)) ? '' : "AND " . $Param->field . " IN (" . $StringResult . ") ";
                } else if (in_array($Param->field, $ArrayEmptyOrExist)) {
                foreach ($Param->value as $Value) {
                    if ($Value == 'Yes') {
                        $StringResult .= (empty($StringResult)) ? $Param->field . " != '' " : "OR " . $Param->field . " != '' ";
                        } else if ($Value == 'No') {
                        $StringResult .= (empty($StringResult)) ? $Param->field . "= '' " : "OR " . $Param->field . " = '' ";
                    }
                }
                $StringResult = (empty($StringResult)) ? '' : "AND (" . $StringResult . ") ";
                } else {
                echo 'Please create new filter spesification';
                exit;
            }
            
            return $StringResult;
        }
    }
   
		
	if (! function_exists('GenerateInsertQuery')) {
        function GenerateInsertQuery($ArrayField, $ArrayParam, $Table, $Param = array()) {
            $Param['AllowSymbol'] = (isset($Param['AllowSymbol'])) ? $Param['AllowSymbol'] : 0;
          
            $StringField = $StringValue = $Value = '';
            foreach ($ArrayField as $Key => $Column) 
			{
				if ($Key != 0 && isset($ArrayParam[$Column])) {
					$StringField .= (empty($StringField)) ? $Column : ", " . $Column;
					
					$Value = (isset($ArrayParam[$Column])) ? $ArrayParam[$Column] : "";
					$Value = pg_escape_string ($Value);	
					if ($Param['AllowSymbol'] == 0) {
						$Value = preg_replace('/[^\x20-\x7E|\x0A]/i', "", $Value);
					}
					
					if($Value=='null' || $Value=='')
					{
						$Value = "null";
					}
					$StringValue .= (empty($StringValue)) ? "'" . $Value . "'" : ", '" . $Value . "'";	
				}
            }
            $Query = "INSERT INTO $Table ($StringField) VALUES ($StringValue) RETURNING $ArrayField[0];";
            return $Query;
        }
    }	
    
    if (! function_exists('GenerateUpdateQuery')) {
        function GenerateUpdateQuery($ArrayField, $ArrayParam, $Table, $Param = array(), $spesific_field = null) {
			//echo $spesific_field;exit;
			//print_r($ArrayParam);
			//print_r($ArrayField);
			//exit;
			$Param['AllowSymbol'] = (isset($Param['AllowSymbol'])) ? $Param['AllowSymbol'] : 0;
			$StringQuery = "";
            foreach ($ArrayField as $Key => $Column) {
                if ($Key != 0 && isset($ArrayParam[$Column])) {
                    $Value = $ArrayParam[$Column];
                    if ($Param['AllowSymbol'] == 0) {
                        $Value = preg_replace("/[^\x20-\x7E|\x0A]/i", "", $Value);
                    }
                    
                    $StringQuery .= (empty($StringQuery)) ? '' : ', ';
					if($Value=='null' || $Value=='')
					{
						$StringQuery .= "$Column = null";
					}else{
						$StringQuery .= "$Column = '" . pg_escape_string($Value) . "'";
					}
					
                }
            }

			if(isset($spesific_field))
			{
				$Query = "UPDATE $Table SET $StringQuery WHERE " . $spesific_field . " = '" . $ArrayParam[$spesific_field] . "'";
				if(isset($ArrayParam[$ArrayField[0]]) && $ArrayParam[$ArrayField[0]]!=null){
					$Query .= " AND ". $ArrayField[0] . " = '" . $ArrayParam[$ArrayField[0]] . "'";
				}
			}else{
				$Query = "UPDATE $Table SET $StringQuery WHERE " . $ArrayField[0] . " = '" . $ArrayParam[$ArrayField[0]] . "'";
			}
            return $Query;
        }
    }
	
	if (! function_exists('GenerateUpdateQuerySpesificWhere')) {
        function GenerateUpdateQuerySpesificWhere($ArrayField, $ArrayParam, $Table, $Param = array(), $spesific_where) {
		//echo $spesific_field;exit;
		//print_r($ArrayParam);
		//print_r($ArrayField);exit;
			$Param['AllowSymbol'] = (isset($Param['AllowSymbol'])) ? $Param['AllowSymbol'] : 0;
			$StringQuery = "";
            foreach ($ArrayField as $Key => $Column) {
                if ($Key != 0 && isset($ArrayParam[$Column])) {
                    $Value = $ArrayParam[$Column];
                    if ($Param['AllowSymbol'] == 0) {
                        $Value = preg_replace("/[^\x20-\x7E|\x0A]/i", "", $Value);
                    }
                    
                    $StringQuery .= (empty($StringQuery)) ? '' : ', ';
                    $StringQuery .= "$Column = '" . pg_escape_string($Value) . "'";
					
                }
            }

			//if(isset($spesific_where))
			//{
				$Query = "UPDATE $Table SET $StringQuery WHERE ". $spesific_where;
			//}
			//print_r($Query);exit;
            return $Query;
        }
    }
	
    
    if (! function_exists('GetNextAutoIncrement')) {
        function GetNextAutoIncrement($Table) {
            $NextAutoIncrement = 1;
            
            $SelectQuery = "SHOW TABLE STATUS ILIKE '$Table'";
            $ResultQuery = mysql_query($SelectQuery) or die(mysql_error());
            if (false !== $Row = mysql_fetch_assoc($ResultQuery)) {
                $NextAutoIncrement = $Row['Auto_increment'];
            }
            
            return $NextAutoIncrement;
        }
    }
    
    if (! function_exists('GetStringMonth')) {
        function GetStringMonth($Param) {
            if (empty($Param['value'])) {
                return  '';
            }
            
            $Param['Year'] = (isset($Param['Year'])) ? $Param['Year'] : date("Y");
            
            $StringMonth = "AND MONTH(" . $Param['field'] . ") = '" . $Param['value'] . "' AND YEAR(" . $Param['field'] . ") = '" . $Param['Year'] . "'";
            return $StringMonth;
        }
    }
    
    if (! function_exists('GetStringBettween')) {
        function GetStringBettween($Param, $Field = array()) {
            $StringResult = '';
            
            if (isset($Param['StartDate']) && !empty($Param['StartDate']) && isset($Param['EndDate']) && !empty($Param['EndDate'])) {
                foreach ($Field as $Value) {
                    $StringResult .= (empty($StringResult)) ? '' : 'OR ';
                    $StringResult .= "$Value between '".$Param['StartDate']."' and '".$Param['EndDate']."' ";
                }
                
                $StringResult = "AND (" . $StringResult . ") ";
            }
            
            return $StringResult;
        }
    }
    
    if (! function_exists('GetStringSorting')) {
        // $Param = array('sort' => '[{"property":"tanggal","direction":"DESC"}]');
        function GetStringSorting($param, $Field = array(), $string_default = '',$default_sorting=array()) {
            $Result = '';
            //print_r($default_sorting);
            //print_r($Field);
            //print_r($param);
            if (isset($param['iSortCol_0'])) 
			{
                for ( $i=0 ; $i<intval( $param['iSortingCols'] ) ; $i++ ) {
                    if ( $param[ 'bSortable_'.intval($param['iSortCol_'.$i]) ] == "true" ) {
						$FieldName 	= $Field[ intval( $param['iSortCol_'.$i] )-1 ];
						$FieldName =(!empty($default_sorting[$FieldName]))?$default_sorting[$FieldName]:$FieldName;
                        $Result 	.= $FieldName." ".pg_escape_string( $param['sSortDir_'.$i] ) .", ";
                    }
                }
                
                $Result = substr_replace( $Result, "", -2 );
			} else if (isset($param['sort'])) 
			{
					$ArrayString = json_decode($param['sort']);
					foreach ($ArrayString as $Array) {
						$FieldName = (isset($Field[$Array->property])) ? $Field[$Array->property] : $Array->property;
						$FieldName =(!empty($default_sorting[$FieldName]))?$default_sorting[$FieldName]:$FieldName;
						$Query = $FieldName . ' ' . $Array->direction;
						
						$Result .= (empty($Result)) ? '' : ', ';
						$Result .= $Query;
					}
			}
			else 
			{
                $Result = $string_default;
			}
            //exit;
            return $Result;
        }
    }
    
    if (! function_exists('json_response')) {
        function json_response($json, $status=200) {
            if ($status != 200) header('HTTP/1.1 ' . $status);
            header('Content-type: application/json; charset=UTF-8');
            echo json_encode( $json );
            exit;
        }
    }
    
    if (! function_exists('GetArrayFromFileUpload')) {
        function GetArrayFromFileUpload($FileUploadPath) {
            $ArrayFile = file($FileUploadPath);
            
            $ArrayRaw = array();
            foreach ($ArrayFile as $StringTemp) {
                $StringCheck = preg_replace('/\,/i', '', trim($StringTemp));
                if (empty($StringCheck)) {
                    continue;
                }
                
                $ArrayTemp = explode(',', $StringTemp);
                foreach ($ArrayTemp as $Key => $Value) {
                    $Value = preg_replace('/^\"|\"$/i', '', trim($Value));
                    $ArrayTemp[$Key] = $Value;
                }
                
                $ArrayRaw[] = $ArrayTemp;
            }
            return $ArrayRaw;
        }
    }
    
    if (! function_exists('EncriptPassword')) {
        function EncriptPassword($Value) {
			$CI =& get_instance();
            return sha1(md5($CI->config->item('encryption_key') . ':' . $Value));
        }
    }
    
    if (! function_exists('GetResource')) {
        function GetResource($Source) {
            $Buffer = '';
            $Handle = fopen($Source, "rb+");
            if ($Handle) {
                while (!feof($Handle)) {
                    $Buffer .= fgets($Handle, 8192);
                }
                fclose($Handle);
            }
            return $Buffer;
        }
    }
    
    if (! function_exists('ImageResize')) {
        function ImageResize($ImageSource, $ImageOutput, $MinWidth, $MinHeight, $IsCrop = 0) {
            $info = @getimagesize($ImageSource);
            if (!empty($info)) {
                $Image = imagecreatefromstring(GetResource($ImageSource));
                $ImageWidth = imagesx($Image);
                $ImageHeight = imagesy($Image);
                
                // Enlarge for Small Image
                if ($ImageWidth < $MinWidth || $ImageHeight < $MinHeight) {
                    $FactorWidth = $FactorHeight = 0;
                    if ($ImageWidth < $MinWidth) {
                        $FactorWidth = $MinWidth / $ImageWidth;
                    }
                    if ($ImageHeight < $MinHeight) {
                        $FactorHeight = $MinHeight / $ImageHeight;
                    }
                    
                    $FactorMultiply = ($FactorWidth > $FactorHeight) ? $FactorWidth : $FactorHeight;
                    $ResultWidth = intval($FactorMultiply * $ImageWidth);
                    $ResultHeight = intval($FactorMultiply * $ImageHeight);
                    
                    // Resize for Large Image
                    } else {
                    $FactorWidth = $ImageWidth / $MinWidth;
                    $FactorHeight = $ImageHeight / $MinHeight;
                    
                    $FactorMultiply = ($FactorWidth < $FactorHeight) ? $FactorWidth : $FactorHeight;
                    $ResultWidth = intval($ImageWidth / $FactorMultiply);
                    $ResultHeight = intval($ImageHeight / $FactorMultiply);
                }
                
                $Result = imagecreatetruecolor($ResultWidth, $ResultHeight);
                imagecopyresampled($Result, $Image, 0, 0, 0, 0, $ResultWidth, $ResultHeight, $ImageWidth, $ImageHeight);
                imagejpeg($Result, $ImageOutput);
                imagedestroy($Image);
                imagedestroy($Result);
                
                if ($IsCrop == 1) {
                    ImageCrop($ImageOutput, $ImageOutput, $MinWidth, $MinHeight);
                }
            }
        }
    }
    
    if (! function_exists('ImageCrop')) {
        function ImageCrop($source, $output, $out_x, $out_y) {
            $info = @getimagesize($source);
            if (!empty($info)){
                $img = imagecreatefromstring(GetResource($source));
                $img_x = imagesx($img);
                $img_y = imagesy($img);
                $img_top = 0;
                $img_left = 0;
                
                if ($img_x <= $out_x && $img_y <= $out_y){
                    copy($source, $output);
                    return;
                }
                
                $diff = round($img_y/2) - round($out_y/2);
                $img_top = 0;
                $img_y = $out_y;
                
                
                
                $out = imagecreatetruecolor($out_x, $out_y);
                imagecopyresampled($out, $img, 0, 0, $img_left, $img_top, $out_x, $out_y, $img_x, $img_y);
                imagejpeg($out, $output);
                imagedestroy($img);
                imagedestroy($out);
            }
        }
    }
    
    if (! function_exists('GetLengthChar')) {
        function GetLengthChar($String, $LengthMax, $Follower = '') {
            if (strlen($String) > $LengthMax) {
                $String = substr($String, 0, $LengthMax);
                $Stringpos = strrpos($String, ' ');
                if (false !== $Stringpos) $String = substr($String, 0, $Stringpos);
                if (!empty($Follower)) {
                    $String .= $Follower;
                }
            }
            return $String;
        }
    }
    
    if (! function_exists('GetStringLimit')) {
        function GetStringLimit($Param) {
            $StringLimit = "25 OFFSET 0";
            
            if ( isset( $Param['iDisplayStart'] ) && $Param['iDisplayLength'] != '-1' ) {
                $StringLimit = pg_escape_string( $Param['iDisplayLength'] )." OFFSET ". pg_escape_string( $Param['iDisplayStart'] );
			} 
			else if (isset($Param['start']) || isset($Param['limit'])) 
			{
                $PageOffset = (isset($Param['start']) && !empty($Param['start'])) ? $Param['start'] : 0;
                $PageLimit = (isset($Param['limit']) && !empty($Param['limit'])) ? $Param['limit'] : 25;
                $StringLimit = "$PageLimit OFFSET $PageOffset";
            }
            return $StringLimit;
        }
    }
    
    if (! function_exists('dt_view')) {
        function dt_view($row, $column, $param) {
            $param['is_edit'] = (isset($param['is_edit'])) ? $param['is_edit'] : 0;
            $param['is_delete'] = (isset($param['is_delete'])) ? $param['is_delete'] : 0;
            $param['is_detail'] = (isset($param['is_detail'])) ? $param['is_detail'] : 0;
         
            if ($param['is_edit'] == 1) {
                $temp[0] = (isset($temp[0])) ? $temp[0] : '';
                $temp[0] .= '<img class="cursor edit" src="'.base_url('static/img/button_edit.png').'"> ';
                $temp[0] .= '<img class="cursor delete" src="'.base_url('static/img/button_delete.png').'"> ';
            }
            if (isset($param['is_edit_only']) && $param['is_edit_only'] == 1) {
                $temp[0] = (isset($temp[0])) ? $temp[0] : '';
                $temp[0] .= '<img class="cursor edit" src="'.base_url('static/img/button_edit.png').'"> ';
            }
            if ($param['is_delete'] == 1) {
                $temp[0] = (isset($temp[0])) ? $temp[0] : '';
                $temp[0] .= '<img class="cursor delete" src="'.base_url('static/img/button_delete.png').'"> ';
            }
            if ($param['is_detail'] == 1) {
                $temp[0] = (isset($temp[0])) ? $temp[0] : '';
                $temp[0] .= '<img class="cursor detail" src="'.base_url('static/img/details_open.png').'"> ';
            }
			
            if (!empty($param['is_custom'])) {
                $temp[0] = (isset($temp[0])) ? $temp[0] : '';
                $temp[0] .= $param['is_custom'];
            }
            if (!empty($temp[0])) {
                $temp[0] .= '<span class="hide">'.json_encode($row).'</span>';
            }
            
            foreach ($column as $key => $value) {
                $temp[] = @$row[$value];
            }
            $temp['extra'] = 'hrmll';
            
            return $temp;
        }
    }
    
    if (! function_exists('dt_view_set')) {
        function dt_view_set($row, $param,$no_hidden=null) {
            $param['is_edit'] = (isset($param['is_edit'])) ? $param['is_edit'] : 0;
            $param['is_delete'] = (isset($param['is_delete'])) ? $param['is_delete'] : 0;
            $param['is_detail'] = (isset($param['is_detail'])) ? $param['is_detail'] : 0;
            $param['is_select'] = (isset($param['is_select'])) ? $param['is_select'] : 0;
            $param['is_un_select'] = (isset($param['is_un_select'])) ? $param['is_un_select'] : 0;
            
			$temp_column = '';
            if ($param['is_edit'] == 1) {
                $temp_column .= '<img class="cursor edit" src="'.base_url('static/img/button_edit.png').'">';
                $temp_column .= '<img class="cursor delete" src="'.base_url('static/img/button_delete.png').'"> ';
            }
            if (isset($param['is_edit_only']) && $param['is_edit_only'] == 1) {
                $temp_column .= '<img class="cursor edit" src="'.base_url('static/img/button_edit.png').'">';
            }
            if ($param['is_delete'] == 1) {
                $temp_column .= '<img class="cursor delete" src="'.base_url('static/img/button_delete.png').'"> ';
            }
            if ($param['is_detail'] == 1) {
                $temp_column .= '<img class="cursor detail" src="'.base_url('static/img/details_open.png').'"> ';
            }
		   if ($param['is_select'] == 1) {
                $temp_column .= ' <i class="icon-ok cursor select"></i>';
            }
			if ($param['is_un_select'] == 1) {
                $temp_column .= ' <i class="icon-remove cursor unselect"></i>';
            }
            if (!empty($param['is_custom'])) {
                $temp_column .= $param['is_custom'];
            }
			
			//print_r(json_encode($row));exit;
			// populate required data
			//if($no_hidden != false)
			//{
				$record = array();
				if (!empty($temp_column)) {
					$temp_column .= '<span class="hide">'.json_encode($row, true).'</span>';
					$record[] = $temp_column;
				}
			//}
		
			
			foreach ($param['column'] as $key) {
				$record[] = (isset($row[$key])) ? $row[$key] : '';
            }
            
			// remove unused data
			// $param['clean_column'] = 1;
			if (!empty($param['clean_column'])) {
				foreach ($row as $key => $value) {
					if (!in_array($key, $param['column'])) {
						unset($row[$key]);
                    }
                }
            }
			//print_r($record);exit;
            return $record;
        }
    }
    
    if(! function_exists('GenerateInsertQueryByTypeData'))
    {
        function 
        GenerateInsertQueryByTypeData($ArrayField,$ArrayParam,$Table)
        {
            $tableData =  mysql_query("select COLUMN_NAME, data_type 
            from information_schema.columns where table_schema = '".DB_NAME."' and 
            table_name = '".$Table."'");
            $StringField = $StringValue = '';
            while($data = mysql_fetch_assoc($tableData))
            {
                foreach ($ArrayField as $Column)
                {
                    if($data['COLUMN_NAME'] == $Column)
                    {
                        $StringField .= (empty($StringField)) ? $Column 
                        : ', ' . $Column;
                        $Value = (isset($ArrayParam[$Column])) ? 
                        $ArrayParam[$Column] : '';
                        $Value = pg_escape_string($Value);
                        
                        if($data['data_type'] =='bit')
                        {
                            $StringValue .= (empty($StringValue)) ? 
                            $Value : ", " . $Value;
                        }else
                        {
                            $StringValue .= (empty($StringValue)) ? "'" 
                            . $Value . "'" : ", '" . $Value . "'";
                        }
                    }
                }
            }
            $Query = "INSERT INTO `$Table` ($StringField) VALUES 
            ($StringValue)";
            return $Query;
        }
    }
    
    if(! function_exists('GenerateUpdateQueryByTypeData'))
    {
        function 
        GenerateUpdateQueryByTypeData($ArrayField,$ArrayParam,$Table)
        {
            $tableData =  mysql_query("select COLUMN_NAME, data_type 
            from information_schema.columns where table_schema = '".DB_NAME."' and 
            table_name = '".$Table."'");
            $StringField = $StringValue = $StringQuery = '';
            while($data = mysql_fetch_assoc($tableData))
            {
                foreach ($ArrayField as $Key => $Column)
                {
                    if($data['COLUMN_NAME'] == $Column)
                    {
                        if ($Key != 0 && isset($ArrayParam[$Column])) {
                            $Value = $ArrayParam[$Column];
                            $StringQuery .= (empty($StringQuery)) ? '' 
                            : ', ';
                            if($data['data_type'] == 'bit')
                            {
                                $StringQuery .= "$Column = $Value ";
                            }else
                            {
                                $StringQuery .= "$Column = '" . 
                                pg_escape_string($Value) . "'";
                            }
                        }
                    }
                }
            }
            $Query = "UPDATE `$Table` SET $StringQuery WHERE " . 
            $ArrayField[0] . " = '" . $ArrayParam[$ArrayField[0]] . "'";
            
            return $Query;
        }
    }
	
	if (! function_exists('get_page_active')) {
		function get_page_active() {
			preg_match('/\/page_(\d+)/i', $_SERVER['REQUEST_URI'], $match);
			$page_no = (isset($match[1])) ? $match[1] : 1;
			$page_no = (!empty($_POST['page_no'])) ? $_POST['page_no'] : $page_no;
			
			
			return $page_no;
        }
    }
	
	if (! function_exists('sent_mail')) {
		function sent_mail($param) {
            //			$headers  = 'MIME-Version: 1.0' . "\r\n";
            //			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers  = 'From: noreply@web.com' . "\r\n";
			$param['message'] = preg_replace('#<br\s*/?>#', "\n", $param['message']);
			@mail($param['to'], $param['subject'], $param['message'], $headers);
        }
    }
	
	if (! function_exists('set_flash_message')) {
		function set_flash_message($value) {
			$_SESSION['flash_message'] = $value;
        }
    }
	
	if (! function_exists('get_flash_message')) {
		function get_flash_message() {
			$value = '';
			if (isset($_SESSION['flash_message'])) {
				$value = $_SESSION['flash_message'];
            }
			
			$_SESSION['flash_message'] = '';
			unset($_SESSION['flash_message']);
			
			return $value;
        }
    }
	
	if (! class_exists('CURL')) {
		class CURL {
			var $callback = false;
            
			function setCallback($func_name) {
				$this->callback = $func_name;
            }
            
			function doRequest($method, $url, $param = array()) {
				// $vars, $referer_address, 
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
				curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
				curl_setopt($ch, CURLOPT_TIMEOUT, 20);
				
                //				curl_setopt($ch, CURLOPT_REFERER, $referer_address);
				
				// post param
                //				if ($method == 'POST') {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $param['param']);
                //				}
				
				// http header
                //				if (isset($param['header']) && count($param['header']) > 0) {
                //					curl_setopt($ch, CURLOPT_HEADER, 1);
                
                curl_setopt($ch, CURLOPT_HEADER, $param['header']);
                //					curl_setopt($ch, CURLOPT_HTTPHEADER, $param['header']);
                //				}
				
                /*		
                    const CLIENT_ID = ****..*** ;
                    const SECRET = ***..***;
                    
                    $base64EncodedClientID = base64_encode(self::CLIENT_ID . ":" . self::SECRET);
                    // $headers = array("Authorization" => "Basic " . $base64EncodedClientId, "Accept" =>"**", "Content-type" => "multipart/form-data");
                    $params = array("grant_type"=>"client_credentials");
                    $url = "https://api.sandbox.paypal.com/v1/oauth2/token";
                    
                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch,CURLOPT_URL, $url);
                    curl_setopt($ch,CURLOPT_POST, true);
                    curl_setopt($ch,CURLOPT_HEADER, $headers);
                    curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
                    $response = curl_exec($ch);  
                /*	*/
				
				$data = curl_exec($ch);
				curl_close($ch);
				
				if ($data) {
					if ($this->callback) {
						$callback = $this->callback;
						$this->callback = false;
						return call_user_func($callback, $data);
                        } else {
						return $data;
                    }
                    } else {
					if (is_resource($ch))
                    return curl_error($ch);
					else
                    return false;
                }
            }
            
			function get($url, $referer_address = '', $param = array()) {
				return $this->doRequest('GET', $url, NULL, $referer_address, $param);
            }
            
			function post($url, $param = array()) {
				return $this->doRequest('POST', $url, $param);
            }
        }
    }
	
	if (! function_exists('save_tinymce')) {
		function save_tinymce($value) {
			$result = $value;
			$result = str_replace("\"", "'", $result);
			$result = htmlentities($result, ENT_QUOTES);
			
			return $result;
        }
    }
    
    // limit words
    
    if (! function_exists('limit_words')) {
        function limit_words($string, $word_limit)
        {
            $words = explode(" ",$string);
            return implode(" ",array_splice($words,0,$word_limit));
        }
    }
    
    // rupiah format
    if (! function_exists('rupiah')) {
        function rupiah($data)
        {
            $rupiah = "";
            $jml = strlen($data);
            
            while($jml > 3)
            {
                $rupiah = "." . substr($data,-3) . $rupiah;
                $l = strlen($data) - 3;
                $data = substr($data,0,$l);
                $jml = strlen($data);
            }
            $rupiah = "Rp " . $data . $rupiah . ",-";
            return $rupiah;
        }
    }
	
    // dollar format
    if (! function_exists('dollar')) {
        function dollar($value) {
			$result = '$ '.number_format($value, 2, '.', ',');
			return $result;
        }
    }
?>