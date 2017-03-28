<!-- Sidebar -->
<nav id="sidebar" style="background-color: #1f4966;">
    <!-- Sidebar Scroll Container -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="side-header side-content bg-white-op">
                <a class="h5 text-white" href="/home">
                    <span class="h4 font-w600 sidebar-mini-hide">Morfix</span>
                </a>
            </div>
            <!-- END Side Header -->

            <!-- Side Content -->
            <div class="side-content">
                <ul class="nav-main">
                    <li>
                        @if ($page == 'home')
                        <a class="active" href="/home"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                        @else
                        <a href="/home"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                        @endif
                    </li>
                    
                    <li class="nav-main-heading"><span class="sidebar-mini-hide">AUTOMATION TOOLS</span></li>
                    
                    <li>
                        @if ($page == 'interaction')
                        <a class="active" href="/interactions"><i class="si si-rocket"></i><span class="sidebar-mini-hide">Interactions</span></a>
                        @else
                        <a href="/interactions"><i class="si si-rocket"></i><span class="sidebar-mini-hide">Interactions</span></a>
                        @endif
                    </li>
                    
                    <li>
                        @if ($page == 'dm')
                        <a class="active" href="/dm"><i class="si si-envelope"></i><span class="sidebar-mini-hide">Direct Message</span></a>
                        @else
                        <a href="/dm"><i class="si si-envelope"></i><span class="sidebar-mini-hide">Direct Message</span></a>
                        @endif
                    </li>
                    
                    <li>
                        @if ($page == 'postscheduling')
                        <a class="active" href="/post-scheduling"><i class="si si-picture"></i><span class="sidebar-mini-hide">Post Scheduling</span></a>
                        @else
                        <a href="/post-scheduling"><i class="si si-picture"></i><span class="sidebar-mini-hide">Post Scheduling</span></a>
                        @endif
                    </li>
                    
                    <li class="nav-main-heading"><span class="sidebar-mini-hide">AFFILIATE AREA</span></li>
                    <li>
                        @if ($page == 'affiliate')
                        <a class="active" href="/affiliate"><i class="si si-trophy"></i><span class="sidebar-mini-hide">Affiliate Area</span></a>
                        @else
                        <a href="/affiliate"><i class="si si-trophy"></i><span class="sidebar-mini-hide">Affiliate Area</span></a>
                        @endif
                    </li>
                    
                    <li class="nav-main-heading"><span class="sidebar-mini-hide">PAYMENT</span></li>
                    <li class="">
                        <a class="" data-toggle="nav-submenu" href="#">
                            <i class="si si-bag"></i><span class="sidebar-mini-hide">Pro Upgrade</span></a>
                        <ul>
                            <li>
                                <a href="base_pages_blank.html">Blank</a>
                            </li>
                            <li>
                                <a href="base_pages_dashboard_v2.html">Dashboard v2</a>
                            </li>
                            <li>
                                <a href="base_pages_search.html">Search Results</a>
                            </li>
                            <li>
                                <a href="base_pages_invoice.html">Invoice</a>
                            </li>
                            <li>
                                <a href="base_pages_faq.html">FAQ</a>
                            </li>
                            <li>
                                <a href="base_pages_inbox.html">Inbox</a>
                            </li>
                            <li>
                                <a href="base_pages_files.html">Files</a>
                            </li>
                            <li>
                                <a href="base_pages_tickets.html">Tickets</a>
                            </li>
                            <li>
                                <a href="base_pages_contacts.html">Contacts</a>
                            </li>
                            <li>
                                <a href="base_pages_coming_soon.html">Coming Soon</a>
                            </li>
                            <li>
                                <a href="base_pages_coming_soon_v2.html">Coming Soon v2</a>
                            </li>
                            <li>
                                <a href="base_pages_maintenance.html">Maintenance</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="" data-toggle="nav-submenu" href="#">
                            <i class="si si-bag"></i><span class="sidebar-mini-hide">Business Add-On</span></a>
                        <ul>
                            <li>
                                <a href="base_pages_ecom_dashboard.html">Dashboard</a>
                            </li>
                            <li>
                                <a href="base_pages_ecom_orders.html">Orders</a>
                            </li>
                            <li>
                                <a href="base_pages_ecom_order.html">Order</a>
                            </li>
                            <li>
                                <a href="base_pages_ecom_products.html">Products</a>
                            </li>
                            <li>
                                <a href="base_pages_ecom_product_edit.html">Product Edit</a>
                            </li>
                            <li>
                                <a href="base_pages_ecom_customer.html">Customer</a>
                            </li>
                        </ul>
                    </li>


                    <li class="nav-main-heading"><span class="sidebar-mini-hide">TRAINING VIDEOS</span></li>
                    <li>
                        <a class="" data-toggle="nav-submenu" href="#"><i class="si si-layers"></i><span class="sidebar-mini-hide">How-to-use Morfix</span></a>
                        <ul>
                            <li>
                                <a href="base_pages_blank.html">Blank</a>
                            </li>
                            <li>
                                <a href="base_pages_dashboard_v2.html">Dashboard v2</a>
                            </li>
                            <li>
                                <a href="base_pages_search.html">Search Results</a>
                            </li>
                            <li>
                                <a href="base_pages_invoice.html">Invoice</a>
                            </li>
                            <li>
                                <a href="base_pages_faq.html">FAQ</a>
                            </li>
                            <li>
                                <a href="base_pages_inbox.html">Inbox</a>
                            </li>
                            <li>
                                <a href="base_pages_files.html">Files</a>
                            </li>
                            <li>
                                <a href="base_pages_tickets.html">Tickets</a>
                            </li>
                            <li>
                                <a href="base_pages_contacts.html">Contacts</a>
                            </li>
                            <li>
                                <a href="base_pages_coming_soon.html">Coming Soon</a>
                            </li>
                            <li>
                                <a href="base_pages_coming_soon_v2.html">Coming Soon v2</a>
                            </li>
                            <li>
                                <a href="base_pages_maintenance.html">Maintenance</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="" data-toggle="nav-submenu" href="#"><i class="si si-layers"></i><span class="sidebar-mini-hide">Affiliate Marketing</span></a>

                        <ul>
                            <li>
                                <a href="base_forms_premade.html">Pre-made</a>
                            </li>
                            <li>
                                <a href="base_forms_elements.html">Elements</a>
                            </li>
                            <li>
                                <a href="base_forms_pickers_more.html">Pickers &amp; More</a>
                            </li>
                            <li>
                                <a href="base_forms_editors.html">Text Editors</a>
                            </li>
                            <li>
                                <a href="base_forms_validation.html">Validation</a>
                            </li>
                            <li>
                                <a href="base_forms_wizard.html">Wizard</a>
                            </li>
                        </ul>
                    </li>
                    
                </ul>


            </div>


            <!--            <div class="side-content">
                            <ul class="nav-main">
                                <li>
                                    <a class="active" href="base_pages_dashboard.html"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                                </li>
                                <li class="nav-main-heading"><span class="sidebar-mini-hide">User Interface</span></li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-badge"></i><span class="sidebar-mini-hide">UI Elements</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_ui_widgets.html">Widgets</a>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Blocks</a>
                                            <ul>
                                                <li>
                                                    <a href="base_ui_blocks.html">Styles</a>
                                                </li>
                                                <li>
                                                    <a href="base_ui_blocks_api.html">Blocks API</a>
                                                </li>
                                                <li>
                                                    <a href="base_ui_blocks_draggable.html">Draggable</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="base_ui_grid.html">Grid</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_typography.html">Typography</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_icons.html">Icons</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_buttons.html">Buttons</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_activity.html">Activity</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_tabs.html">Tabs</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_tiles.html">Tiles</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_cards.html">Cards</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_ribbons.html">Ribbons</a>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Chat</a>
                                            <ul>
                                                <li>
                                                    <a href="base_ui_chat_full.html">Full</a>
                                                </li>
                                                <li>
                                                    <a href="base_ui_chat_fixed.html">Fixed</a>
                                                </li>
                                                <li>
                                                    <a href="base_ui_chat_popup.html">Popup</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Timeline</a>
                                            <ul>
                                                <li>
                                                    <a href="base_ui_timeline.html">Various</a>
                                                </li>
                                                <li>
                                                    <a href="base_ui_timeline_social.html">Social</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="base_ui_navigation.html">Navigation</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_modals_tooltips.html">Modals &amp; Tooltips</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_color_themes.html">Color Themes</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-grid"></i><span class="sidebar-mini-hide">Tables</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_tables_styles.html">Styles</a>
                                        </li>
                                        <li>
                                            <a href="base_tables_responsive.html">Responsive</a>
                                        </li>
                                        <li>
                                            <a href="base_tables_tools.html">Tools</a>
                                        </li>
                                        <li>
                                            <a href="base_tables_pricing.html">Pricing</a>
                                        </li>
                                        <li>
                                            <a href="base_tables_datatables.html">DataTables</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-note"></i><span class="sidebar-mini-hide">Forms</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_forms_premade.html">Pre-made</a>
                                        </li>
                                        <li>
                                            <a href="base_forms_elements.html">Elements</a>
                                        </li>
                                        <li>
                                            <a href="base_forms_pickers_more.html">Pickers &amp; More</a>
                                        </li>
                                        <li>
                                            <a href="base_forms_editors.html">Text Editors</a>
                                        </li>
                                        <li>
                                            <a href="base_forms_validation.html">Validation</a>
                                        </li>
                                        <li>
                                            <a href="base_forms_wizard.html">Wizard</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-main-heading"><span class="sidebar-mini-hide">Develop</span></li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-wrench"></i><span class="sidebar-mini-hide">Components</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_comp_images.html">Images</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_charts.html">Charts (Various)</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_chartjs_v2.html">Charts.js v2</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_calendar.html">Calendar</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_sliders.html">Sliders</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_animations.html">Animations</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_scrolling.html">Scrolling</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_syntax_highlighting.html">Syntax Highlighting</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_rating.html">Rating</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_treeview.html">Tree View</a>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Maps</a>
                                            <ul>
                                                <li>
                                                    <a href="base_comp_maps.html">Google</a>
                                                </li>
                                                <li>
                                                    <a href="base_comp_maps_full.html">Google Full</a>
                                                </li>
                                                <li>
                                                    <a href="base_comp_maps_vector.html">Vector</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Gallery</a>
                                            <ul>
                                                <li>
                                                    <a href="base_comp_gallery_simple.html">Simple</a>
                                                </li>
                                                <li>
                                                    <a href="base_comp_gallery_advanced.html">Advanced</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-magic-wand"></i><span class="sidebar-mini-hide">Layouts</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_layouts_api.html">Layout API</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_default.html">Default</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_default_flipped.html">Default Flipped</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_header_static.html">Static Header</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_sidebar_mini_hoverable.html">Mini Sidebar (Hoverable)</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_side_overlay_hoverable.html">Side Overlay (Hoverable)</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_side_overlay_open.html">Side Overlay (Open)</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_side_native_scrolling.html">Side Native Scrolling</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_sidebar_hidden.html">Hidden Sidebar</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span class="sidebar-mini-hide">Multi Level Menu</span></a>
                                    <ul>
                                        <li>
                                            <a href="#">Link 1-1</a>
                                        </li>
                                        <li>
                                            <a href="#">Link 1-2</a>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 2</a>
                                            <ul>
                                                <li>
                                                    <a href="#">Link 2-1</a>
                                                </li>
                                                <li>
                                                    <a href="#">Link 2-2</a>
                                                </li>
                                                <li>
                                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 3</a>
                                                    <ul>
                                                        <li>
                                                            <a href="#">Link 3-1</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Link 3-2</a>
                                                        </li>
                                                        <li>
                                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 4</a>
                                                            <ul>
                                                                <li>
                                                                    <a href="#">Link 4-1</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#">Link 4-2</a>
                                                                </li>
                                                                <li>
                                                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 5</a>
                                                                    <ul>
                                                                        <li>
                                                                            <a href="#">Link 5-1</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#">Link 5-2</a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 6</a>
                                                                            <ul>
                                                                                <li>
                                                                                    <a href="#">Link 6-1</a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="#">Link 6-2</a>
                                                                                </li>
                                                                            </ul>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-main-heading"><span class="sidebar-mini-hide">Pages</span></li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-layers"></i><span class="sidebar-mini-hide">Generic</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_blank.html">Blank</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_dashboard_v2.html">Dashboard v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_search.html">Search Results</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_invoice.html">Invoice</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_faq.html">FAQ</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_inbox.html">Inbox</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_files.html">Files</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_tickets.html">Tickets</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_contacts.html">Contacts</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_coming_soon.html">Coming Soon</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_coming_soon_v2.html">Coming Soon v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_maintenance.html">Maintenance</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-bag"></i><span class="sidebar-mini-hide">e-Commerce</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_ecom_dashboard.html">Dashboard</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_ecom_orders.html">Orders</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_ecom_order.html">Order</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_ecom_products.html">Products</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_ecom_product_edit.html">Product Edit</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_ecom_customer.html">Customer</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-user"></i><span class="sidebar-mini-hide">User Profile</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_profile.html">Profile</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_profile_v2.html">Profile v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_profile_edit.html">Profile Edit</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-bubbles"></i><span class="sidebar-mini-hide">Forum</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_forum_categories.html">Categories</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_forum_topics.html">Topics</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_forum_discussion.html">Discussion</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_forum_new_topic.html">New Topic</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-lock"></i><span class="sidebar-mini-hide">Authentication</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_auth.html">All Pages</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_login.html">Log In</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_login_v2.html">Log In v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_register.html">Register</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_register_v2.html">Register v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_lock.html">Lock Screen</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_lock_v2.html">Lock Screen v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_reminder.html">Password Reminder</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_reminder_v2.html">Password Reminder v2</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-fire"></i><span class="sidebar-mini-hide">Error Pages</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_errors.html">All Pages</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_400.html">400</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_401.html">401</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_403.html">403</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_404.html">404</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_500.html">500</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_503.html">503</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>-->
            <!-- END Side Content -->
        </div>
        <!-- Sidebar Content -->
    </div>
    <!-- END Sidebar Scroll Container -->
</nav>
<!-- END Sidebar -->

