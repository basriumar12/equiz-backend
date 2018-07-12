<?php
if($_SERVER['REQUEST_METHOD']=='POST') {
    $response = array();
    //mendapatakn data
    $id_quiz = $_POST['id_quiz'];
    $nama_pengguna = $_POST['nama_pengguna'];
    require_once('../koneksi.php');
    
    $sql1 = "SELECT * FROM nilai
            WHERE id_quiz = '$id_quiz'
            AND nama_pengguna_mahasiswa = '$nama_pengguna'";
    $check1 = mysqli_fetch_array(mysqli_query($con,$sql1));
    if(isset($check1)) {
        $response['value'] = 0;
        $response['message'] = 'Anda sudah pernah mengikuti quiz ini.';
        echo json_encode($response);
    } else {
        $sql = "SELECT * FROM quiz
                INNER JOIN dosen
                WHERE id_quiz = '$id_quiz'
                AND terbit = '1'
                AND quiz.nama_pengguna_dosen=dosen.nama_pengguna_dosen";
        $check = mysqli_fetch_array(mysqli_query($con,$sql));
        if(isset($check)) {
            $response["value"] = 1;
            $response["message"] = "Quiz ditemukan";
            $response["id_quiz"] = $check['id_quiz'];
            $response["judul"] = $check['judul'];
            $response['jumlah_soal'] = $check['jumlah_soal'];
            $response['waktu_pengerjaan_soal'] = $check['waktu_pengerjaan_soal'];
            $datetime = explode(' ', $check['waktu_akhir_pengerjaan']);
            $date = explode('-', $datetime[0]);
            $time = explode(':', $datetime[1]);
            $tahun = $date[0];
            $bulan = $date[1];
            $tanggal = $date[2];
            $jam = $time[0];
            $menit = $time[1];
            $response['tahun'] = $tahun;
            $response['bulan'] = $bulan;
            $response['tanggal'] = $tanggal;
            $response['jam'] = $jam;
            $response['menit'] = $menit;
            $response["nama_dosen"] = $check['nama_dosen'];
            echo json_encode($response);
        } else {
            $response["value"] = 0;
            $response["message"] = "Oops! Quiz tidak ditemukan!";
            echo json_encode($response);
        }
    }
    // tutup database
    mysqli_close($con);
} else {
    $response["value"] = 0;
    $response["message"] = "Oops! Coba lagi!";
    echo json_encode($response);
}