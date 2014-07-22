 <div class="col-sm-3 col-md-2 sidebar">
        <?php if (isset($_SESSION['employee_id'])) {?>
          <ul class="nav nav-sidebar">
            <li><a href="myaccount.php">我的账号</a></li>
            <?php if($_SESSION['employee_level'] == "admin"){?>
            <li><a href="employee.php">职工账号管理</a></li>
            <li><a href="import.php">工资表导入</a></li>
            <?php } ?>

            <li><a href="logout.php">退出登录</a></li>
          </ul>
        <?php } ?>
        </div>