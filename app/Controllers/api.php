<?php

namespace App\Controllers;

use phpDocumentor\Reflection\Types\Null_;

class api extends baseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }

    public function index()
    {
        echo "Page Not Found!";
    }

    public function isisewa()
    {
        $input = [];
        foreach ($this->request->getGet() as $key => $value) {
            if (!in_array($key, ['create', 'sewa_id'])) {
                $input[$key] = $value;
            }
        }
        $this->db->table('sewa')->update($input, array("sewa_id" => $this->request->getGet("sewa_id")));
        echo  "Update Success";
    }

    public function markerdelete()
    {
        $where["sewa_id"] = $this->request->getGet("sewa_id");
        $where["marker_latitude"] = $this->request->getGet("lat");
        $where["marker_longitude"] = $this->request->getGet("lng");
        $this->db
            ->table("marker")
            ->delete($where);
        // echo $this->db->getLastQuery();
    }

    public function masukmarker()
    {
        // Inisialisasi array input untuk menyimpan data
        $input = [];
        $input["sewa_id"] = $this->request->getGet("sewa_id");
        $input["marker_latitude"] = $this->request->getGet("lat");
        $input["marker_longitude"] = $this->request->getGet("lng");

        // Cek jika input tidak kosong dan lakukan operasi insert
        if (!empty($input)) {
            $insert = $this->db->table('marker')->insert($input);
            if ($insert) {
                $input["marker_id"] = $this->db->insertID(); // Ambil ID terakhir yang diinsert
            } else {
                return $this->response->setJSON(['error' => 'Gagal menyimpan data']);
            }
        } else {
            return $this->response->setJSON(['error' => 'Input tidak valid']);
        }

        // Kirimkan data dalam format JSON
        return $this->response->setJSON($input);
    }



    public function active()
    {
        $input["store_active"] = $this->request->getGET("store_active");
        $this->db->table('store')->update($input, array("store_id" => $this->request->getGET("store_id")));
        echo $this->db->getLastQuery();
    }


    public function iswritable()
    {
        $dir = $_GET["path"];
        if (is_dir($dir)) {
            if (is_writable($dir)) {
                echo "true";
            } else {
                echo "false";
            }
        } else if (file_exists($dir)) {
            return (is_writable($dir));
        }
    }



    public function hakakses()
    {
        $crud = $this->request->getGET("crud");
        $val = $this->request->getGET("val");
        $val = json_decode($val);
        $position_id = $this->request->getGET("position_id");
        $pages_id = $this->request->getGET("pages_id");
        $where["position_id"] = $this->request->getGET("position_id");
        $where["pages_id"] = $this->request->getGET("pages_id");
        $cek = $this->db->table('positionpages')->where($where)->get()->getNumRows();
        if ($cek > 0) {
            $input1[$crud] = $val;
            $this->db->table('positionpages')->update($input1, $where);
            echo $this->db->getLastQuery();
        } else {
            $input2["position_id"] = $position_id;
            $input2["pages_id"] = $pages_id;
            $input2[$crud] = $val;
            $this->db->table('positionpages')->insert($input2);
            echo $this->db->getLastQuery();
        }
    }

    public function acc()
    {
        $pic_id = $this->request->getGet("pic_id");
        $transaction_id = $this->request->getGet("transaction_id");
        $input["pic_id"] = $pic_id;
        $where["transaction_id"] = $transaction_id;
        $this->db->table("transaction")
            ->where($where)
            ->update($input);
        if ($this->db->affectedRows() > 0) {
            echo "1";
        } else {
            echo "0";
        }
    }

    public function trackingrealtime()
    {
        $from = $this->request->getGet("from");
        $to = $this->request->getGet("to");
        $customer_code = $this->request->getGet("customer_code");
        $tracking = $this->db
            ->table("tracking")
            ->where("customer_code", $customer_code)
            ->where("SUBSTR(tracking_datetime,1,10) >=", $from)
            ->where("SUBSTR(tracking_datetime,1,10) <=", $to)
            ->orderBy("tracking_id", "ASC")
            ->get();

        $sebelumnya = date("Y-m-d H:i:s");
        $jalur = array();
        $no = 0;
        $jalno = 0;

        // Proses data tracking
        foreach ($tracking->getResult() as $tracking) {
            $selisih = strtotime($tracking->tracking_datetime) - strtotime($sebelumnya);
            $selisihMenit = $selisih / 60;

            if ($selisihMenit > 120 || $no == 0) {
                $latawal1 = $tracking->tracking_latitude;
                $lonawal1 = $tracking->tracking_longitude;
                $jalno++;
            }

            $customer = $tracking->customer_name;
            $driver = $tracking->sewa_driver;
            $plat = $tracking->product_ube;
            $lat = $tracking->tracking_latitude;
            $lat = $tracking->tracking_latitude;
            $lon = $tracking->tracking_longitude;
            $kota = $tracking->tracking_kota;
            $time = date("d M Y, H:i", strtotime($tracking->tracking_datetime));

            // Tambahkan ke jalur yang sesuai
            $jalur["Perjalanan_" . $jalno][] = [
                "lat" => $lat,
                "lng" => $lon,
                "time" => $time,
                "kota" => $kota,
                "customer" => $customer,
                "driver" => $driver,
                "plat" => $plat
            ];

            $sebelumnya = $tracking->tracking_datetime;
            $no++;
        }

        // Encode data jalur menjadi format JSON
        return $this->response->setJSON($jalur);
    }

    function getCityFromCoordinates($latitude, $longitude)
    {
        // URL Nominatim API
        $url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=$latitude&lon=$longitude";

        // Buat konteks dengan User-Agent
        $context = stream_context_create([
            'http' => [
                'header' => "User-Agent: MyApplication/1.0\r\n"
            ]
        ]);

        // Ambil data dari URL
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            return "Tidak dapat terhubung ke API";
        }

        // Proses respons JSON
        $data = json_decode($response, true);

        // Ambil nama kota
        $city = $data['address']['city']
            ?? $data['address']['village']
            ?? $data['address']['town']
            ?? "Tidak ditemukan";

        return $city;
    }

    //API terima dari device
    public function terimadata()
    {
        $product_ube = $this->request->getGet("product_ube");
        $tracking_latitude = $this->request->getGet("tracking_latitude");
        $tracking_longitude = $this->request->getGet("tracking_longitude");
        if (empty($product_ube) || empty($tracking_latitude) || empty($tracking_longitude)) {
            return $this->response->setJSON(['error' => 'Data tidak valid.']);
        }
        if ($tracking_latitude < -90 || $tracking_latitude > 90 || $tracking_longitude < -180 || $tracking_longitude > 180) {
            return $this->response->setJSON(['error' => 'Koordinat tidak valid.']);
        }
        $input = array();
        $store = $this->db->table("store")->get()->getRow();
        //check batas waktu
        $sewa = $this->db
            ->table("sewa")
            ->join("product", "product.product_id=sewa.product_id", "left")
            ->join("customer", "customer.customer_id=sewa.customer_id", "left")
            ->where("sewa.sewa_rpulang <", date("Y-m-d"))
            ->where("sewa.sewa_spulang", null)
            ->where("product.product_ube", $product_ube)
            ->get();
        // echo $this->db->getLastQuery();
        if ($sewa->getNumRows() > 0) {
            foreach ($sewa->getResult() as $sewa) {
                $customer_code = $sewa->customer_code;
                $customer_name = $sewa->customer_name;
                $sewa_driver = $sewa->sewa_driver;
                $sewa_id = $sewa->sewa_id;
                $kota = $this->getCityFromCoordinates($tracking_latitude, $tracking_longitude);
                $input1["tracking_latitude"] = $tracking_latitude;
                $input1["tracking_longitude"] = $tracking_longitude;
                $input1["tracking_kota"] = $kota;
                $input1["tracking_datetime"] = date("Y-m-d H:i:s");
                $input1["customer_code"] = $customer_code;
                $input1["product_ube"] = $product_ube;
                $input1["sewa_driver"] = $sewa_driver;
                $input1["sewa_id"] = $sewa_id;
                $input1["customer_name"] = $customer_name;
                $this->db->table("tracking")->insert($input1);
                $input["validasiwaktu"] =  "Waktu Sewa Habis!";
                $store_notifurl = $store->store_notifurl . "?message=Pelanggaran&product_ube=" . $sewa->product_ube . "&berhenti=true&catatan=waktu";
                $input["notifwaktu"] =  $this->notif($store_notifurl);
                $input=$input1;
            }
        }


        //check batas wilayah
        $sewa = $this->db
            ->table("sewa")
            ->join("product", "product.product_id=sewa.product_id", "left")
            ->join("customer", "customer.customer_id=sewa.customer_id", "left")
            ->where("product.product_ube", $product_ube)
            ->where("sewa.sewa_diambil >=", date("Y-m-d"))
            ->where("sewa.sewa_rpulang <=", date("Y-m-d"))
            ->get();
        // echo $this->db->getLastQuery();
        if ($sewa->getNumRows() > 0) {
            function isPointInPolygon($polygon, $point)
            {
                $x = $point[0];
                $y = $point[1];
                $inside = false;
                $n = count($polygon);
                for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
                    $xi = $polygon[$i][0];
                    $yi = $polygon[$i][1];
                    $xj = $polygon[$j][0];
                    $yj = $polygon[$j][1];
                    $intersect = (($yi > $y) != ($yj > $y)) &&
                        ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
                    if ($intersect) $inside = !$inside;
                }
                return $inside;
            }

            function normalizeLongitude($lon)
            {
                return fmod(($lon + 360), 360);
            }

            function isPointInPolygonWithWrap($polygon, $point)
            {
                // Normalisasi garis bujur ke rentang 0-360
                $polygon = array_map(function ($coord) {
                    return [$coord[0], normalizeLongitude($coord[1])];
                }, $polygon);
                $point[1] = normalizeLongitude($point[1]);
                return isPointInPolygon($polygon, $point);
            }

            foreach ($sewa->getResult() as $sewa) {
                $customer_code = $sewa->customer_code;
                $customer_name = $sewa->customer_name;
                $sewa_driver = $sewa->sewa_driver;
                $sewa_id = $sewa->sewa_id;
                $kota = $this->getCityFromCoordinates($tracking_latitude, $tracking_longitude);
                $input2["tracking_latitude"] = $tracking_latitude;
                $input2["tracking_longitude"] = $tracking_longitude;
                $input2["tracking_kota"] = $kota;
                $input2["tracking_datetime"] = date("Y-m-d H:i:s");
                $input2["customer_code"] = $customer_code;
                $input2["product_ube"] = $product_ube;
                $input2["sewa_driver"] = $sewa_driver;
                $input2["sewa_id"] = $sewa_id;
                $input2["customer_name"] = $customer_name;
                $this->db->table("tracking")->insert($input2);
                // echo $this->db->getLastQuery();                
                $input=$input2;

                // pembatasan jalur
                $marker = $this->db
                    ->table("marker")
                    ->where("sewa_id", $sewa_id)
                    ->orderBy("marker_id", "ASC")
                    ->get();
                if ($marker->getNumRows() > 0) {
                    $polygon = [];
                    foreach ($marker->getResult() as $marker) {
                        $polygon[] = [$marker->marker_latitude, $marker->marker_longitude];
                    }
                    $point = [$tracking_latitude, $tracking_longitude];
                    if (isPointInPolygonWithWrap($polygon, $point)) {
                        $input["validasiwilayah"] = "Titik berada di dalam wilayah";
                    } else {
                        $input["validasiwilayah"] =  "Titik berada di luar wilayah";
                        $store_notifurl = $store->store_notifurl . "?message=Pelanggaran&product_ube=" . $product_ube . "&berhenti=true&catatan=wilayah";
                        $input["notifwilayah"] =  $this->notif($store_notifurl);
                    }
                } else {
                    // Logika pembatasan jalur
                }
            }
        }
        // Encode data jalur menjadi format JSON
        return $this->response->setJSON($input);
    }

    //API cek lewat batas waktu
    public function cekwaktu()
    {
        $input = array();
        //check customer
        $sewa = $this->db
            ->table("sewa")
            ->join("product", "product.product_id=sewa.product_id", "left")
            ->join("customer", "customer.customer_id=sewa.customer_id", "left")
            ->where("sewa.sewa_rpulang <", date("Y-m-d"))
            ->where("sewa.sewa_spulang", null)
            ->get();
        // echo $this->db->getLastQuery();
        if ($sewa->getNumRows() > 0) {
            foreach ($sewa->getResult() as $sewa) {
                $input[$sewa->product_ube]["validasi"] =  "Waktu Sewa Habis!";
                $store = $this->db->table("store")->where("store_id", $sewa->store_id)->get()->getRow();
                $store_notifurl = $store->store_notifurl . "?message=Pelanggaran&product_ube=" . $sewa->product_ube . "&berhenti=true&catatan=waktu";
                $input[$sewa->product_ube]["notif"] =  $this->notif($store_notifurl);
            }
        }
        // Encode data jalur menjadi format JSON
        return $this->response->setJSON($input);
    }


    function notif($url)
    {
        // Inisialisasi cURL
        $ch = curl_init();

        // Set opsi cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Jika SSL tidak tervalidasi
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        // Eksekusi cURL
        $response = curl_exec($ch);

        // Periksa kesalahan
        if (curl_errno($ch)) {
            return "Error: " . curl_error($ch);
        } else {
            return "Data: " . $response;
        }

        // Tutup cURL
        curl_close($ch);
    }
}
