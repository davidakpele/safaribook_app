<?php include_once 'components/Header.php'; ?>
    <style>
       /* checkbox checked */
       	.dropdown-menu {position: absolute;top: 100%;left: 0;z-index: 1000;display: none;float: left;min-width: 10rem;padding: 0.5rem 0;margin: 0.125rem 0 0;font-size: 1.0rem;color: #212529;text-align: left;list-style: none;background-color: #fff;background-clip: padding-box;border: 1px solid rgba(0, 0, 0, 0.15);border-radius: 0.25rem;box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 18%);}
		.dropdown-item {display: block;width: 100%;padding: 0.25rem 1rem;clear: both;font-weight: 400;color: #212529;text-align: inherit;white-space: nowrap;background-color: transparent;border: 0;}
		.dropdown-divider {height: 0;margin: 0.5rem 0;overflow: hidden;border-top: 1px solid #e9ecef;}
        input[type="checkbox"]:checked:before {content: '';display: block;width: 4px;height: 8px;border: solid #fff;border-width: 0 2px 2px 0;-webkit-transform: rotate(45deg);transform: rotate(45deg);margin-left: 4px;margin-top: 1px;}
        #idm{overflow: scroll;}
    </style>
</head>
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
			<!-- Main content -->
			<section class="content container-fluid">
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
                                <button id="delete__Btn" title="Delete This Professor" class="btn btn-sm btn-danger btn-flat" type="button"><i class="fa fa-trash"></i> Delete</button>
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
                                        <th>Price</th>
                                        <th>Binding</th>
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