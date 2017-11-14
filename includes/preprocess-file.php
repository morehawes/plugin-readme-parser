<?php

// Show screenshots
// Old version has links section - add links individually but have links request output it all, as per old version

function prp_preprocess_file( $plugin, $version ) {

    $version = ''; // Request a specific version
    $plugin = 'Meerkat'; // Plugin name or URL
    $crlf = "\r\n";

    // Calculate plugin name

    if ( strpos( $plugin, '://' ) === false ) {
        $name = str_replace( ' ', '-', strtolower( $plugin ) );
        $plugin = 'http://plugins.svn.wordpress.org/' . $name . '/';
        if ( is_numeric( $version ) ) {
            $plugin .= 'tags/' . $version;
        } else {
            $plugin .= 'trunk';
        }
        $plugin .= '/readme.txt';
    }
    
    // Get the file
    
    $file = wp_remote_get( $plugin );
    if ( is_wp_error( $file ) ) {
        return 'Could not fetch the file';
        // Error
    } else {
        $readme = $file[ 'body' ];
    }
    
    // Split into array
    
    $readme_array = preg_split( "/((\r(?!\n))|((?<!\r)\n)|(\r\n))/", $readme );
    $readme = '';
    
    // Pre-process each line, making minor modifications where necessary. Re-combine.
    
    $count = count( $readme_array );
    for ( $i = 0; $i < $count; $i++ ) {
        if ( substr( $readme_array [$i ], 0, 4 ) == '=== ' ) {
            $readme_array[ $i ] = str_replace( '===', '#', $readme_array[ $i ] );
        } else {
            if ( substr( $readme_array[ $i ], 0, 3 ) == '== ' ) {
                $readme_array[ $i ] = str_replace( '==', '##' , $readme_array[ $i ] );
            } else {
                if ( substr( $readme_array[ $i ], 0, 2 ) == '= ' ) {
                    $readme_array[ $i ] = str_replace( '=', '###', $readme_array[ $i ] );
                }
            }
        }
        $readme .= $readme_array[ $i ] . $crlf;
    }
      
    // Translate output
    // At this stage carriage returns are still in place and won't output as correct HTML. However, all markdown
    // is converted
    
    $readme = \Michelf\MarkdownExtra::defaultTransform( $readme );
    
    // Split into array
    
    $readme_array = preg_split( "/((\r(?!\n))|((?<!\r)\n)|(\r\n))/", $readme );    
    $readme = array();
    $name = '';
    $section = '';
    
    // Pre-process each line, making minor modifications where necessary. Re-combine.
    
    $count = count( $readme_array );
    for ( $i = 0; $i < $count; $i++ ) {
        
        // Get section namespace
        
        if ( substr( $readme_array[ $i ], 0, 3 ) == '<h1' or substr( $readme_array[ $i ], 0, 3 ) == '<h2' ) {
            $section = strip_tags( $readme_array[ $i ] );
            
            // The first main heading is the plugin name. Grab that and mark anything before the next section as meta
            
            if ( $i == 0 && substr( $readme_array[ $i ], 0, 3 ) == '<h1' ) {
                $name = $section;
                $section = 'Meta';
            }
            
        } else {
            
            // If in the meta section, extract each meta item out seperately. They are identified in the resulting array as they
            // have a colon on the end of their name
            
            if ( $section == 'Meta' ) {
                $meta_line = strip_tags( $readme_array[ $i ] );
                
                if ( $meta_line != '' ) {
                    
                    // The short description has been identified, so add that to its own array element
                    
                    if ( strip_tags( $readme_array[ $i-1 ] ) == '' && strip_tags( $readme_array[ $i+1 ] ) == '' ) {
                        $readme[ 'Short description' ] =  $meta_line; 
                    } else {
                        
                        // The rest of the meta is now processed - each being added to their own array element
                        
                        $colon = strpos( $meta_line, ':' );
                        $meta_name = substr( $meta_line, 0, $colon+1 );
                        $lower_meta = strtolower( $meta_name );
                        $meta_content = substr( $meta_line, $colon+2 );
                        $readme[ $meta_name ] = $meta_content;
                        
                        // For some meta elements, links are added
                        
                        if ( $lower_meta == 'license uri:' or $lower_meta == 'donate link:' ) {
                            $readme[ $meta_name ] = '<a href="' . $meta_content . '">' . $meta_content . '</a>';
                        }
                        if ( $lower_meta == 'stable tag:' ) {
                            $readme[ $meta_name ] = '<a href="https://downloads.wordpress.org/plugin/' . str_replace( ' ', '-', strtolower( $name ) ) . '.' . $meta_content . '.zip">' . $meta_content . '</a>';
                        }                        
                        if ( $lower_meta == 'contributors:' ) {
                            $contributors_array = explode( ',', $readme[ $metaname ] );
                            $contributors = '';
                            for ( $i = 0; $i < count( $contributors_array ); $i++ ) {
                                $contributor = trim( $contributors_array[ $i ] );
                                $contributors .= ', <a href="https://profiles.wordpress.org/' . $contributor . '">' . $contributor . '</a>';
                            }
                            $readme[ $metaname ] = substr( $contributors, 2 );
                        }
                        if ( $lower_meta == 'tags:' ) {
                            $tags_array = explode( ',', $readme[ $metaname ] );
                            $tags = '';
                            for ( $i = 0; $i < count( $tags_array ); $i++ ) {
                                $tag = trim( $tags_array[ $i ] );
                                $tags .= ', <a href="https://wordpress.org/plugins/tags/' . $tag . '">' . $tag . '</a>';
                            }
                            $readme[ $metaname ] = substr( $tags, 2 );
                        }                        
                        // Link version number to download

                    }
                }
                
            } else {
                
                $readme[ $section ] .= $readme_array[ $i ];
            }
        }
              
    }
    
    // Add other collected data to the array
    
    $readme[ 'Name' ] = $name;
    
    return $readme;
}    
?>