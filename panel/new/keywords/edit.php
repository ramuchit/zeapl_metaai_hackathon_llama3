<?php include '../layout/header.php'; ?>
<!-- Start Page Content here -->
<?php include('../layout/sidebar.php'); ?>
<!-- Right Side Content Display Section -->
<div class="tab-content pt-0 w-100">
    <div class="" id="v-filter_keyword" role="tabpanel" aria-labelledby="filter_keyword">
        <div class="content_right">
            <div class="content_section">
                <div class="d-flex flex-column position-relative">
                    <div class="bot_detail_header">
                        <h3 class="timeline_header">Black Listed Keyword</h3>
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="proposal_content_header">Here you can choose to filter the keywords which are
                                inappropriate :</span>
                        </div>
                        <div class="filter_word">
                            <div class="mb-3 mt-2">
                                <label class="form-label" for="blacklist_word">Blacklisted Keywords <span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="selectize-close-btn" id="blacklist_word">
                            </div>
                        </div>

                        <!-- <label class="form-check-label" for="customradio4">Action on Detection </label>
                        <div class="bot_details">
                            <div class="form-check mb-2 form-check-danger">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="block_message">
                                <label class="form-check-label" for="block_message">Block Message</label>
                            </div>
                            <div class="form-check mb-2 form-check-danger">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="replace_word">
                                <label class="form-check-label" for="replace_word">Replace Word</label>
                            </div>
                            <div class="form-check mb-2 form-check-danger">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="review_word">
                                <label class="form-check-label" for="review_word">Flag for Review</label>
                            </div>
                        </div> -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex-column position-relative" id="submit_section" style="display: flex;">
        <div class="payments_content">
            <ul class="list-inline wizard mb-0">
                <li class="next list-inline-item float-end">
                    <a href="javascript: void(0);" class="btn" style="background-color: #55b641;color: #fff;">Submit</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php include('../layout/footer.php'); ?>