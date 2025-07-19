<?php
if($readAccess)
{

echo "
	<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
		<thead>
			<tr>
				<th width='4%'>No.</th>
				<th>No. BA</th>
				<th>Pihak Kesatu</th>
				<th>Pihak Kedua</th>
				<th>Tgl. Berita Acara</th>
				<th>Jml. Perforasi</th>
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
				<tr>
				<td align='center'>".$no."</td>
				<td align='center'>".$no_berita_acara."</td>
				<td>".$nm_pihak_kesatu."<br />
					".$nip_pihak_kesatu."<br />
					".$jbt_pihak_kesatu."
				</td>
				<td>".$nm_pihak_kedua."<br />
					".$nip_pihak_kedua."<br />
					".$jbt_pihak_kedua."
				</td>
				<td align='center'>".$tgl_berita_acara."</td>
				<td align='right'>".$jml_perforasi."</td>
				<td align='center'>";
					
					if($editAccess)
		                echo "<a href='ajax/".$fn."/form_content.php?act=edit&id=".$id_berita_acara."&tgl_awal=".$tgl_awal."&tgl_akhir=".$tgl_akhir."&cond_type=".$cond_type."&fn=".$fn."&men_id=".$men_id."' 
		            				title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#remoteModal'>";
		            else
		            	echo "<a href='javascript:;' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk merubah data !');\">";			        
		            
		            echo "<i class='fa fa-edit'></i></a>&nbsp";
		            
	                if($deleteAccess)
	                	echo "<a title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
	                else
	                	echo "<a href='javascript:;' title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk menghapus data !');\">";

	                echo "
	                <input type='hidden' id='ajax-req-dt' name='id' value='".$id_berita_acara."'/>
	                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
	                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
	                <input type='hidden' id='ajax-req-dt' name='men_id' value='".$men_id."'/>
	                <input type='hidden' id='ajax-req-dt' name='cond_type' value='".$cond_type."'/>
	                <input type='hidden' id='ajax-req-dt' name='tgl_awal' value='".$tgl_awal."'/>
	                <input type='hidden' id='ajax-req-dt' name='tgl_akhir' value='".$tgl_akhir."'/>
	                <i class='fa fa-trash-o'></i></a>
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