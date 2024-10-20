<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>Llama | Bot Details List</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="https://uat.zeapl.com/zeapl_webpanel2/assets/images/zeapl/zeapl_logo.png">

        <!-- Bootstrap Tables css -->
        <link href="assets/libs/bootstrap-table/bootstrap-table.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- App css -->
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style"/>
        <!-- icons -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- Head js -->
        <script src="assets/js/head.js"></script>

        <link rel="stylesheet" href="assets/css/style.css">

        <style>
            body {
                overflow-y: scroll;
            }
            
            div#wrapper div.content-page {
                display: block;
            }

            div.msg_container {
                display: flex;
                justify-content: center;
            }

            p.msg_short {
                margin-bottom: 0px;
                white-space: nowrap;
                min-width: 200px;
                max-width: 200px;
                overflow: hidden;
                text-overflow: ellipsis;
                text-align: center;
            }

            .message_preview_box {
                background-color: #dcf8c6;
                padding: 8px;
                width: 100%;
                border-top-right-radius: 10px;
                border-bottom-left-radius: 10px;
                margin-bottom: 10px;
            }
        </style>


    </head>

    <!-- body start -->
    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <div class="page-loader is-full-page is-active" id="pageLoader" style="display: none;">
                <div class="background">
                    <div class="loader-icon"></div>
                    <p style="color: #eb595f;font-size: 20px;font-weight: 600;">Loading...</p>
                </div>
            </div>

            <div class="header">
                <div class="header-container">
                    <nav class="navbar navbar-light bg-faded navbar-expand p-0">
                        <div class="navbar-brand nav_logo">
                            <img src="https://uat.zeapl.com/zeapl_webpanel2/assets/images/zeapl/zeapl_logo.png" alt="Zeapl Logo" class="logo">
                        </div>
                        
                    </nav>
                </div>
            </div>
            <!-- end Topbar -->

            <!-- Start Page Content here -->

            <div class="content-page">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="user_group_datatable" 
                                data-search="true"
                                data-toggle="table" 
                                data-page-size="10" 
                                data-side-pagination="server"
                                data-pagination="true" 
                                data-buttons-class="primary"
                                data-show-button-text="true"
                                data-buttons-class="primary"
                                data-buttons="buttons"
                                data-show-button-text="true"
                                class="table table-striped tag-table">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Bot name</th>
                                            <th>Primary Language</th>
                                            <th>Response tone</th>
                                            <th>Blacklisted Keywords</th>
                                            <th>Defined Action</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-center">
                                        <tr>
                                            <td>Nerolac bot</td>
                                            <td>English </td>
                                            <td>Formal</td>
                                            <td>
                                                <div class="msg_container">
                                                    <p class="msg_short">"Asian Paints", "Berger Paints.."</p>
                                                    <a href="javascript:void(0)" class="text-primary" data-bs-toggle="modal" data-bs-target="#dataConditionModal">Show More...</a>
                                                </div>
                                            </td>
                                            <td>Replace word</td>
                                        </tr>
                                        <tr>
                                            <td>Nerolac bot</td>
                                            <td>English </td>
                                            <td>Formal</td>
                                            <td>
                                                <div class="msg_container">
                                                    <p class="msg_short">"Asian Paints", "Berger Paints.."</p>
                                                    <a href="javascript:void(0)" class="text-primary" data-bs-toggle="modal" data-bs-target="#dataConditionModal">Show More...</a>
                                                </div>
                                            </td>
                                            <td>Replace word</td>
                                        </tr>
                                        <tr>
                                            <td>Nerolac bot</td>
                                            <td>English </td>
                                            <td>Formal</td>
                                            <td>
                                                <div class="msg_container">
                                                    <p class="msg_short">"Asian Paints", "Berger Paints.."</p>
                                                    <a href="javascript:void(0)" class="text-primary" data-bs-toggle="modal" data-bs-target="#dataConditionModal">Show More...</a>
                                                </div>
                                            </td>
                                            <td>Replace word</td>
                                        </tr>
                                        <tr>
                                            <td>Nerolac bot</td>
                                            <td>English </td>
                                            <td>Formal</td>
                                            <td>
                                                <div class="msg_container">
                                                    <p class="msg_short">"Asian Paints", "Berger Paints.."</p>
                                                    <a href="javascript:void(0)" class="text-primary" data-bs-toggle="modal" data-bs-target="#dataConditionModal">Show More...</a>
                                                </div>
                                            </td>
                                            <td>Replace word</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="dataConditionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header pb-0">
                                <h5 class="modal-title">Preview Conditions</h5>
                                <button type="button" class="btn close font-16" data-bs-dismiss="modal" aria-label="Close">×</button>
                            </div>
                            <div class="modal-body pt-0">
                                <div class="message_preview_box">
                                    <div>
                                        <p>
                                            "Asian paints", "Berger Paints", "Birla Opus Paints",
                                            "Asian paints", "Berger Paints", "Birla Opus Paints",
                                            "Asian paints", "Berger Paints", "Birla Opus Paints",
                                            "Asian paints", "Berger Paints", "Birla Opus Paints",
                                            "Asian paints", "Berger Paints", "Birla Opus Paints"
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- End Page content -->


        </div>
        <!-- END wrapper -->

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Bootstrap Tables js -->
        <script src="assets/libs/bootstrap-table/bootstrap-table.min.js"></script>

        <!-- Init js -->
        <script src="assets/js/pages/bootstrap-tables.init.js"></script>


        <!-- App js-->
        <script src="assets/js/app.min.js"></script>

        <script>
            function buttons() {
                return {
                    btnAdd:{
                        html:'<a class="btn btn-danger" href="index.php" title="Add Content" id="addrecord1"><i class="bi bi bi-plus"></i><span>Create New Bot</span></a>'
                    }

                }
            }
        </script>
        
    </body>
</html>