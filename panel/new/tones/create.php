<?php include '../layout/header.php'; ?>
<!-- Start Page Content here -->
<?php include('../layout/sidebar.php'); ?>
<!-- Right Side Content Display Section -->
<div class="tab-content pt-0 w-100">
<?php
    $targetDir = "../data/tones/tone.json";
    $jsonData = file_get_contents($targetDir);
    $data = json_decode($jsonData, true);
?>
<form action="add.php" method="POST" enctype="multipart/form-data">
    <div id="v-content_tone" role="tabpanel" aria-labelledby="content_tone">
        <div class="content_right">
            <div class="content_section">
                <div class="d-flex flex-column position-relative">
                    <div class="bot_detail_header">
                        <h3 class="timeline_header">Content Tone Setter</h3>
                    </div>
                    <div class="timeline-content">

                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="proposal_content_header">Here you can choose the tone of the response :</span>
                        </div>
                        <br>
                        <label class="form-check-label" for="customradio4">Set Default Tone <span
                                class="text-danger">*</span> </label>
                        <div class="bot_details">
                            <div class="form-check mb-2 form-check-danger">
                                <input class="form-check-input" type="radio" value="Formal" <?php echo $data['tone'] =='Formal' ? 'checked':null; ?> name="tones" id="formal_tone">
                                <label class="form-check-label" for="formal_tone">Formal Tone</label>
                            </div>
                            <div class="form-check mb-2 form-check-danger">
                                <input class="form-check-input" type="radio" value="Casual" <?php echo $data['tone'] =='Casual' ? 'checked':null; ?> name="tones" id="casual_tone">
                                <label class="form-check-label" for="casual_tone">Casual Tone</label>
                            </div>
                            <div class="form-check mb-2 form-check-danger">
                                <input class="form-check-input" type="radio" value="Friendly" <?php echo $data['tone'] =='Friendly' ? 'checked':null; ?> name="tones" id="friendly_tone">
                                <label class="form-check-label" for="friendly_tone">Friendly Tone</label>
                            </div>
                            <div class="form-check mb-2 form-check-danger">
                                <input class="form-check-input" type="radio" value="Enthusiastic" <?php echo $data['tone'] =='Enthusiastic' ? 'checked':null; ?> name="tones"
                                    id="enthusiastic_tone">
                                <label class="form-check-label" for="enthusiastic_tone">Enthusiastic Tone</label>
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
    <!-- <div class="flex-column position-relative" id="submit_section" style="display: flex;">
        <div class="payments_content">
            <ul class="list-inline wizard mb-0">
                <li class="next list-inline-item float-end">
                <button type="submit" class="btn" style="background-color: #55b641;color: #fff;">Submit</button>
                </li>
            </ul>
        </div>
    </div> -->
<form>
</div>
<?php include('../layout/footer.php'); ?>