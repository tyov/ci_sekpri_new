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
    <link rel="stylesheet" type="text/css" href="./assets/custom.css">
    

    
</head>

<body class="easyui-layout">
     <div data-options="region:'north',border:false" style="height:50px;background:#a1caf4;padding:10px; background-image:url(<?php echo base_url('image/banner.png');?>); background-repeat:no-repeat; background-position:center left;"></div>
    <div data-options="region:'east',title:'Filter',collapsed:true" style="width:200px;">
    kiri
    </div>
    <div data-options="region:'west',title:'Menu'" style="width:200px;">
        <?php include('common/sidebar_menu.php') ?>
    </div>
<div data-options="region:'center'" style="background:#eee;">
        <table id="dg" title="Berkas" class="easyui-datagrid" 
            url="<?php echo base_url();?>index.php/berkas/get_berkas"
            toolbar="#toolbar"
            rownumbers="true" pagination="true" border="false" striped="true" fit="true" singleSelect="true" collapsible="false" nowrap="false" pageSize="10" style="width:auto; height: auto;"
            >
        <thead>
            <tr>
                <th field="id_berkas" width="50" halign="center" align="center">No</th>
                <th field="tgl_terima" width="150" halign="center" align="center">Tanggal Terima</th>
                <th field="penerima_berkas_desc" width="150" halign="center">Penerima Berkas</th>
                <th field="pemilik_berkas_desc" width="150" halign="center">Pemilik Berkas</th>
                <th field="keterangan" width="250" halign="center" hidden="true">Keterangan</th>
                <th field="bagian_desc" width="200" halign="center" >Bagian</th>
                <th field="isi_berkas" width="400" halign="center" >Isi Berkas</th>
                <th field="penerima_berkas" width="50"  hidden="true">Penerima Berkas</th>
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
                <input data-options="valueField:'nip',textField:'nama_lengkap',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" name="penerima_berkas" class="easyui-combobox" required="true" label="Penerima:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'nip',textField:'nama_lengkap',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" class="easyui-combobox" name="pemilik_berkas" required="true" label="Pemilik:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                 <select data-options="valueField:'kode',textField:'nama_bagian',url:'<?php echo base_url(); ?>index.php/bagian/get_bagian'" class="easyui-combobox" name="kode_bagian" required="true" label="Bagian:" style="width:100%"></select>
            </div>
            <div style="margin-bottom:10px">
                <input name="isi_berkas" class="easyui-textbox" label="Isi Berkas:" style="width:100%; height:100px" data-options="multiline:true">
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

        $('#tgl_terima').datetimebox({
            required:true
        });

        function checkTime(i) {
            return (i < 10) ? "0" + i : i;
        }

        // $('#tgl_terima').datetimebox('datebox')

    function updateBerkas(){
            var row = $('#dg').datagrid('getSelected');
            console.log(row.tgl_terima);
            if (row){
                $('#dlg').dialog('open').dialog('center').dialog('setTitle','Update Berkas');
                $('#fm').form('load',row);
                url = '<?php echo base_url(); ?>index.php/berkas/update_berkas/'+row.id_berkas;
                var tgl_terima = row.tgl_terima;
                tgl_terima.toString();
                 $('#tgl_terima').datetimebox('setValue', tgl_terima.toString());
            }
        }

    function hapusBerkas() {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Yakin hapus data ini?',function(r){
                if (r){
                    $.post('<?php echo base_url(); ?>index.php/berkas/hapus_berkas/'+row.id_berkas,{id_berkas:row.id_berkas},function(result){
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