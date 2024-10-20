<?php include '../layout/header.php'; ?>
<!-- Start Page Content here -->
<?php include('../layout/sidebar.php'); ?>
<!-- Right Side Content Display Section -->
<div class="tab-content pt-0 w-100">
    <div class="tab-pane fade active show" id="v-bot_creation" role="tabpanel" aria-labelledby="bot_list">
        <div class="content_right">
            <div class="content_section">
                <div class="d-flex flex-column position-relative">
                    <div class="bot_detail_header">
                        <h3 class="timeline_header">Tone List</h3>
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
                                            <th>Tone</th>
                                            <th>Tone Example</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <?php $tonesData = file_get_contents("../data/tones/list.json"); 
                                    $tones = json_decode($tonesData,TRUE);
                                    ?>
                                    <tbody class="text-center">
                                    <?php foreach ($tones as $tone) { ?>
                                        <tr>
                                            <td><?php echo $tone['tone'] ?></td>
                                            <td><?php echo $tone['tone'] ?> </td>
                                            <td><?php echo $tone['tone'] ?></td>
                                            <td><span></span></td>
                                        </tr> 
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('../layout/footer.php'); ?>
