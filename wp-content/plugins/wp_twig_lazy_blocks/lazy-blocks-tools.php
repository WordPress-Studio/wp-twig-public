<?php
require_once WP_CONTENT_DIR . '/themes/wp-twig/vendor/lazy-blocks/classes/class-tools.php';

class WpTwigLazyBlockTools extends LazyBlocks_Tools {

  public function import_block_json( $data ) {
      $meta        = array();
      $meta_prefix = 'lazyblocks_';
      $post_id     = wp_insert_post(
          array(
              'post_title'  => wp_strip_all_tags( $data['title'] ),
              'post_status' => 'publish',
              'post_type'   => 'lazyblocks',
          )
      );

      if ( 0 < $post_id ) {
          // add 'lazyblocks_' prefix.
          foreach ( $data as $k => $val ) {
              if ( ( 'code' === $k || 'supports' === $k ) && is_array( $val ) ) {
                  foreach ( $val as $i => $inner_val ) {
                      $meta[ $meta_prefix . $k . '_' . $i ] = $inner_val;
                  }
              } else {
                  if ( 'slug' === $k ) {
                      $val = substr( $val, strpos( $val, '/' ) + 1 );
                  } elseif ( 'keywords' === $k ) {
                      $val = implode( ',', $val );
                  }

                  $meta[ $meta_prefix . $k ] = $val;
              }
          }

          lazyblocks()->blocks()->save_meta_boxes( $post_id, $meta );

          do_action( 'lzb/import/block', $post_id, $data );

          return $post_id;
      }

      return false;
  }

  public function import_json_ajax() {

    // Check for nonce security.
    // phpcs:ignore
    $nonce = isset( $_POST[ 'lzb_tools_import_nonce' ] ) ? $_POST[ 'lzb_tools_import_nonce' ] : false;

    if ( ! $nonce || ! wp_verify_nonce( $nonce, 'lzb-tools-import-nonce' ) ) {
        return 'nonce error';
    }

    // Check file size.
    if ( empty( $_FILES['lzb_tools_import_json']['size'] ) ) {
        return 'No file selected';
    }

    // Get file data.
    // phpcs:ignore
    $file = $_FILES['lzb_tools_import_json'];

    // Check for errors.
    if ( $file['error'] ) {
        return 'Error uploading file. Please try again';
    }

    // Check file type.
    if ( pathinfo( $file['name'], PATHINFO_EXTENSION ) !== 'json' ) {
        return 'Incorrect file type';
    }

    // Read JSON.
    // phpcs:ignore
    $json = file_get_contents( $file['tmp_name'] );
    $json = json_decode( $json, true );

    // Check if empty.
    if ( ! $json || ! is_array( $json ) ) {
        return 'Import file empty';
    }

    // Remember imported ids.
    $imported_blocks    = array();
    $imported_templates = array();

    // Loop over json.
    foreach ( $json as $data ) {
        if ( isset( $data['id'] ) ) {
            // check if block data.
            if ( isset( $data['icon'] ) || isset( $data['category'] ) || isset( $data['supports'] ) || isset( $data['controls'] ) ) {
                $imported_id = $this->import_block_json( $data );

                if ( $imported_id ) {
                    $imported_blocks[] = $imported_id;
                }
            }
        }
    }

    // imported blocks.
    if ( ! empty( $imported_blocks ) ) {
        // Count number of imported field groups.
        $total_blocks = count( $imported_blocks );

        // Generate text.
        // translators: %s - number of blocks.
        $text = sprintf( esc_html( _n( 'Imported %s block', 'Imported %s blocks', $total_blocks, 'lazy-blocks' ) ), $total_blocks );

        // Add links to text.
        $links = array();
        foreach ( $imported_blocks as $id ) {
            $links[] = '<a href="' . get_edit_post_link( $id ) . '">' . get_the_title( $id ) . '</a>';
        }
        $text .= ' ' . implode( ', ', $links );

        // Add notice.
        return  $text;
    }

    // imported templates.
    if ( ! empty( $imported_templates ) ) {
        // Count number of imported field groups.
        $total_templates = count( $imported_templates );

        // Generate text.
        // translators: %s - number of templates.
        $text = sprintf( esc_html( _n( 'Imported %s template', 'Imported %s templates', $total_templates, 'lazy-blocks' ) ), $total_templates );

        // Add links to text.
        $links = array();
        foreach ( $imported_templates as $id ) {
            $links[] = '<a href="' . get_edit_post_link( $id ) . '">' . get_the_title( $id ) . '</a>';
        }
        $text .= ' ' . implode( ', ', $links );

        // Add notice.
        return  $text;
    }

    return 'Unknown error';
  }
}

