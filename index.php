<?php
$host   = "localhost";
$user   = "root";
$pass   = "";
$db     = "gudang/store";

$koneksi    = mysqli_connect($host,$user,$pass,$db);
if(!$koneksi){ //mengecek keadaan koneksi
    die("Tidak bisa terhubung dengan basis data");
}
$Nama       = "";
$Jenis      = "";
$TglMasuk   = "";
$TglKeluar  = "";
$sukses     = "";
$error      = "";

if(isset($_GET['op'])){
    $op     = $_GET['op'];
}else{
    $op     = "";
}
if($op == 'delete'){
    $id        = $_GET['id'];
    $sql1      = "delete from barang where id = '$id'";
    $q1        = mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses = "Berhasil Menghapus Data";
    }else{
        $error = "Gagal Menghapus Data";
    }
}

if($op == 'edit'){
    $id       = $_GET['id'];
    $sql1     = "select * from barang where id = '$id'";
    $q1       = mysqli_query($koneksi,$sql1);
    $r1       = mysqli_fetch_array($q1);
    $Jenis    = $r1['Jenis'];
    $Nama     = $r1['Nama'];
    $TglMasuk = $r1['TglMasuk'];
    $TglKeluar= $r1['TglKeluar'];

    if($Jenis == ''){
        $error  = "Data tidak ditemukan";
    }
}
if(isset($_POST['simpan'])){//untuk create
    $Nama       = $_POST['Nama'];
    $Jenis      = $_POST['Jenis'];
    $TglMasuk   = $_POST['TglMasuk'];
    $TglKeluar  = $_POST['TglKeluar'];

    if($Nama && $Jenis && $TglMasuk && $TglKeluar){
        if($op == 'edit'){ //update
            $sql1       = "update Barang set Jenis = '$Jenis', Nama = '$Nama', TglMasuk = '$TglMasuk', TglKeluar = '$TglKeluar' where id = '$id'";
            $q1         = mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses     = "Data berhasil diupdate";
            }else{
                $error      = "Data Gagal diupdate";
            }
        }else{
            $sql1   ="insert into barang(Nama,Jenis,TglMasuk,TglKeluar) values ('$Nama','$Jenis','$TglMasuk','$TglKeluar')";
        $q1     = mysqli_query($koneksi,$sql1);
        if($q1){
            $sukses     = "Berhasil Menginput Data Baru";
        }else{
            $error      = "Gagal Menginput Data";
        }
        }
        
    }else{//insert
        $error="Mohon Masukkan Data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .mx-auto {width: 800px;}
        .card {margin-top: 20px;}
    </style>
</head>

<body>
    <div class="max-auto">
        <!--untuk input data--->
    <div class="card">
        <div class ="card-header text-black bg-info">
            Create / Edit Data
        </div>
        <div class="card-body">
            <?php
            if($error){
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error ?>
                </div>
                <?php
                header("refresh:5;url=index.php");
            }
            ?>

            <?php
            if($sukses){
                ?>
                <div class="alert alert-success" role="alaert">
                    <?php echo $sukses ?>
                </div>
                <?php
            }
            ?>
            <form action="" method="POST">
                <div class="mb-3 row">
                <label for="Jenis" class="col-sm-2 col-form-label">Jenis</label>
                <div class="col-sm-10">
                    <select class="form-control" name="Jenis" id="Jenis">
                        <option value="">-Pilih Jenis Barang-</option>
                        <option value="Substitusi"<?php if($Jenis == "Substitusi") echo"Selected"?>>Substitusi</option>
                        <option value="Komplementer"<?php if($Jenis == "Komplementer") echo"Selected"?>>Komplementer</option>
                </select>
                </div>
                </div>
                <div class="mb-3 row">
                <label for="Nama" class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="Nama" name="Nama" value="<?php echo $Nama?>">
                </div>
                </div>
                <div class="mb-3 row">
                <label for="TglMasuk" class="col-sm-2 col-form-label">TglMasuk</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="TglMasuk" name="TglMasuk"value="<?php echo $TglMasuk?>">
                </div>
                </div>
                <div class="mb-3 row">
                <label for="TglKeluar" class="col-sm-2 col-form-label">TglKeluar</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="TglKeluar" name="TglKeluar"value="<?php echo $TglKeluar?>">
                </div>
                </div>
                <div class="col-12">
                    <input type="submit" name="simpan" value="simpan data" class="btn btn-success"/>
            </form> 
        </div>
        </div>
        <!--untuk output data-->
        <div class="card">
        <div class ="card-header text-white bg-primary">
            Data Gudang
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Tanggal Masuk</th>
                        <th scope="col">Tanggal Keluar</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    <tbody>
                        <?php
                        $sql2   = "select * from barang order by id desc";
                        $q2     = mysqli_query($koneksi,$sql2);
                        $urut   = 1;
                        while($r2 = mysqli_fetch_array($q2)){
                            $id         = $r2['id'];
                            $Jenis      = $r2['Jenis'];
                            $Nama       = $r2['Nama'];
                            $TglMasuk   = $r2['TglMasuk'];
                            $TglKeluar  = $r2['TglKeluar'];

                            ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $Jenis?></td>
                                <td scope="row"><?php echo $Nama?></td>
                                <td scope="row"><?php echo $TglMasuk?></td>
                                <td scope="row"><?php echo $TglKeluar?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php>op=delete&id=<?php echo $id?>" onclick="return confirm('Apakah anda yakin ingin menghapus Data?')"><button type="button" class="btn btn-danger">Hapus</button></a>
                                    
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </thead>
            </table>
        </div>
        </div>
    </div>   
</body>
</html>