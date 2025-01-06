<?php
// echo current_url() . " == " . base_url("/index.php/transaction");die;
if (current_url() == base_url("/index.php/transaction")) {
    echo $this->include("template/header_v");
} else {
    echo $this->include("template/headersaja_v");
}
$transaction_type = "keluar";
?>
<style>
    .caption-1 figcaption {
        position: absolute;
        bottom: 0;
        right: 0;
    }

    .caption-2 figcaption {
        width: 80%;
        position: absolute;
        bottom: 1rem;
        left: 10%;
        background: rgba(255, 255, 255, 0.6);
    }

    .caption-3 figcaption {
        position: absolute;
        bottom: 0;
        right: 0;
        transform: translateY(-50%);
    }

    .separator {
        border-bottom: 1px dashed #aaa;
    }

    .text-small {
        font-size: 8px;
    }

    .text-small0 {
        font-size: 12px;
    }

    .text-small1 {
        font-size: 14px;
    }

    .text-small2 {
        font-size: 15px;
    }

    .img_product {
        width: 100%;
        height: 150px;
        border: rgba(155, 155, 155, 0.5) solid 1px;
        border-radius: 4px;
    }

    /* Atur tinggi di bawah 768px (ukuran layar HP) */
    @media (max-width: 768px) {
        .img_product {
            height: 220px;
        }
    }

    .divimg_product {
        margin-bottom: 10px;
    }

    .pointer {
        cursor: pointer;
    }

    .figcaption {
        background-color: rgba(0, 0, 0, 0.8);
        border-radius: 2px;
        padding: 5px;
    }

    #listproduct {
        overflow-y: scroll;
    }

    #listproduct::-webkit-scrollbar {
        display: none;
    }

    #listproduct {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .centerpage {
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .hide {
        display: none;
    }

    #bayara {
        background-color: white;
        padding: 50px !important;
        border: rgba(200, 100, 200, 0.1) solid 1px;
        border-radius: 5px;
        box-shadow: rgba(200, 100, 200, 0.1) 0px 0px 5px 5px;
        z-index: 100;
    }

    .absolute-top-right {
        position: absolute;
        right: 5px;
        top: 5px;
    }

    .disabled {
        opacity: 0.1;
    }
