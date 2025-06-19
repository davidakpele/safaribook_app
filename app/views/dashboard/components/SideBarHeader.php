<div class="user-panel">
    <div class="pull-left image">
        <img src="<?=ASSETS?>images/admin.png" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
        <p><?=((null !==($_SESSION['name']))?$_SESSION['name']:'')?></p>
        <small style="font-size:9px"><?=((null !==($_SESSION['role_name']))?$_SESSION['role_name']:'')?></small>
    </div>
</div>