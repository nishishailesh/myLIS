<?php
session_start();

echo '<html>';
echo '<head>';

echo '</head>';
echo '<body>';


//echo '<pre>';
//print_r($GLOBALS);
//echo '</pre>';

include 'common.php';


if(!login_varify())
{
exit();
}

main_menu();

if(isset($_POST['submit']) && isset($_POST['sample_id']))
{
		if($_POST['submit']=='next')
		{
			$sample_id=$_POST['sample_id']+1;
		}
		if($_POST['submit']=='prev')
		{
			$sample_id=$_POST['sample_id']-1;
		}
		if($_POST['submit']=='OK' || $_POST['submit']=='refresh')
		{
			$sample_id=$_POST['sample_id'];
		}
}

else
{
	$sample_id=1;
}
	

echo '<form method=post>';
echo '<table bgcolor=lightpink>';
echo '<tr><th colspan=10>Autoverification</th><th>press refresh button once before proceeding to next sample</th></tr>';
echo '<tr><input type=hidden name=sample_id value=\''.$sample_id.'\'>';	
echo '<td><input type=submit value=prev name=submit >';
echo '<input type=submit value=next name=submit >';
echo '<input type=submit value=refresh name=submit ></td></tr>';
echo '<tr><td><input type=text name=sample_id value=\''.$sample_id.'\'></td>';	
echo '<td><input type=submit value=OK name=submit ></td></tr>';
echo '</form>';
echo '</table>';

echo '<table>
		<tr>
		<td valign=top>';

				echo '<table>
					<tr><td>';
				autoverify($sample_id,'autoverify_action.php','no');
				autoverify($sample_id,'autoverify_action.php','yes');
				echo '</td></tr>';
				echo '<tr><td>';
					print_chronology_of_a_sample($sample_id);
				echo '</td></tr>';
				echo '</table>';
echo '</td>';


echo '<td  valign=top>';
print_sample($sample_id,'','');
echo '</td></tr>';

//echo '<td valign=top>';
//print_examinations_tt($sample_id);
//echo '</td>';

echo '</tr></table>';


?>
