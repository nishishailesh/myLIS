<?php 
session_start();
include 'common.php';
if(isset($_POST['action']))
{
	if($_POST['action']=='download')
	{
		download_blob($_POST);
	}
}


echo '<html>';
echo '<head>';




echo '
<link rel="stylesheet" type="text/css" href="date/datepicker.css" /> 
<script type="text/javascript" src="date/datepicker.js"></script>
<script type="text/javascript" src="date/timepicker.js"></script>';
echo  '
<STYLE>
th{background-color:lightgreen;}
tr.submit{background-color:lightblue;}
tr.top{background-color:lightpink;}
tr.top2{background-color:lightgreen;}
tr.top3{background-color:lightblue;}

tr{background-color:lightgray;}
tr.equipment{background-color:#E366BF;}
button{padding:0;}
td{vertical-align:top;}


td.equipment{color:olive;}
td.target{background-color:orange;}
td.observation{background-color:orangered;}
</STYLE>

<script  language="javascript">

function show_hide(id){
if (document.getElementById(id).style.display == \'none\' ) {
        document.getElementById(id).style.display = \'\';
    }else if (document.getElementById(id).style.display == \'\') {
		document.getElementById(id).style.display = \'none\';
    }
} 
function show_hide_c(classs,me){
	var elems = document.getElementsByClassName(classs);
	for (i=0;i<elems.length;i++){
		if(elems[i].style.display == \'\')
		{elems[i].style.display = \'none\';/*me.innerHTML=\'+\';*/}
	else if(elems[i].style.display == \'none\')
		{elems[i].style.display = \'\';/*me.innerHTML=\'--\';*/}	
	}
		}
</script>


';
echo '</head>';
echo '<body>';



global $cr_raw, $c_raw, $r_raw, $cc_raw;


function select_code()
{
	echo '<table><form method=post action=calibration.php>';
	echo '<tr><th>View Calibrations</th><td>';
	mk_select_grand('code','','');mk_select_grand('equipment_name','','');
	echo '</td></tr>';
	echo '<tr class="submit" ><td colspan=2><button type=submit name=action value=show_for_code>View Calibrations</button></td></tr>';	
	echo '</form></table>';	
}


function new_calibration()
{
		echo '	<form method=post>
					<table>
					<tr><th>Add New calibration</th><td>
					<tr>	
						<td><button type=submit name=action  value=new_calibration >New Calibration</button></td>';
						//<td><input type=text readonly name=code  value=\''.$_POST['code'].'\'></td>					
					echo '</tr>
					</table>
				</form>';
}				
				
////////////BLOB
function file_to_str($file,$size)
{
	$link=start_nchsls();
	$fd=fopen($file,'r');
	$str=fread($fd,$size);
	return mysql_real_escape_string($str,$link);
}

function find_primary($tname)
{
	$link=start_nchsls();
	
	if(!$result_desc=mysql_query('desc `'.$tname.'`',$link))
	{
		echo 'Desc table error:find_primary()'.mysql_error();
		return;
	}
	
	$pri=array();
	while($desc_array=mysql_fetch_assoc($result_desc))
	{
		if($desc_array['Key']=='PRI')
		{
			$pri[]=$desc_array['Field'];
		}
	}
	return $pri;
		
}

function find_blob($tname)
{
	$link=start_nchsls();
	
	if(!$result_desc=mysql_query('desc `'.$tname.'`',$link))
	{
		echo 'Desc table error:find_primary()'.mysql_error();
		return;
	}
	
	$blob=array();
	while($desc_array=mysql_fetch_assoc($result_desc))
	{
		if($desc_array['Type']=='longblob')
		{
			$blob[]=$desc_array['Field'];
		}
	}
	return $blob;
		
}


function upload_blob($tname,$field)
{
	
	$pri=find_primary($tname);
	$where_condition=' ';
	foreach($pri as $key=>$value)
	{
		$where_condition=$where_condition.' `'.$value.'`=\''.$_POST[$value].'\' and ';
	}
	$where_condition_final=substr($where_condition,0,-4);	
	$file_name=$field.'_name';
	//echo '<h3>'.$file_name.'</h3>';
		if(strlen($_FILES[$field]['tmp_name'])>0)
		{
		$str=file_to_str($_FILES[$field]['tmp_name'],$_FILES[$field]['size']);
		$sql='update `'.$tname.'` set '.
				$field.'=\''.$str.'\', '.
				$file_name.'=\''.$_FILES[$field]['name'].'\' 
				where '.$where_condition_final;
						
		//echo $sql;
		
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		else
		{
			echo 'upload is success<br>';
		}
	}
else
	{
		echo 'nothing to upload';
	}

}



function show_blob($field_name)
{
	echo '<input type=reset value=r>';
	echo '<input type=file name=\''.$field_name.'\' >';
}

function download_blob($post)
{
	$link=start_nchsls();
	
	$pri=find_primary($post['tname']);

	$where_condition=' ';
	$file=$post['tname'];
	foreach($pri as $key=>$value)
	{
		$where_condition=$where_condition.' `'.$value.'`=\''.$post[$value].'\' and ';
		$file=$file.'-'.$value;
	}
	$where_condition_final=substr($where_condition,0,-4);	
	
	
	$blob=find_blob($post['tname']);

	foreach($blob as $keyy=>$valuee)
	{
		$sql='select `'.$valuee.'` , `'.$valuee.'_name` from `'.$post['tname'].'` where '.$where_condition_final;	
		//echo $sql;
		if(!$result=mysql_query($sql,$link))
		{
			echo 'download_blob() error:'.mysql_error();
		}
		else
		{
			$result_array=mysql_fetch_assoc($result);	
			$name=$result_array[$valuee.'_name'];
			$length=10000000000;
			$type='pdf';
			header("Content-Disposition: attachment; filename=$name");
			header("Content-length: $length");
			header("Content-type: $type");		
			echo $result_array[$valuee];
		}
	}
	exit(0);
}

/////////////////
function mk_select_grand($name,$disabled,$default)
{
	$link=start_nchsls();
	$sql='select * from `'.$name.'`';
	
	if(!$result=mysql_query($sql,$link)){return FALSE;}
	
		echo '<select   style="width: 100px;" '.$disabled.' name='.$name.'>';
		while($result_array=mysql_fetch_assoc($result))
		{
			if($result_array[$name]==$default || $result_array['first']==$default)
			{
				if(isset($result_array['second']))
				{
					echo '<option selected  value= \''.$result_array['first'].'\'  > '.$result_array['second'].' </option>';
				}
				else
				{
					echo '<option selected  value= \''.$result_array[$name].'\'  > '.$result_array[$name].' </option>';		
				}
			}
			else
			{
				if(isset($result_array['second']))
				{
					echo '<option  value= \''.$result_array['first'].'\'  > '.$result_array['second'].' </option>';
				}
				else
				{
					echo '<option  value= \''.$result_array[$name].'\'  > '.$result_array[$name].' </option>';		
				}
			}
		}
		echo '</select>';	
		return TRUE;
}
/*
function read_new($table,$target,$header,$input_title,$input_name,$input_value,$input_show)
{
	$link=start_nchsls();
	if(!$result=mysql_query('desc `'.$table.'`',$link))
	{
		echo 'Desc table error:'.mysql_error();
	}
	else
	{
		echo '<table><form method=post action=\''.$target.'\'>';
		echo '<tr><th colspan=2>'.$header.'</th></tr>';
		echo '<input type=hidden name=tname value=\''.$table.'\'>';
		while($result_array=mysql_fetch_assoc($result))
		{
				if($result_array['Extra']=='auto_increment')
				{
					echo '<tr><td>'.$result_array['Field'].'</td><td><input type=text name=\''.$result_array['Field'].'\' readonly></td></tr>';
				}
				
				elseif($result_array['Type']=='date')
				{
					echo '<tr><td>'.$result_array['Field'].'</td><td><input id=\''.$result_array['Field'].'\' class="datepicker" size="11" title="D-MMM-YYYY" name=\''.$result_array['Field'].'\' /> </td></tr>';
				}			
				else
				{
					echo '<tr><td>'.$result_array['Field'].'</td><td>';
					//if(!mk_select_from_table($result_array['Field'],'',''))
					if(!mk_select_grand($result_array['Field'],'',''))

					{
						$exp=explode('(',$result_array['Type']);//print_r($exp);
						if($exp[0]=='varchar' && $exp[1]>100)
						{
							echo '<textarea name=\''.$result_array['Field'].'\' ></textarea>';							
						}
						else
						{
							echo '<input type=text name=\''.$result_array['Field'].'\' >';							
						}
					}
					echo '</td></tr>';
				}
		}
	
	echo '<tr class="submit" ><td colspan=2><button type=submit title="'.$input_title.'" name="'.$input_name.'"  value="'.$input_value.'" >'.$input_show.'</button></td></tr>';	
	echo '</form></table>';	
	}
}
*/


function read_new($table,$target,$header,$input_title,$input_name,$input_value,$input_show)
{	
	$link=start_nchsls();
	$pri=find_primary($table);
	if(!$result=mysql_query('desc `'.$table.'`',$link))
	{
		echo 'Desc table error:'.mysql_error();
	}
	else
	{
		echo '<table><form method=post action=\''.$target.'\'     enctype=\'multipart/form-data\'>';
		echo '<tr><th colspan=2>'.$header.'</th></tr>';
		echo '<input type=hidden name=tname value=\''.$table.'\'>';
				
		while($result_array=mysql_fetch_assoc($result))
		{
				echo '<tr><td>'.$result_array['Field'].'</td><td>';		
				if($result_array['Extra']=='auto_increment')
				{
					echo '<input type=text name=\''.$result_array['Field'].'\' readonly></td></tr>';
				}




				elseif($result_array['Type']=='date')
				{
					$dt_id='';
					foreach($pri as $keyy=>$valuee)
					{
						$dt_id=$dt_id.'_'.$valuee;
					}
					$dt_id=$dt_id.'_'.$result_array['Field'];
					echo '<input  id=\''.$dt_id.'\' class="datepicker" size="10" name=\''.$result_array['Field'].'\' />';
				}	
				elseif($result_array['Type']=='longblob')
				{
					show_blob($result_array['Field']);
				}
				else
				{
					if(!mk_select_grand($result_array['Field'],'',''))
					{
						$exp=explode('(',$result_array['Type']);//print_r($exp);
						if($exp[0]=='varchar' && $exp[1]>100)
						{
							echo '<textarea name=\''.$result_array['Field'].'\' ></textarea>';							
						}
						else
						{
							echo '<input type=text name=\''.$result_array['Field'].'\' >';							
						}
					}
					echo '</td></tr>';
				}
		}
	
	echo '<tr class="submit" ><td colspan=2><button type=submit title="'.$input_title.'" name="'.$input_name.'"  value="'.$input_value.'" >'.$input_show.'</button></td></tr>';	
	echo '</form></table>';	
	}
}



////////////

function save_new($table,$post)
{
	$link=start_nchsls();
	$sql1='insert into `'.$table.'` ';
	$sql2=' (';
	$sql3=' values(';
	
	if(!$result=mysql_query('desc `'.$table.'`',$link))
	{
		echo 'Desc table error:save_insert()'.mysql_error();
		return;
	}
	while($result_array=mysql_fetch_assoc($result))
	{
		if($result_array['Type']=='longblob')
		{
		
		}
		else
		{
		$sql2=$sql2.'`'.$result_array['Field'].'`,';
		$sql3=$sql3.'\''.$post[$result_array['Field']].'\',';
		}
	}
	
	$sql22=substr($sql2,0,-1);
	$sql33=substr($sql3,0,-1);
	$sql22=$sql22.')';
	$sql33=$sql33.')';
	$sql=$sql1.$sql22.$sql33;
	
	//echo $sql;	
	if(!$result=mysql_query($sql,$link))
	{
		echo 'save_insert()'.mysql_error();
	}

	echo '<h3>edit to upload attachment</h3>';
}



function save_edit($post)
{

	$link=start_nchsls();

	if(!$result=mysql_query('desc `'.$post['tname'].'`',$link))
	{
		echo 'Desc table error:save_edit()'.mysql_error();
		return;
	}


	$sql1=' update `'.$post['tname'].'` set';
	$sql2=' ';
	$sql3=' where ';
	
		
	while($result_array=mysql_fetch_assoc($result))
	{
		if($result_array['Key']=='PRI')
		{
			$sql3=$sql3.'`'.$result_array['Field'].'`=\''.$post[$result_array['Field']].'\' and ';
		}
		elseif($result_array['Type']=='longblob')
		{
			//upload_blob($post['tname'],$result_array['Field']);
			//at last to prevent overwriting upload file name
		}
		else
		{
			$sql2=$sql2.'`'.$result_array['Field'].'`=\''.$post[$result_array['Field']].'\',';
		}
	}
	$sql22=substr($sql2,0,-1);
	$sql33=substr($sql3,0,-4);

	$sql=$sql1.$sql22.$sql33;
	
	//echo $sql;	

	if(!$result=mysql_query($sql,$link))
	{
		echo 'save_edit()'.mysql_error();
	}
	else
	{
		echo 'Successfully saved '.mysql_affected_rows($link).' records<br>';
	}
	
	
	save_blob($post);

}








function save_blob($post)
{

	$link=start_nchsls();

	if(!$result=mysql_query('desc `'.$post['tname'].'`',$link))
	{
		echo 'Desc table error:save_edit()'.mysql_error();
		return;
	}
		
	while($result_array=mysql_fetch_assoc($result))
	{
		if($result_array['Type']=='longblob')
		{
			upload_blob($post['tname'],$result_array['Field']);
		}
	}
	//echo $sql;	
}




function del($post)
{
	/*[tname] => calibration
            [action] => delete
            [calibration_id] => 1*/
            
 	
///find primary keys


	$link=start_nchsls();
	
	if(!$result_desc=mysql_query('desc `'.$post['tname'].'`',$link))
	{
		echo 'Desc table error:show()'.mysql_error();
		return;
	}
	
	$pri=array();
	while($desc_array=mysql_fetch_assoc($result_desc))
	{
		if($desc_array['Key']=='PRI')
		{
			$pri[]=$desc_array['Field'];
		}
//////////////////////
	}
	
	$sql='delete from '.$post['tname'].' where ';
	$where='';
	foreach($pri as $key=>$value)
	{
		$where=$where.'`'.$value.'`=\''.$post[$value].'\' and ';
	}
	
	$sql=$sql.$where;
	
	$sql_final=substr($sql,0,-4);

	//98252 53108           
     //echo $sql_final;
     
     if(!$result=mysql_query($sql_final,$link))
	{
		echo 'save_edit()'.mysql_error();
	}
	else
	{
		echo 'Successfully deleted '.mysql_affected_rows($link).' records';
	}

}

function field_type($tname)
{
	$link=start_nchsls();
	$ft=array();
	if(!$result_desc=mysql_query('desc `'.$tname.'`',$link))
	{
		echo 'Desc table error:show()'.mysql_error();
		return;
	}
	while($result_array=mysql_fetch_assoc($result_desc))
	{
		$ft[$result_array['Field']]=$result_array['Type'];
	}
	return $ft;	
}

function show($sql,$header,$top_raw,$tname,$target,$all_readonly,$display_heads,$equipment_name)
{
	$link=start_nchsls();
	$pri=find_primary($tname);
	$ft=field_type($tname);
	
	if(!$result=mysql_query($sql,$link))
	{
		echo 'show() error:'.mysql_error();
	}
	else
	{
		echo '<table>';
		
		if($display_heads=='yes')
			{
			echo '<tr><th colspan=20>'.$header.'</th></tr>';
			
			echo '<tr  class="top">';
			echo '<td></td>';
			foreach($top_raw as $key=>$value)
			{
					echo '<td>';
					echo $value;
					echo '</td>';								
			}		
			echo '</tr>';
		}
		
		while($result_array=mysql_fetch_assoc($result))
		{
			if($result_array['equipment_name']==$equipment_name)
			{
					echo '<tr class="equipment">';
			}
			else
			{
				echo '<tr>';
			}
			
			echo '<form method=post action=\''.$target.'\'   enctype=\'multipart/form-data\'>';
			echo '<input type=hidden name=tname value=\''.$tname.'\'>';
			
			if($all_readonly=='readonly'){$disabled='disabled';}else{$disabled='';}
			echo '<td>	<button '.$disabled.' type=submit name=action  value=delete >Delete</button>
						<button '.$disabled.' type=submit name=action  value=save_edit >Save</button>
						<button '.$disabled.' type=submit name=action  value=download >Download</button>
					</td>';
			
			foreach($result_array as $key=>$value)
			{
					if(in_array($key,$pri)){$readonly='readonly';}else{$readonly='';}
					if($all_readonly=='readonly'){$readonly='readonly';}
					echo '<td>';
				$dt_id='';
				foreach($pri as $keyy=>$valuee)
				{
					$dt_id=$dt_id.'_'.$result_array[$valuee];
				}
				$dt_id=$dt_id.'_'.$key;
				
				if($ft[$key]=='date')
				{
					echo '<input '.$readonly.' value=\''.$value.'\' id=\''.$dt_id.'\' class="datepicker" size="10" name=\''.$key.'\' />';
				}	

				elseif($ft[$key]=='longblob')
				{
					show_blob($key);
				}		
				else
				{
					//echo '<h5>'.$value.'</h5>';

					if(!mk_select_grand($key,$readonly,$value))
					{
						$exp=explode('(',$ft[$key]);//print_r($exp);
						
						if($exp[0]=='varchar' && $exp[1]>100)
						{
							echo '<textarea rows=3 '.$readonly.' name=\''.$key.'\' >'.$value.'</textarea>';							
						}
						else
						{
							echo '<input  size=10 '.$readonly.' value=\''.$value.'\' type=text name=\''.$key.'\' >';							
						}						
					}
				}					
					
					/////////////////////gooood
				
					echo '</td>';								
			}
			echo '</form>';
			echo '</tr>';
		}
		echo '</table>';
	}
}

$c_raw=array('calibration_id','code','date','equipment','purpose','reagents','calibrators','responses',
			'correlation_function','quality_check','detail','file_attached','file_attached_name' );



//////////////end report
if(!login_varify())
{
exit();
}
main_menu();

echo '<table><tr><td>';
select_code();
echo '</td><td>';
echo new_calibration();
echo '</td></tr></table>';

if(isset($_POST['action']))
{
	if($_POST['action']=='save_new')
	{
		save_new($_POST['tname'],$_POST);
	}
	elseif($_POST['action']=='save_edit')
	{
		save_edit($_POST);
	}
	elseif($_POST['action']=='delete')
	{
		del($_POST);
	}	
	elseif($_POST['action']=='new_calibration')
	{
		read_new('calibration','calibration.php','New Calibration','Click to save','action','save_new','Save');
	}
}
else
{
	/*
	echo '<table><form method=post action=calibration.php>';
	echo '<tr><th>Select code of examination</th><td>';
	mk_select_grand('code','','');
	echo '</td></tr>';
	echo '<tr class="submit" ><td colspan=2><button type=submit name=action value=show_for_code>New/Edit Calibrations</button></td></tr>';	
	echo '<tr class="submit" ><td colspan=2><button type=submit name=action value=report_for_code>Report Summary</button></td></tr>';	
	echo '</form></table>';	
	*/
}

if(isset($_POST['code']))
{
	show('select * from calibration where code=\''.$_POST['code'].'\' order by `date_of_calibration` desc','Calibrations',$c_raw,'calibration','calibration.php','','yes',$_POST['equipment_name']);						

}


/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

?>
