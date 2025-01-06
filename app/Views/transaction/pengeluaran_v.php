<?php echo $this->include("template/header_v"); ?>
<style>
    .well {
        background-color: rgba(0, 0, 0, 0.1);
        padding: 10px;
        margin: 1px;
        border-radius: 5px;
    }

    .wellisi {
        /* border: grey solid 1px; */
        padding: 5px;
        border-radius: 5px;
    }

    .welltr {
        background-color: rgba(255, 255, 255, 0.8);
        margin: 5px 0 5px 0;
        padding: 5px;
    }
</style>

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
                                        <input type="hidden" name="pengeluaran_id" />
                                    </h1>
                                </form>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $namabutton = 'name="change"';
                                $judul = "Update Pengeluaran";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Tambah Pengeluaran";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="pengeluaran_date">Date:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control date" id="pengeluaran_date" name="pengeluaran_date" placeholder="" value="<?= $pengeluaran_date; ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="pengeluaran_name">Keperluan:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control" id="pengeluaran_name" name="pengeluaran_name" placeholder="" value="<?= $pengeluaran_name; ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="pengeluaran_description">Description:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control" id="pengeluaran_description" name="pengeluaran_description" placeholder="" value="<?= $pengeluaran_description; ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="pengeluaran_nominal">Nominal:</label>
                                    <div class="col-sm-10">
                                        <input required type="number" min="0" class="form-control" id="pengeluaran_nominal" name="pengeluaran_nominal" placeholder="" value="<?= $pengeluaran_nominal; ?>">
                                    </div>
                                </div>

                                <input type="hidden" name="pengeluaran_id" value="<?= $pengeluaran_id; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <button type="button" class="btn btn-warning col-md-offset-1 col-md-5" onClick="location.href='<?= base_url("pengeluaran"); ?>'">Back</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
                        <?php
                        if (isset($_GET["from"]) && $_GET["from"] != "") {
                            $from = $_GET["from"];
                        } else {
                            $from = date("Y-m-d");
                        }

                        if (isset($_GET["to"]) && $_GET["to"] != "") {
                            $to = $_GET["to"];
                        } else {
                            $to = date("Y-m-d");
                        }
                        ?>
                        <form class="form-inline">
                            <label for="from">Dari:</label>&nbsp;
                            <div class="col-2">
                                <input type="date" id="from" name="from" class="form-control" value="<?= $from; ?>">
                            </div>&nbsp;

                            <label for="to">Ke:</label>&nbsp;
                            <div class="col-2">
                                <input type="date" id="to" name="to" class="form-control" value="<?= $to; ?>">
                            </div>&nbsp;
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
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
                                        <th>Date</th>
                                        <th>Keperluan</th>
                                        <th>Deskripsi</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $usr = $this->db
                                        ->table("pengeluaran")
                                        ->where("pengeluaran_date >=", $from)
                                        ->where("pengeluaran_date <=", $to)
                                        ->orderBy("pengeluaran_id", "ASC")
                                        ->get();
                                    //echo $this->db->getLastquery();
                                    $no = 1;
                                    foreach ($usr->getResult() as $usr) {
                                        $ok = array("", "OK");
                                    ?>
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
                                                            <input type="hidden" name="pengeluaran_id" value="<?= $usr->pengeluaran_id; ?>" />
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
                                                            isset(session()->get("halaman")['10']['act_delete'])
                                                            && session()->get("halaman")['10']['act_delete'] == "1"
                                                        )
                                                    ) { ?>
                                                        <form method="post" class="btn-action" style="">
                                                            <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                            <input type="hidden" name="pengeluaran_id" value="<?= $usr->pengeluaran_id; ?>" />
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                            <td><?= $usr->pengeluaran_date; ?></td>
                                            <td><?= $usr->pengeluaran_name; ?></td>
                                            <td><?= $usr->pengeluaran_description; ?></td>
                                            <td><?= number_format($usr->pengeluaran_nominal,0,",","."); ?></td>
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
    var title = "Pengeluaran";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>