</style>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-md-5 col-sm-12'>
            <div class="card" style="font-size: 20px; font-weight:bold; text-align:center;">.: Pengajuan Barang :.</div>
            <div class="card">
                <div class="card-body row">
                    <input id="fokus" type="hidden" value="barcode" />
                    <div id="test"></div>
                    <div class="col-8">
                        <div class="input-group mb-3">
                            <input onfocusin="$('#fokus').val('barcode')" id="inputbarcode" autofocus type="text" class="form-control" placeholder="Scan Barcode" aria-label="Scan Barcode" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary fa fa-edit" type="button"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <img src="<?= base_url("images/store_picture/" . $store_picture); ?>" class="img" style="width:auto; height:auto; max-height:50px;" />
                    </div>
                    <div class="col-12 ">
                        <input type="hidden" id="modalstatus" value="keluar" />
                        <input type="hidden" id="listnotastatus" value="2" />
                        <input type="hidden" id="kasterakhirval" />
                        <input type="hidden" id="kasshift" value="0" />
                        <div class="col-12 p-0">
                            <button data-toggle="tooltip" data-placement="top" title="Transaksi Pending" onclick="listnota(2);nota(0);" class="btn  btn-warning fa fa-flag-checkered mb-2 btn-child" type="button"></button>
                            <button data-toggle="tooltip" data-placement="top" title="Transaksi Sukses" onclick="listnota(0);nota(0);" class="btn  btn-success fa fa-check mb-2 btn-child" type="button"></button>
                            <button data-toggle="tooltip" data-placement="top" title="Refresh Halaman" onclick="refresh();" class="btn  btn-info fa fa-refresh mb-2 btn-child" type="button"></button>
                            <button data-toggle="tooltip" data-placement="top" title="Buat Nota Baru" onclick="createnota();" class="btn  btn-primary fa fa-plus mb-2 btn-child" type="button"></button>
                            <?php if (session()->get("store_member") == 1) { ?>
                                <button data-toggle="tooltip" data-placement="top" title="Masukkan Member" onclick="member();" class="btn  btn-primary fa fa-user mb-2 btn-child" type="button"></button>
                            <?php } ?>
                        </div>
                        <div id="keterangan" class="alert alert-info col-12  p-1 text-center" role="alert"></div>

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
                            <input onchange="listnota(-1)" type="date" id="from" name="from" class="form-control" value="<?= $from; ?>">&nbsp;
                            <label for="to">Ke:</label>&nbsp;
                            <input onchange="listnota(-1)" type="date" id="to" name="to" class="form-control" value="<?= $to; ?>">&nbsp;
                            <button onclick="hariini()" type="button" class="btn btn-primary">Hari Ini</button>
                        </form>

                        <div class="my-1" id="listnota"></div>
                        <div class="separator my-3"></div>
                    </div>
                    <div class="col-12" id="nota">

                    </div>
                    <div onclick="fokus('bayar')" onfocusout="fokus('barcode');" class="modal " id="bayar">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Pengajuan</h4>
                                </div>
                                <div class="modal-body">
                                    <div>
                                        <div class="form-group">
                                            <label for="user_id">NAMA (Pilih Nama):</label> &nbsp<button onclick="daftarbaru();" type="button" class="btn btn-warning btn-xs">Daftar Baru</button>
                                            <input list="username" name="user_name" id="user_name" class="form-control">
                                            <input name="user_id" id="user_id" type="hidden">
                                            <datalist id="username">
                                                <?php
                                                $userData = [];
                                                $user = $this->db->table("user")->orderBy("user_name", "asc")->get();
                                                foreach ($user->getResult() as $user) {
                                                    $userData[$user->user_name] = [
                                                        'id' => $user->user_id,
                                                        'division' => $user->division_id,
                                                        'nip' => $user->user_nip
                                                    ];
                                                ?>
                                                    <option value="<?= $user->user_name; ?>"></option>
                                                <?php } ?>
                                            </datalist>

                                            <script>
                                                function daftarbaru() {
                                                    $("#user_name").focus();
                                                    pendaftaranenable();
                                                }
                                                var userData = <?php echo json_encode($userData); ?>;
                                                $(document).ready(function() {
                                                    $('#user_name').on('change', function() {
                                                        var inputVal = $(this).val();
                                                        var selectedUser = userData[inputVal];

                                                        if (selectedUser) {
                                                            var userId = selectedUser.id;
                                                            var division = selectedUser.division;
                                                            var nip = selectedUser.nip;

                                                            $("#division_id").val(division);
                                                            $("#user_nip").val(nip);
                                                            $("#user_id").val(userId);

                                                            // alert("User terpilih dengan ID: " + userId + "\nDivision: " + division + "\nNIP: " + nip);
                                                        } else {
                                                            $("#user_id").val(0);
                                                            let isipesan="Silahkan Pilih Nama dari Daftar.<br/>Atau Silahkan Daftar Baru!";
                                                            toast('INFO >>>', isipesan);
                                                        }
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <div class="form-group">
                                            <label for="user_nip">NIP:</label> &nbsp
                                            <input readonly type="text" class="form-control" id="user_nip">
                                        </div>
                                        <div class="form-group">
                                            <label for="division_id">Bidang:</label>
                                            <select readonly id="division_id" class="form-control">
                                                <?php $division = $this->db->table("division")->orderBy("division_id")->get();
                                                foreach ($division->getResult() as $division) {
                                                    if ($division->division_id == 2) {
                                                        $selected = "selected";
                                                    } else {
                                                        $selected = "";
                                                    } ?>
                                                    <option value="<?= $division->division_id; ?>" <?= $selected; ?>><?= $division->division_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <button onclick="pelunasan();" type="button" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button onclick="fokus('barcode');" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div onclick="fokus('modalawal');" onfocusout="fokus('barcode');" class="modal " id="showmodalawal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Kas Masuk</h4>
                                </div>
                                <div class="modal-body">
                                    <div>Shift : <span class="kasshift"></span></div>
                                    <div>Modal Awal : Rp. <span class="kasawal"></span></div>
                                    <div>Kas Terakhir : Rp. <span class="kasterakhir"></span></div>
                                    <div class="form-inline">
                                        <label for="modalawal">Jumlah Uang:</label> &nbsp
                                        <input type="number" class="form-control" id="modalawal"> &nbsp
                                        <button onclick="kasmodal('masuk')" type="button" class="btn btn-primary">Submit</button>
                                    </div>
                                    <div>Keterangan : <span class="keteranganmodalawal">Modal awal dari owner.</span></div>
                                </div>
                                <div class="modal-footer">
                                    <button onclick="fokus('barcode');" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div onclick="fokus('modalakhir');" onfocusout="fokus('barcode');" class="modal " id="showmodalakhir">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Kas Keluar</h4>
                                </div>
                                <div class="modal-body">
                                    <div>Shift : <span class="kasshift"></span></div>
                                    <div>Modal Awal : Rp. <span class="kasawal"></span></div>
                                    <div>Kas Terakhir : Rp. <span class="kasterakhir"></span></div>
                                    <div class="form-inline">
                                        <label for="modalakhir">Jumlah Uang:</label> &nbsp
                                        <input type="number" class="form-control" id="modalakhir"> &nbsp
                                        <button onclick="kasmodal('keluar')" type="button" class="btn btn-primary">Submit</button>
                                    </div>
                                    <div>Keterangan : <span class="keteranganmodalakhir">Stor uang kepada owner.</span></div>
                                </div>
                                <div class="modal-footer">
                                    <button onclick="fokus('barcode');" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div onclick="" class="modal " id="showmember">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Member</h4>
                                </div>
                                <div class="modal-body" id="listmember">
                                    <form class="form-inline" action="/action_page.php">
                                        <label for="member_no" class="mr-sm-2">Member No.:</label>
                                        <input onkeyup="carimember('member_no',this.value)" type="text" class="form-control mb-2 mr-sm-2" placeholder="Masukkan Whatsapp" id="member_no">
                                        <label for="member_name" class="mr-sm-2">Name:</label>
                                        <input onkeyup="carimember('member_name',this.value)" type="text" class="form-control mb-2 mr-sm-2" placeholder="Masukkan Nama" id="member_name">
                                    </form>
                                    <div class="table-responsive m-t-40" id="listmembernya">

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button onclick="fokus('barcode');" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div onclick="fokus('insertqty');" class="modal " id="jmlnota">
                        <div class="modal-dialog modal-xs">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Tambah Jumlah Produk</h4>
                                </div>
                                <div class="modal-body" id="listmember">
                                    <div class="form-inline">
                                        <label for="member_name" class="mr-sm-2">Jml:</label>
                                        <input type="number" class="form-control mb-2 mr-sm-2" placeholder="Masukkan Jumlah" id="qtyproduct" value="1"> &nbsp
                                        <input id="qtyproduct_id" value="0" type="hidden" />
                                        <button onclick="insertnotaproduk();" type="button" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button onclick="fokus('barcode');" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        function hariini() {
                            $("#from").val('<?= date("Y-m-d"); ?>');
                            $("#to").val('<?= date("Y-m-d"); ?>');
                        }

                        function insertmember(transaction_id, member_id) {
                            // alert("<?= base_url("insertmember"); ?>?transaction_id="+transaction_id+"&member_id="+member_id);
                            $.get("<?= base_url("insertmember"); ?>", {
                                    transaction_id: transaction_id,
                                    member_id: member_id
                                })
                                .done(function(data) {
                                    $("#showmember").modal('hide');
                                    nota(transaction_id);
                                });
                        }

                        function carimember(tipe, isi) {
                            let transaction_id = $("#transaction_id").val();
                            let arraycarimember;
                            if (tipe == 'member_no') {
                                arraycarimember = {
                                    transaction_id: transaction_id,
                                    member_no: isi
                                };
                            } else if (tipe == 'member_name') {
                                arraycarimember = {
                                    transaction_id: transaction_id,
                                    member_name: isi
                                };
                            }
                            $.get("<?= base_url("listmember"); ?>", arraycarimember)
                                .done(function(data) {
                                    $("#listmembernya").html(data);
                                });
                        }

                        function modalstatus() {
                            $.get("<?= base_url("posisishift"); ?>")
                                .done(function(data) {
                                    $("#modalstatus").val(data);
                                    if (data == 'keluar') {
                                        // $(".btn-child").prop('disabled', true);
                                        $("#btnmodalawal").attr('data-original-title', 'Modal Awal dari Owner');
                                    }
                                    if (data == 'masuk') {
                                        // $(".btn-child").prop('disabled', false);
                                        $("#btnmodalawal").attr('data-original-title', 'Update Modal Awal');
                                    }
                                });
                        }

                        function modalnominal() {
                            $.get("<?= base_url("nominalkas"); ?>")
                                .done(function(data) {
                                    $("#kasterakhirval").val(data);
                                    $(".kasterakhir").html(formatRupiah(data));
                                });
                        }

                        function modalawal() {
                            $.get("<?= base_url("modalawalkas"); ?>")
                                .done(function(data) {
                                    $(".kasawal").html(formatRupiah(data));
                                });
                        }

                        function shift() {
                            $.get("<?= base_url("shift"); ?>")
                                .done(function(data) {
                                    $(".kasshift").html(data);
                                    $("#kasshift").val(data);
                                });
                        }

                        function datamodalkas() {
                            //realtime kas
                            modalnominal();
                            //modal awal
                            modalawal();
                            //shift
                            shift();
                        }
                    </script>
                    <script>
                        function modalkas(type) {
                            if (type == 'masuk') {
                                $("#showmodalawal").modal();
                                fokus('modalawal');
                            }
                            if (type == 'keluar') {
                                $("#showmodalakhir").modal();
                                fokus('modalakhir');
                                $('#modalakhir').val(0).focus();
                            }

                            datamodalkas();

                        }

                        function member() {
                            $("#listmembernya").html("");
                            $("#showmember").modal();
                            fokus('memberno');

                        }
                    </script>
                    <script>
                        function fokus(type) {
                            switch (type) {
                                case 'barcode':
                                    $("#inputbarcode").focus();
                                    $("#fokus").val("barcode");
                                    break;
                                case 'cari':
                                    $("#cariproduk").focus();
                                    $("#fokus").val("cari");
                                    break;
                                case 'bayar':
                                    $("#uang").focus();
                                    $("#fokus").val("bayar");
                                    break;
                                case 'modalawal':
                                    $("#modalawal").focus();
                                    $("#fokus").val("modalawal");
                                    break;
                                case 'modalakhir':
                                    $("#modalakhir").focus();
                                    $("#fokus").val("modalakhir");
                                    break;
                                case 'memberno':
                                    $("#member_name").val('');
                                    $('#member_no').focus();
                                    $("#fokus").val("memberno");
                                    break;
                                case 'membername':
                                    $("#member_no").val('');
                                    $("#member_name").focus();
                                    $("#fokus").val("membername");
                                    break;
                                case 'insertqty':
                                    $("#fokus").val("insertqty");
                                    $("#qtyproduct").focus();
                                    break;
                                default:
                                    $("#inputbarcode").focus();
                                    $("#fokus").val("barcode");
                                    break;
                            }
                        }
                    </script>
                    <script>
                        function print(transaction_id) {
                            window.open('<?= base_url("transactionprintumum?transaction_id="); ?>' + transaction_id, '_blank');
                        }

                        function cekstatus(transaction_id) {
                            $.get("<?= base_url("cekstatus"); ?>", {
                                    transaction_id: transaction_id
                                })
                                .done(function(data) {
                                    if (data == 0) {
                                        $("#printicon").show();
                                    } else {
                                        $("#printicon").hide();
                                    }
                                });
                        }

                        function pendaftaranenable(){
                            $("#user_nip").removeAttr("readonly");
                            $("#division_id").removeAttr("readonly");
                        }
                        function pendaftarandisable(){
                            $("#user_nip").attr("readonly", true);
                            $("#division_id").attr("readonly", true);
                        }

                        function pelunasan() {
                            pendaftarandisable();
                            let user_id = $("#user_id").val();
                            let user_name = $("#user_name").val();
                            let user_nip = $("#user_nip").val();
                            let division_id = $("#division_id").val();
                            let transaction_id = $("#transaction_id").val();
                            // $('#test').html('<?= base_url("pelunasan"); ?>?user_id=' + user_id + '&user_name=' + user_name + '&user_nip=' + user_nip + "&division_id=" + division_id + "&transaction_id=" + transaction_id);
                            $.get("<?= base_url("pelunasan"); ?>", {
                                    user_id: user_id,
                                    user_name: user_name,
                                    user_nip: user_nip,
                                    division_id: division_id,
                                    transaction_id: transaction_id
                                })
                                .done(function(data) {
                                    updatestatus(transaction_id, data);
                                    // print(transaction_id);
                                    alert("Pengajuan Sedang Diproses!");
                                    listnota(2);nota(0);
                                    $("#bayar").modal('hide');
                                    cekstatus(transaction_id);
                                    fokus('barcode');
                                });
                        }

                        function formatRupiah(num) {
                            var str = num.toString().replace("", ""),
                                parts = false,
                                output = [],
                                i = 1,
                                formatted = null;
                            if (str.indexOf(".") > 0) {
                                parts = str.split(".");
                                str = parts[0];
                            }
                            str = str.split("").reverse();
                            for (var j = 0, len = str.length; j < len; j++) {
                                if (str[j] != ",") {
                                    output.push(str[j]);
                                    if (i % 3 == 0 && j < (len - 1)) {
                                        output.push(".");
                                    }
                                    i++;
                                }
                            }
                            formatted = output.reverse().join("");
                            return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
                        };

                        function kembalian() {
                            let uang = $("#uang").val();
                            let tagihan = $("#tagihan").val();
                            let kembalian = uang - tagihan;
                            $("#kembaliannya").val(kembalian);
                            $("#bayarannya").val(uang);
                            // alert(kembalian);
                            $(".dibayar").html(formatRupiah(uang));
                            $(".kembalian").html(formatRupiah(kembalian));
                        }

                        function closebayar() {
                            $("#bayar").hide();
                        }

                        function bayar() {
                            pendaftarandisable();
                            $("#bayar").modal();
                            fokus('bayar');
                            let tagihan = $("#tagihan").val();
                            $(".bill").html(formatRupiah(tagihan));
                        }

                        function cariproduk() {
                            let product_name = $("#cariproduk").val();
                            let type = $("#typesearch").val();
                            plistproduct(type, product_name);
                        }

                        function refresh() {
                            listnota(-1);
                            nota(0);
                            refreshlistproduct();
                        }

                        function refreshlistproduct() {
                            let typelist = $("#typesearch").val();
                            plistproduct(typelist, '');
                        }

                        function plistproduct(type, product_name) {
                            if (type == "gambar") {
                                // alert("<?= base_url("listproductgambarumum"); ?>?product_name="+product_name+"&positionm_id="+positionm_id);
                                $.get("<?= base_url("listproductgambarumum"); ?>", {
                                        product_name: product_name
                                    })
                                    .done(function(data1) {
                                        $("#listproduct").html(data1);
                                        $("#typesearch").val("gambar");
                                    });
                            }
                            if (type == "list") {
                                // alert("<?= base_url("listproductlistumum"); ?>?product_name=" + product_name + "&positionm_id=" + positionm_id);
                                $.get("<?= base_url("listproductlistumum"); ?>", {
                                        product_name: product_name
                                    })
                                    .done(function(data2) {
                                        $("#listproduct").html(data2);
                                        $("#typesearch").val("list");
                                    });
                            }
                            // fokus('barcode');                          
                        }

                        function listnota(transaction_status) {
                            let from = $("#from").val();
                            let to = $("#to").val();
                            if (transaction_status == '-1') {
                                transaction_status = $("#listnotastatus").val();
                            }
                            // alert("<?= base_url("listnotaumum"); ?>?transaction_status="+transaction_status+"&from="+from+"&to="+to);
                            $.get("<?= base_url("listnotaumum"); ?>", {
                                    transaction_type: '<?= $transaction_type; ?>',
                                    transaction_status: transaction_status,
                                    from: from,
                                    to: to
                                })
                                .done(function(data) {
                                    $("#listnota").html(data);
                                    $("#listnotastatus").val(transaction_status);
                                    if (transaction_status == 0) {
                                        $("#keterangan").html("Transaksi Sukses");
                                    }
                                    if (transaction_status == 2) {
                                        $("#keterangan").html("Transaksi Pending");
                                    }
                                });
                        }

                        function nota(transaction_id) {
                            // alert("<?= base_url("notaumum"); ?>?transaction_id="+transaction_id);
                            $.get("<?= base_url("notaumum"); ?>", {
                                    transaction_id: transaction_id,
                                    transaction_type: '<?= $transaction_type; ?>'
                                })
                                .done(function(data) {
                                    $("#nota").html(data);
                                    setTimeout(function() {
                                        cekstatus(transaction_id);
                                        fokus('barcode');
                                    }, 100);
                                });
                        }

                        function createnota() {
                            let transaction_shift = $("#kasshift").val();
                            // alert("<?= base_url("createnotaumum"); ?>?transaction_shift="+transaction_shift+"&transaction_type=<?= $transaction_type; ?>");
                            $.get("<?= base_url("createnotaumum"); ?>", {
                                    transaction_shift: transaction_shift,
                                    transaction_type: '<?= $transaction_type; ?>'
                                })
                                .done(function(data) {
                                    // alert(data);
                                    listnota($("#listnotastatus").val());
                                    nota(data);
                                });
                        }

                        function insertnotaproduk() {
                            let product_id = $("#qtyproduct_id").val();
                            let transactiond_id = $("#transactiond_id").val();
                            let qty = $("#qtyproduct").val();
                            if (transactiond_id > 0) {
                                // updateqty(transactiond_id, 'update', qty);
                            } else {
                                insertnotaqty(product_id, qty);
                            }

                            $("#jmlnota").modal("hide");
                            $("#qtyproduct").val(1);
                        }

                        function insertjmlnota(product_id) {
                            if ($("#transaction_id").val() > 0) {
                                $("#jmlnota").modal();
                                $("#qtyproduct_id").val(product_id);
                                fokus('insertqty');
                            } else {
                                toast('INFO >>>', 'Nota tidak ditemukan!');
                            }
                        }
                        //masukin product hanya multi qty
                        function insertnotaqty(product_id, transactiond_qty) {
                            let transaction_id = $("#transaction_id").val();
                            let transactiond_id = $("#transactiond_id").val();
                            $("#transactiond_id").val(0);
                            // $("#test").text("<?= base_url("insertnotaumum"); ?>?transaction_id="+transaction_id+"&transactiond_id="+transactiond_id+"&product_id="+product_id+"&transactiond_qty="+transactiond_qty+"&transaction_type=<?= $transaction_type; ?>");
                            $.get("<?= base_url("insertnotaumum"); ?>", {
                                    transaction_id: transaction_id,
                                    transactiond_id: transactiond_id,
                                    product_id: product_id,
                                    transactiond_qty: transactiond_qty,
                                    transaction_type: '<?= $transaction_type; ?>'
                                })
                                .done(function(data) {
                                    if(data>0){
                                        // alert(data);
                                        listnota($("#listnotastatus").val());
                                        nota(transaction_id);

                                        refreshlistproduct();
                                    }else{
                                        alert("Stok tidak mencukupi!");
                                    }
                                });
                        }
                        //masukin product hanya satu pcs
                        function insertnota(product_id) {
                            let transaction_id = $("#transaction_id").val();
                            // alert("<?= base_url("insertnota"); ?>?transaction_id="+transaction_id+"&product_id="+product_id);
                            $.get("<?= base_url("insertnota"); ?>", {
                                    transaction_id: transaction_id,
                                    product_id: product_id
                                })
                                .done(function(data) {
                                    // alert(data);
                                    listnota($("#listnotastatus").val());
                                    nota(transaction_id);

                                    refreshlistproduct();
                                });
                        }

                        function insertnotabarcode(product_batch) {

                            let transaction_id = $("#transaction_id").val();
                            $.get("<?= base_url("insertnota"); ?>", {
                                    transaction_id: transaction_id,
                                    product_batch: product_batch
                                })
                                .done(function(data) {
                                    // alert(data);
                                    listnota($("#listnotastatus").val());
                                    nota(transaction_id);
                                });
                        }

                        function deletenota(transaction_id) {
                            // alert("<?= base_url("deletenota"); ?>?transaction_id="+transaction_id);
                            let ok = confirm(' you want to delete?');
                            // alert(ok);
                            if (ok == true) {
                                $.get("<?= base_url("deletenota"); ?>", {
                                        transaction_id: transaction_id
                                    })
                                    .done(function(data) {
                                        // alert(data);
                                        listnota($("#listnotastatus").val());
                                        nota(transaction_id);
                                        refreshlistproduct();
                                    });
                            }
                        }

                        function updatestatus(transaction_id, transaction_status) {
                            // alert("<?= base_url("updatestatus"); ?>?transaction_id="+transaction_id+"&transaction_status="+transaction_status);                            
                            $.get("<?= base_url("updatestatus"); ?>", {
                                    transaction_id: transaction_id,
                                    transaction_status: transaction_status
                                })
                                .done(function(data) {
                                    $("#status").val(transaction_status);
                                    cekstatus(transaction_id);
                                });
                        }

                        function updateqty(transactiond_id, type, transactiond_qty) {
                            // alert("<?= base_url("updateqty"); ?>?transactiond_id="+transactiond_id+"&type="+type+"&transactiond_qty="+transactiond_qty+"&positionm_id="+positionm_id); 

                            $.get("<?= base_url("updateqty"); ?>", {
                                    transactiond_id: transactiond_id,
                                    type: type,
                                    transactiond_qty: transactiond_qty
                                })
                                .done(function(data) {
                                    // alert(data);
                                    listnota($("#listnotastatus").val());
                                    let transaction_id = $("#transaction_id").val();
                                    nota(transaction_id);
                                    updatestatus(transaction_id, 2);
                                    refreshlistproduct();
                                });
                        }

                        function deletetransactiond(transaction_id, product_id, product_qty) {
                            // alert("<?= base_url("deletetransactiond"); ?>?transactiond_id="+transactiond_id+"&product_id="+product_id+"&product_qty="+product_qty);
                            let ok = confirm(' you want to delete?');
                            // alert(ok);
                            if (ok == true) {
                                $.get("<?= base_url("deletetransactiond"); ?>", {
                                        transaction_id: transaction_id,
                                        product_id: product_id,
                                        product_qty: product_qty,
                                        transaction_type: '<?= $transaction_type; ?>'
                                    })
                                    .done(function(data) {
                                        // alert(data);
                                        listnota($("#listnotastatus").val());
                                        nota(transaction_id);
                                        updatestatus(transaction_id, 2);
                                        refreshlistproduct();
                                    });
                            }
                        }
                        $(document).ready(function() {
                            listnota($("#listnotastatus").val());
                            plistproduct('gambar', '');
                            closebayar();
                            modalstatus();
                            shift();
                            $('#showmember').on('hidden.bs.modal', function(e) {
                                fokus('barcode');
                            })
                        });
                        $(document).on("keyup", function(e) {
                            let ifokus = $("#fokus").val();
                            let transaction_id = $("#transaction_id").val();

                            if (e.which == 9) {
                                // alert(ifokus);
                                if (ifokus == "" || ifokus == "barcode") {
                                    fokus('cari');
                                } else if (ifokus == "cari") {
                                    fokus('barcode');
                                } else if (ifokus == "memberno") {
                                    fokus('membername');
                                } else if (ifokus == "membername") {
                                    fokus('memberno');
                                }
                            } else if (e.which == "13") {
                                if (ifokus == "barcode") {
                                    let product_batch = $("#inputbarcode").val();
                                    if (product_batch == "") {
                                        alert("Barcode tidak boleh kosong!");
                                    }
                                    insertnotabarcode(product_batch);
                                    $("#inputbarcode").val("");
                                } else if (ifokus == "bayar") {
                                    pelunasan();
                                } else if (ifokus == "modalawal") {
                                    kasmodal('masuk');
                                } else if (ifokus == "modalakhir") {
                                    kasmodal('keluar');
                                } else if (ifokus == "insertqty") {
                                    insertnotaproduk();
                                }

                            } else if (e.which == 17) {
                                if (transaction_id > 0) {
                                    bayar();
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
        <div class='col-md-7 col-sm-12'>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 row mb-2">
                            <div class="col-md-6 col-sm-12 mb-2">
                                <button onclick="plistproduct('gambar','');" class="btn btn-info btn-block">Bergambar</button>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-2">
                                <button onclick="plistproduct('list','');" class="btn btn-info btn-block">List Data</button>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="input-group mb-3">
                                <input type="hidden" id="typesearch" value="gambar" />
                                <input onfocusin="$('#fokus').val('cari')" onkeyup="cariproduk();" id="cariproduk" type="text" class="form-control" placeholder="Cari Produk" aria-label="Cari Produk" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary fa fa-search" type="button"></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 row" id="listproduct">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "Poin of Sale";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
    setTimeout(() => {
        $(".sidebartoggler").click();
        $(".page-titles").hide();
        $("#inputbarcode").focus();
    }, 300);
</script>

<?php echo  $this->include("template/footer_v"); ?>