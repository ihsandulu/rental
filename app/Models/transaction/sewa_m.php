<?php

namespace App\Models\transaction;

use App\Models\core_m;

class sewa_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek sewa
        if ($this->request->getVar("sewa_id")) {
            $sewad["sewa_id"] = $this->request->getVar("sewa_id");
        } else {
            $sewad["sewa_id"] = -1;
        }

        $us = $this->db
            ->table("sewa")
            ->getWhere($sewad);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "sewa_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $sewa) {
                foreach ($this->db->getFieldNames('sewa') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $sewa->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('sewa') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $sewa_id =   $this->request->getPost("sewa_id");
            $this->db
                ->table("marker")
                ->delete(array("sewa_id" =>  $sewa_id));
            $this->db
                ->table("sewa")
                ->delete(array("sewa_id" =>  $sewa_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'sewa_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }

            //bulan romawi		
            $array_bulan = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
            $bulan = $array_bulan[date('n')];


            $quno = $this->db
                ->table("sewa")
                ->orderBy("sewa_id", "desc")
                ->limit("1")
                ->get();
            if ($quno->getNumRows() > 0) {
                //caribulan
                $sewaterakhir = $quno->getRow()->sewa_no;
                $blsewa = explode("-", $sewaterakhir);
                $blnsewa = $blsewa[1];
                $noterakhir = end($blsewa);
                if ($blnsewa != $bulan) {
                    $sewa_no = 1;
                } else {
                    $sewa_no = $noterakhir + 1;
                    //$sewa_no=1;
                }
            } else {
                $sewa_no = 1;
            }
            $input["sewa_no"] = "RNT-" . $bulan . date("-Y-") . str_pad($sewa_no, 5, "0", STR_PAD_LEFT);
            $input["sewa_date"] = date("Y-m-d");
            // print_r($input);die;
            $builder = $this->db->table('sewa');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $sewa_id = $this->db->insertID();
            $sewatemp =   $this->request->getPost("sewa_id");
            $this->db->table("marker")->where(array("sewa_id" => $sewatemp))->update(array("sewa_id" => $sewa_id));
            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'sewa_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('sewa')->update($input, array("sewa_id" => $this->request->getPost("sewa_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
