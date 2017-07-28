
<div data-options="region:'center'" style="background:#eee;">
        <table id="dg_ruangan"  class="easyui-datagrid" 
            url="<?php echo base_url();?>index.php/master_ruangan/get_master_ruangan"
            toolbar="#toolbar"
            rownumbers="true" pagination="true" border="false" striped="true" singleSelect="true" nowrap="false" pageSize="10" style="width:auto; height: 545px;" 
            >
        <thead>
            <tr>
                <th field="id_ruangan" width="80" halign="center" align="center">No</th>
                <th field="keterangan" width="200" halign="center" align="center">Keterangan</th>

            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="tambah_master_ruangan()">Tambah</a>
    </div>
</div>
    <div id="dlg_ruangan" class="easyui-dialog" style="width:400px"
            closed="true" buttons="#dlg_ruangan-buttons">
        <form id="fm_ruangan" method="post" novalidate style="margin:0;padding:20px 50px">
            <div style="margin-bottom:20px;font-size:14px;border-bottom:1px solid #ccc">Data</div>
            <div style="margin-bottom:10px">
            <div style="margin-bottom:10px">
                <input data-options="valueField:'keterangan',textField:'keterangan'" class="easyui-textbox" name="keterangan" required="true" label="Keterangan:" style="width:100%">
            </div>

        </form>
    </div>
    <div id="dlg_ruangan-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="simpan_master_ruangan()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_ruangan').dialog('close')" style="width:90px">Cancel</a>
    </div>

    <div id="mm_ruangan" class="easyui-menu" style="width:120px;">
    <div data-options="iconCls:'icon-edit'" plain="true" onclick="update_master_ruangan()">Edit</div>
    <div data-options="iconCls:'icon-remove'" plain="true" onclick="hapus_master_ruangan()">Hapus</div>
    <div class="menu-sep"></div>
    <div>Exit</div>
    </div>

<script type="text/javascript">
        
    var url;
    function tambah_master_ruangan(){

           $('#dlg_ruangan').dialog('open').dialog('center').dialog('setTitle','Tambah Berkas');
           $('#fm_ruangan').form('clear');
           url = '<?php echo base_url(); ?>index.php/master_ruangan/tambah_master_ruangan';
        }



        $('#dg_ruangan').datagrid({
            rowStyler: function(index,row){
                if (row.status_desc=="Belum Terkirim"){
                    return 'background-color:#48A7C9;color:#fff;';
                }
            }
        });


        $("#dg_ruangan").datagrid({  
            onRowContextMenu: function (e, rowIndex, rowData) { 
                e.preventDefault(); 
                $(this).datagrid("clearSelections"); 
                $(this).datagrid("selectRow", rowIndex);
                $('#mm_ruangan').menu('show', {  
                    left: e.pageX,
                    top: e.pageY  
                });  
                e.preventDefault();
            }  
        });


        // $('#tgl_terima').datetimebox('datebox')

    function update_master_ruangan(){
            var row = $('#dg_ruangan').datagrid('getSelected');
            console.log(row.id_ruangan);
            if (row){
                $('#dlg_ruangan').dialog('open').dialog('center').dialog('setTitle','Update ruangan');
                $('#fm_ruangan').form('load',row);
                url = '<?php echo base_url(); ?>index.php/master_ruangan/update_master_ruangan/'+row.id_ruangan;

            }
        }

    function hapus_master_ruangan() {
        var row = $('#dg_ruangan').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Yakin hapus data ini?',function(r){
                if (r){
                    $.post('<?php echo base_url(); ?>index.php/master_ruangan/hapus_master_ruangan/'+row.id_ruangan,{id_ruangan:row.id_ruangan},function(result){
                        //if (result.success){
                            $('#dg_ruangan').datagrid('reload');    // reload the user data
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

    function simpan_master_ruangan(){
        //console.log("test");
        //console.log(url);

        $('#fm_ruangan').form('submit',{
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
            $('#dlg_ruangan').dialog('close'); // close the dialog
            $('#dg_ruangan').datagrid('reload'); // reload the user data
            //}
        }
        });
    }
</script>