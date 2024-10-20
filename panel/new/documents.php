<?php include_once('../header.php'); ?>
            <!-- Start Page Content here -->
            <?php include_once('../sidebar.php'); ?>
                <!-- Right Side Content Display Section -->
                <div class="tab-content pt-0 w-100">
                    <div class="tab-pane fade active show" id="v-bot_creation" role="tabpanel" aria-labelledby="bot_creation">
                        <div class="content_right">
                            <div class="content_section">
                                <div class="d-flex flex-column position-relative">
                                    <div class="bot_detail_header">
                                        <h3 class="timeline_header">Create New Bot</h3>
                                        <h3 class="timeline_header">Step 1/3</h3>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="bot_details">
                                            <div class="detail_left">
                                                <div class="mb-3">
                                                    <label for="bot_name" class="form-label">Bot Name <span class="text-danger">*</span> </label>
                                                    <input type="text" id="bot_name" class="form-control">
                                                </div>
                                            </div>

                                            <div class="detail_right">
                                                <div class="mb-3">
                                                    <label for="bot_lang" class="form-label">Primary Language <span class="text-danger">*</span> </label>
                                                    <select class="form-select" id="bot_lang">
                                                        <option selected>Select Bot Primary Language</option>
                                                        <option>English</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="knoledge_base" class="form-label">Upload Knowledge Base <span class="text-danger">*</span> </label>
                                            <input type="file" data-plugins="dropify" id="knoledge_base"/>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <span class="proposal_content_header">Below you can choose the priority of Knowledge Base uploaded over Llama Model :</span>
                                        </div>
                                        <label class="form-check-label" for="customradio4">Set Priority Order </label>
                                        <div class="priority_order">
                                            <div class="priority_left">
                                                <div class="form-check mb-2 form-check-danger">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="customradio4">
                                                    <label class="form-check-label" for="customradio4">Provided Knowledge Base</label>
                                                </div>
                                            </div>

                                            <div class="priority_left">
                                                <div class="form-check mb-2 form-check-danger">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="llama_model">
                                                    <label class="form-check-label" for="llama_model">Llama Model</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-content_tone" role="tabpanel" aria-labelledby="content_tone">
                        <div class="content_right">
                            <div class="content_section">
                                <div class="d-flex flex-column position-relative">
                                    <div class="bot_detail_header">
                                        <h3 class="timeline_header">Content Tone Setter</h3>
                                        <h3 class="timeline_header">Step 2/3</h3>
                                    </div>
                                    <div class="timeline-content">

                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <span class="proposal_content_header">Here you can choose the tone of the response :</span>
                                        </div>
                                        <br>
                                        <label class="form-check-label" for="customradio4">Default Tone <span class="text-danger">*</span> </label>
                                        <div class="bot_details">
                                            <div class="form-check mb-2 form-check-danger">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="formal_tone">
                                                <label class="form-check-label" for="formal_tone">Formal Tone</label>
                                            </div>
                                            <div class="form-check mb-2 form-check-danger">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="casual_tone">
                                                <label class="form-check-label" for="casual_tone">Casual Tone</label>
                                            </div>
                                            <div class="form-check mb-2 form-check-danger">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="friendly_tone">
                                                <label class="form-check-label" for="friendly_tone">Friendly</label>
                                            </div>
                                            <div class="form-check mb-2 form-check-danger">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="enthusiastic_tone">
                                                <label class="form-check-label" for="enthusiastic_tone">Enthusiastic Tone</label>
                                            </div>
                                        </div>

                                        <div class="mb-3 mt-2">
                                            <label for="example-textarea" class="form-label">Tone Example </label>
                                            <textarea class="form-control" id="example-textarea" rows="5" spellcheck="false" placeholder="Example : Tell me about the Sony WH-1000XM4 headphones..."></textarea>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-filter_keyword" role="tabpanel" aria-labelledby="filter_keyword">
                        <div class="content_right">
                            <div class="content_section">
                                <div class="d-flex flex-column position-relative">
                                    <div class="bot_detail_header">
                                        <h3 class="timeline_header">Keyword Filter Management</h3>
                                        <h3 class="timeline_header">Step 3/3</h3>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <span class="proposal_content_header">Here you can choose to filter the keywords which are inappropriate :</span>
                                        </div>
                                        <div class="filter_word">
                                            <div class="mb-3 mt-2">
                                                <label class="form-label" for="blacklist_word">Blacklisted Keywords <span class="text-danger">*</span> </label>
                                                <input type="text" class="selectize-close-btn" id="blacklist_word">
                                            </div>
                                        </div>
                                        
                                        <label class="form-check-label" for="customradio4">Action on Detection </label>
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
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="d-flex flex-column position-relative">
                        <div class="payments_content">
                            <ul class="list-inline wizard mb-0">
                                <li class="previous list-inline-item disabled">
                                    <a href="javascript: void(0);" class="btn btn-secondary">Previous</a>
                                </li>
                                <li class="next list-inline-item float-end disabled">
                                    <a href="javascript: void(0);" class="btn" style="background-color: #3061AA;color: #fff;">Next</a>
                                </li>
                                <li class="next list-inline-item float-end disabled" style="display: none;">
                                    <a href="javascript: void(0);" class="btn" style="background-color: #55b641;color: #fff;">Submit</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
<?php include_once('../footer.php'); ?>