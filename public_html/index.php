<html>
<head>
</head>
<body onload="document.getElementById('input').focus();">
    
<?php
include("elvex.inc.php");
$input=$_POST["input"];
$input_orig=$input;

//Mostrar conversa

$result=consulta("SELECT * FROM (SELECT * FROM conversa ORDER BY ID DESC LIMIT 20) AS ttbl ORDER BY ID ASC;");
while($myrow=mysql_fetch_array($result)){
    echo("$myrow[quien] : $myrow[frase]<br>");
}

consulta("insert into conversa (quien,frase) values('User','$input')");

//Procesar entrada

if($input=="medita"){
    piensatodo();
    $respuesta="Ommm";
}

if($input){
    $largo_input=strlen($input)-1;
                                            //recibir sacadas de pillo (concepto : respuesta)
    if(stripos($input,":")!==FALSE){
        $array=explode(":",$input);
        consulta("insert into sacadas (concepto,respuesta) values ('$array[0]','$array[1]')");
        $respuesta="jejeje, ok";
    }
    
    
    //creer verdades absolutas, "porque lo digo yo y punto" (frases terminadas en punto)
    
    if($input[$largo_input]=="."){
        $input=rtrim($input,".");
        consulta("insert into verdades (frase,razon_id) values ('$input ','0')");
        piensatodo();
        $respuesta="OK";
        
    }
    
    if(stripos($input,"si ")===0){     //Procesar "Si...entonces"
        $input2=quita($input,"si ");
        $array=explode("entonces",$input2);
        $a=arregla($array[0]);
        $b=arregla($array[1]);
        consulta("insert into implicancias (si,entonces) values ('$a','$b')");         //SI A implica B...
        $no_b=arregla(str_replace("no no","","no $b"));
        $no_a=arregla(str_replace("no no","","no $a"));
        consulta("insert into implicancias (si,entonces) values ('$no_b','$no_a')");   //No B implica no A...
        $result=consulta("select * from implicancias where si='$b'");                        //y si B implica C...
        while($myrow=mysql_fetch_array($result)){
            if(!row("select * from implicancias where si='$b' and entonces='$myrow[entonces]' ")){
                $c=$myrow[entonces];
                consulta("insert into implicancias (si,entonces) values ('$a','$c')");         // A implica C
                $no_c=arregla(str_replace("no no","","no $c"));
                consulta("insert into implicancias (si,entonces) values ('$no_c','$no_a')");   // y No C implica no A
            }
        }
        piensatodo();
        $respuesta="ya...";
    }
    
    //contestar "inteligentemente" segun BD
    
    if(!$respuesta){           //sacar preguntas quien, como, donde, etc. el resultado, a $input2
        $input=rtrim($input,"?");
        $result=consulta("select * from preguntas order by id desc");
        $condiciones="";
        $input2=$input;
        while($myrow=mysql_fetch_array($result)){
            $input2=quita($input2,$myrow[pregunta]);
            $input2_orig=$input2;
        }
        
        $respuesta="";
        
        
        if(quita(" ".$input2." "," no ")!=($input2)){   // Revisar negaciones y armar la respuesta en caso que lo que dice uno sea falso
            $input2=quita(" ".$input2." "," no");
            $no="No, $input2";
            //$respuesta.="$no";
        }
        
       
        $input2=arregla($input2);           //generar consulta con las palabras obtenidas
        //echo "input2=$input2<br>";
        $input2_palabras_array=explode(" ",$input2);
        foreach($input2_palabras_array as $palabra){
            $condiciones.="frase regexp '$palabra ' and ";
        }
        $condiciones.="TRUE";
        //echo $condiciones;
        $existe_respuesta=FALSE;
        $result=consulta("select * from verdades where $condiciones order by rand() limit 2");
        while($myrow=mysql_fetch_array($result)){
            $existe_respuesta=TRUE;
            $respuesta=$myrow[frase];
            $razon_id=$myrow[razon_id];
            if($razon_id!=0){
                $rrow=row("select * from verdades where id=$razon_id");
                $razon=$rrow[frase];
            }
            $respuesta2=$respuesta;
            foreach($input2_palabras_array as $palabra){
                $respuesta2=arregla(quita(" ".$respuesta2." "," ".$palabra." "));
                
            }
        $respuesta3.=$respuesta2.",";
        if($respuesta3==","){
                if($no&&$existe_respuesta) {$respuesta3=$no.".$razon.";echo("here");}
                    else
                    {$respuesta3="Si,asi es. $razon";
                        
                    }
            };
        }            
     $respuesta=rtrim($respuesta3,",");
     //if($no&&!$respuesta) $respuesta=$no." $respuesta";
     
     if(($respuesta=="no")&&($existe_respuesta)) $respuesta="No, $razon ";
     
    }

    //No tengo respuesta, pero puedo tener preguntas
   
if(!$respuesta){
    $input=rtrim($input_orig,"?")." ";
    $result=consulta("select * from implicancias order by rand()");
    $stop=FALSE;
    while(($myrow=mysql_fetch_array($result))&&!$stop){
        //echo "$myrow[entonces]<br>";
        if(strpos(" ".$input," ".$myrow[entonces]." ")!==FALSE){
            $respuesta.=$myrow[si]."?";
            $stop=TRUE;
        }
    }
}


    //contestar "tontamente" segun BD (sacadas de pillo)
    
if(true){
    $input=rtrim($input_orig,"?")." ";
    $result=consulta("select * from sacadas order by length(concepto) desc");
    $stop=FALSE;
    while(($myrow=mysql_fetch_array($result))&&!$stop){
        //echo "concepto=$myrow[concepto]<br>";
        if(strpos(" ".$input," ".$myrow[concepto]." ")!==FALSE){
            $stop=TRUE;
            $concepto=$myrow[concepto];
            $result2=consulta("select * from sacadas where concepto ='$concepto'");
            $array_respuesta=array();
            while($myrow2=mysql_fetch_array($result2)){
                $array_respuesta[]=$myrow2[respuesta];
            }
            shuffle($array_respuesta);
            if($respuesta) $coma=",";
            $respuesta.=$coma.$array_respuesta[0];
        }
    }
    
    
    
}
 
 
 
 
// ME DOY!!! 
    
if(!$respuesta) $respuesta="no cacho eso...";


echo("User : $input_orig<br>Elvex : $respuesta");
consulta("insert into conversa (quien,frase) values('Elvex','$respuesta')");
}

?>
<form action="index.php" method="post">
<input type="text" name="input" id="input" autocomplete="off">
</form>
</body></html>
