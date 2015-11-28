<?php
//require doctor username and password
//takes $_GET from addressbar
//in future OUTSIDE users may be varified by a mysql table , using $_GET
//use: <IP of server>/view_scope.php?code=GLC

function view_data_doctor($sql)
{
	$link=mysql_connect('127.0.0.1','doctor','doctor');
	mysql_select_db('biochemistry',$link);

	if(!$result_id=mysql_query($sql,$link)){echo mysql_error();}
	$array_id=mysql_fetch_assoc($result_id);
	
	$first_data='yes';
	
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}
	echo '<table border=1>';
	
	while($array=mysql_fetch_assoc($result))
	{
		echo '<tr style="background-color:lightgray;"><th>Examination:'.$array['name_of_examination'].'</th><th>Sample:'.$array['sample_type'].'</th><th>Preservative:'.$array['preservative'].'</th></tr>';
		foreach($array as $key=>$value)
		{
			if($key!='result' && $key!='sample_id')
			if(strlen($value)>50)
			{
				echo '<tr><td>'.$key.'</td><td colspan=2><textarea cols=60>'.$value.'</textarea></td></tr>';
			}
			else
			{
				echo '<tr><td>'.$key.'</td><td colspan=2><pre>'.$value.'</pre></td></tr>';
			}
		}
	}
	echo '</table>';
}

if(isset($_GET['code']))
{
	view_data_doctor('select  * from scope where id<1000 and code=\''.$_GET['code'].'\' order by name_of_examination');
}
else
{
	view_data_doctor('select  * from scope where id<1000 order by name_of_examination');
}
?>
