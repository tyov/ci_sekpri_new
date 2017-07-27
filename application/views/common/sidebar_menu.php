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
 $(document).ready(function() {
     $('#tt').tree({
         url: '<?php echo base_url('index.php/menu/getData');?>',
         onClick: function(node){
            console.log(node);
            loadCenterContent(node);
        }
    });

      function loadCenterContent(param){
         var url  = param.attributes.url;
         var view = param.attributes.view;

     //    var target = '#center-content';
     //    var param={url:url,view:view};
     //    $.ajax({
     //      method: "POST",
     //      url: "<?Php echo base_url();?>index.php/menu/getContent",
     //      data: param,
     //      beforeSend: function(){
     //         // Handle the beforeSend event
     //        },
     //       complete: function(response){
            //console.log(response.responseText);
             // Handle the complete event
             // $('#center-content').html('');
             // $('#center-content').html(response.responseText);
             // $.parser.parse();
             // $.parser.parse('#center-content');
            $('#content_tab').tabs("close", 0);
            $('#content_tab').tabs('add', {
                
                    title: view,
                    // iconCls:node.iconCls,   
                    cache:false,
                    href:base_url+'index.php/menu/getContentMenu/'+view
                    
                
            });
            
            
            $('#content_tab').panel('refresh',base_url+'index.php/menu/getContentMenu/'+view);
     //       }
     //    });
      }

});
</script> 