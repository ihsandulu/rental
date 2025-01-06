<?php

namespace App\Models\transaction;

use App\Models\core_m;

class tracking_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek tracking
        /* if ($this->request->getVar("tracking_id")) {
            $trackingd["tracking_id"] = $this->request->getVar("tracking_id");
        } else {
            $trackingd["tracking_id"] = -1;
        }

        $us = $this->db
            ->table("tracking")
            ->getWhere($trackingd);
        $larang = array("log_id", "id", "user_id", "action", "data", "tracking_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $tracking) {
                foreach ($this->db->getFieldNames('tracking') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $tracking->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('tracking') as $field) {
                $data[$field] = "";
            }
        } */



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $tracking_id =   $this->request->getPost("tracking_id");
            $this->db
                ->table("tracking")
                ->delete(array("tracking_id" =>  $tracking_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'tracking_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $builder = $this->db->table('tracking');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $tracking_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'tracking_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('tracking')->update($input, array("tracking_id" => $this->request->getPost("tracking_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
