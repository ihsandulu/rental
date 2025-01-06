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

    .judul {
        text-align: left !important;
        font-weight: bold;
        font-size: 20px;
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

                    </div>

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
                        <div class="judul">Laba : <span id="laba"></span></div>
                        <table id="" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                            <thead class="">
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice No.</th>
                                    <th>Customer</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $usr = $this->db
                                    ->table("sewa")
                                    ->join("customer", "customer.customer_id = sewa.customer_id", "left")
                                    ->join(
                                        "(SELECT SUM(sewad_nominal) as nominal, sewa_id FROM sewad GROUP BY sewa_id) as sewad_total",
                                        "sewad_total.sewa_id = sewa.sewa_id",
                                        "left"
                                    )
                                    ->where("sewa_date >=", $from)
                                    ->where("sewa_date <=", $to)
                                    ->orderBy("sewa.sewa_id", "ASC")
                                    ->get();

                                //echo $this->db->getLastquery();
                                $no = 1;
                                $laba = 0;
                                foreach ($usr->getResult() as $usr) {
                                    $ok = array("", "OK");
                                    $laba += $usr->nominal;
                                ?>
                                    <tr>
                                        <td><?= $usr->sewa_date; ?></td>
                                        <td><?= $usr->sewa_no; ?></td>
                                        <td><?= $usr->customer_name; ?></td>
                                        <td><?= number_format($usr->nominal, 0, ",", "."); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <script>
                            $(document).ready(function() {
                                $("#laba").text("Rp <?= number_format($laba, 0, ",", "."); ?>,-");
                            });
                        </script>
                    </div>

                    <div class="table-responsive m-t-40">
                        <div class="judul">Rugi : <span id="rugi"></span></div>
                        <table id="" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                            <thead class="">
                                <tr>
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
                                $rugi = 0;
                                foreach ($usr->getResult() as $usr) {
                                    $ok = array("", "OK");
                                    $rugi += $usr->pengeluaran_nominal;
                                ?>
                                    <tr>
                                        <td><?= $usr->pengeluaran_date; ?></td>
                                        <td><?= $usr->pengeluaran_name; ?></td>
                                        <td><?= $usr->pengeluaran_description; ?></td>
                                        <td><?= number_format($usr->pengeluaran_nominal, 0, ",", "."); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <script>
                            $(document).ready(function() {
                                $("#rugi").text("Rp <?= number_format($rugi, 0, ",", "."); ?>,-");
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "Laba & Rugi : Rp <?=number_format($laba-$rugi, 0, ",", ".");?>,-";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>