<ul id="tt" class="easyui-tree">
  <!--  <li>
        <span>Berkas</span>
        <ul>
            <li><span>Berkas</span></li>
            <li><span>Ekspedisi</span></li>
            <li><span>Master Ekspedisi</span></li>
        </ul>
    </li>
    <li>
        <span>Agenda</span>
        <ul>
            <li><span>Agenda</span></li>
            <li><span>Ekspedisi</span></li>
            <li><span>Master Agenda</span></li>
        </ul>
    </li>
    <li>
        <span>Laporan</span>
        <ul>
            <li><span>Laporan</span></li>
            <li><span>Ekspedisi</span></li>
            <li><span>Master Laporan</span></li>
        </ul>
    </li>-->
</ul>

<script type="text/javascript">
 $('#tt').tree({
    url: '<?php echo base_url('index.php/menu/getData');?>'
});
</script> 