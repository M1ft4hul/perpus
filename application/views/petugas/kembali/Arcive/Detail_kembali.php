<!--css khusus halaman ini -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.css">


<!--modal dialog untuk hapus -->
  <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Konfirmasi Hapus</h4>
                </div>
            
                <div class="modal-body">
                    <p>Anda akan menghapus Data Buku beserta detail stok buku ini</p>
                    <p><strong>Peringatan</strong>  Setelah data dihapus, data tidak dapat dikembalikan!</p>
                    <br />
                    <p>Ingin melanjutkan menghapus?</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<!--content -->
<div class="box box-solid box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-book"></i> Daftar Buku Kembali</h3>
    <div class="box-tools pull-right">
    
    </div><!-- /.box-tools -->

  </div><!-- /.box-header -->
   <div class="box-body">
   <!--div class="btn-group"><a href="<?php echo base_url(); ?>admin/Kembali/edit"  class="btn btn-success" role="button" data-toggle="tooltip" title="Kembalikan"><i class="fa fa-plus"></i> Kembalikan Buku</a></div-->
   <div class="form-group"></div>
   <table id="" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th class="center"> <i class="glyphicon glyphicon-plus"></i></th>
                <th>ID Detail Pinjam</th>
                <th>Judul</th>
                <th>No Buku</th>
                <th>Pilihan</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No</th>
                <th class="center"> <i class="glyphicon glyphicon-plus"></i></th>
                <th>ID Detail Pinjam</th>
                <th>Judul</th>
                <th>No Buku</th>
                <th>Pilihan</th>
            </tr>
        </tfoot>
        <tbody>
         <?php
  $no = 1;
    foreach($data_detail->result_array() as $op)
    {
    ?>
            <tr>
                <td><?php echo $no++ ;?></td>
                <td class="details-control"><i class="btn btn-box-tool" data-toggle="tooltip" title="Tampilkan Detail"><i class="glyphicon glyphicon-plus"></i></i>
                </td>
                <td><?php echo $op['id_pinjam'];?></td>
                <td><?php $buku=$op['id_buku'];
                    foreach ($data_buku ->result_array()  as $op2) {
                      if($op2['id_buku']==$buku){
                          echo $op2['judul'];
                      }
                    }?></td>
                <td><?php $buku=$op['id_buku'];
                    foreach ($data_detail ->result_array()  as $op2) {
                      if($op2['id_buku']==$buku){
                          echo $op2['no_buku'];
                      }
                    }?></td>
                <td>
                <?php echo 
                    '<div class="btn-group">
                    <a href="'.base_url().'admin/Kembali/detail_kembali/?id_pinjam='.$op['id_pinjam'].'" class="btn btn-info" role="button" data-toggle="tooltip" title="Detail Pinjam"><i class="fa fa-list"></i></a>
                    </div>
                     <a href="'.base_url().'admin/Kembali/kembalikan/?id_pinjam='.$op['id_pinjam'].'" class="btn btn-warning" role="button" data-toggle="tooltip" title="Kembalikan"><i class="fa fa-share"></i></a>
                </td>
            </tr>';?>
<?php
    }
  ?>            
         </tbody>
    </table>
  </div>
  <div class="box-footer">
    Menampilkan daftar buku kembali, untuk melihat <strong> detail </strong> dan <strong> Kembalikan </strong> klik tombol pada kolom pilihan.
  </div><!-- box-footer -->
</div><!-- /.box -->


      
  