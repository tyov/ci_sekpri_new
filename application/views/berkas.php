<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SEKPRI PDAM</title>
    <script type="text/javascript"> 
            var base_url = "<?php echo base_url(); ?>";  
        </script>
    <link rel="stylesheet" type="text/css" href="./assets/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="./assets/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="./assets/themes/color.css">
    <!--<link rel="stylesheet" type="text/css" href="./assets/demo.css">-->
    <!--<link rel="stylesheet" type="text/css" href="./assets/fonts/fonts.css">-->
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
    <!--<script type="text/javascript" src="./assets/jquery.easyui.patch.js"></script>-->
    <link rel="stylesheet" type="text/css" href="./assets/fonts/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="./assets/fonts/font-awesome/css/font-awesome.min.css">

    <style>
        *{
            font-size:12px;
        }
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-family: 'ubunturegular', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-weight: 400;
            padding:20px;
            font-size:12px;
            margin:0;
            background-color:#2980b9;
        }
        .logo{
            text-align:center;
            margin-bottom:10px;
        }
        .logo img.pdam{
            background-color:#fff;
            width:100px;
            border-radius:50%;
            padding:10px;
            margin-left:0px;
        }
        .logo img.app{
            background-color:#eee;
            width:60px;
            border-radius:50%;
            margin-left:-25px;
        }
        .titleBox{
            font-size:26px;
            text-align:center;
            padding:0 10px 10px 10px;
            color:#fff;
        }
        .supTitle{
            font-size:16px;
            color:#fff;
            z-index:10;
            padding:0 10px 0 10px;
        }
        .supTitle span{
            background-color:#f39c12;
            padding:5px;
            border-radius:6px;
            margin:1px;
        }
        .box{
            width:300px;
            margin:auto;
            border-radius:4px;
        }
        .inputText, .inputButton{
            width:100%;
            -webkit-box-sizing : border-box;‌​
            -moz-box-sizing : border-box;
            box-sizing : border-box;
            border:none;
            font-size:14px;
            transition:all ease-in-out 0.15s;
        }
        .inputText{
            padding:15px;
            background-color:#fff;
            border-bottom:#ddd solid 1px;
            text-align:center;
        }
        .inputButton{
            padding:10px;
            background-color:#2ecc71;
            font-size:16px;
            border-bottom:#27ae60 solid 3px;
            border-radius:4px;
            color:#fff;
        }
        .inputButton:hover{
            background-color:#27ae60;
            border-bottom:#1f9f55 solid 3px;
            color:#eee;
            text-shadow: 0px 0px 3px #fff;
        }
        .inputArea, .buttonArea{
            padding:10px;
        }
        .inputArea input:first-child {
            border-radius:4px 4px 0 0;
        }
        .inputArea input:last-child {
            border-bottom:none;
            border-radius:0 0 4px 4px;
            border-bottom:#ddd solid 3px;
        }
        .footer{
            text-align:center;
            color:#fff;
            padding-top:10px;
        }
        #infoArea{color:#fff; text-align:center;}
    </style>

    
</head>

<body class="easyui-layout">
    <div data-options="region:'north',border:false" style="height:50px;background:#a1caf4;padding:10px; background-image:url(<?php echo base_url('image/banner.png');?>); background-repeat:no-repeat; background-position:center left;"></div>
    <div data-options="region:'south',title:'South Title',split:true" style="height:100px;"></div>
    <div data-options="region:'east',title:'East',split:true" style="width:100px;"></div>
    <div data-options="region:'west',title:'West',split:true" style="width:100px;"><ul id="tt" class="easyui-tree">
    <li>
        <span>Folder</span>
        <ul>
            <li>
                <span>Sub Folder 1</span>
                <ul>
                    <li><span>File 11</span></li>
                    <li><span>File 12</span></li>
                    <li><span>File 13</span></li>
                </ul>
            </li>
            <li><span>File 2</span></li>
            <li><span>File 3</span></li>
        </ul>
    </li>
    <li><span>File21</span></li>
</ul></div>
<div data-options="region:'center'" style="padding:5px;background:#eee;">
        <table id="dg" title="Berkas" class="easyui-datagrid" style="width:auto;height:500px"
            url="<?php echo base_url();?>index.php/berkas/get_berkas"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="id_berkas" width="30">Kode Berkas</th>
                <th field="tgl_terima" width="50">Tanggal Terima</th>
                <th field="penerima_berkas_desc" width="50">Penerima Berkas</th>
                <th field="pemilik_berkas_desc" width="50">Pemilik Berkas</th>
                <th field="bagian_desc" width="50">Bagian</th>
                <th field="isi_berkas" width="50">Isi Berkas</th>
                <th field="penerima_berkas" width="50" hidden="true">Penerima Berkas</th>
                <th field="pemilik_berkas" width="50" hidden="true">Pemilik Berkas</th>
                <th field="bagian" width="50" hidden="true">Bagian</th>
            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="tambahBerkas()">Tambah</a>
    </div>
