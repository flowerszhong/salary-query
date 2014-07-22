<?php
include 'dbc.php';

session_start($_COOKIE['PHPSESSID']);

$err = array();



foreach ($_GET as $key => $value) {
    $get[$key] = filter($value); //get variables are filtered.
}

if ($_POST['doLogin'] == 'Login' || $_POST['doLogin'] == '登录') {
    
    foreach ($_POST as $key => $value) {
        $data[$key] = filter($value); // post variables are filtered
    }
    
    
    $employee_id = $data['employee_id'];
    $pass        = $data['pwd'];
    
    
    $user_cond = "employee_id='$employee_id'";
    
    
    $sql_select = "SELECT `id`,`pwd`,`name`,`level` FROM employee WHERE $user_cond";
    
    $result = mysql_query($sql_select) or die(mysql_error());
    
    $num = mysql_num_rows($result);


    
    if ($num > 0) {
        $row = mysql_fetch_row($result);
        if ($row) {
            
            list($id,$pwd,$employee_name,$employee_level) = $row;
            if ($pwd == $pass) {
                if (empty($err)) {
                    // this sets session and logs user in  
                    session_start();
                    session_regenerate_id(true); //prevent against session fixation attacks.
                    
                    // this sets variables in the session 
                    $_SESSION['employee_id']         = $employee_id;
                    $_SESSION['employee_name']       = $employee_name;
                    $_SESSION['employee_level']      = $employee_level;
                    // $_SESSION['user_level'] = ADMIN_LEVEL;
                    $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
                    // var_dump($_SESSION);
                    
                    //update the timestamp and key for cookie
                    $stamp = time();
                    $ckey  = GenKey();
                    $sql_update = "update employee set `ctime`='$stamp', `ckey` = '$ckey' where id='$id'";
                    mysql_query($sql_update) or die(mysql_error());
                    
                    //set a cookie 
                    
                    if (isset($_POST['remember'])) {
                        setcookie("employee_id", $_SESSION['user_id'], time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
                        setcookie("employee_key", sha1($ckey), time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
                        setcookie("employee_name", $_SESSION['employee_name'], time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
                    }

                    
                    header("Location: myaccount.php");
                    die();
                }
            } else {
                //$msg = urlencode("Invalid Login. Please try again with correct user email and password. ");
                $err[] = "登录出错，请填写正确的工号及密码";
                //header("Location: login.php?msg=$msg");
            }
        } else {
            $err[] = "登录出错，该账号不存在";
        }
    } else {
        echo "no record";
    }
    
}


if (!empty($_SESSION['employee_id'])) {
    // echo $_SESSION['employee_id'];
    header("Location: myaccount.php");
    die();
}

?>


<?php
$page_title = "登录";
include 'includes/head.php';
include 'includes/errors.php';
?>



<div class="container login-box">
            <h1 class="title">登录并查询工资</h1>
            <div class="account-wall">
                <img class="profile-img" src="assets/image/avatar.png"
                    alt="">
                <form class="form-signin" action="login.php" method="post" name="logForm">
	                <input type="text" name="employee_id" class="form-control" placeholder="请输入工号" required autofocus>
	                <input type="password" name="pwd" class="form-control" placeholder="密码" required>
	                <button class="btn-submit btn btn-lg btn-primary btn-block"  name="doLogin" value="Login" type="submit">
	                    登录</button>
	                <label class="checkbox pull-left">
	                    <input type="checkbox" value="1" name="remember">
	                    记住我
	                </label>
	                <a href="forgot.php" class="pull-right need-help">忘记密码 </a><span class="clearfix"></span>
                </form>
            </div>
</div>


<?php
include 'includes/footer.php';
?>
