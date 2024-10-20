<?php include '../layout/header.php'; ?>
<!-- Start Page Content here -->
<?php include('../layout/sidebar.php'); ?>
<div class="tab-content pt-0 w-100">
    <form action="upload_document.php" method="POST" enctype="multipart/form-data">
    <div class="">
        <div class="content_right">
            <div class="content_section">
                <div class="d-flex flex-column position-relative">
                    <div class="bot_detail_header">
                        <h3 class="timeline_header">Upload A File</h3>
                        <h3 class="timeline_header"><a href="index" class="timeline_header">Back</a></h3>
                    </div>
                    <div class="timeline-content">
                        <div class="mb-3">
                            <input type="file" data-plugins="dropify" id="knoledge_base"  name="uploadedFile" />
                        </div>
                    </div>
                    <ul class="list-inline wizard mb-0">
                        <li class="next list-inline-item float-end">
                            <button type="submit" class="btn m-2" style="background-color: #55b641;color: #fff;">Submit</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<?php include('../layout/footer.php'); ?>