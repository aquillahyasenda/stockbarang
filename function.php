<?php
session_start();

//Membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","stockbarang");

//Menambah barang baru
if(isset($_POST['addnewbuku'])){
    $namabuku = $_POST['namabuku'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    
    $addtotable = mysqli_query($conn, "insert into stock (namabuku, deskripsi, stock) values('$namabuku','$deskripsi','$stock')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    
    }
};

//Menambah barang masuk
if(isset($_POST['bukumasuk'])){
    $bukunya = $_POST['bukunya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbuku='$bukunya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbuku, keterangan, qty) values('$bukunya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbuku='$bukunya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    
    }
}

//Menambah barang keluar
if(isset($_POST['addbukukeluar'])){
    $bukunya = $_POST['bukunya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbuku='$bukunya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;

    $addtokeluar = mysqli_query($conn, "insert into keluar (idbuku, penerima, qty) values('$bukunya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbuku='$bukunya'");
    if($addtokeluar&&$updatestockmasuk){
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
    
    }
}

//update info barang
if(isset($_POST['updatebuku'])){
    $idb = $_POST['idb'];
    $namabuku = $_POST['namabuku'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn,"update stock set namabuku='$namabuku', deskripsi='$deskripsi' where idbuku ='$idb'");
    if($update){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    
    }
}

//Menghapus barang dari stock
if(isset($_POST['hapusbuku'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn,"delete from stock where idbuku='$idb'");
    if($hapus){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    
    }
};

//Mengubah data barang masuk
if(isset($_POST['updatebukumasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"select * from stock where idbuku='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg + $selisih; 
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbuku='$idb'");
        $updatenya = mysqli_query($conn,"update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
        if($kurangistocknya&&$updatenya){
          header('location:masuk.php');
            } else {
            echo 'Gagal';
            header('location:masuk.php');
    
    }
    }else{
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg - $selisih; 
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbuku='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
        if($kurangistocknya&&$updatenya){
          header('location:masuk.php');
            } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    }
}

//Menghapus barang masuk
if(isset($_POST['hapusbukumasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbuku='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok-$qty;

    $update = mysqli_query($conn,"update stock set stock='$selisih' where idbuku='$idb'");
    $hapusdata = mysqli_query($conn,"delete from masuk where idmasuk='$idm'");

    if($update&&$hapusdata){
        header('location:masuk.php');
    }else{
        header('location:masuk.php');
    }
}

//Mengubah data buku keluar
if(isset($_POST['updatebukukeluar'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idk'];
    $deskripsi = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"select * from stock where idbuku='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg - $selisih; 
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbuku='$idb'");
        $updatenya = mysqli_query($conn,"update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
        if($kurangistocknya&&$updatenya){
          header('location:keluar.php');
            } else {
            echo 'Gagal';
            header('location:keluar.php');
    
    }
    }else{
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg + $selisih; 
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbuku='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
        if($kurangistocknya&&$updatenya){
          header('location:keluar.php');
            } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    }
}

//Menghapus barang keluar
if(isset($_POST['hapusbukukeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbuku='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok+$qty;

    $update = mysqli_query($conn,"update stock set stock='$selisih' where idbuku='$idb'");
    $hapusdata = mysqli_query($conn,"delete from keluar where idkeluar='$idk'");

    if($update&&$hapusdata){
        header('location:keluar.php');
    }else{
        header('location:keluar.php');
    }
}


?>