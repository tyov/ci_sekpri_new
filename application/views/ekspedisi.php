
<div data-options="region:'center'" style="background:#eee;">
        <table id="dg_ekspedisi"  class="easyui-datagrid" 
            url="<?php echo base_url();?>index.php/ekspedisi/get_ekspedisi"
            toolbar="#toolbar"
            rownumbers="true" pagination="true" border="false" striped="true" singleSelect="true" nowrap="false" pageSize="10" fitColumns="true" style="width:auto; height: 545px;" 
            >
        <thead>
            <tr>
                <th field="id_ekspedisi" width="50" halign="center" align="center">No Ekspedisi</th>
                <th field="id_jenis_ekspedisi" width="150" halign="center" align="center">Jenis Ekspedisi</th>
                <th field="id_berkas" width="150" halign="center">No Berkas</th>
                <th field="tgl_ekspedisi" width="150" halign="center">Tanggal Ekspedisi</th>
                <th field="tujuan" width="250" halign="center" hidden="true">Tujuan</th>
                <th field="keterangan" width="200" halign="center" >Keterangan</th>
                <th field="petugas_ekspedisi" width="400" halign="center" >Petugas Ekspedisi</th>
            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="tambahEkspedisi()">Tambah</a>
    </div>
</div>
    <div id="dlg_ekspedisi" class="easyui-dialog" style="width:400px"
            closed="true" buttons="#dlg_ekspedisi-buttons">
        <form id="fm_ekspedisi" method="post" novalidate style="margin:0;padding:20px 50px">
            <div style="margin-bottom:20px;font-size:14px;border-bottom:1px solid #ccc">Data</div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'id_jenis_ekspedisi',textField:'id_jenis_ekspedisi',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" name="id_jenis_ekspedisi" class="easyui-textbox" required="true" label="id_jenis_ekspedisi:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'id_berkas',textField:'id_berkas',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" class="easyui-textbox" name="id_berkas" required="id_berkas" label="id_berkas:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                 <select data-options="valueField:'tujuan',textField:'tujuan',url:'<?php echo base_url(); ?>index.php/bagian/get_bagian'" class="easyui-textbox" name="tujuan" required="true" label="tujuan:" style="width:100%"></select>
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'petugas_ekspedisi',textField:'petugas_ekspedisi',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" class="easyui-textbox" name="petugas_ekspedisi" required="true" label="petugas_ekspedisi:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'keterangan',textField:'keterangan',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" class="easyui-textbox" name="keterangan" required="true" label="keterangan:" style="width:100%">
            </div>

        </form>
    </div>
    <div id="dlg_ekspedisi-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="simpanEkspedisi()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_ekspedisi').dialog('close')" style="width:90px">Cancel</a>
    </div>

    <div id="mm_ekspedisi" class="easyui-menu" style="width:120px;">
    <div data-options="iconCls:'icon-edit'" plain="true" onclick="updateEkspedisi()">Edit</div>
    <div data-options="iconCls:'icon-remove'" plain="true" onclick="hapusEkspedisi()">Hapus</div>
    <div class="menu-sep"></div>
    <div>Exit</div>
    </div>

<script type="text/javascript">
        
    var url;
    function tambahEkspedisi(){

           $('#dlg_ekspedisi').dialog('open').dialog('center').dialog('setTitle','Tambah Ekspedisi');
           $('#fm_ekspedisi').form('clear');
           url = '<?php echo base_url(); ?>index.php/ekspedisi/tambah_ekspedisi';
        }

        $('#dg_ekspedisi').datagrid({
            rowStyler: function(index,row){
                if (row.status_desc=="Belum Terkirim"){
                    return 'background-color:#48A7C9;color:#fff;';
                }
            }
        });

        $("#dg_ekspedisi").datagrid({  
            onRowContextMenu: function (e, rowIndex, rowData) { 
                e.preventDefault(); 
                $(this).datagrid("clearSelections"); 
                $(this).datagrid("selectRow", rowIndex);
                $('#mm_ekspedisi').menu('show', {  
                    left: e.pageX,
                    top: e.pageY  
                });  
                e.preventDefault();
            }  
        });

    function updateEkspedisi(){
            var row = $('#dg_ekspedisi').datagrid('getSelected');
            if (row){
                $('#dlg_ekspedisi').dialog('open').dialog('center').dialog('setTitle','Update Ekspedisi');
                $('#fm_ekspedisi').form('load',row);
                url = '<?php echo base_url(); ?>index.php/Ekspedisi/update_ekspedisi/'+row.id_ekspedisi;
            }
        }

    function hapusEkspedisi() {
        var row = $('#dg_ekspedisi').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Yakin hapus data ini?',function(r){
                if (r){
                    $.post('<?php echo base_url(); ?>index.php/Ekspedisi/hapus_ekspedisi/'+row.id_ekspedisi,{id_ekspedisi:row.id_ekspedisi},function(result){
                        //alert(result.error);
                         $('#dg_ekspedisi').datagrid('reload');
                        //if (result.success){
                            //console.log("asd");
                            //$('#dg_ekspedisi').datagrid('reload');    // reload the user data
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

    function simpanEkspedisi(){
        //console.log("test");
        //console.log(url);

        $('#fm_ekspedisi').form('submit',{
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
            $('#dlg_ekspedisi').dialog('close'); // close the dialog
            $('#dg_ekspedisi').datagrid('reload'); // reload the user data
            //}
        }
        });
    }
</script>