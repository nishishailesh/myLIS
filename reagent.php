<?php
session_start();

echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';


/*
2014-10-30: chnage in raw color when lot changes. to better visualize change in lot


*/

include 'common.php';


if(!login_varify())
{
exit();
}

function search_form()
{
	$link=start_nchsls();
	$sql='desc reagent';
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}
	$tr=1;
	echo '<table border=1 bgcolor=lightgray><form method=post>';
	echo '	<tr>
				<td bgcolor=yellow><input type=submit name=submit value=search></td></tr>';
	while($ar=mysql_fetch_assoc($result))
	{
		if($tr%3==1){echo '<tr>';}
			echo '<td><input type=checkbox name=\'chk_'.$ar['Field'].'\' ></td><td>'.$ar['Field'].'</td><td>';
			if(!mk_select_from_table($ar['Field'],'',''))
			{
				  echo '<input type=text name=\''.$ar['Field'].'\' >';
			}
		echo '</td>';
		if($tr%3==0){echo '</tr>';}
		$tr++;
	}
	echo '</form></table>';
}


function group_edit()
{
	echo '<table border=1 bgcolor=lightgreen><form  method=post>';
	echo '<tr><td>';
	mk_select_from_sql('desc reagent','Field','','');
	echo '</td><tr><td>';
	echo '<input type=text name=\'value\' ></td></tr>';
	echo '<tr><td bgcolor=yellow><input type=submit name=submit value=group_edit>';
	echo '</td></tr></table></form>';	
}



function group_save($post)
{
	if(isset($post['submit']))
	{
		if($post['submit']=='group_edit' && isset($_SESSION['reagent_query']))
		{
			if($post['Field']!='id' && $post['Field']!='reagent_name' &&  $post['Field']!='serial_number')
			{
				$ex=explode('where',$_SESSION['reagent_query']);
				$sql='update reagent set '.$post['Field'].'=\''.$post['value'].'\' where '.$ex[1].' ';
				//echo $sql;
				$link=start_nchsls();
				if(!$result=mysql_query($sql,$link)){echo mysql_error();}
			}
		}
	}	
}

function edit_form($id)
{
	$link=start_nchsls();
	$sql='select * from reagent where id=\''.$id.'\'';
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}

	while($ar=mysql_fetch_assoc($result))
	{
		$tr=1;
		echo '<table border=1 bgcolor=lightpink><form  method=post>';
		echo '	<tr><td bgcolor=yellow><input type=submit name=submit value=save></td></tr><tr>';
		foreach($ar as $key=>$value)
		{
			if($tr%3==1){echo '<tr>';}
			echo '<td>'.$key.'</td><td>';
			if(!mk_select_from_table($key,'',$value))
			{
					if($key=='id')
					{
					  echo '<input type=text readonly name=\''.$key.'\' value=\''.$value.'\'>';
					}
					else
					{
					  echo '<input type=text name=\''.$key.'\' value=\''.$value.'\'>';				
					}	
			}
			echo '</td>';
			if($tr%3==0){echo '</tr>';}
			$tr++;
		}
		echo '</form></table>';
	}

}


function print_reagent($sql)
{
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}
	echo '<table border=1 bgcolor=lightgreen>
	<tr><th colspan=20 >Reagents Matching Search Criteria</th></tr>
	<tr><th></th><th></th><th>ID</th><th>Reagent</th><th>Lot</th><th>Creation</th><th>Expiry</th><th>Prepared By</th><th>Pack Size</th><th>Serial Number</th><th>Status</th>
	<th>Temperature on receipt</th><th>Receipt</th><th>Opening</th><th>Details</th></tr>
	
	';

	$prev_lot='';

	while($ar=mysql_fetch_assoc($result))
	{
		if($ar['lot']!=$prev_lot)	// to change raw color when lot changes.
		{
			$rstyle='style="background-color:pink;"';
		}
		else
		{
			$rstyle='style="background-color:lightgreen;"';
		}

		echo '<form method=post>';
		echo '<tr '.$rstyle.'>';
		echo '<td bgcolor=yellow><button type=submit name=submit value=delete>x</button></td>';
		echo '<td bgcolor=yellow><button type=submit name=submit value=edit>e</button></td>';
		echo '<td><input type=text name=id readonly value=\''.$ar['id'].'\'</td>';
		echo '<td>'.$ar['reagent_name'].'</td>';
		//echo '<td style="background-color:pink;" >'.$ar['lot'].'</td>';				
		echo '<td>'.$ar['lot'].'</td>';
		echo '<td>'.$ar['dop'].'</td>';
		echo '<td>'.$ar['doe'].'</td>';
		echo '<td>'.$ar['prepared_by'].'</td>';		
		echo '<td>'.$ar['pack_size'].'</td>';		
		echo '<td>'.$ar['serial_number'].'</td>';
		echo '<td>'.$ar['reagent_status'].'</td>';
		echo '<td>'.$ar['temperature_on_receipt'].'</td>';
		echo '<td>'.$ar['dor'].'</td>';
		echo '<td>'.$ar['doo'].'</td>';
		echo '<td>'.$ar['detail'].'</td>';
		echo '</tr>';
		echo '</form>';
		$prev_lot=$ar['lot'];			
		//print_r($ar);
	}
	echo '</table>';
	
}

