<?php
	error_reporting(0);
	include ('config.php');
	$records = array();

	$result = $db->query("SELECT * FROM apple");

	if ($result) {
		if ($result->num_rows){
			while ($row = $result->fetch_object()){
				$records[] = $row;
			}
		}
	}







	if(isset($_POST['input'])){
		$new = $_POST['data'];
		
		// $xkaliy = ("SELECT xkaliy FROM a")

		$input = $db->query("INSERT INTO apple (aktual) 
			VALUES ('$new')");

		if($input){
			echo "<script> alert('Tambah data berhasil...');</script>";
			header("Refresh:0");
		}

	}



?>

<!DOCTYPE html>
<html lang="en">
<head>

	<title>Hasil Peramalan</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
	<link href="css/bootstrap.min.css" rel="stylesheet">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="style.css">
<!--===============================================================================================-->
<script>
	function show() {
		var x;
		var person = prompt("Masukkan data", "");
		if(person != null){
			document.getElementById('demo').value = person;
		}else{
			alert("data belum diisi");
		}
	}
	
</script>

</head>
<body>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
  <a class="navbar-brand" href="index.html" style="padding-left:100px">Peramalan Trend</a>
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="index.html">HOME</a>
    </li>
  </ul>
</nav>


<h1 id="judul">PERAMALAN TREND LEAST SQUARE</h1>
<h4 id="subjudul">Metode Kuadrat Terkecil</h4>

	<div class="limiter">
		<div class="container-table100">
			<div class="wrap-table100">
				<div class="table100">
					<table>
						<thead>
							<tr class="table100-head">
								<th class="column1">Tahun</th>
								<th class="column1">Y</th>
								<th class="column2">X</th>
								<th class="column3">X.Y</th>
								<th class="column4">X^2</th>
	
							</tr>
						</thead>
						<tbody>
							<?php 
							$x = 0;
							$xx = 0;
							$xxx= 0;
							$y = 0;
							$i = 2009; 
							$ang = (int) 0;
							$totalpangkatdua = (int) 0;
							$hasilxkaliy = (int) 0;
							


							foreach ($records as $r) { ?>
								<tr>
									<td class="column1"><?php echo $i; ?></td>
									<td class="column1"><?php echo $r->aktual; ?></td>
									<td class="column2"><?php echo $x; ?></td>

									<?php 
									$xy = $r->aktual * $xx;
									$hasilxkaliy += $xy;
									 ?>


									<td class="column3"><?php echo $r->aktual * $xx++ ; ?></td>
                           <?php 
                            $pangkatdua = $xxx * $y ;
							$totalpangkatdua += $pangkatdua; 

							?> 
									<td class="column4"><?php echo $xxx++ * $y++ ; ?></td>



								</tr>

							<?php 

							$ang += $x;
							
							

							$i++;
							$x++;

							} 

							?>

								
							<?php


								$hasil = $db->query("SELECT SUM(aktual) as data FROM apple");
								if ($hasil->num_rows){
									while ($row = $hasil->fetch_object()){
										$jumlah[] = $row;
									}
								}

								foreach ($jumlah as $j) { 
									$sum = $j->data;
								}



							?>

							<?php 
							$jml_data_a = array();
							$jml_data = $db->query("SELECT * FROM apple");
							while ($jml = $jml_data->fetch_object()) {
								$jml_data_a[] = $jml;
							}
							$itung = count($jml_data_a);

							 ?>

								<tr>
									<td class="column1" style="color:#ff0000;">Jumlah</td>
									<td class="column1" style="color:#ff0000;"><?php echo $sum; ?></td>
									<td class="column2" style="color:#ff0000;"><?php echo $ang; ?></td>
									<td class="column2" style="color:#ff0000;"><?php echo $hasilxkaliy; ?></td>
									<td class="column2" style="color:#ff0000;"><?php echo $totalpangkatdua; ?></td>
									
								</tr>

								<tr>
									<td align="center" colspan="5" ><p align="center">a =
									<?php 
									$a = (($sum * $totalpangkatdua) - ($ang * $hasilxkaliy)) / (($itung * $totalpangkatdua) - ($ang * $ang));
									echo round($a, 2);

									 ?></p>
										
									</td>
								</tr>

								<tr>
									<td align="center" colspan="5"><p align="center">b =
									<?php 
									$hasil_b = (($itung * $hasilxkaliy) - ($ang * $sum)) / (($itung * $totalpangkatdua) - ($ang * $ang));
									echo round($hasil_b,2 );
									 ?></p>
										
									</td>
								</tr>

								<tr>
									<td align="center" colspan="5"><p align="center">Y =
									<?php
										$jmlx = $x - 1;
										$hasil_y = (($a) + ($hasil_b * $jmlx++	));
										echo round($hasil_y, 2);
									 ?></p>
										
									</td>
								</tr>


								<?php 
										if(isset($_POST['prediksi'])){
											
											$tahun = $_POST['tahun'];

											if(!isset($tahun)){
												echo "<script> alert('Masukkan Tahun');</script>";
												
											} else{

											$ex = $x-1;
											$prediksi = ($a) + ($hasil_b * ($ex  + $tahun));
											
											}
											


										}


								 ?>

								 <?php if(isset($_POST['prediksi'])) : ?>

								 <tr>
								 	<td align="center" colspan="5" style="background-color:#060033; color:white"> <p align="center">Peramalan Untuk <?php echo $tahun ?> Tahun Berikutnya adalah = <?php echo round($prediksi, 2) ?> </p> </td>
								 </tr>

								<?php else : ?>



								<?php endif; ?>


						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div style="text-align: center; margin-bottom: 30px;">
	<form action="" method="POST">
	<input type="hidden" id="demo" name="data" value=""></input>

									<div style="text-align:center; margin-bottom:20px">
										<p style="display:inline">Prediksi Untuk </p>
										<select	name="tahun" style="display:inline; color:#000; width: 130px ">
											<option value="Pilih Tahun" selected disabled>Pilih Tahun</option>
											<option value="1">1 Tahun</option>
											<option value="2">2 Tahun</option>
											<option value="3">3 Tahun</option>
											<option value="4">4 Tahun</option>
											<option value="5">5 Tahun</option>
											<option value="6">6 Tahun</option>
											<option value="7">7 Tahun</option>
											<option value="8">8 Tahun</option>
											<option value="9">9 Tahun</option>
											<option value="10">10 Tahun</option>
										</select>
										<p style="display:inline">Berikutnya...</p>
									</div>
	<input type="submit" class=" btn btn-primary" onclick="show()" name="input" value="Input Data"></input>
	<input type="submit" class="btn btn-primary " name="prediksi" value="Prediksi Tahun Berikutnya"></input>
	</form>
	</div>

<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>