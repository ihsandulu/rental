<?php echo $this->include("template/header_v"); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        z-index: 1;
    }

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

    .tjudul {
        margin-bottom: 10px !important;
        font-weight: bold;
        text-decoration: underline;
        color: black;
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

                        <?php if (!isset($_POST['new']) && !isset($_POST['edit']) && !isset($_GET['report']) && !isset($_POST['check'])) { ?>
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
                                        <input type="hidden" name="sewa_id" />
                                    </h1>
                                </form>
                            <?php } ?>
                        <?php }

                        if (isset($_POST['check'])) { ?>
                            <form action="<?= base_url("sewa"); ?>" method="get" class="col-md-2">
                                <h1 class="page-header col-md-12">
                                    <button class="btn btn-warning btn-block btn-lg" value="OK" style="">Back</button>
                                </h1>
                            </form>
                        <?php } ?>
                    </div>

                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $namabutton = 'name="change"';
                                $judul = "Update Sewa";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Tambah Sewa";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="customer_id">Customer:</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select" id="customer_id" name="customer_id">
                                            <option value="" <?= ($customer_id == "") ? "selected" : ""; ?>>Pilih Customer</option>
                                            <?php $customer = $this->db->table("customer")->orderBy("customer_name")->get();
                                            foreach ($customer->getResult() as $customer) { ?>
                                                <option value="<?= $customer->customer_id; ?>" <?= ($customer_id == $customer->customer_id) ? "selected" : ""; ?>><?= $customer->customer_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="sewa_driver">Mobil:</label>
                                    <div class="col-sm-12">
                                        <div class="row well">
                                            <div class="col-sm-5 tjudul">Product</div>
                                            <div class="col-sm-2 tjudul" style="">Tgl Ambil</div>
                                            <div class="col-sm-2 tjudul">Tgl Kembali</div>
                                            <div class="col-sm-3 tjudul">Driver</div>


                                            <div class="col-sm-5 p-1">
                                                <select onchange="isinominal()" class="form-control select" id="product_id" name="product_id">
                                                    <option value="">Pilih Product</option>
                                                    <?php $product = $this->db->table("product")->orderBy("product_name")->get();
                                                    foreach ($product->getResult() as $product) { ?>
                                                        <option data-nominal="<?= $product->product_sell; ?>" value="<?= $product->product_id; ?>" <?= ($product_id == $product->product_id) ? "selected" : ""; ?>>
                                                            <?= $product->product_ube; ?> : <?= $product->product_name; ?> (<?= number_format($product->product_sell, 0, ",", "."); ?>)
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <input id="sewa_nominal" type="hidden" />
                                                <script>
                                                    function isinominal() {
                                                        let sewa_nominal = $("#product_id option:selected").data("nominal");
                                                        $("#sewa_nominal").val(sewa_nominal);
                                                    }
                                                </script>
                                            </div>
                                            <?php
                                            if($sewa_diambil!=""){
                                                $sewa_diambil= $sewa_diambil;
                                            }else{
                                                $sewa_diambil= date("Y-m-d");
                                            }
                                            
                                            if($sewa_rpulang!=""){
                                                $sewa_rpulang= $sewa_rpulang;
                                            }else{
                                                $sewa_rpulang= date("Y-m-d");
                                            }
                                           
                                            ?>
                                            <div class="col-sm-2">
                                                <input required type="text" class="form-control date" id="sewa_diambil" name="sewa_diambil" placeholder="Tgl Ambil" value="<?= $sewa_diambil; ?>">
                                            </div>
                                            <div class="col-sm-2">
                                                <input required type="text" class="form-control date" id="sewa_rpulang" name="sewa_rpulang" placeholder="Tgl Kembali" value="<?= $sewa_rpulang; ?>">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="sewa_driver" name="sewa_driver" placeholder="Driver" value="<?= $sewa_driver; ?>">
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="sewa_id" name="sewa_id" value="<?= ($sewa_id == "") ? date("ymdHis") : $sewa_id; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <button type="button" class="btn btn-warning col-md-offset-1 col-md-5" onClick="location.href='<?= base_url("sewa"); ?>'">Back</button>
                                    </div>
                                </div>
                                <hr />
                                <div class="" style="padding: 0px 20px 20px 20px;">
                                    <div class="lead">
                                        <h3>(<span class="text-primary">Optional</span>) Pilih Pembatasan Jalur</h3>
                                    </div>
                                    <div id="map"></div>
                                </div>
                                <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

                                <?php
                                $centerLat = -6.200000;
                                $centerLng = 106.816666;
                                if (isset($_POST["edit"])) {
                                    $latlon = array();
                                    $marker = $this->db->table("marker")->where("sewa_id", $sewa_id)->orderBy("marker_id", "ASC")->get();
                                    // echo $this->db->getLastQuery();die;
                                    foreach ($marker->getResult() as $m) {
                                        $lat = $m->marker_latitude;
                                        $lng = $m->marker_longitude;
                                        $latlon[] = ["lat" => $m->marker_latitude, "lng" => $m->marker_longitude];
                                    }
                                    // Menghitung titik tengah (center) dari array latlon
                                    $sumLat = 0;
                                    $sumLng = 0;
                                    $count = count($latlon);

                                    if ($count > 0) {
                                        // Menghitung jumlah dari semua latitude dan longitude
                                        foreach ($latlon as $point) {
                                            $sumLat += $point["lat"];
                                            $sumLng += $point["lng"];
                                        }

                                        // Menghitung rata-rata (titik tengah)
                                        $centerLat = $sumLat / $count;
                                        $centerLng = $sumLng / $count;
                                    }
                                }
                                ?>

                                <script>
                                    // Inisialisasi peta
                                    const map = L.map('map').setView([<?= $centerLat; ?>, <?= $centerLng; ?>], 8);

                                    // Menambahkan tile layer
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        maxZoom: 18,
                                    }).addTo(map);

                                    // Menyimpan semua marker dan koordinatnya
                                    const markers = [];
                                    const coordinates = [];

                                    // Menyimpan polygon area
                                    let polygon = null;

                                    // Fungsi untuk menggambar ulang polygon
                                    function drawPolygon() {
                                        if (polygon) {
                                            map.removeLayer(polygon);
                                        }
                                        if (coordinates.length > 2) {
                                            polygon = L.polygon(coordinates, {
                                                color: 'blue',
                                                fillColor: 'blue',
                                                fillOpacity: 0.3
                                            });
                                            polygon.addTo(map);
                                        }
                                    }

                                    // Fungsi untuk menambahkan marker dan menghapusnya saat klik
                                    function simpanmarker(lat, lng) {
                                        // Membuat marker baru
                                        const marker = L.marker([lat, lng], {
                                            draggable: false
                                        }).addTo(map);

                                        // Menyimpan marker dan koordinatnya
                                        markers.push(marker);
                                        coordinates.push([lat, lng]);

                                        // Event klik pada marker untuk menghapus marker
                                        marker.on('click', function() {
                                            const index = markers.indexOf(marker);

                                            if (index > -1) {
                                                // Menghapus marker dari peta dan array
                                                map.removeLayer(marker);
                                                markers.splice(index, 1);
                                                coordinates.splice(index, 1);

                                                // Menggambar ulang polygon
                                                drawPolygon();

                                                let sewa_id = $("#sewa_id").val();
                                                // Mengirim data ke server untuk menyimpan marker
                                                $.get("<?= base_url("api/markerdelete"); ?>", {
                                                    sewa_id: sewa_id,
                                                    lat: lat,
                                                    lng: lng
                                                }).done(function(data) {});
                                            }
                                        });

                                        // Menggambar ulang polygon
                                        drawPolygon();
                                    }
                                    <?php
                                    if (isset($_POST["edit"])) {
                                        foreach ($latlon as $latlonItem) { ?>
                                            simpanmarker(<?= $latlonItem['lat']; ?>, <?= $latlonItem['lng']; ?>);
                                    <?php }
                                    } ?>

                                    // Event klik pada peta untuk menambahkan marker
                                    map.on('click', function(e) {
                                        const {
                                            lat,
                                            lng
                                        } = e.latlng;

                                        simpanmarker(lat, lng);

                                        let sewa_id = $("#sewa_id").val();
                                        // Mengirim data ke server untuk menyimpan marker
                                        $.get("<?= base_url("api/masukmarker"); ?>", {
                                            sewa_id: sewa_id,
                                            lat: lat,
                                            lng: lng
                                        }).done(function(data) {});

                                    });
                                </script>




                            </form>
                        </div>
                    <?php } else if (isset($_POST['check'])) { ?>
                        <div class="">
                            <div class="lead">
                                <h3>Check Car</h3>
                            </div>
                            <div>
                                <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                                    <thead class="">
                                        <tr>
                                            <th>Product</th>
                                            <th>Pengembalian</th>
                                            <th>Tampak Muka</th>
                                            <th>Tampak Belakang</th>
                                            <th>Tampak Kanan</th>
                                            <th>Tampak Kiri</th>
                                            <th>Bensin (%)</th>
                                            <?php if (!isset($_GET["report"])) { ?>
                                                <th>Action</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $usr = $this->db
                                            ->table("sewa")
                                            ->join("product", "product.product_id=sewa.product_id", "left")
                                            ->where("sewa_id", $this->request->getPost("sewa_id"))
                                            ->orderBy("product_name", "ASC")
                                            ->get();
                                        //echo $this->db->getLastquery();
                                        $no = 1;
                                        foreach ($usr->getResult() as $usr) {
                                            $ok = array("", "OK");
                                        ?>
                                            <tr>
                                                <td><?= $usr->product_name; ?><br /><span class="text-primary"><?= $usr->product_ube; ?></span></td>
                                                <td><input id="sewa_spulang<?= $usr->sewa_id; ?>" value="<?= $usr->sewa_spulang; ?>" class="form-control date" /></td>
                                                <td><input type="checkbox" value="1" <?= ($usr->sewa_tmuka == "1") ? "checked" : ""; ?> id="sewa_tmuka<?= $usr->sewa_id; ?>" value="<?= $usr->sewa_tmuka; ?>" class="form-control" /></td>
                                                <td><input type="checkbox" value="1" <?= ($usr->sewa_tbelakang == "1") ? "checked" : ""; ?> id="sewa_tbelakang<?= $usr->sewa_id; ?>" value="<?= $usr->sewa_tbelakang; ?>" class="form-control" /></td>
                                                <td><input type="checkbox" value="1" <?= ($usr->sewa_tkanan == "1") ? "checked" : ""; ?> id="sewa_tkanan<?= $usr->sewa_id; ?>" value="<?= $usr->sewa_tkanan; ?>" class="form-control" /></td>
                                                <td><input type="checkbox" value="1" <?= ($usr->sewa_tkiri == "1") ? "checked" : ""; ?> id="sewa_tkiri<?= $usr->sewa_id; ?>" value="<?= $usr->sewa_tkiri; ?>" class="form-control" /></td>
                                                <td><input type="number" min="0" max="100" id="sewa_tbensin<?= $usr->sewa_id; ?>" value="<?= $usr->sewa_tbensin; ?>" class="form-control" /></td>
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
                                                            <button onclick="isisewa('<?= $usr->sewa_id; ?>');" type="button" class="btn btn-sm btn-warning "><span class="fa fa-edit" style="color:white;"></span> </button>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <script>
                                    function isisewa(sewa_id) {
                                        let sewa_spulang = $("#sewa_spulang" + sewa_id).val();
                                        let sewa_tmuka = $("#sewa_tmuka" + sewa_id).is(':checked') ? 1 : 0;
                                        let sewa_tbelakang = $("#sewa_tbelakang" + sewa_id).is(':checked') ? 1 : 0;
                                        let sewa_tkanan = $("#sewa_tkanan" + sewa_id).is(':checked') ? 1 : 0;
                                        let sewa_tkiri = $("#sewa_tkiri" + sewa_id).is(':checked') ? 1 : 0;
                                        let sewa_tbensin = $("#sewa_tbensin" + sewa_id).val();
                                        $.get("<?= base_url(); ?>/api/isisewa", {
                                                sewa_id: sewa_id,
                                                sewa_spulang: sewa_spulang,
                                                sewa_tmuka: sewa_tmuka,
                                                sewa_tbelakang: sewa_tbelakang,
                                                sewa_tkanan: sewa_tkanan,
                                                sewa_tkiri: sewa_tkiri,
                                                sewa_tbensin: sewa_tbensin
                                            })
                                            .done(function(data) {
                                                toast('INFO >>>', data);
                                            });
                                    }
                                </script>
                            </div>
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
                                        <?php //if (!isset($_GET["report"])) { 
                                        ?>
                                        <th>Action</th>
                                        <?php //} 
                                        ?>
                                        <th>Date</th>
                                        <th>Inv No.</th>
                                        <th>Customer</th>
                                        <th>Car</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $usr = $this->db
                                        ->table("sewa")
                                        ->join("product", "product.product_id=sewa.product_id", "left")
                                        ->join("customer", "customer.customer_id=sewa.customer_id", "left")
                                        ->where("sewa_date >=", $from)
                                        ->where("sewa_date <=", $to)
                                        ->orderBy("sewa_id", "ASC")
                                        ->get();
                                    //echo $this->db->getLastquery();
                                    $no = 1;
                                    foreach ($usr->getResult() as $usr) {
                                        $ok = array("", "OK");
                                    ?>
                                        <tr>
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
                                                        <button class="btn btn-sm btn-success " name="check" value="OK"><span class="fa fa-check-square-o" style="color:white;"></span> </button>
                                                        <input type="hidden" name="sewa_id" value="<?= $usr->sewa_id; ?>" />
                                                    </form>
                                                <?php } ?>

                                                <?php if (!isset($_GET["report"])) { ?>
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
                                                            <input type="hidden" name="sewa_id" value="<?= $usr->sewa_id; ?>" />
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
                                                            <input type="hidden" name="sewa_id" value="<?= $usr->sewa_id; ?>" />
                                                        </form>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                            <td><?= $usr->sewa_date; ?></td>
                                            <td><?= $usr->sewa_no; ?></td>
                                            <td><?= $usr->customer_name; ?></td>
                                            <td>
                                                <?= $usr->product_name; ?> (<span class="text-primary"><?= $usr->product_ube; ?></span>),
                                                Pengambilan: <span class="text-primary"><?= $usr->sewa_diambil; ?></span>,
                                                R. Pengembalian:<span class="text-primary"><?= $usr->sewa_rpulang; ?></span>,
                                                Driver: <span class="text-primary"><?= $usr->sewa_driver; ?></span>,
                                                Pengembalian:<span class="text-primary"><?= $usr->sewa_spulang; ?></span>
                                            </td>
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
    var title = "Transaksi Rental";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>