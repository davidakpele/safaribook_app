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
				<h1>System Settings</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard </a></li>
					<li class="active">System Settings</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class="content container-fluid">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Reconfigure This System Accordingly</h3>
                        <div class="box-tools pull-right">
                            <a href="<?=ROOT?>dashboard/" class="btn btn-sm btn-flat btn-primary">
                                <i class="fa fa-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </div>
                    <form method="post" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <div class="form-group">
                                        <label for="company-name">Company Name:<span class="text-danger">*</span></label>
                                        <textarea colspan="4" row="4" class="summernote form-control form-control-sm rounded-0 company-name" name="company-name" placeholder="Write your company name here." required="required"></textarea>
                                        <small class="error-block company-name-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="company-tagline">Company Tagline:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company-tagline" id="company-tagline" value="" placeholder="Company Tagline:" />
                                        <small class="error-block company-tagline-error"></small>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="company-main-logo">Company Logo:<span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="company-main-logo" id="company-main-logo" value=""/>
                                            <small class="error-block company-main-logo-error"></small>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="company-icon-logo">Company Icon Logo:<span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="company-icon-logo" id="company-icon-logo" value=""/>
                                            <small class="error-block company-icon-logo-error"></small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="company-rc-number">Company RC Number:<span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="company-rc-number" id="company-rc-number" value=""  placeholder="Company RC Number" />
                                        <small class="error-block company-rc-number-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="company-email">Company Email:<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="company-email" id="company-email" value=""  placeholder="@gmail.com" />
                                        <small class="error-block company-email-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="company-address">Company Address:<span class="text-danger">*</span></label>
                                        <textarea colspan="4" row="4" class="summernote form-control form-control-sm rounded-0 company-address" name="company-address" placeholder="Write your company address here." required="required"></textarea>
                                        <small class="error-block company-address-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="company-telephone">Company Telephone:<span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="company-telephone" id="company-telephone" value=""  placeholder="+234" />
                                        <small class="error-block company-telephone-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="company-url">Company Website:<span class="text-danger">*</span></label>
                                        <input type="url" class="form-control" name="company-url" id="company-url" value=""  placeholder="www.google.com"/>
                                        <small class="error-block company-url-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="company-country">Company Country:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company-country" id="company-country" value=""  placeholder="Nigeria" />
                                        <small class="error-block company-country-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="company-city">Company City:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company-city" id="company-city" value=""  placeholder="Ibadan" />
                                        <small class="error-block company-city-error"></small>
                                    </div>
                                    <div class="form-group pull-right">
                                        <button type="reset" class="btn btn-flat btn-default">
                                            <i class="fa fa-rotate-left"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-flat bg-blue save-configuration-changes" style="width:190px">
                                            <i class="fa fa-pencil"></i> Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
				
			
		</section>
			<!-- /.content -->
		</div>
<?php include_once 'components/Footer.php';?>
<script type="module" src="<?=ASSETS?>admin/assets/dist/js/app/config/config.js"></script>
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
</html>