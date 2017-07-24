<?php
mssql_connect("10.10.50.201","swpdam","swpdam");
mssql_select_db("ScadaNetDbArchive_1");


$query = mssql_query("select * from ScadaNetDb.dbo.Stations");
while($data = mssql_fetch_array($query)){
	echo $data["label"]."<br>";
}
?>