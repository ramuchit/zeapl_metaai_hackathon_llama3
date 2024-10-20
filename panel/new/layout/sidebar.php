
                <!-- Left Side Menu Display Section -->
                <div class="left_sidebar">
                    <?php
                        $link = rtrim($_SERVER['REQUEST_URI'], '/');
                        $link_array = explode('/',$link);
                        $activePage = $link_array[count($link_array)-1];
                        $activePageParent = $link_array[count($link_array)-2];
                    ?>
                    <div class="nav flex-column nav-pills nav-pills-tab left_menu_list" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <div class="left_card_menu">
                            <div class="card_menu_items">
                                <a class="nav-link <?= ($activePage == 'document' || $activePage == 'index' || $activePageParent == 'document') ? 'active':''; ?>"  href="../document/" >
                                    <span class="d-flex align-items-centerjustify-content-center">
                                    <span class="material-symbols-outlined">edit</span>
                                        <span>Bot Knowledge base</span>
                                    </span>
                                </a>
                                <a class="nav-link <?= ($activePage == 'tones' || $activePageParent == 'tones') ? 'active':''; ?>" href="../tones/create">
                                    <span class="d-flex align-items-centerjustify-content-center">
                                    <span class="material-symbols-outlined">content_paste</span>
                                        <span>Content Tone Setter</span>
                                    </span>
                                </a>
                                <a class="nav-link <?= ($activePage == 'keywords' || $activePageParent == 'keywords') ? 'active':''; ?>" href="../keywords/">
                                    <span class="d-flex align-items-centerjustify-content-center">
                                    <span class="material-symbols-outlined">match_word</span>
                                        <span>Keyword Management</span>
                                    </span>
                                </a>
                                <a class="nav-link <?= ($activePage == 'intents' || $activePageParent == 'intents') ? 'active':''; ?>" href="../intents/">
                                    <span class="d-flex align-items-centerjustify-content-center">
                                    <span class="material-symbols-outlined">target</span>
                                        <span>Intent List</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>