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
  $sql = "SELECT nip,nama_dosen FROM dosen";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo "<table><tr><th>NIP</th><th>NAMA_DOSEN</th><th>aksi</th></tr>";
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      echo "<tr><td>",$row["nip"],"</td><td>",$row["nama_dosen"],"</td>
      <td>
          <a href='dosen.php?aksi=update&nip=",$row["nip"],"&namadosen=",$row['nama_dosen'],"'>Edit</a> |
          <a href='dosen.php?aksi=delete&nip=",$row['nip'],"&namadosen=",$row['nama_dosen'],"'>Hapus</a>
      </td>
      </tr>";
    }
  } else {
    echo "0 results";
  }//endif
}//endfunction

/* Insert data */
function tambah_data($conn){
  $NIP=$_GET["nip"];
  $NAMA=$_GET["namadosen"];

  if(!empty($NIP) && !empty($NAMA)){
    $sql = "INSERT INTO dosen (nip,nama_dosen) VALUES ('$NIP','$NAMA')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      echo "New record created successfully<br></br>
      <a href='dosen.php'>click here to go back</a>";
    } else {
      echo "0 results, data sudah ada";
    }//endif
  } else{
    echo "insert gagal, data tidak lengkap";
  }//endif
}//endfunction

/* Edit data */
function ubah_data($conn){
  $NIP=$_GET["nip"];
  $NAMA=$_GET["namadosen"];

  $sql = "UPDATE dosen SET nama_dosen='$NAMA' WHERE nip=$NIP";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Record created successfully<br></br>
    <a href='dosen.php'>click here to go back</a>";
  } else {
    echo "0 results";
  }//endif
}//endfunction

/* Delete data */
function hapus_data($conn){
  $NIP=$_GET["nip"];
  $sql = "DELETE FROM dosen WHERE nip=$NIP";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Record deleted successfully<br></br>
    <a href='dosen.php'>click here to go back</a>";
  } else {
    echo "0 results";
  }//endif
}//endfunction

//hitung_nip
function hitung_nip($conn){
  $query10 = mysqli_query($conn, "SELECT IFNULL(MAX(nip),86200000) as kodeTerbesar FROM dosen");
	$data10 = mysqli_fetch_array($query10);
	$NIP = $data10['kodeTerbesar'];
  return ++$NIP;
}
/*================END FUNCTION SQL==================*/

if(ISSET($_GET['aksi'])){
  if($_GET['aksi']=='update'){
    echo
    "<form action='dosen.php' method='get'>
    <fieldset>
    <legend>FORM DOSEN:</legend>
    NIP:<br><input type='text' value='",$_GET['nip'],"' id='nip' name='nip' readonly><br>
    Nama Dosen:<br><input type='text' id='namadosen' name='namadosen'><br><br>
    <input type='hidden' id='aksi' name='aksi' value='update_confirm'>
    <input type='submit' value='ubah'>
    </form>
    <a href='dosen.php'>cancel</a>";
  } elseif ($_GET['aksi']=='delete') {
    echo
    "<form action='dosen.php' method='get'>
    <fieldset>
    <legend>FORM DOSEN:</legend>
    NIP:<br><input type='text' value='",$_GET['nip'],"' id='nip' name='nip' readonly><br>
    Nama Dosen:<br><input type='text' value='",$_GET['namadosen'],"' id='namadosen' name='namadosen' readonly><br><br>
    <input type='hidden' id='aksi' name='aksi' value='delete_confirm'>
    <input type='submit' value='hapus'>
    </form>
    <a href='dosen.php'>cancel</a>";
  }
} else {
    echo
    "<form action='dosen.php' method='get'>
    <fieldset>
    <legend>FORM DOSEN:</legend>
    NIP:<br><input type='text' value='",hitung_nip($conn),"' id='nip' name='nip' readonly><br>
    Nama Dosen:<br><input type='text' id='namadosen' name='namadosen'><br><br>
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
