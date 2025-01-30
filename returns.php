<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
      <h1>Returns List</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Returns</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> New Return</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>Return#</th>
                  <th>Order#</th>
                  <th>Return Reason</th>
                  <th>Return Date</th>
                  <th>Status</th>
                  <th>Staff</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("SELECT r.*, u.firstname, u.lastname FROM tblreturn r LEFT JOIN tbluseraccount u ON u.id=r.staff_id ORDER BY r.return_date DESC");
                      $stmt->execute();
                      foreach($stmt as $row){
                        echo "
                          <tr>
                            <td>".$row['returnid']."</td>
                            <td>".$row['orderid']."</td>
                            <td>".$row['return_reason']."</td>
                            <td>".date('M d, Y', strtotime($row['return_date']))."</td>
                            <td>".$row['return_status']."</td>
                            <td>".$row['firstname'].' '.$row['lastname']."</td>
                            <td>
                              <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['returnid']."'><i class='fa fa-edit'></i> Edit</button>
                              <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['returnid']."'><i class='fa fa-trash'></i> Delete</button>
                            </td>
                          </tr>
                        ";
                      }
                    }
                    catch(PDOException $e){
                      echo $e->getMessage();
                    }

                    $pdo->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
     
  </div>
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/returns_modal.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });
});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'returns_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.returnid').val(response.returnid);
      $('#edit_orderid').val(response.orderid);
      $('#edit_reason').val(response.return_reason);
      $('#edit_status').val(response.return_status);
      $('.return_name').html(response.returnid);
    }
  });
}
