<ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN MENU</li>
    <!-- Optionally, you can add icons to the links -->
        <li class="active">
            <a href="<?=ROOT?>dashboard">
                <i class="fa fa-dashboard"></i> 
                <span>Dashboard</span>
            </a>
        </li>
    
        <li class="">
            <a href="<?php echo ROOT.'dashboard/stock'?>">
                <i class="fa fa-book"></i>
                <span>Stock</span>
            </a>
        </li> 
        <li class="treeview ">
            <a href="#"><i class="fa fa-file-text"></i> <span>Invoices</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="">
                    <a href="<?=ROOT.'dashboard/create_invoice'?>">
                        <i class="fa fa fa-cog"></i>
                        Create New Invoice
                    </a>
                </li>
                <li class="">
                    <a href="<?=ROOT.'dashboard/manage_invoice'?>">
                        <i class="fa fa fa-cog"></i>
                        Manage Invoices
                    </a>
                </li>
            </ul>
        </li>

        <li class="treeview ">
            <a href="#"><i class="fa fa-archive"></i> <span>Quotation</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="">
                    <a href="<?=ROOT.'dashboard/create_quotation'?>">
                        <i class="fa fa fa-cog"></i>
                        Create New Quotation
                    </a>
                </li>
                <li class="">
                    <a href="<?=ROOT.'dashboard/manage_quotation'?>">
                        <i class="fa fa fa-cog"></i>
                        Manage Quotation
                    </a>
                </li>
            </ul>
        </li>
            <li class="header">SETTINGS</li>
                <li class="">
                    <a href="<?=ROOT?>users" rel="noopener noreferrer">
                        <i class="fa fa-users"></i> <span>User Management</span>
                    </a>
                </li>
                <li class="">
                    <a href="<?=ROOT?>settings?action=role" rel="noopener noreferrer">
                        <i class="fa fa-cogs"></i> <span>Settings</span>
                    </a>
                </li>
            </li>
        </li>
    </ul>
