<?php echo $this->include("template/header_v"); ?>

<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <style>
        #map {
            height: 500px;
        }
    </style>
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
</head>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <?php if (!isset($_GET['customer_code']) && !isset($_POST['new']) && !isset($_POST['edit'])) {
                            $coltitle = "col-md-10";
                        } else {
                            $coltitle = "col-md-8";
                        } ?>
                        <div class="<?= $coltitle; ?>">
                            <h4 class="card-title"></h4>
                        </div>
                    </div>

                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $namabutton = 'name="change"';
                                $judul = "Update Tracking";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Tambah Tracking";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="tracking_datetime">Date:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control date" id="tracking_datetime" name="tracking_datetime" placeholder="" value="<?= $tracking_datetime; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="tracking_name">Keperluan:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control" id="tracking_name" name="tracking_name" placeholder="" value="<?= $tracking_name; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="tracking_description">Description:</label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control" id="tracking_description" name="tracking_description" placeholder="" value="<?= $tracking_description; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="tracking_nominal">Nominal:</label>
                                    <div class="col-sm-10">
                                        <input required type="number" min="0" class="form-control" id="tracking_nominal" name="tracking_nominal" placeholder="" value="<?= $tracking_nominal; ?>">
                                    </div>
                                </div>

                                <input type="hidden" name="tracking_id" value="<?= $tracking_id; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <button type="button" class="btn btn-warning col-md-offset-1 col-md-5" onClick="location.href='<?= base_url("tracking"); ?>'">Back</button>
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

                        if (isset($_GET["customer_code"]) && $_GET["customer_code"] != "") {
                            $customer_code = $_GET["customer_code"];
                        } else {
                            $customer_code = "";
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

                            <label for="to">Customer:</label>&nbsp;
                            <div class="col-2">
                                <select id="customer_code" name="customer_code" class="form-control select">
                                    <option value="">-- Pilih Customer --</option>
                                    <?php $customer = $this->db->table("customer")->orderBy("customer_name", "ASC")->get();
                                    foreach ($customer->getResult() as $customer) { ?>
                                        <option value="<?= $customer->customer_code; ?>" <?= ($customer->customer_code == $customer_code) ? "selected" : ""; ?>>(<?= $customer->customer_code; ?>) <?= $customer->customer_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>&nbsp;

                            <div class="col-2">
                                <button type="submit" class="btn btn-primary btn-sm" style="position:relative; left:30px;">Submit</button>
                            </div>&nbsp;
                        </form>
                        <?php if ($message != "") { ?>
                            <div class="alert alert-info alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong><?= $message; ?></strong>
                            </div>
                        <?php } ?>
                        <br />
                        <h3 style="font-weight:bold;">Peta Rute Perjalanan</h3>
                        <div id="map"></div>
                        <?php
                        $latawal1 = "-6.200000";
                        $lonawal1 = "106.816666";
                        ?>

                        <script>
                            // Inisialisasi peta
                            var map = L.map('map').setView([<?= $latawal1; ?>, <?= $lonawal1; ?>], 6);

                            // Menambahkan layer OpenStreetMap
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map);

                            // Fungsi untuk menambahkan jalur dan marker
                            function addRoute(route, color, name, kota) {
                                // Menambahkan marker pada titik awal dan akhir
                                L.marker([route[0].lat, route[0].lng]).addTo(map)
                                    .bindPopup(`<b>${name} - Titik Awal</b><br>Time: ${route[0].time}`);

                                L.marker([route[route.length - 1].lat, route[route.length - 1].lng]).addTo(map)
                                    .bindPopup(`<b>${name} - Titik Akhir</b><br>Time: ${route[route.length - 1].time}`);

                                // Menambahkan keterangan waktu di titik Purwakarta dan Pemalang tanpa marker
                                route.forEach((point, index) => {
                                    // Menambahkan keterangan waktu hanya saat mouse hover di Purwakarta dan Pemalang
                                    // if (point.time && index !== 0 && index !== route.length - 1) {
                                    L.marker([point.lat, point.lng], {
                                            opacity: 0
                                        }) // Tidak ada marker yang terlihat
                                        .addTo(map)
                                        // .bindPopup(`<b>${point.kota}</b><br>${point.time}`)
                                        
                                        .bindPopup(`<b>${point.customer}</b> (${point.driver})<br><b>${point.kota}</b><br>${point.time}<br>${point.plat}`)
                                        .on('mouseover', function() {
                                            this.openPopup();
                                        })
                                        .on('mouseout', function() {
                                            this.closePopup();
                                        });
                                    // }
                                });

                                // Membuat jalur routing
                                L.Routing.control({
                                    waypoints: route.map(point => L.latLng(point.lat, point.lng)),
                                    lineOptions: {
                                        styles: [{
                                            color,
                                            weight: 4
                                        }]
                                    },
                                    routeWhileDragging: false, // Tidak bisa drag rute
                                    addWaypoints: false, // Tidak bisa menambahkan atau memindahkan waypoint
                                    createMarker: function() {
                                        return null;
                                    }, // Tidak membuat marker otomatis
                                    show: false // Tidak menampilkan popup keterangan (legend)
                                }).addTo(map);
                            }

                            /* // Jalur 1: Jakarta, Purwakarta, Bandung
                            const route1 = [{
                                    lat: -6.200000,
                                    lng: 106.816666,
                                    time: '08:00'
                                }, // Jakarta
                                {
                                    lat: -6.555000,
                                    lng: 107.432222,
                                    time: '09:30'
                                }, // Purwakarta
                                {
                                    lat: -6.914744,
                                    lng: 107.609810,
                                    time: '11:00'
                                } // Bandung
                            ];

                            // Tambahkan Jalur 1
                            addRoute(route1, 'blue', 'Jalur 1');*/

                          

                            function realtime() {
                                $.ajax({
                                    url: "<?= base_url("api/trackingrealtime"); ?>",
                                    type: "GET",
                                    data: {
                                        from: $("#from").val(),
                                        to: $("#to").val(),
                                        customer_code: $("#customer_code").val()
                                    },
                                    success: function(routes) {
                                        // alert(routes);
                                        for (let routeName in routes) {
                                            if (routes[routeName].length > 0) {
                                                const color = 'blue';
                                                addRoute(routes[routeName], color, routeName);
                                            }
                                        }
                                    }
                                });
                            }
                            realtime();
                            setInterval(() => {
                                realtime();
                            }, 60000);
                        </script>



                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                                <thead class="">
                                    <tr><!-- 
                                        <?php if (!isset($_GET["report"])) { ?>
                                            <th>Action</th>
                                        <?php } ?> -->
                                        <th>Date</th>
                                        <th>Kota</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Customer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $usr = $this->db
                                        ->table("tracking")
                                        ->join("customer", "customer.customer_code = tracking.customer_code", "left")
                                        ->where("SUBSTR(tracking_datetime,1,10) >=", $from)
                                        ->where("SUBSTR(tracking_datetime,1,10) <=", $to)
                                        ->orderBy("tracking_id", "ASC")
                                        ->get();
                                    //echo $this->db->getLastquery();
                                    $no = 1;
                                    foreach ($usr->getResult() as $usr) {
                                        $ok = array("", "OK");
                                    ?>
                                        <tr>
                                            <!-- <?php if (!isset($_GET["report"])) { ?>
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
                                                            <input type="hidden" name="tracking_id" value="<?= $usr->tracking_id; ?>" />
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
                                                            <input type="hidden" name="tracking_id" value="<?= $usr->tracking_id; ?>" />
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?> -->
                                            <td><?= $usr->tracking_datetime; ?></td>
                                            <td><?= $usr->tracking_kota; ?></td>
                                            <td><?= $usr->tracking_latitude; ?></td>
                                            <td><?= $usr->tracking_longitude; ?></td>
                                            <td><?= $usr->customer_name; ?></td>
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
    var title = "Tracking";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>