</div>
    <div id="dlg" class="easyui-dialog" style="width:400px"
            closed="true" buttons="#dlg-buttons">
        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
            <div style="margin-bottom:20px;font-size:14px;border-bottom:1px solid #ccc">Data</div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'kode_direktur',textField:'keterangan',url:'<?php echo base_url(); ?>index.php/bagian/get_direktur'" name="KE" class="easyui-combobox" required="true" label="Ke:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'kode_direktur',textField:'keterangan',url:'<?php echo base_url(); ?>index.php/bagian/get_direktur'" name="POSISI" class="easyui-combobox" required="true" label="Posisi:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input id="TGL_KIRIM" name="TGL_KIRIM"  label="Tgl Kirim:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'kode',textField:'nama_bagian',url:'<?php echo base_url(); ?>index.php/bagian/get_bagian'" class="easyui-combobox" name="bagian" required="true" label="Bagian:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="keterangan" class="easyui-textbox" label="Keterangan:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                 <input data-options="valueField:'nip',textField:'nama_lengkap',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" class="easyui-combobox" name="PENGIRIM" required="true" label="Pengirim:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'nip',textField:'nama_lengkap',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" class="easyui-combobox" name="pengambil" label="Pengambil:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <select class="easyui-combobox" name="status" required="true" label="Status:" style="width:100%" >
                    <option value="0">Belum Terkirim</option>
                    <option value="1">Sudah Terkirim</option>
                </select>
            </div>
        </form>
    </div>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="simpanBerkas()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>

    <div id="mm" class="easyui-menu" style="width:120px;">
    <div data-options="iconCls:'icon-edit'" plain="true" onclick="updateBerkas()">Edit</div>
    <div data-options="iconCls:'icon-remove'" plain="true" onclick="hapusBerkas()">Hapus</div>
    <div class="menu-sep"></div>
    <div>Exit</div>
    </div>
</body>
<script type="text/javascript">
            
    var url;
    function tambahBerkas(){

           $('#dlg').dialog('open').dialog('center').dialog('setTitle','Tambah Berkas');
           $('#fm').form('clear');
           url = '<?php echo base_url(); ?>index.php/berkas/tambah_berkas';
        }



        $('#dg').datagrid({
            rowStyler: function(index,row){
                if (row.status_desc=="Belum Terkirim"){
                    return 'background-color:#48A7C9;color:#fff;';
                }
            }
        });


        $("#dg").datagrid({  
            onRowContextMenu: function (e, rowIndex, rowData) { 
                e.preventDefault(); 
                $(this).datagrid("clearSelections"); 
                $(this).datagrid("selectRow", rowIndex);
                $('#mm').menu('show', {  
                    left: e.pageX,
                    top: e.pageY  
                });  
                e.preventDefault();
            }  
        });

        $('#TGL_KIRIM').datetimebox({
            required:true
        });

        function checkTime(i) {
            return (i < 10) ? "0" + i : i;
        }

        // $('#TGL_KIRIM').datetimebox('datebox')

    function updateBerkas(){
            var row = $('#dg').datagrid('getSelected');
            console.log(row.TGL_KIRIM);
            if (row){
                $('#dlg').dialog('open').dialog('center').dialog('setTitle','Update Berkas');
                $('#fm').form('load',row);
                url = '<?php echo base_url(); ?>index.php/berkas/update_berkas/'+row.NOMOR;
                var tgl_kirim = row.TGL_KIRIM;
                tgl_kirim.toString();
                 $('#TGL_KIRIM').datetimebox('setValue', tgl_kirim.toString());
            }
        }

    function hapusBerkas() {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Yakin hapus data ini?',function(r){
                if (r){
                    $.post('<?php echo base_url(); ?>index.php/berkas/hapus_berkas/'+row.NOMOR,{NOMOR:row.NOMOR},function(result){
                        //if (result.success){
                            $('#dg').datagrid('reload');    // reload the user data
                        //} else {
                        //     $.messager.show({    // show error message
                        //         title: 'Error',
                        //         msg: result.errorMsg
                        //     });
                        // }
                    },'json');
                }
            });
        }
    }

    function simpanBerkas(){
        //console.log("test");
        //console.log(url);

        $('#fm').form('submit',{
            url: url,
            onSubmit: function(){
            return $(this).form('validate');
            },
        success: function(result){
            // var result = eval('('+result+')');
            // if (result.errorMsg){
            //     $.messager.show({
            //         title: 'Error',
            //         msg: result.errorMsg
            //     });
            // } else {
            $('#dlg').dialog('close'); // close the dialog
            $('#dg').datagrid('reload'); // reload the user data
            //}
        }
        });
    }
</script> 
</html>