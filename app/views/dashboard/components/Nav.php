<nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!-- The user image in the navbar-->
                    <img src="<?=ASSETS?>images/admin.png" class="user-image" alt="User Image">
                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                    <span class="hidden-xs"><?=((null !==($_SESSION['name']))?$_SESSION['name']:'')?></span>
                </a>
                <ul class="dropdown-menu">
                    <!-- The user image in the menu -->
                    <li class="user-header">
                        <img src="<?=ASSETS?>images/admin.png" class="img-circle" alt="User Image">
                        <p>
                           <?=((null !==($_SESSION['role_name']))?$_SESSION['role_name']:'')?><small>Member Since Mar, 2010</small>
                        </p>
                    </li>
                    <!-- Menu Body -->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="<?=ROOT?>dashboard/edituser/1" class="btn btn-primary btn-flat">
                                Edit Profile                            </a>
                        </div>
                        <div class="pull-right">
                            <a href="#" id="logout" class="btn btn-danger btn-flat">Logout</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>