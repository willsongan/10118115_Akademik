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
  $sql = "SELECT nim,nama_mhs FROM mahasiswa";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo "<table><tr><th>NIM</th><th>NAMA_MHS</th><th>aksi</th></tr>";
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      echo "<tr><td>",$row["nim"],"</td><td>",$row["nama_mhs"],"</td>
      <td>
          <a href='mahasiswa.php?aksi=update&nim=",$row["nim"],"&namamhs=",$row['nama_mhs'],"'>Edit</a> |
          <a href='mahasiswa.php?aksi=delete&nim=",$row['nim'],"&namamhs=",$row['nama_mhs'],"'>Hapus</a>
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
  $NAMA=$_GET["namamhs"];

  if(!empty($NIM) && !empty($NAMA)){
    $sql = "INSERT INTO mahasiswa (nim,nama_mhs) VALUES ('$NIM','$NAMA')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      echo "New record created successfully
      <br></br>
      <a href='mahasiswa.php'>click here to go back</a>";
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
  $NAMA=$_GET["namamhs"];

  $sql = "UPDATE mahasiswa SET nama_mhs='$NAMA' WHERE nim=$NIM";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Record created successfully
    <br></br>
    <a href='mahasiswa.php'>click here to go back</a>";
  } else {
    echo "0 results";
  }//endif
}//endfunction

/* Delete data */
function hapus_data($conn){
  $NIM=$_GET["nim"];

  $sql = "DELETE FROM mahasiswa WHERE nim=$NIM";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "Record deleted successfully<br></br>
    <a href='mahasiswa.php'>click here to go back</a>";
  } else {
    echo "0 results";
  }//endif
}//endfunction

//hitung_nim
function hitung_nim($conn){
  $query10 = mysqli_query($conn, "SELECT IFNULL(MAX(nim),10118000) as kodeTerbesar FROM mahasiswa");
	$data10 = mysqli_fetch_array($query10);
	$NIM = $data10['kodeTerbesar'];
  return ++$NIM;
}
/*================END FUNCTION SQL==================*/

if(ISSET($_GET['aksi'])){
  if($_GET['aksi']=='update'){
    echo
    "<form action='mahasiswa.php' method='get'>
    <fieldset>
    <legend>FORM MAHASISWA:</legend>
    nim:<br><input type='text' value='",$_GET['nim'],"' id='nim' name='nim'><br>
    Nama MAHASISWA:<br><input type='text' id='namamhs' name='namamhs'><br><br>
    <input type='hidden' id='aksi' name='aksi' value='update_confirm'>
    <input type='submit' value='ubah'>
    </form>
    <a href='mahasiswa.php'>cancel</a>";
  } elseif ($_GET['aksi']=='delete') {
    echo
    "<form action='mahasiswa.php' method='get'>
    <fieldset>
    <legend>FORM MAHASISWA:</legend>
    nim:<br><input type='text' value='",$_GET['nim'],"' id='nim' name='nim'><br>
    Nama MAHASISWA:<br><input type='text' value='",$_GET['namamhs'],"' id='namamhs' name='namamhs'><br><br>
    <input type='hidden' id='aksi' name='aksi' value='delete_confirm'>
    <input type='submit' value='hapus'>
    </form>
    <a href='mahasiswa.php'>cancel</a>";
  }
} else {
    echo
    "<form action='mahasiswa.php' method='get'>
    <fieldset>
    <legend>FORM MAHASISWA:</legend>
    nim:<br><input type='text' value='",hitung_nim($conn),"' id='nim' name='nim'><br>
    Nama MAHASISWA:<br><input type='text' id='namamhs' name='namamhs'><br><br>
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
