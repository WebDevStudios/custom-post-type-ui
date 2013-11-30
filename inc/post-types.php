<?php
add_submenu_page( 'cpt_main_menu', __( 'Add/Edit Post Types', 'cpt-plugin' ), __( 'Add/Edit Post Types', 'cpt-plugin' ), 'manage_options', 'cpt_sub_manage_cpt', 'cpt_manage_cpt' );
