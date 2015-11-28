<?php
session_start();

echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';

/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

include 'common.php';

function select_sample_for_worklist($filename)
{
	echo '<table border=1 id=\'id\' bgcolor=lightgrey CELLPADDING=0 CELLSPACING=0>';
	echo '<form method=post action=\''.$filename.'\'>';
	echo '	<tr>
				<td>from_sample_id:<input type=text name=from_sample_id></td>
				<td>to_sample_id:<input type=text name=to_sample_id></td>
				<td><input type=submit value=print_sample_wise_worklist></td>';
	echo '</form></table>';

}	


if(!login_varify())
{
exit();
}
main_menu();
echo '<h2 style="page-break-before: always;"></h2>';	

if(isset($_POST['from_sample_id']) && isset($_POST['to_sample_id']))
{
	echo '<table border=1>';
	$counter=1;
	for($sample_id=$_POST['from_sample_id'];$sample_id<=$_POST['to_sample_id'];$sample_id++)
	{
		if($counter%4==1){echo '<tr>';}
		echo '<td valign=top>';
		print_sample_worklist($sample_id);
		echo '</td>';
		if($counter%4==0){echo '<tr>';}
		$counter++;
	}
	echo '</table>';
}
else
{
	select_sample_for_worklist('sample_wise_worklist.php');
}

?>
