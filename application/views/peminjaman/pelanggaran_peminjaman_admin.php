    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Validasi Pelanggaran Peminjaman</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Peminjaman</li>
                <li class="breadcrumb-item active">Pelanggaran Peminjaman</li>
              </ol>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      <?php if ($this->session->flashdata('success')) : ?>
        <input type="hidden" class="toasterSuccess" value="<?= $this->session->flashdata('success')  ?>">
      <?php else : ?>
        <input type="hidden" class="toasterDanger" value="<?= $this->session->flashdata('danger')  ?>">
      <?php endif; ?>
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Data Pelanggaran Peminjaman</h3>

                  <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                      <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Register</th>
                        <th>Judul</th>
                        <th>Peminjam</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Keterangan</th>
                        <th>Status Pelanggaran</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i = 1;
                      foreach ($buku as $b) : ?>
                        <tr>
                          <td><?= $i++ ?></td>
                          <td><?= $b['register'] ?></td>
                          <td><?= $b['judul_buku'] ?></td>
                          <td>Telat</td>
                          <td><?= $b['nama'] ?></td>
                          <td><?= $b['denda'] ?></td>
                          <?php if ($b['status_sirkulasi'] == 4) { ?>
                            <td><span class="badge bg-danger">Belum Tuntas</span></td>
                            <td><a href="../peminjaman/validasiPelanggaran/<?= $b['id_sirkulasi']?>" class="btn btn-success"><i class="fas fa-check"></i></a></td>
                          <?php } else if ($b['status_sirkulasi'] == 9) { ?>
                            <td><span class="badge bg-primary">Tuntas</span></td>
                            <td></td>
                          <?php } ?>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!-- /.row -->


        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-default">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Perpanjangan Peminjaman</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="kode_pinjam">Kode Peminjaman</label>
              <select name="" id="kode_pinjam" class="form-control">
                    <option value="">A21010</option>
                    <option value="">A21011</option>
                </select>
            </div>
            <div class="form-group">
              <label for="buku">Nama Buku</label>
              <input type="text" name="" id="buku" class="form-control" readonly>
            </div>
            <div class="form-group">
              <label for="waktu">Waktu Akhir Peminjaman</label>
              <input type="date" name="" id="waktu" class="form-control" readonly>
            </div>
            <div class="form-group">
              <label for="panjang">Jangka Waktu Perpanjangan</label>
              <input type="date" name="" id="panjang" class="form-control">
            </div>

          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-success">Ajukan Perpanjangan</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <!-- /.content-wrapper -->