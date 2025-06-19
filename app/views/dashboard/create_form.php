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
                    <li class="active">Product</li>
                    <li class="active">Form</li>
                </ol>
            </section>
            <section class="content container-fluid">
                <div id="error" class="error error-ico" style="display:none"></div>
                <div id="messagediv" class="success success-ico" style="display:none"></div>
				<form action="javascript:void(0)" id="addstudent" method="post" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title"><?= (isset($data['edit_product']) ?"Edit Book Details":"Form Add New Book")?> </h3>
							<div class="box-tools pull-right">
								<a href="<?=ROOT?>dashboard/stock" class="btn btn-sm btn-flat btn-primary">
									<i class="fa fa-arrow-left"></i> Go Back
								</a>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<input type="hidden" class="form_state" value="<?= isset($data['edit_product']) ? 'edit' : 'create' ?>">
								<input type="hidden" id="product_id" value="<?= isset($data['edit_product']) ? $_GET['id'] : '' ?>">
								<div class="col-md-12">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<label for="title">Book Title:*</label>
										<input type="text" class="form-control product_title" id="product_title" name="product_title" placeholder="Book Title"value="<?= isset($data['edit_product']) ? htmlspecialchars($data['edit_product']->title) : '' ?>">
									</div>  

									<div class="col-md-6 col-sm-12 col-xs-12">
										<label for="price">Product Price*</label>
										<div class="input-group no-margin-bottom product_price_container">
											<span class="input-group-addon">₦</span>
											<input type="text" class="unit-price form-control item-input calculate product_price required" aria-describedby="sizing-addon1" placeholder="0.00" id="product_price" name="product_price" value="<?= isset($data['edit_product']) ? number_format((float) $data['edit_product']->sale_price, 2) : '' ?>"/>
										</div>
									</div>

									<div class="col-md-4 col-sm-12 col-xs-12">
										<label for="binding">Binding*</label>
										<select name="binding" id="binding" class="product_binding form-control select2">
											<option value="" <?= !isset($data['edit_product']) ? 'selected' : '' ?>>--Select--</option>
											<option value="HB" <?= (isset($data['edit_product']) && $data['edit_product']->binding === 'HB') ? 'selected' : '' ?>>HB</option>
											<option value="PB" <?= (isset($data['edit_product']) && $data['edit_product']->binding === 'PB') ? 'selected' : '' ?>>PB</option>
										</select>
									</div>
								</div>
								<div class="col-md-12" id="guidanceform"></div>
								<div class="col-md-12">
									<div class="pull-right"  style="margin-top:20px">
										<button type="reset" class="btn btn-flat btn-default btn-sm">
											<i class="fa fa-rotate-left"></i> Reset
										</button>
										<button type="submit" id="btn_add_product" class="btn btn-flat btn-sm btn btn-primary add-row" style="width:200px">
											<i class="fa fa-save"></i> <?= (isset($data['edit_product']) ?"Save Changes":"Save New")?>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
		</section>
</div>
    </div>
<?php include_once 'components/Footer.php';?>
<script type="module" src="<?=ASSETS?>js/add_product.js"></script>
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
        var csrfhash = '90fe7e42026da44cd6d796f704bf6cd6';
        var csrf = {};
        csrf[csrfname] = csrfhash;
        $.ajaxSetup({
            "data": csrf
        });
    }

    function reload_ajax() {
        table.ajax.reload(null, false);
    }

    $(function() {
        $('.tooltip').tooltip();	
        $('.tooltip-left').tooltip({ placement: 'left' });	
        $('.tooltip-right').tooltip({ placement: 'right' });	
        $('.tooltip-top').tooltip({ placement: 'top' });	
        $('.tooltip-bottom').tooltip({ placement: 'bottom' });
        $('.popover-left').popover({placement: 'left', trigger: 'hover'});
        $('.popover-right').popover({placement: 'right', trigger: 'hover'});
        $('.popover-top').popover({placement: 'top', trigger: 'hover'});
        $('.popover-bottom').popover({placement: 'bottom', trigger: 'hover'});
        $('.notification').click(function() {
            var $id = $(this).attr('id');
            switch($id) {
                case 'notification-sticky':
                    $.jGrowl("Stick this!", { sticky: true });
                break;
                case 'notification-header':
                    $.jGrowl("A message with a header", { header: 'Important' });
                break;
                default:
                    $.jGrowl("Hello world!");
                break;
            }
        });
    });
    </script>
    </body>

    </html>