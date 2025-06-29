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

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
					Stock 					<small>Data Stock</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Stock </a></li>
					<li class="active">Master </li>
					<li class="active">Stock  Data</li>
				</ol>
			</section>
            <div id="page-loader" style="display: flex; align-items: center; justify-content: center; height: 100vh; flex-direction: column; gap: 1rem;">
                <img src="<?=ASSETS?>icons/loader.gif" alt="Loading..." style="width: 30px; height: 30px;" />
                
                <div class="spinner-border" role="status" style="width: 2rem; height: 1rem;">
                    <span class="sr-only">Loading...</span>
                </div>

                <p style="text-align: center; margin: 0;">Loading stock...</p>
            </div>
			<!-- Main content -->
			<section class="content container-fluid main-edit-container">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Master Stock  Data</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="box-body">
                        <div class="mt-2 mb-4">
                            <a href="<?=ROOT?>dashboard/create_product?action=create_new"><button type="button" class="btn btn-sm bg-blue btn-flat"><i class="fa fa-plus"></i> Add Book</button></a>
                            <div class="pull-right insiderBox" id="iz" style="display:none">
                                <button id="delete__Btn" title="Delete" class="btn btn-sm btn-danger btn-flat" type="button"><i class="fa fa-trash"></i> Delete</button>
                                <button disabled="disabled" class="btn btn-sm" style="background-color: #000000; border-radius:25px"><span class="pull-left" id="deletebadge" style="color: #fff;">Selected</span></button>
                            </div>
                        </div>
                        
                        <form action="" method="post" id="idm">
                            <table
                                class="table js-basic-example dataTable table-striped table-bordered table-hover"
                                id="stock-list-table">
                                <thead>
                                    <tr>
                                        <th>Item No</th>
                                        <th><input type="checkbox" id="chk_all" value=""/></th>
                                        <th>Title</th>
                                        <th>Binding</th>
                                        <th>Price</th>
                                        <th>Status</th>
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
<?php include_once 'components/Footer.php';?>
<script type="module" src="<?=ASSETS?>js/stock.js"></script>
</body>
</html>