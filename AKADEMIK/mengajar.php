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
  $sql = "SELECT nip,kode_mk FROM mengajar";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo "<table><tr><th>NIP</th><th>KODE_MK</th><th>aksi</th></tr>";
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      echo "<tr><td>",$row["nip"],"</td><td>",$row["kode_mk"],"</td>
      <td>
          <a href='mengajar.php?aksi=delete&nip=",$row['nip'],"&kode_mk=",$row['kode_mk'],"'>Hapus</a>
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
  $KODE_MK=$_GET["kode_mk"];

  if(!empty($NIP) && !empty($KODE_MK)){
    $sql = "INSERT INTO mengajar (nip,kode_mk) VALUES ('$NIP','$KODE_MK')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      echo "New record created successfully";
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
  $KODE_MK=$_GET["kode_mk"];

  $sql = "UPDATE mengajar SET kode_mk='$KODE_MK' WHERE nip=$NIP";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Record created successfully";
  } else {
    echo "0 results";
  }//endif
}//endfunction

/* Delete data */
function hapus_data($conn){
  $NIP=$_GET["nip"];
  $KODE_MK=$_GET["kode_mk"];
  $sql = "DELETE FROM mengajar WHERE nip=$NIP AND kode_mk='$KODE_MK'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Record deleted successfully";
  } else {
    echo "0 results";
  }//endif
}//endfunction
/*================END FUNCTION SQL==================*/
if(ISSET($_GET['aksi'])){
   if ($_GET['aksi']=='delete') {
    echo
    "<form action='mengajar.php' method='get'>
    <fieldset>
    <legend>FORM MENGAJAR:</legend>
    NIP:<br><input type='text' value='",$_GET['nip'],"' id='nip' name='nip'><br>
    KODE_MK:<br><input type='text' value='",$_GET['kode_mk'],"' id='kode_mk' name='kode_mk'><br><br>
    <input type='hidden' id='aksi' name='aksi' value='delete_confirm'>
    <input type='submit' value='hapus'>
    </form>
    <a href='mengajar.php'>cancel</a>";
  }
} else {
  $sql2 = "SELECT nip FROM dosen";
  $result = mysqli_query($conn, $sql2);

  if (mysqli_num_rows($result) > 0) {
    echo "<form action='mengajar.php' method='get'>
          <fieldset>
          <legend>FORM MENGAJAR:</legend>
          NIP:
          <select name = 'nip'>";
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      echo "<option>",$row["nip"],"</option>";
    }//endwhile
    echo "</select><br>";
  }//endif

  $sql3 = "SELECT kode_mk FROM matakuliah";
  $result = mysqli_query($conn, $sql3);
  if (mysqli_num_rows($result) > 0) {
    echo "KODE_MK:
          <select name = 'kode_mk'>";
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      echo "<option>",$row["kode_mk"],"</option>";
    }//endwhile
    echo "</select>";
  }//endif

  echo "<input type='hidden' id='aksi' name='aksi' value='insert'><br>
          <input type='submit' value='simpan'>
          </form>";
}//endif


// Program utama
if(ISSET($_GET['aksi'])){
  switch ($_GET['aksi']) {
    case 'insert':
      tambah_data($conn);
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
