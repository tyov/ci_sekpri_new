
<div data-options="region:'center'" style="background:#eee;">
        <table id="dg_agenda"  class="easyui-datagrid" 
            url="<?php echo base_url();?>index.php/agenda/get_agenda"
            toolbar="#toolbar"
            rownumbers="true" pagination="true" border="false" striped="true" singleSelect="true" nowrap="false" pageSize="10" fitColumns="true" style="width:auto; height: 545px;" 
            >
        <thead>
            <tr>
                <th field="id_agenda" width="50" halign="center" align="center">No</th>
                <th field="id_ruangan_desc" width="90" halign="center" align="center">Ruangan</th>
                <th field="id_pemesan_desc" width="150" halign="center" align="center">Pemesan</th>
                <th field="tgl_pemesanan" width="150" halign="center" align="center">Tanggal Pemesanan</th>
                <th field="keterangan" width="300" halign="center">Keterangan</th>
                <th field="tgl_mulai" width="150" halign="center" align="center">Tanggal Mulai</th>
                <th field="tgl_selesai" width="150" halign="center" align="center">Tanggal Selesai</th>
            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="tambahAgenda()">Tambah</a>
    </div>
</div>
    <div id="dlg_agenda" class="easyui-dialog" style="width:400px"
            closed="true" buttons="#dlg_agenda-buttons">
        <form id="fm_agenda" method="post" novalidate style="margin:0;padding:20px 50px">
            <div style="margin-bottom:20px;font-size:14px;border-bottom:1px solid #ccc">Data</div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'nip',textField:'nama_lengkap',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" name="id_pemesan" class="easyui-combobox" required="true" label="Pemesan:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'id_ruangan',textField:'keterangan',url:'<?php echo base_url(); ?>index.php/master_ruangan/get_master_ruangan_desc'" class="easyui-combobox" name="id_ruangan" required="true" label="Ruangan:" style="width:100%">
            </div>

            <div style="margin-bottom:10px">
                <input data-options="valueField:'tgl_mulai',textField:'tgl_mulai'" class="easyui-datetimebox" name="tgl_mulai" required="true" label="Tgl Mulai:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'tgl_selesai',textField:'tgl_selesai'" class="easyui-datetimebox" name="tgl_selesai" required="true" label="Tgl Selesai:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="Keterangan" class="easyui-textbox" label="Keterangan:" style="width:100%; height:100px" data-options="multiline:true">
            </div>
        </form>
    </div>
    <div id="dlg_agenda-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="simpanAgenda()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_agenda').dialog('close')" style="width:90px">Cancel</a>
    </div>

    <div id="mm_agenda" class="easyui-menu" style="width:120px;">
    <div data-options="iconCls:'icon-edit'" plain="true" onclick="updateAgenda()">Edit</div>
    <div data-options="iconCls:'icon-remove'" plain="true" onclick="hapusAgenda()">Hapus</div>
    <div class="menu-sep"></div>
    <div>Exit</div>
    </div>

<script type="text/javascript">
        
    var url;
    function tambahAgenda(){

           $('#dlg_agenda').dialog('open').dialog('center').dialog('setTitle','Tambah Agenda');
           $('#fm_agenda').form('clear');
           url = '<?php echo base_url(); ?>index.php/agenda/tambah_agenda';
        }



        $('#dg_agenda').datagrid({
            rowStyler: function(index,row){
                if (row.status_desc=="Belum Terkirim"){
                    return 'background-color:#48A7C9;color:#fff;';
                }
            }
        });


        $("#dg_agenda").datagrid({  
            onRowContextMenu: function (e, rowIndex, rowData) { 
                e.preventDefault(); 
                $(this).datagrid("clearSelections"); 
                $(this).datagrid("selectRow", rowIndex);
                $('#mm_agenda').menu('show', {  
                    left: e.pageX,
                    top: e.pageY  
                });  
                e.preventDefault();
            }  
        });


        function checkTime(i) {
            return (i < 10) ? "0" + i : i;
        }

        // $('#tgl_terima').datetimebox('datebox')

    function updateAgenda(){
            var row = $('#dg_agenda').datagrid('getSelected');
            console.log(row.tgl_pemesanan);
            if (row){
                $('#dlg_agenda').dialog('open').dialog('center').dialog('setTitle','Update Agenda');
                $('#fm_agenda').form('load',row);
                url = '<?php echo base_url(); ?>index.php/agenda/update_agenda/'+row.id_agenda;
            }
        }

    function hapusAgenda() {
        var row = $('#dg_agenda').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Yakin hapus data ini?',function(r){
                if (r){
                    $.post('<?php echo base_url(); ?>index.php/agenda/hapus_agenda/'+row.id_agenda,{id_agenda:row.id_agenda},function(result){
                        //if (result.success){
                            $('#dg_agenda').datagrid('reload');    // reload the user data
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

    function simpanAgenda(){
        console.log("test");
        //console.log(url);

        $('#fm_agenda').form('submit',{
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
            $('#dlg_agenda').dialog('close'); // close the dialog
            $('#dg_agenda').datagrid('reload'); // reload the user data
            //}
        }
        });
    }
</script>