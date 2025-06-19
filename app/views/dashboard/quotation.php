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
            <section class="content">
  <h2>Create New <span class="invoice_type">Invoice</span> </h2>
  <!-- <hr> -->

  <div id="response" class="alert alert-success" style="display:none;">
      <a href="#" class="close" data-dismiss="alert">×</a>
      <div class="message"></div>
  </div>

  <form method="post" id="create_invoice">
      <div class="row">
        <div class="col-xs-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="float-left">Customer Information</h4>
                    <div class="d-flex"><b>OR</b> &nbsp;<div class="float-right select-customer"><span class="item-select-link"> Select Existing Customer</span></div></div>
                    <div class="clear"></div>
                </div>
                <div class="panel-body form-group form-group-sm">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="name" style="color:black">Name:*</label>
                                <input type="text" class="form-control copy-input required customer_name" name="customer_name" id="customer_name" placeholder="Contact Name" tabindex="1">
                                <small class="help-block-error-msg" style="color:#dd4b39;"></small>
                            </div>
                            <div class="form-group">
                                <label for="customer_invoice_no" style="color:black">Invoice Number:*</label>
                                <input type="text" class="form-control margin-bottom copy-input required customer_invoice_no" name="customer_invoice_no" id="customer_address_1" placeholder="Invoice Number" tabindex="3">	
                                <small class="help-block-error-msg" style="color:#dd4b39;"></small>
                            </div>
                            <div class="form-group">
                                <label for="due_date" style="color:black">Invoice Date:*</label>
                                <div class="input-group date" id="invoice_due_date">
                                    <input type="text" class="form-control required due_date" name="invoice_due_date" placeholder="Due Date" data-date-format="DD/MM/YYYY">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <small class="help-block-error-msg" style="color:#dd4b39;"></small>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group no-margin-bottom">
                                <label for="" style="color:black">Shipping Via:*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control copy-input" name="customer_address_2" id="customer_address_2" placeholder="Shipping Via" tabindex="4">
                                </div>
                            </div>
                            <div class="form-group margin-bottom">
                                <label for="" style="color:black">Customer Reference:*</label>
                                <div class="form-group">
                                    <input type="text" class="form-control copy-input required" name="customer_county" id="customer_county" placeholder="Customer Reference" tabindex="6">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tel" style="color:black">Telephone:*</label>
                                <input type="tel" class="form-control copy-input required customer_telephone" id="customer_telephone" placeholder="+234" >
                                <small class="help-block-error-msg" style="color:#dd4b39;"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4> Invoice To</h4>
                </div>
                <div class="panel-body form-group form-group-sm">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="name" style="color:black">FullName:*</label>
                                <input type="text" class="form-control copy-input required client_name" id="client_name" placeholder="Client full name" >
                                <small class="help-block-error-msg" style="color:#dd4b39;"></small>
                            </div>
                            <div class="form-group">
                                <label for="city" style="color:black">City/Town:*</label>
                                <input type="text" class="form-control copy-input required client_city" id="client_city" placeholder="Client City / Town" >
                                <small class="help-block-error-msg" style="color:#dd4b39;"></small>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <button type="button" class="btn btn-info payment-details" style="float:right"><span class="glyphicon glyphicon-credit-card" aria-hidden="true" style="color:gray"></span>&nbsp; Select payment details</button>
        </div>
    </div>

    

    <div>
        <button id="add-section" type="button" class="btn btn-flat btn-sm btn btn-success add-row">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add New Section
        </button>
        
    </div>
      
      <!-- / end client details section -->
      <div class="input-section">
        
        <div id="sections-container">
            <!-- Sections will be added here dynamically -->
        </div>
        
        <div class="action-buttons">
            <button id="calculate" type="button" class="btn btn-flat btn-sm btn btn-default add-row"><span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>&nbsp; Generate Invoice</button>
            <button id="print-invoice" disabled type="button" class="btn btn-flat btn-sm btn btn-primary add-row"><span class="glyphicon glyphicon-print" aria-hidden="true"></span>&nbsp;Print Invoice</button>
        </div>
    </div>
    
    <div class="output-section" id="output-section" style="display: none;">
        <h2>Invoice Preview</h2>
        <div id="invoice-container"></div>
    </div>
    
  </form>

  <div class="modal fada" id="select-add-payment-details" tabindex="-1" role="dialog" aria-labelledby="select-add-payment-details" aria-hidden="true">  
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          
          <h4 class="modal-title text-center">Select Bank Details</h4>
          <button type="button" class="close close-payment-details" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
            <div class="form-group" id="bank-select-group">
                <select class="form-control" id="bank-select"></select>
                <small class="help-block-msg" style="color:#dd4b39;"></small>
            </div>	      
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-primary button-payment-details" id="selected">Select</button>
          <button type="button" data-dismiss="modal" class="btn close-payment-details">Cancel</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fada" id="select-customer-users" tabindex="-1" role="dialog" aria-labelledby="select-add-payment-details" aria-hidden="true">  
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">Select An Existing Customer</h4>
          <button type="button" class="close close-select-customer" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
            <table class="table js-basic-example dataTable table-striped table-bordered table-hover" id="user-list-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Telephone</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
            </tbody>
        </table>		      
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn close-select-customer">Cancel</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fada" id="select-stock-product" tabindex="-1" role="dialog" aria-labelledby="select-add-payment-details" aria-hidden="true">  
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">Stock Product Table</h4>
          <button type="button" class="close close-select-stock-product" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
            <table class="table js-basic-example dataTable table-striped table-bordered table-hover" id="product-list-table">
                <thead>
                    <tr>
                        <th>Item No</th>
                        <th>Title</th>
                        <th>Binding</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
            </tbody>
        </table>		      
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn close-select-stock-product">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  
</section>
</div>
    </div>
<?php include_once 'components/Footer.php';?>
<script src="<?=ASSETS?>js/main.js"></script>
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