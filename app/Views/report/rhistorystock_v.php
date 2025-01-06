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

                    if (isset($_GET["productid"]) && $_GET["productid"] > 0) {
                        $productid = $_GET["productid"];
                    } else {
                        $productid = 0;
                    }

                    if (isset($_GET["productidn"])) {
                        $productidn = $_GET["productidn"];
                    } else {
                        $productidn = "";
                    }

                    ?>




                    <?php if ($message != "") { ?>
                        <div class="alert alert-info alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong><?= $message; ?></strong>
                        </div>
                    <?php } ?>

                    <div class="table-responsive m-t-40">
                        <?php if (isset($_GET['productid']) && $_GET['productid'] > 0) { ?>
                            <div class="row">
                                <div class="bold text-primary  col-md-12">Nama Product : <span id="namaproductid" class=""><?= $productidn; ?></span></div>
                                <!-- <div class="bold col-md-6 text-right">Stok Terakhir Periode Ini : <span id="stok" class=""></span></div> -->
                            </div>
                        <?php } ?>
                        <table id="example231" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                            <thead class="">
                                <tr>
                                    <th>No.</th>
                                    <th>Product</th>
                                    <th>Plat No.</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sewa = $this->db
                                    ->table("sewa")
                                    ->groupStart() // Menggunakan `groupStart` sesuai konvensi CodeIgniter 4
                                    ->where("sewa_diambil <=", date("Y-m-d"))
                                    ->where("sewa_rpulang >=", date("Y-m-d"))
                                    ->where("sewa_spulang IS NULL") // Gunakan format IS NULL
                                    ->groupEnd()
                                    ->orgroupStart() 
                                    ->where("sewa_rpulang <", date("Y-m-d"))
                                    ->where("sewa_spulang IS NULL")
                                    ->groupEnd()
                                    ->get();
echo $this->db->getLastQuery();
                                $mobil = [];
                                foreach ($sewa->getResult() as $row) { // Gunakan $row agar variabel $sewa tidak tertimpa
                                    $mobil[] = $row->product_id;
                                }

                                $product = $this->db
                                    ->table("product")
                                    ->orderBy("product_status", "ASC")
                                    ->orderBy("product_name", "ASC")
                                    ->get();
                                // echo $this->db->getLastquery();
                                $no = 1;
                                foreach ($product->getResult() as $usr) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no; ?></td>
                                        <td class="text-left"><?= $usr->product_name; ?></td>
                                        <td class="text-center"><span class="text-primary"><?= $usr->product_ube; ?></span></td>
                                        <?php
                                        if (in_array($usr->product_id, $mobil)) {
                                            $alstock = "danger";
                                            $salstock = "Not Ready";
                                        } else {
                                            $alstock = "success";
                                            $salstock = "Ready";
                                        }
                                        ?>
                                        <td class="text-<?= $alstock; ?>">
                                            <?= $salstock; ?>
                                        </td>
                                    </tr>
                                <?php $no++;
                                }    ?>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var title = "History Stok";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>