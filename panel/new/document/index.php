<?php include '../layout/header.php'; ?>
<!-- Start Page Content here -->
<?php include('../layout/sidebar.php'); ?>
<!-- Right Side Content Display Section -->
 <?php 
 $targetDir = __DIR__ . "/uploads/document_data.json";
 $jsonData = file_get_contents($targetDir);
 $data = json_decode($jsonData, true);
 ?>
<div class="tab-content pt-0 w-100">
    <div class="tab-pane fade active show" id="v-bot_creation" role="tabpanel" aria-labelledby="bot_list">
        <div class="content_right">
            <div class="content_section">
                <div class="d-flex flex-column position-relative">
                    <div class="bot_detail_header">
                        <h3 class="timeline_header">Bot Knowledge base</h3>
                    </div>
                    <div class="timeline-content">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <table id="user_group_datatable" data-search="true" data-toggle="table"
                                    data-page-size="10" data-pagination="true"
                                    data-buttons-class="primary" data-show-button-text="true"
                                    data-buttons-class="primary" data-buttons="buttons" data-show-button-text="true"
                                    class="table table-striped tag-table">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Id</th>
                                            <th>File</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php if($data !== null){  ?>
                                        <tbody class="text-center">
                                       <?php foreach ($data as $item) { ?>
                                            <tr>
                                                <td><?php echo $item['file_id']; ?></td>
                                                <td><a href="<?php echo $item['uploadedFilePath']; ?>" target="_blank">View</a></td>
                                                <td><?php echo $item['created_at']; ?></td>
                                                <td><button class="btn btn-danger " onclick="deleteCp('<?php echo $item['id']; ?>')" id="<?php echo $item['id']; ?>"><i class="bi bi-trash"></i></button></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function deleteCp(id) {
    if (confirm('Are you sure you want to delete this record?')) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_record.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    location.reload(); 
                } else {
                    alert('Error: ' + xhr.statusText);
                }
            }
        };
        xhr.send("id=" + encodeURIComponent(id));
    }
}
function buttons() {
    return {
        btnAdd: {
            html: '<a class="btn btn-danger" href="create" title="Add Content" id="addrecord1"><i class="bi bi bi-plus"></i><span>Upload file</span></a>'
        }

    }
}
</script>
<?php include('../layout/footer.php'); ?>