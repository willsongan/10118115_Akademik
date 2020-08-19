<?php
require_once "index.html";
echo "<section class='content'>";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "akademik";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

/*================FUNCTION SQL==================*/
/* Read data */
function tampil_data($conn){
  $sql = "SELECT kode_mk,nama_mk,sks FROM matakuliah";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo "<table><tr><th>kode_mk</th><th>nama_mk</th><th>sks</th><th>aksi</th></tr>";
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      echo "<tr><td>",$row["kode_mk"],"</td><td>",$row["nama_mk"],"</td><td>",$row['sks'],"</td>
      <td>
          <a href='matakuliah.php?aksi=update&kode_mk=",$row["kode_mk"],"&nama_mk=",$row['nama_mk'],"&sks=",$row["sks"],"'>Edit</a> |
          <a href='matakuliah.php?aksi=delete&kode_mk=",$row['kode_mk'],"&nama_mk=",$row['nama_mk'],"&sks=",$row["sks"],"'>Hapus</a>
      </td>
      </tr>";
    }
  } else {
    echo "0 results";
  }//endif
}//endfunction

/* Insert data */
function tambah_data($conn){
  $KODE_MK=$_GET["kode_mk"];
  $NAMA=$_GET["nama_mk"];
  $SKS=$_GET["sks"];

  if(!empty($KODE_MK) && !empty($NAMA) && !empty($SKS)){
    $sql = "INSERT INTO matakuliah (kode_mk,nama_mk,sks) VALUES ('$KODE_MK','$NAMA','$SKS')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      echo "New record created successfully
      <br></br>
      <a href='matakuliah.php'>click here to go back</a>";
    } else {
      echo "0 results, data sudah ada";
    }//endif
  } else{
    echo "insert gagal, data tidak lengkap";
  }//endif
}//endfunction

/* Edit data */
function ubah_data($conn){
  $KODE_MK=$_GET["kode_mk"];
  $NAMA=$_GET["nama_mk"];
  $SKS=$_GET["sks"];

  $sql = "UPDATE matakuliah SET nama_mk='$NAMA',sks=$SKS WHERE kode_mk='$KODE_MK'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Record created successfully
    <br></br>
    <a href='matakuliah.php'>click here to go back</a>";
  } else {
    echo "0 results";
  }//endif
}//endfunction

/* Delete data */
function hapus_data($conn){
  $KODE_MK=$_GET["kode_mk"];

  $sql = "DELETE FROM matakuliah WHERE kode_mk='$KODE_MK'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Record deleted successfully
    <br></br>
    <a href='matakuliah.php'>click here to go back</a>";
  } else {
    echo "0 results";
  }//endif
}//endfunction

//hitung_kode_mk
function hitung_kode_mk($conn){
  $query10 = mysqli_query($conn, "SELECT IFNULL(MAX(kode_mk),'IF1000') as kodeTerbesar FROM matakuliah");
	$data10 = mysqli_fetch_array($query10);
	$KODE_MK = $data10['kodeTerbesar'];
  $urutan = (int) substr($KODE_MK, 2, 4);
  ++$urutan;
  $huruf = "IF";
  $KODE_MK = $huruf . sprintf("%02s", $urutan);
  return $KODE_MK;
}
/*================END FUNCTION SQL==================*/

if(ISSET($_GET['aksi'])){
  if($_GET['aksi']=='update'){
    echo
    "<form action='matakuliah.php' method='get'>
    <fieldset>
    <legend>FORM matakuliah:</legend>
    kode_mk:<br><input type='text' value='",$_GET['kode_mk'],"' id='kode_mk' name='kode_mk' readonly><br>
    Nama matakuliah:<br><input type='text' id='nama_mk' name='nama_mk'><br>
    SKS:<br><input type='text' id='sks' name='sks'><br><br>
    <input type='hidden' id='aksi' name='aksi' value='update_confirm'>
    <input type='submit' value='ubah'>
    </form>
    <a href='matakuliah.php'>cancel</a>";
  } elseif ($_GET['aksi']=='delete') {
    echo
    "<form action='matakuliah.php' method='get'>
    <fieldset>
    <legend>FORM matakuliah:</legend>
    kode_mk:<br><input type='text' value='",$_GET['kode_mk'],"' id='kode_mk' name='kode_mk' readonly><br>
    Nama matakuliah:<br><input type='text' value='",$_GET['nama_mk'],"' id='nama_mk' name='nama_mk' readonly><br>
    SKS:<br><input type='text' value='",$_GET['sks'],"' id='sks' name='sks' readonly><br><br>
    <input type='hidden' id='aksi' name='aksi' value='delete_confirm'>
    <input type='submit' value='hapus'>
    </form>
    <a href='matakuliah.php'>cancel</a>";
  }
} else {
    echo
    "<form action='matakuliah.php' method='get'>
    <fieldset>
    <legend>FORM matakuliah:</legend>
    kode_mk:<br><input type='text' value='",hitung_kode_mk($conn),"' id='kode_mk' name='kode_mk' readonly><br>
    Nama matakuliah:<br><input type='text' id='nama_mk' name='nama_mk'><br>
    SKS:<br><input type='text' id='sks' name='sks'><br><br>
    <input type='hidden' id='aksi' name='aksi' value='insert'>
    <input type='submit' value='simpan'>
    </form>";

  }


// Program utama
if(ISSET($_GET['aksi'])){
  switch ($_GET['aksi']) {
    case 'insert':
      tambah_data($conn);
      tampil_data($conn);
      break;
    case 'update':
      tampil_data($conn);
      break;
    case 'update_confirm':
      ubah_data($conn);
      tampil_data($conn);
      break;
    case 'delete':
      tampil_data($conn);
      break;
    case 'delete_confirm':
      hapus_data($conn);
      tampil_data($conn);
      break;

  }
} else {
  tampil_data($conn);
}

mysqli_close($conn);
?>
