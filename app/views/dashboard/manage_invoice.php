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
				    Invoice <small>Data Invoice</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Invoice </a></li>
					<li class="active">Master </li>
					<li class="active">Invoice  Data</li>
				</ol>
			</section>
            <div id="page-loader" style="display: flex; align-items: center; justify-content: center; height: 100vh; flex-direction: column; gap: 1rem;">
                <img src="<?=ASSETS?>icons/loader.gif" alt="Loading..." style="width: 30px; height: 30px;" />
                
                <div class="spinner-border" role="status" style="width: 2rem; height: 1rem;">
                    <span class="sr-only">Loading...</span>
                </div>

                <p style="text-align: center; margin: 0;">Loading Invoice...</p>
            </div>
			<!-- Main content -->
			<section class="content container-fluid main-edit-container">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Manage Invoice  Data</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mt-2 mb-4">
                            <a href="<?=ROOT?>dashboard/create_invoice"><button type="button" class="btn btn-sm bg-blue btn-flat"><i class="fa fa-plus"></i> Create new Invoice</button></a>
                            <div class="pull-right insiderBox" id="iz" style="display:none">
                                <button id="delete__Btn" title="Delete" class="btn btn-sm btn-danger btn-flat" type="button"><i class="fa fa-trash"></i> Delete</button>
                                <button disabled="disabled" class="btn btn-sm" style="background-color: #000000; border-radius:25px"><span class="pull-left" id="deletebadge" style="color: #fff;">Selected</span></button>
                            </div>
                        </div>
                        
                        <form action="" method="post" id="idm">
                            <table
                                class="table js-basic-example dataTable table-striped table-bordered table-hover"
                                id="invoice-list-table">
                                <thead>
                                    <tr>
                                        <th>Item No</th>
                                        <th><input type="checkbox" id="chk_all" value=""/></th>
                                        <th>Invoice No.</th>
                                        <th>Invoice To.</th>
                                        <th>Issued Date</th>
                                        <th>Invoice Type</th>
                                        <th>Officer</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                

                <div class="modal fada" id="shareInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="select-add-payment-details" aria-hidden="true">  
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="shareInvoiceForm" class="email-compose-form">
                                <div class="modal-header">
                                    <h5 class="modal-title">Share Invoice</h5>
                                    <button type="button" class="close close-shareInvoiceModal" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="email-compose-form">
                                        <!-- To Row -->
                                        <div class="email-row with-actions">
                                            <label for="email_to" class="share_invoice_label">To</label>
                                            <input id="email_to" name="email_to" class="form-control" placeholder="Enter recipient email(s)" />
                                            <div class="email-actions">
                                                <button type="button" id="toggleCc">Add Cc</button>
                                                <button type="button" id="toggleBcc">Add Bcc</button>
                                            </div>
                                        </div>

                                        <!-- Cc Row -->
                                        <div class="email-row ccContainer" id="ccContainer">
                                            <label for="email_cc" class="share_invoice_label">Cc</label>
                                            <input id="email_cc" name="email_cc" class="form-control" placeholder="Enter Cc email(s)" />
                                        </div>

                                        <!-- Bcc Row -->
                                        <div class="email-row bccContainer" id="bccContainer">
                                            <label for="email_bcc" class="share_invoice_label">Bcc</label>
                                            <input id="email_bcc" name="email_bcc" class="form-control" placeholder="Enter Bcc email(s)" />
                                        </div>

                                    </div>

                                    <!-- Subject -->
                                    <div class="form-group email-row">
                                        <label for="email_subject" class="share_invoice_label">Subject</label>
                                        <input type="text" id="email_subject" name="email_subject" class="form-control" placeholder="Enter subject">
                                    </div>

                                    <!-- Message -->
                                    <div class="form-group">
                                        <label for="email_message" class="share_invoice_label">Message</label>
                                        <textarea id="email_message" name="email_message" colspan="4" row="4" class="summernote form-control form-control-sm rounded-0"  placeholder="Enter your message here..." required="required"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn close-shareInvoiceModal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Send Invoice</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content hidden">
                <h2>Edit <span class="invoice_type">Invoice</span> </h2>
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
                                                    <input type="text" class="form-control customer_shipping_via" name="customer_shipping_via" id="customer_shipping_via" placeholder="Shipping Via" tabindex="4">
                                                </div>
                                            </div>
                                            <div class="form-group margin-bottom">
                                                <label for="" style="color:black">Customer Reference:*</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control customer_reference required" name="customer_reference" id="customer_reference" placeholder="Customer Reference" tabindex="6">
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
                                    <h4>(Invoice Issued) To</h4>
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
                                                <label for="address" style="color:black">Address <small>(Optional)</small>:*</label>
                                                <div id="address_lines_container">
                                                    <input type="text" class="form-control copy-input required address-line" id="address-line" placeholder="Client Address / Location" >
                                                </div>
                                                
                                                    <div class="row" id="address_row_container">
                                                    <!-- dynamic input lines go here -->
                                                    </div>
                                                
                                                <button type="button" class="btn btn-xs btn-success mt-1 add_new_address_line">Add Address Line</button>
                                                <small class="help-block-error-msg" style="color:#dd4b39;"></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="city" style="color:black">City/Town:*</label>
                                                <input type="text" class="form-control copy-input required client_city" id="client_city" placeholder="Client City / Town" >
                                                <small class="help-block-error-msg" style="color:#dd4b39;"></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="telephone" style="color:black">Telephone:*</label>
                                                <input type="tel" class="form-control copy-input required client_telephone" id="client_telephone" placeholder="+234" >
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
                            <button id="print-invoice" type="button" class="btn btn-flat btn-sm btn btn-primary add-delivery-cost"><i class="fa fa-usd" aria-hidden="true"></i></span>&nbsp;Add Cost of Delivery
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
<?php include_once 'components/Footer.php';?>
<script type="module" src="<?=ASSETS?>js/manage_invoice.js"></script>
<script type="text/javascript">
    $('.summernote').summernote({
        codeviewFilter: false,
        codeviewIframeFilter: true,
        spellCheck: true,
        height: 80,
        disableDragAndDrop: true,
        toolbar: [
          ['font', ['bold', 'fontsize', 'italic', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ],
        image: [
            ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
            ['float', ['floatLeft', 'floatRight', 'floatNone']],
            ['remove', ['removeMedia']]
          ],
          link: [
            ['link', ['linkDialogShow', 'unlink']]
          ],
          table: [
            ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
            ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
          ],
          air: [
            ['color', ['color']],
            ['font', ['bold', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']]
          ],
          styleTags: [
              'p',
                  { title: 'Blockquote', tag: 'blockquote', className: 'blockquote', value: 'blockquote' },
                  'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
            ],
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Merriweather'],
            fontNamesIgnoreCheck: ['Merriweather'],
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
            
      });

    </script>
</body>
</body>
</html>