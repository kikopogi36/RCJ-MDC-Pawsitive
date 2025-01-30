<?php
include 'include/session.php';
include 'include/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>List of Returns</h2>
            <div class="box">
                <div class="box-header">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addNewReturn">
                        <i class="fa fa-plus"></i> New Return
                    </button>
                </div>
                <div class="box-body">
                    <table id="returnsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Return#</th>
                                <th>Order#</th>
                                <th>Return Reason</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Staff</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $conn = $pdo->open();
                            try {
                                $stmt = $conn->prepare("SELECT r.*, s.firstname as staff_name 
                                                      FROM tblreturn r 
                                                      LEFT JOIN tbluseraccount s ON s.id=r.staff_id 
                                                      ORDER BY return_date DESC");
                                $stmt->execute();
                                foreach ($stmt as $row) {
                                    echo "
                                        <tr>
                                            <td>".$row['returnid']."</td>
                                            <td>".$row['orderid']."</td>
                                            <td>".$row['return_reason']."</td>
                                            <td>".date('M d, Y', strtotime($row['return_date']))."</td>
                                            <td>".$row['return_status']."</td>
                                            <td>".$row['staff_name']."</td>
                                            <td>
                                                <button class='btn btn-success btn-sm edit' data-id='".$row['returnid']."'>
                                                    <i class='fa fa-edit'></i> Edit
                                                </button>
                                                <button class='btn btn-danger btn-sm delete' data-id='".$row['returnid']."'>
                                                    <i class='fa fa-trash'></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    ";
                                }
                            }
                            catch(PDOException $e) {
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
</div>

<!-- Add Return Modal -->
<div class="modal fade" id="addNewReturn" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Return</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="returns_add.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Order ID:</label>
                        <input type="text" class="form-control" name="orderid" required>
                    </div>
                    <div class="form-group">
                        <label>Return Reason:</label>
                        <textarea class="form-control" name="reason" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status:</label>
                        <select class="form-control" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="add">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Return Modal -->
<div class="modal fade" id="editReturn" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Return</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="returns_edit.php" method="POST">
                <input type="hidden" name="id" id="return_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Order ID:</label>
                        <input type="text" class="form-control" name="orderid" id="edit_orderid" required>
                    </div>
                    <div class="form-group">
                        <label>Return Reason:</label>
                        <textarea class="form-control" name="reason" id="edit_reason" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status:</label>
                        <select class="form-control" name="status" id="edit_status" required>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="edit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Return Modal -->
<div class="modal fade" id="deleteReturn" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Return</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="returns_delete.php" method="POST">
                <input type="hidden" name="id" id="del_return_id">
                <div class="modal-body">
                    <p>Are you sure you want to delete this return?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function(){
    // Initialize DataTable
    $('#returnsTable').DataTable({
        responsive: true
    });

    // Edit Return
    $(document).on('click', '.edit', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: 'returns_row.php',
            data: {id:id},
            dataType: 'json',
            success: function(response){
                $('#return_id').val(response.returnid);
                $('#edit_orderid').val(response.orderid);
                $('#edit_reason').val(response.return_reason);
                $('#edit_status').val(response.return_status);
                $('#editReturn').modal('show');
            }
        });
    });

    // Delete Return
    $(document).on('click', '.delete', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $('#del_return_id').val(id);
        $('#deleteReturn').modal('show');
    });
});
</script>

<?php include 'include/footer.php'; ?>
