<?php
if($readAccess)
{
	echo "
	<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
		<thead>
			<tr>
				<th width='4%'>No.</th>
				<th>Tanggal</th>
				<th>Keterangan</th>
				<th>Keluar Blok</th>
				<th>Masuk Blok</th>				
				<th>Sisa Blok</th>
				<th>Jum. Lembar</th>
				<th>Nilai Uang (Rp.)</th>
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
				<td align='center'>".$tgl_persediaan."</td>
				<td>".$keterangan."</td>
				<td align='right'>".number_format($blok_keluar)."</td>
				<td align='right'>".number_format($blok_masuk)."</td>				
				<td align='right'>".number_format($sisa_blok)."</td>
				<td align='right'>".number_format($jumlah_lembar)."</td>
				<td align='right'>".number_format($nilai_uang)."</td>
				
				<td align='center'>";
					if($no_persediaan==1)
					{
						echo "<font color='red'>can't modified</font>";
					}else{
						if($editAccess)
			                echo "<a href='ajax/".$fn."/form_content.php?act=edit&id=".$id_persediaan."&fk_permohonan=".$fk_permohonan."&fn=".$fn."&men_id=".$men_id."' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#remoteModal'>";
			            else
			            	echo "<a href='javascript:;' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk merubah data !');\">";

		            	echo "<i class='fa fa-edit'></i></a>&nbsp;";

		            	if($deleteAccess)
		                	echo "<a title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
		                else
		                	echo "<a href='javascript:;' title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk menghapus data !');\">";

		                echo "
		                <input type='hidden' id='ajax-req-dt' name='id' value='".$id_persediaan."'/>
		                <input type='hidden' id='ajax-req-dt' name='fk_permohonan' value='".$fk_permohonan."'/>
		                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
		                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
		                <input type='hidden' id='ajax-req-dt' name='men_id' value='".$men_id."'/>
		                <i class='fa fa-trash-o'></i></a>";
		            }
	            echo "</td>
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