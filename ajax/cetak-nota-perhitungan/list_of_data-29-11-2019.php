<?php
if($readAccess)
{
	echo "
	<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
		<thead>
			<tr>
				<th width='4%'>No.</th>
				<th>Kode Rekening</th>
				<th>Jenis Retribusi</th>
				<th>No.SKRD/<br />No. Nota</th>
				<th>Masa Retribusi</th>
				<th>Dasar Pengenaan Pajak</th>			
				<th>Total Bayar</th>
				<th width='8%'>Aksi</th>
			</tr>
		</thead>
		<tbody>";
			
			$no=0;
			while($row = $list_of_data->FetchRow())
			{
				foreach($row as $key => $val){
	                  $key=strtolower($key);
	                  $$key=$val;
	            }	            

				$no++;
				echo "
				<tr><td align='center'>".$no."</td>
				<td align='center'>".$kd_rekening."</td>
				<td>".$nm_rekening."</td>
				<td align='center'>".$no_skrd."/".$no_nota_perhitungan."</td>
				<td>".get_monthName($bln_retribusi)." ".$thn_retribusi."</td>
				<td>".$dasar_pengenaan."</td>
				<td align='right'>".number_format($total_retribusi)."</td>
				<td align='center'>";
					$filename1 = ($imb=='0'?'print-preview1.php':'print-preview2.php');
					$filename2 = ($imb=='0'?'nota-perhitungan-pdf1.php':'nota-perhitungan-pdf2.php');
	            	echo "
	            	<a href='ajax/".$fn."/".$filename1."?id=".$id_nota."&mengetahui=".$mengetahui."&diperiksa=".$diperiksa."' class='btn btn-xs btn-default' target='_blank'>
	                	<i class='fa fa-print'></i>
	                </a>
	                <a href='ajax/".$fn."/".$filename2."?id=".$id_nota."&mengetahui=".$mengetahui."&diperiksa=".$diperiksa."' class='btn btn-xs btn-default' target='_blank'>
	                	<i class='fa fa-file-pdf-o'></i>
	                </a>
	            </td>
				</tr>";
			}
			
		echo "</tbody>
	</table>";
}
else
{
	echo "
	<div class='alert alert-warning fade in'>
		<i class='fa-fw fa fa-warning'></i>
		Anda tidak memiliki hak akses untuk melihat data !
	</div>";
}