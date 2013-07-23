<?php
function conecta(){
$link = mysql_connect("localhost", "user", "password") or die ('error! '.mysql_error());
mysql_select_db("elvex",$link);
return ($link);
}

function row($sql){
//echo $sql;
$link=conecta();
$result=mysql_query($sql,$link);echo mysql_error();
$myrow=mysql_fetch_array($result);echo mysql_error();
return $myrow;
}

function consulta($sql){
$link=conecta();
//echo $sql;
$result=mysql_query($sql);echo mysql_error();
return($result);
}

function arregla($string){
  $string=trim($string);
  while(stripos($string,"  "))
      $string=str_replace("  "," ",$string);
  return($string);
}

function quita($string,$search){
  $string2=$string;
  if(stripos($string,$search)!==FALSE){
      $string_array=explode($search,$string);
      $string2="";
      //echo "pregunta:$myrow[pregunta]<br>";
      foreach($string_array as $trozo){                    
	  $string2.=$trozo." ";
	  //echo "trozo::$trozo<br>";
      }
  }
  $string2=arregla($string2);
  return($string2);
}

function piensatodo(){
  $result1=consulta("select * from implicancias");
  while($myrow1=mysql_fetch_array($result1)){
    $result2=consulta("select * from verdades");
    while($myrow2=mysql_fetch_array($result2)){
      if((stripos($myrow2[frase],$myrow1[si])!==FALSE) && (stripos($myrow2[frase],"no ".$myrow1[si])===FALSE)){
      $conclusion=str_replace($myrow1[si],$myrow1[entonces],$myrow2[frase]);
      //echo "$conclusion<br>";
      if(!row("select * from verdades where frase='$conclusion'")){
	consulta("insert into verdades (frase,razon_id) values('$conclusion',$myrow2[id])");
	echo ".";
      }
      }
    }
  }
}


?>
