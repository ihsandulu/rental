<?php

namespace App\Models\transaction;

use App\Models\core_m;

class pengeluaran_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek pengeluaran
        if ($this->request->getVar("pengeluaran_id")) {
            $pengeluarand["pengeluaran_id"] = $this->request->getVar("pengeluaran_id");
        } else {
            $pengeluarand["pengeluaran_id"] = -1;
        }

        $us = $this->db
            ->table("pengeluaran")
            ->getWhere($pengeluarand);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "pengeluaran_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $pengeluaran) {
                foreach ($this->db->getFieldNames('pengeluaran') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $pengeluaran->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('pengeluaran') as $field) {
                $data[$field] = "";
            }
        }



        //delete
        if ($this->request->getPost("delete") == "OK") {
            $pengeluaran_id =   $this->request->getPost("pengeluaran_id");
            $this->db
                ->table("pengeluaran")
                ->delete(array("pengeluaran_id" =>  $pengeluaran_id));
            $data["message"] = "Delete Success";
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'pengeluaran_id') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $builder = $this->db->table('pengeluaran');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $pengeluaran_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";
        }
        //echo $_POST["create"];die;

        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'pengeluaran_picture') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $this->db->table('pengeluaran')->update($input, array("pengeluaran_id" => $this->request->getPost("pengeluaran_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;
        }
        return $data;
    }
}
