<?php

namespace App\Models;



class login_m extends core_m
{
    function sendEmail($user)
    {
        $email = \Config\Services::email();

        $email->setTo('ihsan.dulu@gmail.com');  // Email penerima
        $email->setFrom('admin@rental.qithy.com', 'Rental Qithy'); // Email pengirim
        $email->setSubject('Login Masuk');
        $email->setMessage('<p>Telah login user:' . $user . '. Pada : ' . date("Y-m-d H:i:s") . '</p>');

        if ($email->send()) {
            echo "Email berhasil dikirim.";
        } else {
            echo "Email gagal dikirim.";
            echo $email->printDebugger(['headers']);
        }
    }

    public function index()
    {
        //require_once("meta_m.php");
        $data = array();
        $data["message"] = "";
        $data["hasil"] = "";
        $data['masuk'] = 0;




        if (isset($_POST["email"]) && isset($_POST["password"])) {
            $builder = $this->db->table("user")
                ->select("*, user.store_id AS store_id")
                ->join("position", "position.position_id=user.position_id", "left")
                ->join("store", "store.store_id=user.store_id", "left")
                ->where("user_email", $this->request->getVar("email"))
                ->where("user.store_id", $this->request->getVar("storeid"));
            $user1 = $builder
                ->get();



            // define('production',$this->db->database);
            // echo production;
            // $lastquery = $this->db->getLastQuery();
            // echo $lastquery;
            // die;
            //    $query = $this->db->query("SELECT * FROM `user`  WHERE `user_email` = 'ihsan.dulu@gmail.com'");
            //     echo $query->getFieldCount();
            // die;

            $halaman = array();
            if ($user1->getNumRows() > 0) {
                foreach ($user1->getResult() as $user) {
                    $password = $user->user_password;
                    if (password_verify($this->request->getVar("password"), $password)) {

                        // echo $user->store_id;die;
                        $this->session->set("position_administrator", $user->position_administrator);
                        $this->session->set("position_name", $user->position_name);
                        $this->session->set("user_name", $user->user_name);
                        $this->session->set("user_id", $user->user_id);
                        $this->session->set("store_id", $user->store_id);
                        $this->session->set("store_name", $user->store_name);
                        $this->session->set("store_picture", $user->store_picture);
                        $this->session->set("store_phone", $user->store_phone);
                        $this->session->set("store_address", $user->store_address);
                        $this->session->set("store_noteinvoice", $user->store_noteinvoice);
                        $this->session->set("store_web", $user->store_web);
                        $this->session->set("store_member", $user->store_member);
                        $this->session->set("store_akun", $user->store_akun);
                        $this->session->set("store_notifurl", $user->store_notifurl);

                        //tambahkan modul di sini                         
                        $pages = $this->db->table("positionpages")
                            ->join("pages", "pages.pages_id=positionpages.pages_id", "left")
                            ->where("position_id", $user->position_id)
                            ->get();
                        foreach ($pages->getResult() as $pages) {
                            // $halaman = array(109, 110, 111, 112, 116, 117, 118, 119, 120, 121, 122, 123, 159, 173,187,188,189,190,192,196);
                            $halaman[$pages->pages_id]['act_read'] = $pages->positionpages_read;
                            $halaman[$pages->pages_id]['act_create'] = $pages->positionpages_create;
                            $halaman[$pages->pages_id]['act_update'] = $pages->positionpages_update;
                            $halaman[$pages->pages_id]['act_delete'] = $pages->positionpages_delete;
                            $halaman[$pages->pages_id]['act_approve'] = $pages->positionpages_approve;
                        }
                        $this->session->set("halaman", $halaman);

                        //Kirim Email
                        $this->sendEmail($user->user_name);
                        
                        $data["hasil"] = " Selamat Datang  " . $user->user_name;
                        $this->session->setFlashdata('hasil', $data["hasil"]);
                        $data['masuk'] = 1;
                    } else {
                        $data["hasil"] = " Password Salah !";
                        // $data["hasil"]=password_verify('123456', '123456').">>>".$this->request->getVar("password").">>>".$password;
                    }
                }
            } else {
                $data["hasil"] = " Email Salah !";
            }
        }

        $this->session->setFlashdata('message', $data["hasil"]);
        return $data;
    }
}
