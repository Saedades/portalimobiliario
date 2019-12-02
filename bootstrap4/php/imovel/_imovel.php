<?php
	require_once '../partials/connectDB.php';
	include_once '../partials/validate_session.php';
	echo '<pre>' . var_export($_POST, true) . '</pre>';
				/*
				<!------------------------------------------------------------------------------------------------------------------------->
				<!--- 	domid 				domuser			domtitle		domdescr				domactive 			domvalor		--->
				<!--- 	$subject_id 		$user_id		$subject_title	$subject_description	$subject_status		$subject_value 	--->
				<!------------------------------------------------------------------------------------------------------------------------->
				*/


				if(!isset($_POST['sbmtd'])) {
					//why are you here??
					header('Location ../profile.php');
				}

				$idsubject = isset($_POST['domid']) ? $_POST['domid'] : 0 ;

				$iduser_A = isset($_POST['domuser']) ? $_POST['domuser'] : 0 ;
				$iduser_B = isset($_SESSION['login']) ? $_SESSION['login'] : 0 ;
				$titulo = (isset($_POST['domtitle']) AND strlen($_POST['domtitle'])>1) ? $_POST['domtitle'] : '' ;
				$kwid = isset($_POST['domkwid']) ? $_POST['domkwid'] : '' ;
				$descr = isset($_POST['domdescr']) ? $_POST['domdescr'] : '' ;
				$addr = isset($_POST['domaddr']) ? $_POST['domaddr'] : '' ;
				$local = isset($_POST['domlocal']) ? $_POST['domlocal'] : '' ;
				$zip1 = (isset($_POST['domzip1']) AND strlen($_POST['domzip1'])>1) ? $_POST['domzip1'] : '0' ;
				$zip2 = (isset($_POST['domzip2']) AND strlen($_POST['domzip2'])>1) ? $_POST['domzip2'] : '0' ;
				$negocio = (isset($_POST['domnegocio'])) ? $_POST['domnegocio'] : '0' ;
				$domvalor = (isset($_POST['domvalor'])) ? str_replace(',', '', $_POST['domvalor']) : '0' ;
				$domvalor2 = (isset($_POST['domvalor2'])) ? str_replace(',', '', $_POST['domvalor2']) : '0' ;
				$domvalor3 = (isset($_POST['domvalor3'])) ? str_replace(',', '', $_POST['domvalor3']) : '0' ;

				$tipocasa = (isset($_POST['domtipocasa'])) ? $_POST['domtipocasa'] : '0' ;
				$tipologia = (isset($_POST['domtipologia'])) ? $_POST['domtipologia'] : '0' ;

				echo $negocio;

				$ref = isset($_POST['domref']) ? $_POST['domref'] : '' ;

				

				$state= isset($_POST['domestado']) ? $_POST['domestado'] : 1;

				$value_B = isset($_POST['domvalor']) ? $_POST['domvalor'] : '-1' ;
				$value = ($value_B === '0' or  $value_B === '-1' or $value_B === '')?  '0' :  $value_B ;

				$value_B2 = isset($_POST['domvalor2']) ? $_POST['domvalor2'] : '-1' ;
				$value2 = ($value_B2 === '0' or  $value_B2 === '-1' or $value_B2 === '')?  '0' :  $value_B2 ;

				$value_B3 = isset($_POST['domvalor3']) ? $_POST['domvalor3'] : '-1' ;
				$value3 = ($value_B3 === '0' or  $value_B3 === '-1' or $value_B3 === '')?  '0' :  $value_B3 ;
				echo $value;

				if($iduser_A != $iduser_B) {
					//wtf?
					header('Location ../profile.php');
				}

				$iduser = $iduser_A;

			 /*<!------------------------------------------------------------------------------------------------------------------------->*/



	//check if its updating or creating
	if($idsubject==0) {
		//creating

		$aquery = 'INSERT INTO imoveis(titulo, descricao, user, KWID, morada, local, zip1, zip2, negocio, tipocasa, tipologia, val_neg, val_co_contra, val_co_cobra, estado)
					VALUES ("' . $titulo . '", "' . $descr . '", ' .
							$iduser . ',"' . $kwid . '","' . $addr . '","' .
							$local . '",' . $zip1 . ',' . $zip2 . ',' . $negocio . ',' . $tipocasa . ',' . $tipologia . ',' . $domvalor . ',' . $domvalor2 . ',' . $domvalor3 . ',' . $state .')';

		echo '<h4 style="color:darkblue">CREATING</h4>';
		echo '<h3>' . $aquery . '</h3>';

      	if($newid = $conn->query($aquery))
		{

			//id 	idimovel 	iduser 	idcontato 	estado 	assinado_em
			$aquery = 'INSERT INTO angariacoes(idimovel, iduser, idcontato, estado)
					VALUES ('.$conn->insert_id.', '.$iduser.',0,1)';
			header('Location: imovel.php?id=' . $conn->insert_id . "&code=2");
			echo "INSERT DONE, NOT CREATING ANG";
		}
		else
			echo("Error description: " . mysqli_error($conn));

		echo "aqui1";
	}
	else {
		//updating
		$aquery =
			'UPDATE imoveis SET
				titulo="' . $titulo . '",
				KWID="' . $kwid . '",
				descricao="' . $descr . '",
				val_neg='. str_replace(',', '', $value) . ',
				estado='. $state . ',
				val_co_contra=' . str_replace(',', '', $value2) . ',
				val_co_cobra=' . str_replace(',', '', $value3) .',
				morada="' . $addr . '",
				local= "' . $local . '",
				zip1= ' . $zip1 . ', 
				zip2=' . $zip2 . ',
				negocio=' . $negocio . ',
				tipocasa=' . $tipocasa . ',
				tipologia=' . $tipologia . '
			WHERE id=' . $idsubject;

		echo '<h3 style="color:darkblue; text-align:center;">UPDATING</h3>';
		echo '<h3 style="color:gray;font-weight:normal; font-family: Arial, Helvetica, sans-serif;  text-align:center;">'. $aquery .'</h3>';


		if($conn->query($aquery))
		{
			header('Location: imovel.php?id=' . $idsubject . "&code=1");
			echo "UPDATE DONE";
		}
		else
			echo "UPDATE FAILED.<br>" . mysqli_error($conn);

		echo "aqui2";
	}

	echo 'AQUIII';
