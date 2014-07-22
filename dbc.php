<?php

/************* MYSQL DATABASE SETTINGS *****************
1. Specify Database name in $dbname
2. MySQL host (localhost or remotehost)
3. MySQL user name with ALL previleges assigned.
4. MySQL password

Note: If you use cpanel, the name will be like account_database
*************************************************************/
// for local
define("DB_HOST", "localhost"); // set database host
define("DB_USER", "root"); // set database user
define("DB_PASS", "root"); // set database password
define("DB_NAME", "salary_db"); // set database name

//for live


declare (encoding = 'UTF-8');

error_reporting(E_ALL);


$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");
mysql_query("SET NAMES 'UTF8'");
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database");


/* Registration Type (Automatic or Manual) 
1 -> Automatic Registration (Users will receive activation code and they will be automatically approved after clicking activation link)
0 -> Manual Approval (Users will not receive activation code and you will need to approve every user manually)
*/
$user_registration = 1; // set 0 or 1

define("COOKIE_TIME_OUT", 10); //specify cookie timeout in days (default is 10 days)
define('SALT_LENGTH', 9); // salt for password

//define ("ADMIN_NAME", "admin"); // sp

/* Specify user levels */
define("ADMIN_LEVEL", 5);
define("HEADER_LEVEL", 2);
define("USER_LEVEL", 1);
define("GUEST_LEVEL", 0);



/*************** reCAPTCHA KEYS****************/
$publickey  = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
$privatekey = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";


/**** PAGE PROTECT CODE  ********************************
This code protects pages to only logged in users. If users have not logged in then it will redirect to login page.
If you want to add a new page and want to login protect, COPY this from this to END marker.
Remember this code must be placed on very top of any html or php page.
********************************************************/

function page_protect()
{
    session_start();
    
    global $db;
    
    /* Secure against Session Hijacking by checking user agent */
    if (isset($_SESSION['HTTP_USER_AGENT'])) {
        if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {
            logout();
            exit;
        }
    }
    
    // before we allow sessions, we need to check authentication key - ckey and ctime stored in database
    
    /* If session not set, check for cookies set by Remember me */
    if (!isset($_SESSION['employee_id']) && !isset($_SESSION['employee_name'])) {
        if (isset($_COOKIE['employee_id']) && isset($_COOKIE['employee_key'])) {
            /* we double check cookie expiry time against stored in database */
            
            $cookie_user_id = filter($_COOKIE['employee_id']);
            $rs_ctime = mysql_query("select 'ckey','ctime' from 'employee' where 'employee_id' ='$cookie_user_id'") or die(mysql_error());
            
            list($ckey, $ctime) = mysql_fetch_row($rs_ctime);
            
            // var_dump($ckey);
            
            // coookie expiry
            if ((time() - $ctime) > 60 * 60 * 24 * COOKIE_TIME_OUT) {
                logout();
            }
            /* Security check with untrusted cookies - dont trust value stored in cookie.       
            /* We also do authentication check of the 'ckey' stored in cookie matches that stored in database during login*/
            
            if (!empty($ckey)&& !empty($_COOKIE['employee_name']) && $_COOKIE['employee_key'] == sha1($ckey)) {
                session_regenerate_id(); //against session fixation attacks.
                
                $_SESSION['employee_id']         = $_COOKIE['user_id'];
                $_SESSION['employee_name']       = $_COOKIE['employee_name'];
                /* query user level from database instead of storing in cookies */
                $rs_employee_level                = mysql_query("select level from students where id='$_SESSION[user_id]'");
                $level_row              = mysql_fetch_array($rs_employee_level);
                $user_level                  = $evel_row['level'];
                $_SESSION['employee_level']      = $user_level;
                $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
                
            } else {
                logout();
            }
            
        } else {
            header("Location: login.php");
            exit();
        }
    }
}



function filter($data)
{
    // $data = trim(htmlentities(strip_tags($data)));//fuck htmlentities,not for chinese
    $data = trim(htmlspecialchars(strip_tags($data)));
    
    
    
    if (get_magic_quotes_gpc())
        $data = stripslashes($data);
    
    $data = mysql_real_escape_string($data);
    
    return $data;
}


function GenKey($length = 7)
{
    $password = "";
    $possible = "0123456789abcdefghijkmnopqrstuvwxyz";
    
    $i = 0;
    
    while ($i < $length) {
        
        
        $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        
        if (!strstr($password, $char)) {
            $password .= $char;
            $i++;
        }
        
    }
    
    return $password;
    
}








function logout()
{
    global $db;
    session_start();
    
    $sess_employee_id = strip_tags(mysql_real_escape_string($_SESSION['employee_id']));
    $cook_employee_id = strip_tags(mysql_real_escape_string($_COOKIE['employee_id']));
    
    if (isset($sess_employee_id) || isset($cook_employee_id)) {
        $sql_update = "UPDATE employee SET ckey= '', ctime= '' where id='$sess_employee_id' OR id = '$cook_employee_id'";
        $update_result = mysql_query($sql_update) or die(mysql_error());
        
    }
    
    /************ Delete the sessions****************/
    unset($_SESSION['employee_id']);
    unset($_SESSION['name']);
    unset($_SESSION['employee_level']);
    unset($_SESSION['HTTP_USER_AGENT']);
    session_unset();
    session_destroy();
    
    /* Delete the cookies*******************/
    setcookie("employee_id", '', time() - 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
    setcookie("employee_name", '', time() - 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
    setcookie("employee_key", '', time() - 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
    
    header("Location: index.php");
}


function checkAdmin()
{
    return true;
}





?>