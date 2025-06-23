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
					User<small>Edit User Data</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
					<li class="active">Edit</li>
					<li class="active">User Data</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class="content container-fluid">
                <div class="row">
                    <div class="col-sm-12 mb-4">
                    <a href="<?=ROOT?>user/list" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Cancel
                    </a>
                </div>

                <div class="<?=(($data['editdata']->role==5 || $data['editdata']->role==7)?'col-sm-4':'col-sm-4')?>">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">System Administrator</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body pb-0">
                            <div class="text-center">
                                <img class='user-image img-fluid' src='<?=ASSETS?>images/admin.png' alt='User profile picture'>
                            </div>
                            <p class="text-muted text-center"><?=$data['editdata']->role_name;?> </p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Email: </b> <a class="float-right" href="mailto:<?=$data['editdata']->email?>"><?=$data['editdata']->email;?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Mobile: </b> <a class="float-right"><?=$data['editdata']->telephone?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
    
                <div class="<?=(($data['editdata']->role==5 || $data['editdata']->role==7)?'col-sm-4':'col-sm-4')?>">
                    <div id="success" style="display:none; color:#000;"></div>
                    <form action="javascript:void(0)"  id="user_info" method="post" accept-charset="utf-8">
                    <input type="hidden" id="id" value="<?=$data['editdata']->customer_id;?>"/>
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Data User</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="container"><div id="error" class="error error-ico statusMsg" style="display:none; max-width:350px"></div></div>
                                <div class="box-body pb-0">
                              
                                    <div class="form-group">
                                        <label for="name">Fullname</label>
                                        <input type="text" id="username" class="form-control username" value="<?=$data['editdata']->name;?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="user_email" class="form-control user_email" value="<?=$data['editdata']->email;?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="tel">Telephone</label>
                                        <input type="tel" id="user_telephone" class="form-control user_telephone" value="<?=$data['editdata']->telephone;?>">
                                    </div>
                                    <div class="form-group">
                                        <select id="user_role" name="level" class="form-control select2 user_role" style="width: 100%!important">
                                            <option value="">Choose Level</option>
                                            <?php foreach ($data['rolelist'] as $value):?>
                                            <option <?=($value['id'] === $data['editdata']->role?'selected': '');?> data-role="<?=((isset($value['id']))?$value['name'] : '');?>" value="<?=((isset($value['id']))?$value['id'] : '');?>"><?=$value['name'];?></option>
                                            <?php endforeach;?>
                                        </select>
                                        <small class="help-block7"></small>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" id="btn-info" class="btn btn-success update_user_details">Save</button>
                                </div>
                            </div>
                        </form>    
                    </div>
                    <div class="<?=(($data['id'] ==1)?'col-sm-4':'col-sm-4')?>">
                        <form action="javascript:void(0)" id="<?=(($data['id'] ==1)?'isUpdataPassword':'user_level')?>" method="post" accept-charset="utf-8">
                            <input type="hidden" name="id" value="<?=$data['editdata']->customer_id;?>" />
                            <div class="box <?=(($data['id'] ==1)?'box-warning':'box-primary')?>">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?=(($data['id'] ==1)?'Change Password':'Level')?></h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body pb-0">
                                    <div class="form-group oji1">
                                        <label for="'old">Current Password</label>
                                        <input type="password" placeholder="Current Password" id="old" class="form-control">  
                                        <small class="help-block1"></small>
                                    </div>
                                </div>
                                    <div class="box-body pb-0">
                                        <div class="form-group oji2">
                                            <label for="new">New Password</label>
                                            <input type="password" placeholder="New Password" id="new" class="form-control">
                                            <small class="help-block2"></small>
                                        </div>
                                    </div>
                                    <div class="box-body pb-0">
                                        <div class="form-group oji3">
                                            <label for="new_confirm">Confirm Password</label>
                                            <input type="password" placeholder="Confirmation Password" id="new_confirm" class="form-control">
                                            <small class="help-block3"></small>
                                        </div>
                                    </div>
                                <?php if($data['id'] ==1):?>
                                    <div class="box-footer">
                                        <button type="reset" class="btn btn-flat btn-danger"><i class="fa fa-rotate-left"></i> Reset</button>
                                        <button type="submit" id="btn-pass" class="btn btn-flat btn-primary update_password_btn">Change Password</button>            
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="box-footer">
                                    <button type="submit" id="btn-level" class="btn btn-success">Save</button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
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