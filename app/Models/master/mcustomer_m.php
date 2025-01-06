<?php

namespace App\Models\master;

use App\Models\core_m;

class mcustomer_m extends core_m
{
    function generateUniqueCode($name, $date)
    {
        // Ambil 3 huruf pertama dari nama
        $initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $name), 0, 3));

        // Format tanggal menjadi tahunbulantgl (YYMMDD)
        $formattedDate = date('ymd', strtotime($date));

        // Generate 3 digit angka random
        $randomNumber = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

        // Gabungkan semuanya
        return $initials . $formattedDate . $randomNumber;
    }

    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek customer
        if ($this->request->getVar("customer_id")) {
            $customerd["customer_id"] = $this->request->getVar("customer_id");
        } else {
            $customerd["customer_id"] = -1;
        }
        $us = $this->db
            ->table("customer")
            ->getWhere($customerd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "customer_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $customer) {
                foreach ($this->db->getFieldNames('customer') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $customer->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('customer') as $field) {
                $data[$field] = "";
            }
        }

        //upload image
        $data['uploadcustomer_picture'] = "";
        if (isset($_FILES['customer_picture']) && $_FILES['customer_picture']['name'] != "") {
            // $request = \Config\Services::request();
            $file = $this->request->getFile('customer_picture');
            $name = $file->getName(); // Mengetahui Nama File
            $originalName = $file->getClientName(); // Mengetahui Nama Asli
            $tempfile = $file->getTempName(); // Mengetahui Nama TMP File name
            $ext = $file->getClientExtension(); // Mengetahui extensi File
            $type = $file->getClientMimeType(); // Mengetahui Mime File
            $size_kb = $file->getSize('kb'); // Mengetahui Ukuran File dalam kb
            $size_mb = $file->getSize('mb'); // Mengetahui Ukuran File dalam mb


            //$namabaru = $file->getRandomName();//define nama fiel yang baru secara acak

            if ($type == 'image/jpg' || $type == 'image/jpeg' || $type == 'image/png') //cek mime file
            {    // File Tipe Sesuai   
                helper('filesystem'); // Load Helper File System
                $direktori = ROOTPATH . 'images\customer_picture'; //definisikan direktori upload            
                $customer_picture = str_replace(' ', '_', $name);
                $customer_picture = date("H_i_s_") . $customer_picture; //definisikan nama fiel yang baru
                $map = directory_map($direktori, FALSE, TRUE); // List direktori

                //Cek File apakah ada 
                foreach ($map as $key) {
                    if ($key == $customer_picture) {
                        delete_files($direktori, $customer_picture); //Hapus terlebih dahulu jika file ada
                    }
                }
                //Metode Upload Pilih salah satu
                //$path = $this->request->getFile('uploadedFile')->customer($direktori, $namabaru);
                //$file->move($direktori, $namabaru)
                if ($file->move($direktori, $customer_picture)) {
                    $data['uploadcustomer_picture'] = "Upload Success !";
                    $input['customer_picture'] = $customer_picture;
                } else {
                    $data['uploadcustomer_picture'] = "Upload Gagal !";
                }
            } else {
                // File Tipe Tidak Sesuai
                $data['uploadcustomer_picture'] = "Format File Salah !";
            }
        }

        //delete
        if ($this->request->getPost("delete") == "OK") {
            $customer_id =   $this->request->getPost("customer_id");
            $this->db
                ->table("customer")
                ->delete(array("customer_id" =>  $customer_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'customer_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $name = $input["customer_name"];
            $date = date("Y-m-d"); // Format YYYY-MM-DD
            $uniqueCode = $this->generateUniqueCode($name, $date);

            $input["customer_code"] = $uniqueCode;

            $builder = $this->db->table('customer');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $customer_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'customer_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('customer')->update($input, array("customer_id" => $this->request->getPost("customer_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
