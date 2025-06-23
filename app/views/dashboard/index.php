<?php include_once 'components/Header.php'; ?>

<body class="hold-transition skin-yellow sidebar-mini">
	<div class="wrapper">
		<header class="main-header">
        <?php include_once 'components/Logo.php';?> 		
        <?php include_once 'components/Nav.php';?> 			
		</header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <?php include_once 'components/SideBarHeader.php';?> 		
                <?php include_once 'components/SideBar.php';?>
            </section>
        </aside>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>Dashboard </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="active">Dashboard</li>
                    <li class="active">Application Data</li>
                </ol>
            </section>
        <section class="content container-fluid">		
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-navy">
                        <div class="inner active_user_box"></div>
                        <div class="icon">
                            <img src="<?=ASSETS?>images/admin.png" alt="" style="max-width:90px">
                        </div>
                        <a href="<?=ROOT?>dashboard/users" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box " style="background-color:#B22222; color:#fff">
                        <div class="inner">
                            <h3><?=(($data['records'])?$data['records']['invoices'][1] : '0');?></h3>
                            <p>Invoices</p>
                        </div>
                        <div class="icon">
                            <img src="<?=ASSETS?>images/invoice.png" alt="" style="max-width:90px">
                        </div>
                        <a href="<?=ROOT?>dashboard/manage_invoice" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i> </a>
                    </div>
                </div>
              
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?=(($data['records'])?$data['records']['books'][1] : '0');?></h3>
                            <p>Books</p>
                        </div>
                        <div class="icon">
                            <img src="<?=ASSETS?>images/cs.png" alt="" style="max-width:90px">
                        </div>
                        <a href="<?=ROOT?>dashboard/stock" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
               
                
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-blue" >
                        <div class="inner">
                            <h3><?=(($data['records'])?$data['records']['users'][1] : '0');?></h3>
                            <p>Co-Worker</p>
                        </div>
                        <div class="icon">
                            <img src="<?=ASSETS?>images/workers.png" alt="" style="max-width:90px">
                        </div>
                        <a href="<?=ROOT?>user/list" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                        </div>
                    </div>
                </div>


                <div class="box" style="max-width:1200px">
                    <div class="box-header with-border">
                        <h3 class="box-title">Advanced Analytics </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="chart">
                                    <div id="PieChart" class="col-md-6" style="height: 400px; max-width: 800px; margin: 0px auto;"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="chart">
                                    <div id="product_review" class="col-md-6" style="height: 400px; max-width: 800px; margin: 0px auto;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"></div>
			</section>
			<!-- /.content -->
			</div>
			<?php include_once 'components/Footer.php';?>
			</div>
            <script src="<?=ASSETS?>admin/assets/dist/js/app/config/home.js" type="module"></script>
			<script type="text/javascript">
				$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
					return {
						"iStart": oSettings._iDisplayStart,
						"iEnd": oSettings.fnDisplayEnd(),
						"iLength": oSettings._iDisplayLength,
						"iTotal": oSettings.fnRecordsTotal(),
						"iFilteredTotal": oSettings.fnRecordsDisplay(),
						"iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
						"iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
					};
				};

				function ajaxcsrf() {
					var csrfname = 'csrf_test_name';
					var csrfhash = '4546c4c40312eb1a952fb0d1002c7246';
					var csrf = {};
					csrf[csrfname] = csrfhash;
					$.ajaxSetup({
						"data": csrf
					});
				}

				function reload_ajax() {
					table.ajax.reload(null, false);
				}
			</script>

			</body>

			</html>