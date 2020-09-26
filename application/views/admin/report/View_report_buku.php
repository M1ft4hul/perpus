<!--css khusus halaman ini -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.css">


<!--modal dialog untuk hapus -->

<!--content -->
<div class="box box-solid box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-book"></i> Daftar Pemasokan Buku</h3>
    <div class="box-tools pull-right">

    </div><!-- /.box-tools -->

  </div><!-- /.box-header -->
  <div class="box-body">
    <div class="form-group"></div>
    <table id="table-buku1" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th width="5%">No</th>
          <th>ISBN</th>
          <th>Judul Buku</th>
          <th>Kategori</th>
          <th>Penerbit</th>
          <th>Pengarang</th>
          <th>No. Rak</th>
          <th>Tahun Terbit</th>
          <th>Total Stok</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        foreach ($data_buku->result_array() as $op) {
          ?>
          <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $op['ISBN']; ?></td>
            <td><?php echo $op['judul']; ?></td>
            <td><?php $kategori = $op['id_kategori'];
                  foreach ($data_kategori->result_array() as $op2) {
                    if ($op2['id_kategori'] == $kategori) {
                      echo $op2['kategori'];
                    }
                  } ?></td>
            <td><?php $penerbit = $op['id_penerbit'];
                  foreach ($data_penerbit->result_array() as $op2) {
                    if ($op2['id_penerbit'] == $penerbit) {
                      echo $op2['nama_penerbit'];
                    }
                  } ?></td>
            <td><?php $pengarang = $op['id_pengarang'];
                  foreach ($data_pengarang->result_array() as $op2) {
                    if ($op2['id_pengarang'] == $pengarang) {
                      echo $op2['nama_pengarang'];
                    }
                  } ?></td>
            <td><?php $no_rak = $op['no_rak'];
                  foreach ($data_rak->result_array() as $op2) {
                    if ($op2['no_rak'] == $no_rak) {
                      echo $op2['nama_rak'];
                    }
                  } ?></td>
            <td><?php echo $op['thn_terbit']; ?></td>
            <td><?php echo $op['stok']; ?></td>
            <td><?php echo $op['ket']; ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    <div>
      <h3><a href="<?php echo base_url(); ?>admin/report/pdfkan" class="btn btn-warning" role="button" data-toggle="tooltip"><i class="fa fa-print"></i> Print PDF</a></h3>
    </div>
    <div>
      <h3><a href="<?php echo base_url(); ?>admin/report/excel" class="btn btn-danger" role="button" data-toggle="tooltip"><i class="fa fa-print"></i> Export Excel</a></h3>
    </div>

  </div>
  <div class="box-footer">
    Menampilkan daftar buku
  </div><!-- box-footer -->
</div><!-- /.box -->