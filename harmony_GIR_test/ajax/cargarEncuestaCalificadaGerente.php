<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	$reemplazar1=array("", "", "", "");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$idEncuesta = $_POST['idEncuesta'];
		$idUsuario = $_POST['idUsuario'];
		$idCoachingUser = $_POST['idCoachingUser'];
		$tipoUsuario = $_POST['tipoUsuario'];
		$num_Preguntas = $_POST['num_Preguntas'];
		

		$queryCiclosContestados="SELECT DISTINCT
		CY.NAME 
		FROM COACHING_ANSWERED CA 
		INNER JOIN COACHING_QUESTIONS CQ ON CQ.COACHING_QUESTION_SNR=CA.COACHING_QUESTION_SNR 
		INNER JOIN CYCLES CY ON CY.CYCLE_SNR=CA.CYCLE_SNR 
		WHERE CA.COACHING_USER_SNR='".$idCoachingUser."' 
		AND CA.COACHING_SNR='".$idEncuesta."' 
		AND CQ.REC_STAT=0 AND CA.REC_STAT=0 AND CY.REC_STAT=0 ";
		//echo $queryCiclosContestados."<br>";
		$rsCiclosCon=sqlsrv_query($conn, $queryCiclosContestados);
		$c=0;
		$ciclosArray=["","",""];
		
		while($cicloCon = sqlsrv_fetch_array($rsCiclosCon)){
			$ciclosArray[$c]=$cicloCon['NAME'];
			$c++;
		}

		$x=1;
		foreach($ciclosArray as $indice=>$valor){
			if(empty($valor)){
				$ciclosArray[$indice]="Sin Ciclo";
			}
			echo "<script>$('#pciclo'+'".$x."').text('".$ciclosArray[$indice]."');</script>";
			$x++;
		}

		//ciclo actual
		$arrDiaDeCiclo = sqlsrv_fetch_array(sqlsrv_query($conn," SELECT CYCLE_SNR,NAME FROM CYCLES WHERE  '".date("Y-m-d")."' BETWEEN START_DATE AND FINISH_DATE "));
		$ciclo = $arrDiaDeCiclo['CYCLE_SNR'];
		//end ciclo actual 

		//ultimo ciclo 
		$arrUltimoCiclo = sqlsrv_fetch_array(sqlsrv_query($conn," SELECT top 1 CYCLE_SNR FROM COACHING_ANSWERED CA 
		INNER JOIN COACHING_QUESTIONS CQ ON  CQ.COACHING_QUESTION_SNR=CA.COACHING_QUESTION_SNR
		WHERE CA.COACHING_USER_SNR='".$idCoachingUser."'
		AND CA.COACHING_SNR='".$idEncuesta."'
		AND CQ.REC_STAT=0 AND CA.REC_STAT=0
		ORDER BY CQ.SORT_NUM desc "));
		$ultimociclo = $arrUltimoCiclo['CYCLE_SNR'];
		//end ultimo ciclo de la pregunta



		//Asignar repre
		$qUsers = "SELECT U.USER_SNR,U.USER_NR+' - '+U.LNAME+' '+U.MOTHERS_LNAME+' '+U.FNAME AS REPRE 
		FROM COACHING_USERS_ASSIGNED CUA 
		INNER JOIN USERS U ON U.USER_SNR=CUA.USER_SNR
		WHERE U.REC_STAT=0 AND CUA.REC_STAT=0
		AND U.USER_SNR<>'00000000-0000-0000-0000-000000000000'
		AND U.USER_SNR = '".$idUsuario."'
		AND U.REC_STAT=0
		AND CUA.REC_STAT=0
		ORDER BY U.USER_NR,U.LNAME,U.MOTHERS_LNAME,U.FNAME";
		echo "<script>
			$('#sltRepreCoaching').empty();
		</script>";
						
		$rsU = sqlsrv_query($conn, $qUsers);
		while($row = sqlsrv_fetch_array($rsU)){
			echo "<script>$('#sltRepreCoaching').append('<option value=".$row['USER_SNR']." >".$row['REPRE']."</option>');</script>";
		}

		//end asignar repre

		//cargar pregunta de los 3 ciclos

		echo "<script>
			$('.divAS').hide();
			$('.txtCG').val('');
			$('.txtASC1').val('');
			$('.txtASC2').val('');
			$('.txtASC3').val('');
			$('.sltComboCoach').val('00000000-0000-0000-0000-000000000000');
		</script>";

		$queryPreguntas="SELECT ANSWER_SNR,ANSWER_STRING FROM COACHING_ANSWERED CA 
		INNER JOIN COACHING_QUESTIONS CQ ON  CQ.COACHING_QUESTION_SNR=CA.COACHING_QUESTION_SNR
		INNER JOIN  CYCLES CY ON CY.CYCLE_SNR=CA.CYCLE_SNR
		WHERE CA.COACHING_USER_SNR='".$idCoachingUser."'
		AND CA.COACHING_SNR='".$idEncuesta."'
		AND CQ.REC_STAT=0
		AND CA.REC_STAT=0
		AND CY.REC_STAT=0 
		ORDER BY CY.NAME,CQ.SORT_NUM";
		//echo $queryPreguntas."<br>";
		$rsP=sqlsrv_query($conn, $queryPreguntas);

		$j=1;
		$i=1;
		$no=1;
		while($preg = sqlsrv_fetch_array($rsP)){

			
			//$i == $ var+1 
			if($i==$num_Preguntas+1){
				$j=1;
				$no++;
			}
			//$i == ($ var * 2) + 1 
			if( $i == ($num_Preguntas * 2)+1){
				$j=1;
				$no++;
			}

			if($j<10){
                $j="0".$j;
            }else{
                $j=$j;
            }


			$string = str_replace(array("\r", "\n"), '', $preg['ANSWER_STRING']);
			

			if($preg['ANSWER_SNR']=="19387357-945A-41B6-8604-AE87F7660190" || $preg['ANSWER_SNR']=="F75816CB-BFEB-4AF3-802A-4831E7CEFDAD"){
				echo "<script>$('#divASC'+'".$no."'+'_'+'".$j."').show();</script>";
			}
			echo "<script>$('#sltComboC'+'".$no."'+'_'+'".$j."').val('".$preg['ANSWER_SNR']."');</script>";
			echo "<script>$('#txtASC'+'".$no."'+'_'+'".$j."').val('".$string."');</script>";

			$i++;
			$j++;

		}


		//end cargar preguntas de los 3 ciclos

		//logica para bloquear campos 
		$queryCargarPreguntas="SELECT * FROM COACHING_ANSWERED 
		WHERE COACHING_ANSWERED_SNR<>'00000000-0000-0000-0000-000000000000'
		AND REC_STAT=0
		AND COACHING_USER_SNR='".$idCoachingUser."' ";

		

		$params = array();
		$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		$rspreg = sqlsrv_query( $conn, $queryCargarPreguntas , $params, $options );
		$row_count = sqlsrv_num_rows( $rspreg );

		//seleccionar columna 

		if($row_count == $num_Preguntas){
			echo "<script>$('#hdnColCoachingUser').val(2);</script>";
		}


		if($row_count == ($num_Preguntas * 2)){
			echo "<script>$('#hdnColCoachingUser').val(3);</script>";
		}


		if($row_count == $num_Preguntas){

			$j=1;
			for($i=1;$i<=$num_Preguntas;$i++){
				if($i<10){
					
					$j="0".$i;
				}else{
					$j=$i;
					
				}
				echo "<script>$('#txtASC1_'+'".$j."').prop('disabled', true);</script>";
				echo "<script>$('#sltComboC1_'+'".$j."').prop('disabled', true);</script>";

				echo "<script>$('#txtASC2_'+'".$j."').prop('disabled', false);</script>";
				echo "<script>$('#sltComboC2_'+'".$j."').prop('disabled', false);</script>";

				echo "<script>$('#txtASC3_'+'".$j."').prop('disabled', true);</script>";
				echo "<script>$('#sltComboC3_'+'".$j."').prop('disabled', true);</script>";
			}

		}

		if($row_count == ($num_Preguntas * 2)){

			$j=1;
			for($i=1;$i<=$num_Preguntas;$i++){
				if($i<10){
					
					$j="0".$i;
				}else{
					$j=$i;
					
				}
				echo "<script>$('#txtASC1_'+'".$j."').prop('disabled', true);</script>";
				echo "<script>$('#sltComboC1_'+'".$j."').prop('disabled', true);</script>";

				echo "<script>$('#txtASC2_'+'".$j."').prop('disabled', true);</script>";
				echo "<script>$('#sltComboC2_'+'".$j."').prop('disabled', true);</script>";

				echo "<script>$('#txtASC3_'+'".$j."').prop('disabled', false);</script>";
				echo "<script>$('#sltComboC3_'+'".$j."').prop('disabled', false);</script>";
			}

		}

		if($row_count == ($num_Preguntas * 3)){

			$j=1;
			for($i=1;$i<=$num_Preguntas;$i++){
				if($i<10){
					
					$j="0".$i;
				}else{
					$j=$i;
					
				}
				echo "<script>$('#txtASC1_'+'".$j."').prop('disabled', true);</script>";
				echo "<script>$('#sltComboC1_'+'".$j."').prop('disabled', true);</script>";

				echo "<script>$('#txtASC2_'+'".$j."').prop('disabled', true);</script>";
				echo "<script>$('#sltComboC2_'+'".$j."').prop('disabled', true);</script>";

				echo "<script>$('#txtASC3_'+'".$j."').prop('disabled', true);</script>";
				echo "<script>$('#sltComboC3_'+'".$j."').prop('disabled', true);</script>";
			}

		}
		
		


		//logica para bloquear campos


		//repre logica de bloqueo


		$arrUltimaPreg = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT top 1
		ANSWER_SNR,ANSWER_STRING 
		FROM COACHING_ANSWERED CA 
		INNER JOIN COACHING_QUESTIONS CQ ON CQ.COACHING_QUESTION_SNR=CA.COACHING_QUESTION_SNR 
		INNER JOIN CYCLES CY ON CY.CYCLE_SNR=CA.CYCLE_SNR 
		WHERE CA.COACHING_USER_SNR='".$idCoachingUser."' 
		AND CA.COACHING_SNR='".$idEncuesta."'
		AND CQ.REC_STAT=0
		AND CA.REC_STAT=0
		AND CY.REC_STAT=0 
		ORDER BY CY.NAME desc ,CQ.SORT_NUM desc "));

		$ultimaPregReplica = $arrUltimaPreg['ANSWER_STRING'];

		

		if($tipoUsuario==4){

			$j=1;
			for($i=1;$i<=$num_Preguntas;$i++){
				if($i<10){
					
					$j="0".$i;
				}else{
					$j=$i;
					
				}
				echo "<script>$('#txtASC1_'+'".$j."').prop('disabled', true);</script>";
				echo "<script>$('#sltComboC1_'+'".$j."').prop('disabled', true);</script>";

				echo "<script>$('#txtASC2_'+'".$j."').prop('disabled', true);</script>";
				echo "<script>$('#sltComboC2_'+'".$j."').prop('disabled', true);</script>";

				echo "<script>$('#txtASC3_'+'".$j."').prop('disabled', true);</script>";
				echo "<script>$('#sltComboC3_'+'".$j."').prop('disabled', true);</script>";
			}




			if($row_count==$num_Preguntas and $ultimaPregReplica=="" ){
				echo "<script>$('#txtASC1_'+'".$num_Preguntas."').prop('disabled', false);</script>";
				echo "<script>$('#txtASC2_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#txtASC3_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#hdnColCoachingUser').val(1);</script>";
			}else{
				echo "<script>$('#txtASC1_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#txtASC2_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#txtASC3_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#hdnColCoachingUser').val(1);</script>";
			}

			if($row_count==($num_Preguntas * 2) and $ultimaPregReplica==""  ){
				echo "<script>$('#txtASC1_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#txtASC2_'+'".$num_Preguntas."').prop('disabled', false);</script>";
				echo "<script>$('#txtASC3_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#hdnColCoachingUser').val(2);</script>";
			}else{
				echo "<script>$('#txtASC1_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#txtASC2_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#txtASC3_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#hdnColCoachingUser').val(2);</script>";

			}

			if($row_count==($num_Preguntas * 3) && $ultimaPregReplica=="" ){
				echo "<script>$('#txtASC1_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#txtASC2_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#txtASC3_'+'".$num_Preguntas."').prop('disabled', false);</script>";
				echo "<script>$('#hdnColCoachingUser').val(3);</script>";
			}else{
				echo "<script>$('#txtASC1_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#txtASC2_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#txtASC3_'+'".$num_Preguntas."').prop('disabled', true);</script>";
				echo "<script>$('#hdnColCoachingUser').val(3);</script>";
			}

		

			

		}else if($tipoUsuario !=4){

			echo "<script>$('#txtASC1_'+'".$num_Preguntas."').prop('disabled', true);</script>";
			echo "<script>$('#txtASC2_'+'".$num_Preguntas."').prop('disabled', true);</script>";
			echo "<script>$('#txtASC3_'+'".$num_Preguntas."').prop('disabled', true);</script>";

		}
		//end repre logica de bloqueo

	}
?>