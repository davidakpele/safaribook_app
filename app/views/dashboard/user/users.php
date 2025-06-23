<?php $this->view('dashboard/components/Header'); ?>

<body class="hold-transition skin-yellow sidebar-mini">
	<div class="wrapper">
		<header class="main-header">
        <?php $this->view('dashboard/components/Logo')?> 		
        <?php $this->view('dashboard/components/Nav');?> 			
		</header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <?php $this->view('dashboard/components/SideBarHeader');?> 		
                <?php $this->view('dashboard/components/SideBar');?>
            </section>
        </aside>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
					Users
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Stock </a></li>
					<li class="active">Master </li>
					<li class="active">Stock  Data</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class="content container-fluid">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?=((null !==($_SESSION['role_name']))?$_SESSION['role_name']:'')?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mt-2 mb-4">
                            <a href="<?=ROOT?>user/add"><button type="button" class="btn btn-sm bg-blue btn-flat"><i class="fa fa-plus"></i> Add New User</button></a>
                            <div class="pull-right insiderBox" id="iz" style="display:none">
                                <button id="delete__Btn" title="Delete" class="btn btn-sm btn-danger btn-flat" type="button"><i class="fa fa-trash"></i> Delete</button>
                                <button disabled="disabled" class="btn btn-sm" style="background-color: #000000; border-radius:25px"><span class="pull-left" id="deletebadge" style="color: #fff;">Selected</span></button>
                            </div>
                        </div>
                        
                        <form action="" method="post" id="idm">
                            <table  class="table js-basic-example dataTable table-striped table-bordered table-hover user_list_table" id="users-list-table">
                                <thead>
                                    <tr>
                                        <th>s/n</th>
                                        <th><input type="checkbox" id="chk_all" value=""/></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Created On</th>
                                        <th>Updated On</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </section>
        </div>
  <!-- Required JS -->
  <script src="<?=ASSETS?>admin/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/datatables.net-bs/js/jquery.dataTables.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <!-- Datatables Buttons -->
        <script src="<?=ASSETS?>admin/assets/bower_components/datatables.net-bs/plugins/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/datatables.net-bs/plugins/Buttons-1.5.6/js/buttons.bootstrap.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/datatables.net-bs/plugins/JSZip-2.5.0/jszip.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/datatables.net-bs/plugins/pdfmake-0.1.36/pdfmake.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/datatables.net-bs/plugins/pdfmake-0.1.36/vfs_fonts.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/datatables.net-bs/plugins/Buttons-1.5.6/js/buttons.html5.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/datatables.net-bs/plugins/Buttons-1.5.6/js/buttons.print.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/datatables.net-bs/plugins/Buttons-1.5.6/js/buttons.colVis.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/pace/pace.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/dist/js/adminlte.min.js"></script>
        <!-- Textarea editor -->
        <script src="<?=ASSETS?>admin/assets/bower_components/codemirror/lib/codemirror.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/codemirror/mode/xml.min.js"></script>
        <script src="<?=ASSETS?>admin/assets/bower_components/froala_editor/js/froala_editor.pkgd.min.js"></script>
        <!-- App JS -->
        <script src="<?=ASSETS?>admin/assets/dist/js/app/dashboard.js"></script>
        <script src="<?=ASSETS?>admin/assets/dist/js/jquery.mask.js" type="text/javascript"></script>
        <script src="<?=ASSETS?>admin/assets/dist/js/jquery.mask.min.js" type="text/javascript"></script>
        <script src="<?=ASSETS?>admin/assets/plugins/table/datatable.js" type="text/javascript"></script>
        <script>
            const base_url='<?=ROOT?>';
        </script>
        <script type="module"  src="<?=ASSETS?>js/users.js"></script>
    </body>
</html>