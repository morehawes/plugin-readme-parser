<?php
function plugin_readme_parser( $paras = '', $content = '' ) {

    // Extract parameters

    //extract( shortcode_atts( array( 'assets' => '', 'exclude' => '', 'ext' => '', 'hide' => '', 'include' => '', 'scr_url' => '', 'scr_ext' => '' , 'target' => '_blank', 'nofollow' => '', 'ignore' => '', 'cache' => '', 'version' => '', 'mirror' => '', 'links' => 'bottom', 'name' => '' ), $paras ) );

    return prp_preprocess_file();
}

add_shortcode( 'readme', 'plugin_readme_parser' );
?>