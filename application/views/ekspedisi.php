
<div data-options="region:'center'" style="background:#eee;">
        <table id="dg_ekspedisi"  class="easyui-datagrid" 
            url="<?php echo base_url();?>index.php/ekspedisi/get_ekspedisi"
            toolbar="#toolbar"
            rownumbers="true" pagination="true" border="false" striped="true" singleSelect="true" nowrap="false" pageSize="10" fitColumns="true" style="width:auto; height: 545px;" 
            >
        <thead>
            <tr>
                <th field="id_ekspedisi" width="100" halign="center" align="center">No Ekspedisi</th>
                <th field="id_berkas" width="100" halign="center" align="center">No Berkas</th>
                <th field="id_jenis_ekspedisi_desc" width="250" halign="center" align="center">Jenis Ekspedisi</th>
                <th field="tgl_ekspedisi" width="200" halign="center" align="center">Tanggal Ekspedisi</th>
                <th field="tujuan_desc" width="150" halign="center">Tujuan</th>
                <th field="petugas_ekspedisi_desc" width="150" halign="center" >Petugas Ekspedisi</th>
                <th field="keterangan" width="400" halign="center" >Keterangan</th>
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
                <input data-options="valueField:'id_berkas',textField:'id_berkas',url:'<?php echo base_url(); ?>index.php/berkas/get_berkas_desc'" class="easyui-combobox" name="id_berkas" required="id_berkas" label="No Berkas:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'id_jenis_ekspedisi',textField:'keterangan',url:'<?php echo base_url(); ?>index.php/master_ekspedisi/get_master_ekspedisi_desc'" name="id_jenis_ekspedisi" class="easyui-combobox" required="true" label="Jenis:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                 <input data-options="valueField:'nip',textField:'nama_lengkap',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" class="easyui-combobox" name="tujuan" required="true" label="Tujuan:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                 <input data-options="valueField:'nip',textField:'nama_lengkap',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan'" class="easyui-combobox" name="petugas_ekspedisi" required="true" label="Petugas:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input data-options="valueField:'keterangan',textField:'keterangan',url:'<?php echo base_url(); ?>index.php/karyawan/get_karyawan', multiline:true" class="easyui-textbox" name="keterangan" required="true" label="keterangan:" style="width:100%; height:100px" >
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