function show_reagent($post)
{
	if(isset($post['submit']))
	{
		if($post['submit']=='search')
		{
			$str='select * from reagent where ';
			foreach ($post as $key=>$value)
			{
				if(substr($key,0,4)=='chk_' && $value=='on')
				{
					$str=$str.substr($key,4).' like \''.$post[substr($key,4)].'\' and ';
				}
			}
			if(substr($str,-4)=='and ')
			{
				print_reagent(substr($str,0,-4));
				$_SESSION['reagent_query']=substr($str,0,-4);
				//echo substr($str,0,-4);
			}
			else
			{
				echo 'No specific condition is given for search';
			}
		}
		else
		{
			print_reagent($_SESSION['reagent_query']);
		}
	}
}



//edit save
function save_reagent($post)
{
	if(isset($post['submit']))
	{
		if($post['submit']=='save')
		{
			$str='update reagent set ';
			foreach ($post as $key=>$value)
			{
				if($key!='submit' && $key!='id')
				{
					$str=$str.' '.$key.'=\''.$value.'\' , ';
				}
			}
			$str=substr($str,0,-2);
			$str=$str.' where id=\''.$post['id'].'\'';
			//echo $str;
			$link=start_nchsls();
			if(!$result=mysql_query($str,$link)){echo mysql_error();}
		}
	}
}


function delete_reagent($post)
{
	if(isset($post['submit']))
	{
		if($post['submit']=='delete')
		{
			$str='delete from reagent where id=\''.$post['id'].'\'';
			$link=start_nchsls();
			if(!$result=mysql_query($str,$link)){echo mysql_error();}
		}
	}	
	
}




function insert_reagent()
{
	$link=start_nchsls();
	$sql='desc reagent';
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}
	$tr=1;
	echo '<table border=1 bgcolor=lightblue><form method=post>';
	echo '	<tr>
				<td bgcolor=yellow><input type=submit name=submit value=insert></td></tr>';
	while($ar=mysql_fetch_assoc($result))
	{
		if($tr%3==1){echo '<tr>';}
			echo '<td>'.$ar['Field'].'</td><td>';
			if(!mk_select_from_table($ar['Field'],'','') && $ar['Field']!='id')
			{
				if($ar['Field']!='serial_number')
				{
				  echo '<input type=text name=\''.$ar['Field'].'\' >';
				}
				else
				{
					echo 'from:<input type=text name=\''.$ar['Field'].'_from\' >';
					echo 'to:<input type=text name=\''.$ar['Field'].'_to\' >';
				}
				
			}
		echo '</td>';
		if($tr%3==0){echo '</tr>';}
		$tr++;
	}
	echo '</form></table>';	
}

/*

Array
(
    [submit] => insert
    [reagent_name] => CR_R1_NAOH
    [lot] => 
    [dop] => 
    [doe] => 
    [prepared_by] => 
    [pack_size] => 
    [serial_number_from] => 23
    [serial_number_to] => 45
    [reagent_status] => calibrated
    [detail] => 
)

*/

function save_inserted($post)
{
	if(isset($post['submit']))
	{
		if($post['submit']=='insert')
		{
			for($i=$post['serial_number_from'];$i<=$post['serial_number_to'];$i++)
			{
				$str='insert into reagent ';
				$first=' (';
				$second=' values(';
				foreach ($post as $key=>$value)
				{
					if($key!='submit' && $key!='id' && $key!='serial_number_from' && $key!='serial_number_to')
					{
						$first=$first.$key.',';
						$second=$second.'\''.$value.'\' , ';
					}
				}
						$first=$first.'serial_number,';
						$second=$second.'\''.$i.'\' , ';				
				$first=substr($first,0,-1);
				$second=substr($second,0,-2);
				
				$first=$first.') ';
				$second=$second.') ';
				
				$str=$str.$first.$second;
				//echo $str.'<br>';
				$link=start_nchsls();
				if(!$result=mysql_query($str,$link)){echo mysql_error();}

			}
		}
	}
}

//$sql = "SELECT reagent_name,reagent_status,sum(`pack_size`) FROM `reagent`group by reagent_name,reagent_status LIMIT 0, 30 ";
//SELECT reagent_name,reagent_status,sum(`pack_size`) FROM `reagent`group by reagent_name,reagent_status
main_menu();
save_reagent($_POST);
delete_reagent($_POST);
save_inserted($_POST);
group_save($_POST);
if(isset($_POST['submit']))
{
	if($_POST['submit']=='edit')
	{
		edit_form($_POST['id']);
	}
}
show_reagent($_POST);

echo '<table><tr><td>';
search_form();
echo '</td><td>';
group_edit();
echo '</td><tr><td colspan=2>';
insert_reagent();
echo '</td></tr>';
view_data(17);
/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/


?>
