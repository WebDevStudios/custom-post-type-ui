<?php
add_submenu_page( 'cpt_main_menu', __( 'Add/Edit Taxonomies', 'cpt-plugin' ), __( 'Add/Edit Taxonomies', 'cpt-plugin' ), 'manage_options', 'cpt_sub_manage_taxonomies', 'cpt_manage_taxonomies' );
