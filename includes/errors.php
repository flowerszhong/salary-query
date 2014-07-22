<?php
/******************** ERROR MESSAGES*************************************************
This code is to show error messages 
**************************************************************************/
if(!empty($err))  {
 echo "<div class=\"msg\">";
foreach ($err as $e) {
  echo "<div class='error-msg'>$e</div>";
  }
echo "</div>";	
 }


/******************************* END ********************************/	  
if(!empty($msg))  {
 echo "<div class=\"msg\">";
foreach ($msg as $m) {
  echo "<div class='ok-msg'>$m</div>";
  }
echo "</div>";	
 }


?>