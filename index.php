<?php
session_start();

echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';

//echo '<pre>';
//print_r($GLOBALS);
//echo '</pre>';

echo '
<form method=post action=main_menu.php>
<table>
<tr>
<td>Login Name</td>
<td><input type=text name=login value=WriteLoginName></td>
</tr>
<tr>
<td>Password</td>
<td><input type=password name=password value=password></td>
</tr>
<tr>
<td><input type=submit name=action value=Login></td>
</tr>
</table>
</form> ';

echo '</body>';

?>
