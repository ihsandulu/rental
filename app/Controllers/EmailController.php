<?php 
namespace App\Controllers;

use CodeIgniter\Controller;

class EmailController extends Controller
{
    public function sendEmail()
    {
        // Inisialisasi email library
        $email = \Config\Services::email();

        // Set pengirim, penerima, subjek, dan pesan
        $email->setFrom('admin@rental.qithy.com', 'Admin Rental Qithy');
        $email->setTo('ihsan.dulu@gmail.com');
        $email->setSubject('Test Email');
        $email->setMessage('Isi pesan email');

        // Mengirim email
        if ($email->send()) {
            echo 'Email berhasil dikirim.';
        } else {
            echo 'Email gagal dikirim.';
            // Menampilkan error jika ada
            print_r($email->printDebugger());
        }
    }
}
