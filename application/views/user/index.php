<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Data</h6>
                </div>
                <div class="card-body">
                    <form action="" id="userForm" name="userForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <label class="form-label">Nama User</label>
                        <input type="text" class="form-control" id="nama_user" name="nama_user" required>
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <label class="form-label">Password</label>
                        <input type="text" class="form-control" id="password" name="password" required>
                        <label class="form-label">Level</label>
                        <select name="level" id="level" class="form-control">
                            <option value="" disabled selected>pilih level</option>
                            <?php 
                            foreach ($level as $row) {?>
                                <option value="<?= $row->id ?>"><?= $row->level_name ?></option>
                            <?php }
                            ?>
                        </select>
                        <button type="submit" class="btn btn-primary" id="save-data" style="margin-top: 10px;"><i class="fa fa-save"></i> | Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="div-table-user"></div>
                    </div>
                </div>
            </div>
        </div>


    </div>


</div>
<!-- /.container-fluid -->