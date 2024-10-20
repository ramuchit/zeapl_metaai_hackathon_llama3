<?php include '../layout/header.php'; ?>
<!-- Start Page Content here -->
<?php include('../layout/sidebar.php'); ?>
<!-- Right Side Content Display Section -->
<div class="tab-content pt-0 w-100">
    <form action="add.php" method="POST">
    <div class="" id="v-filter_keyword" role="tabpanel" aria-labelledby="filter_keyword">
        <div class="content_right">
            <div class="content_section">
                <div class="d-flex flex-column position-relative">
                    <div class="bot_detail_header">
                        <h3 class="timeline_header">Intent List</h3>
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="proposal_content_header">Here you can choose to intent and it's action :</span>
                        </div>
                        <div class="filter_word">
                            <div class="mt-2">
                                <label class="form-label" for="blacklist_word">Intent<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="blacklist_word" name="intent[query]" required>
                            </div>
                            <div class="mt-2">
                                <label class="form-label" for="blacklist_word">Intent Action <span
                                        class="text-danger">*</span> </label>
                                <textarea rows="5"  class="form-control" id="blacklist_word" name="intent[action]" required></textarea>
                            </div>
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