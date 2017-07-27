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
    kanan
    </div>
    <div data-options="region:'center'" id="center-content" style="background:#eee;">
        <div id='content_tab' class="easyui-tabs isinya" border='false' fit="true" cache='false'>
        <div id='isi_content' title="Main Content" style='overflow:hidden'>
        </div>
        </div>
    </div>
    <div data-options="region:'west',title:'Menu'" style="width:200px;">
        <?php include('common/sidebar_menu.php') ?>
    </div>

   
</body>
<script type="text/javascript">
 
</script> 
</html>