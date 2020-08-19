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
  $sql = "SELECT nim,nip,kode_mk,nilai FROM mengikuti";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo "<table><tr><th>NIM</th><th>NIP</th><th>KODE_MK</th><th>NILAI</th><th>EDIT</th></tr>";
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      echo "<tr><td>",$row["nim"],"</td><td>",$row["nip"],"</td><td>",$row["kode_mk"],"</td><td>",$row["nilai"],"</td>
      <td>
          <a href='mengikuti.php?aksi=update&nim=",$row["nim"],"&nip=",$row['nip'],"&kode_mk=",$row['kode_mk'],"&nilai=",$row['nilai'],"'>Edit</a> |
          <a href='mengikuti.php?aksi=delete&nim=",$row['nim'],"&nip=",$row['nip'],"&kode_mk=",$row['kode_mk'],"&nilai=",$row['nilai'],"'>Hapus</a>
      </td>
      </tr>";
    }
  } else {
    echo "0 results";
  }//endif
}//endfunction

/* Insert data */
function tambah_data($conn){
  $NIM=$_GET["nim"];
  $NIP=$_GET["nip"];
  $KODE_MK=$_GET["kode_mk"];
  $NILAI=$_GET["nilai"];

  if(!empty($NIM) && !empty($NIP) && !empty($KODE_MK) && !empty($NILAI)){
    $sql = "INSERT INTO mengikuti (nim,nip,kode_mk,nilai) VALUES ('$NIM','$NIP','$KODE_MK','$NILAI')";
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
  $NIM=$_GET["nim"];
  $NIP=$_GET["nip"];
  $KODE_MK=$_GET["kode_mk"];
  $NILAI=$_GET["nilai"];

  $sql = "UPDATE mengikuti SET nilai=$NILAI WHERE nim=$NIM AND nip=$NIP AND kode_mk='$KODE_MK'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Record created successfully";
  } else {
    echo "0 results";
  }//endif
}//endfunction

/* Delete data */
function hapus_data($conn){
  $NIM=$_GET["nim"];
  $NIP=$_GET["nip"];
  $KODE_MK=$_GET["kode_mk"];
  $sql = "DELETE FROM mengikuti WHERE nim=$NIM AND nip=$NIP AND kode_mk='$KODE_MK'";
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
    "<form action='mengikuti.php' method='get'>
    <fieldset>
    <legend>FORM MENGIKUTI:</legend>
    NIM:<input type='text' value='",$_GET['nim'],"' id='nim' name='nim'><br>
    NIP:<input type='text' value='",$_GET['nip'],"' id='nip' name='nip'><br>
    KODE_MK:<input type='text' value='",$_GET['kode_mk'],"' id='kode_mk' name='kode_mk'><br>
    NILAI:<input type='text' value='",$_GET['nilai'],"' id='nilai' name='nilai'><br>
    <input type='hidden' id='aksi' name='aksi' value='delete_confirm'>
    <input type='submit' value='hapus'>
    </form>
    <a href='mengikuti.php'>cancel</a>";
  }

  if($_GET['aksi']=='update'){
    echo
    "<form action='mengikuti.php' method='get'>
    <fieldset>
    <legend>FORM MENGIKUTI:</legend>
    NIM:<input type='text' value='",$_GET['nim'],"' id='nim' name='nim'><br>
    NIP:<input type='text' value='",$_GET['nip'],"' id='nip' name='nip'><br>
    KODE_MK:<input type='text' value='",$_GET['kode_mk'],"' id='kode_mk' name='kode_mk'><br>
    NILAI:<input type='text' id='nilai' name='nilai'><br>
    <input type='hidden' id='aksi' name='aksi' value='update_confirm'>
    <input type='submit' value='ubah'>
    </form>
    <a href='mengikuti.php'>cancel</a>";
  }

} else {
  $sql1 = "SELECT nim FROM mahasiswa";
  $result = mysqli_query($conn, $sql1);

  if (mysqli_num_rows($result) > 0) {
    echo "<form action='mengikuti.php' method='get'>
          <fieldset>
          <legend>FORM MENGIKUTI:</legend>
          NIM:
          <select name = 'nim'>";
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      echo "<option>",$row["nim"],"</option>";
    }//endwhile
    echo "</select><br>";
  }//endif

  $sql2 = "SELECT nip FROM dosen";
  $result = mysqli_query($conn, $sql2);

  if (mysqli_num_rows($result) > 0) {
    echo "NIP:
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
    echo "</select><br>";
  }//endif

  echo "  NILAI: <input type='text' id='nilai' name='nilai'>
          <input type='hidden' id='aksi' name='aksi' value='insert'><br>
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
    case 'update':
      tampil_data($conn);
      break;
    case 'update_confirm':
      ubah_data($conn);
      tampil_data($conn);
      break;

  }
} else {
  tampil_data($conn);
}

mysqli_close($conn);
?>
