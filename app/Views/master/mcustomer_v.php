<?php echo $this->include("template/header_v"); ?>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <?php if (!isset($_GET['user_id']) && !isset($_POST['new']) && !isset($_POST['edit'])) {
                            $coltitle = "col-md-10";
                        } else {
                            $coltitle = "col-md-8";
                        } ?>
                        <div class="<?= $coltitle; ?>">
                            <h4 class="card-title"></h4>
                            <!-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6> -->
                        </div>
                       
                        <?php if (!isset($_POST['new']) && !isset($_POST['edit']) && !isset($_GET['report'])) { ?>
                            <?php if (isset($_GET["user_id"])) { ?>
                                <form action="<?= site_url("user"); ?>" method="get" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button class="btn btn-warning btn-block btn-lg" value="OK" style="">Back</button>
                                    </h1>
                                </form>
                            <?php } ?>
                            <?php 
                            if (
                                (
                                    isset(session()->get("position_administrator")[0][0]) 
                                    && (
                                        session()->get("position_administrator") == "1" 
                                        || session()->get("position_administrator") == "2"
                                    )
                                ) ||
                                (
                                    isset(session()->get("halaman")['10']['act_create']) 
                                    && session()->get("halaman")['10']['act_create'] == "1"
                                )
                            ) { ?>
                            <form method="post" class="col-md-2">
                                <h1 class="page-header col-md-12">
                                    <button name="new" class="btn btn-info btn-block btn-lg" value="OK" style="">New</button>
                                    <input type="hidden" name="customer_id" />
                                </h1>
                            </form>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $namabutton = 'name="change"';
                                $judul = "Update Kategori";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Tambah Kategori";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">                                                     
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="customer_name">Nama Customer:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" autofocus class="form-control" id="customer_name" name="customer_name" placeholder="" value="<?= $customer_name; ?>">
                                    </div>
                                </div>                                                      
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="customer_address">Alamat:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" autofocus class="form-control" id="customer_address" name="customer_address" placeholder="" value="<?= $customer_address; ?>">
                                    </div>
                                </div>                                                      
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="customer_ktp">KTP:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" autofocus class="form-control" id="customer_ktp" name="customer_ktp" placeholder="" value="<?= $customer_ktp; ?>">
                                    </div>
                                </div>                                                      
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="customer_wa">Whatsapp:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" autofocus class="form-control" id="customer_wa" name="customer_wa" placeholder="" value="<?= $customer_wa; ?>">
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="customer_picture">Bukti (KTP/SIM):</label>
                                    <div class="col-sm-10">
                                        <input type="file" autofocus class="form-control" id="customer_picture" name="customer_picture" placeholder="" value="<?= $customer_picture; ?>">
                                        <?php if($customer_picture!=""){$user_image="images/customer_picture/".$customer_picture;}else{$user_image="images/customer_picture/no_image.png";}?>
                                          <img id="customer_picture_image" width="100" height="100" src="<?=base_url($user_image);?>"/>
                                          <script>
                                            function readURL(input) {
                                                if (input.files && input.files[0]) {
                                                    var reader = new FileReader();
                                        
                                                    reader.onload = function (e) {
                                                        $('#customer_picture_image').attr('src', e.target.result);
                                                    }
                                        
                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                            }
                                        
                                            $("#customer_picture").change(function () {
                                                readURL(this);
                                            });
                                          </script>
                                    </div>
                                </div>

                                <input type="hidden" name="customer_id" value="<?= $customer_id; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <button class="btn btn-warning col-md-offset-1 col-md-5" onClick="location.href=<?= site_url("customer"); ?>">Back</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
                        <?php if ($message != "") { ?>
                            <div class="alert alert-info alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong><?= $message; ?></strong>
                            </div>
                        <?php } ?>

                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                                <thead class="">
                                    <tr>
                                        <?php if (!isset($_GET["report"])) { ?>
                                            <th>Action</th>
                                        <?php } ?>
                                        <th>No.</th>
                                        <th>Code</th>
                                        <th>Customer</th>
                                        <th>Address</th>
                                        <th>KTP</th>
                                        <th>Whatsapp</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $usr = $this->db
                                        ->table("customer")
                                        ->orderBy("customer_name", "ASC")
                                        ->get();
                                    //echo $this->db->getLastquery();
                                    $no = 1;
                                    foreach ($usr->getResult() as $usr) { ?>
                                        <tr>
                                            <?php if (!isset($_GET["report"])) { ?>
                                                <td style="padding-left:0px; padding-right:0px;">
                                                    <?php 
                                                    if (
                                                        (
                                                            isset(session()->get("position_administrator")[0][0]) 
                                                            && (
                                                                session()->get("position_administrator") == "1" 
                                                                || session()->get("position_administrator") == "2"
                                                            )
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['10']['act_update']) 
                                                            && session()->get("halaman")['10']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                        <input type="hidden" name="customer_id" value="<?= $usr->customer_id; ?>" />
                                                    </form>
                                                    <?php }?>
                                                    
                                                    <?php 
                                                    if (
                                                        (
                                                            isset(session()->get("position_administrator")[0][0]) 
                                                            && (
                                                                session()->get("position_administrator") == "1" 
                                                                || session()->get("position_administrator") == "2"
                                                            )
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['10']['act_delete']) 
                                                            && session()->get("halaman")['10']['act_delete'] == "1"
                                                        )
                                                    ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                        <input type="hidden" name="customer_id" value="<?= $usr->customer_id; ?>" />
                                                    </form>
                                                    <?php }?>
                                                </td>
                                            <?php } ?>
                                            <td><?= $no++; ?></td>
                                            <td><?= $usr->customer_code; ?></td>
                                            <td><?= $usr->customer_name; ?></td>
                                            <td><?= $usr->customer_address; ?></td>
                                            <td><?= $usr->customer_ktp; ?></td>
                                            <td><?= $usr->customer_wa; ?></td>
                                            <td><a target="_blank" href="<?=base_url("images/customer_picture/".$usr->customer_picture); ?>" class="fa fa-download"></a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "Master Customer";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>