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
				<div class="errContainer">
					<div id="messagediv" class="success success-ico" style="display:none"></div>
					<p class="statusMsg error-ico" style="display:none"> </p>
				</div>
			</section>
			<!-- Main content -->
			<section class="content container-fluid">
				<form action="javascript:void(0)" id="isAddUser"  method="post" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Add Users </h3>
							<div class="box-tools pull-right">
								<a href="<?=ROOT?>user/list" class="btn btn-sm btn-flat btn-primary">
									<i class="fa fa-arrow-left"></i> Cancel
								</a>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12 ">
									<div class="form-group">
										<label for="firstname">First Name:<span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="firstname" id="firstname" value="" placeholder="First Name"/>
                                        <small class="error-block firstname-error"></small>
                                    </div>
									<div class="form-group">
										<label for="lastname">Last Name:<span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="lastname" id="lastname" value="" placeholder="Last Name:" />
                                        <small class="error-block lastname-error"></small>
                                    </div>
									<div class="form-group">
										<label for="email">Email Address:<span class="text-danger">*</span></label>
										<input type="email" class="form-control" name="Email" id="email" value="" placeholder="Email"/>
                                        <small class="error-block email-error"></small>
									</div>
									<div class="form-group">
										<label for="tel">Mobile:<span class="text-danger">*</span></label>
										<input type="tel" class="form-control" name="mobile" id="mobile" value=""  placeholder="+234" />
                                        <small class="error-block telephone-error"></small>
									</div>
									<div class="form-group">
										<label for="assign">Department:<span class="text-danger">*</span></label>
										<select id="role" class="form-control select2" name="role">
											<option value="" selected>--Empty--</option>
                                            <?php if(!empty($data['roles'])):?>
                                                <?php foreach ($data['roles'] as $role):?>
                                                <option value="<?=$role['id']?>"><?=$role['name']?></option>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                        <small class="error-block role-error"></small>
									</div>
									<div class="form-group pull-right">
										<button type="reset" class="btn btn-flat btn-danger">
											<i class="fa fa-rotate-left"></i> Reset
										</button>
										<button type="submit" class="btn btn-flat bg-green add-new-user">
											<i class="fa fa-pencil"></i> Save
										</button>
									</div>
								</div>
							</div>
						</div>	
					</div>
				</form>
			
		</section>
			<!-- /.content -->
		</div>

            <footer class="main-footer">
                <!-- To the right -->
                <div class="pull-right hidden-xs">
                    <p style="color:#b9b9b9;">&copy; 2025 -  All Right Reserved</p>
                </div>
                <!-- Default to the left -->
                <strong style="color:#b9b9b9;">Powered by <a href="<?=ROOT?>" style="text-decoration:none"><?=DEVELOPER_SIGNUATURE?></a></strong>    
            </footer>
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