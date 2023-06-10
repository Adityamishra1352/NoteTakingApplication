<?php
//Connecting to the database
$insert=false;
$update=false;
$delete=false;
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";
$conn = mysqli_connect($servername, $username, $password, $database);
// echo $_POST["snoEdit"];
if (!$conn) {
  die("Failed connecting to the server-->" . mysqli_connect_error());
}
if(isset($_GET['delete'])){
  $sno=$_GET['delete'];
  // $delete=true;
  $sql="DELETE FROM `notes` WHERE `sno`='$sno'";
  $result=mysqli_query($conn,$sql);
  if($result){
    $delete=true;
  }
}
if($_SERVER['REQUEST_METHOD']=='POST'){
  if(isset($_POST['snoEdit'])){
    $sno=$_POST['snoEdit'];
    $title=$_POST['titleEdit'];
    $description=$_POST['descriptionEdit'];
    // echo $sno. $title . $description;
    $sql="UPDATE `notes` SET `title`='$title', `description`='$description' WHERE `notes`.`sno`='$sno'";
    $result=mysqli_query($conn,$sql);  
    if($result){
      $update=true;
    }
  }
  else{
  $title=$_POST['title'];
  $description=$_POST['description'];
  $sql="INSERT INTO `notes`(`title`,`description`) VALUES ('$title','$description')";
  $result=mysqli_query($conn,$sql);
  if($result){
    $insert=true;
  }
}
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Note Taking Application</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
</head>

<body>
  <!-- Button EDIT modal -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
  Edit Modal
</button> -->

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit This Note</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/crud/index.php" method="post">
      <div class="modal-body">
        <input type="hidden" name="snoEdit" id="snoEdit">
      <div class="mb-3">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" class="form-control" id="titleEdit" name="titleEdit">
      </div>
      <div class="mb-3">
        <label for="desc" class="form-label">Note Description</label>
        <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
    </div>
  </div>
</div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">PHP CRUD</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact Us</a>
          </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>
  <?php
  if($insert){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong>Your note has been submitted successfully!!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  if($update){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong>Your note has been updated successfully!!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  if($delete){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong>Your note has been deleted successfully!!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  ?>
  <div class="container my-4">
    <h2>Add a Note</h2>
    <form action="/crud/index.php" method="post">
      <div class="mb-3">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" class="form-control" id="title" name="title">
      </div>
      <div class="mb-3">
        <label for="desc" class="form-label">Note Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
</div>
  <div class="container">
  <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S.No</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
    $sql = "SELECT * FROM `notes`";
    $result = mysqli_query($conn, $sql);
    $count=1;
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>
      <th scope='row'>".$count."</th>
      <td>".$row['title']."</td>
      <td>".$row['description']."</td>
      <td><button class='edit btn btn-sm btn-primary' id=".$row['sno'].">Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['sno'].">Delete</button></td>
    </tr>";
    
      $count=$count+1;
    }
    ?>
      </tbody>
    </table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
    integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS"
    crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
      let table = new DataTable('#myTable');
    </script>
    <script>
      edits=document.getElementsByClassName('edit');
      Array.from(edits).forEach((element)=>{
        element.addEventListener("click",(e)=>{
          console.log("edit ",);
          tr=e.target.parentNode.parentNode;
          title=tr.getElementsByTagName('td')[0].innerText;
          description=tr.getElementsByTagName('td')[1].innerText;
          console.log(title,description);
          snoEdit.value=e.target.id;
          console.log(e.target.id);
          descriptionEdit.value=description;
          titleEdit.value=title;
          $('#editModal').modal('toggle');
        })
      })
      deletes=document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element)=>{
        element.addEventListener("click",(e)=>{
          sno=e.target.id.substr(1,);
          if(confirm("Do you really want to delete this note?")){
            console.log("Yes");
            window.location=`/crud/index.php?delete=${sno}`;
          }
          else{
            console.log("No");
          }
        })
      })
    </script>
</body>

</html>