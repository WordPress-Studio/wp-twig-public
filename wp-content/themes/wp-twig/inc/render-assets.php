<?php

function getBlockStyle($row, $css_optimized, $block_stylesheets) {
  $blockName = $row['blockName'];


    $blockName = str_replace('lazyblock/', 'lazyblock-', $blockName);
    $sheet = '/blocks/' . $blockName . '/block.css';
    if ($css_optimized) {
      $stylesheet =  '/blocks/' . $blockName . '/block.css';
    } else  {
      $stylesheet = get_stylesheet_directory_uri() . $sheet;
    }

     // If blocks include lazyblock then push unique block list into an array
     if (!in_array($stylesheet, $block_stylesheets) &&  strpos($blockName, 'lazyblock') !== false && file_exists(get_stylesheet_directory() . $sheet)) {
      return $stylesheet;
    }

}

function getBlockJS($row, $js_optimized, $block_scripts) {
  $blockName = $row['blockName'];


    $blockName = str_replace('lazyblock/', 'lazyblock-', $blockName);
    $sheet = '/blocks/' . $blockName . '/block.js';
    if ($js_optimized) {
      $stylesheet =  '/blocks/' . $blockName . '/block.js';
    } else  {
      $stylesheet = get_stylesheet_directory_uri() . $sheet;
    }

     // If blocks include lazyblock then push unique block list into an array
     if (!in_array($stylesheet, $block_scripts) &&  strpos($blockName, 'lazyblock') !== false && file_exists(get_stylesheet_directory() . $sheet)) {
      return $stylesheet;
    }

}

function renderInnerBlockStyles($row, $css_optimized) {
  $block_stylesheets = array();
  foreach($row['innerBlocks'] as $inner_blocks ) {
      $stylesheet = getBlockStyle($inner_blocks, $css_optimized, $block_stylesheets);
      if ($stylesheet) {
          array_push($block_stylesheets, $stylesheet);
      }

      foreach($inner_blocks['innerBlocks'] as $inner_row ) {
          $innerSheets = renderInnerBlockStyles($inner_row, $css_optimized);
          foreach($innerSheets as $inner_sheet ) {
              array_push($block_stylesheets, $inner_sheet);
          }
      }
  }

  $stylesheet = getBlockStyle($row, $css_optimized, $block_stylesheets);
  if ($stylesheet) {
      array_push($block_stylesheets, $stylesheet);
  }


  return $block_stylesheets;
}

function renderInnerBlockScripts($row, $js_optimized) {
  $block_scripts = array();
  foreach($row['innerBlocks'] as $inner_blocks ) {
      $stylesheet = getBlockJS($inner_blocks, $js_optimized, $block_scripts);
      if ($stylesheet) {
          array_push($block_scripts, $stylesheet);
      }

      foreach($inner_blocks['innerBlocks'] as $inner_row ) {
          $innerSheets = renderInnerBlockScripts($inner_row, $js_optimized);
          foreach($innerSheets as $inner_sheet ) {
              array_push($block_scripts, $inner_sheet);
          }
      }
  }

  $stylesheet = getBlockJS($row, $js_optimized, $block_scripts);
  if ($stylesheet) {
      array_push($block_scripts, $stylesheet);
  }


  return $block_scripts;
}

// Render blocks and page specific stylesheets
function renderStyleSheets($timber_post) {
  $data = array();
  $css_optimized = is_css_optimized();

  // Get block specific stylesheet
  $block_stylesheets = array();
  if ( has_blocks( $timber_post->post_content ) ) {
      $blocks = parse_blocks( $timber_post->post_content );

      foreach($blocks as $row ) {
          $row_sheets = renderInnerBlockStyles($row, $css_optimized);
          foreach($row_sheets as $row_sheet) {
            if (!in_array($row_sheet, $block_stylesheets)) {
              array_push($block_stylesheets, $row_sheet);
            }
          }
      }

  }

  if ($css_optimized) {
      $file_name = $timber_post->post_name . '-block';
      $cssURL = minifyCSS($block_stylesheets, $file_name);
      if (strpos($cssURL, '.css') !== false) {
          $data['block_style_sheets'] = array($cssURL);
      }
  } else {
    $data['block_style_sheets'] =  $block_stylesheets;
  }

  $post_id = $timber_post->id;

  if ($css_optimized) {
      $stylesheets = get_post_meta($post_id, 'page_meta_stylesheets', true) ? get_post_meta($post_id, 'page_meta_stylesheets', true) : array();
      $file_name = $timber_post->post_name . '-page';

      $cssURL = minifyCSS($stylesheets, $file_name);
      if (strpos($cssURL, '.css') !== false) {
          $data['page_specific_style_sheets'] = array($cssURL);
      }
  }

  return $data;
}

// Render blocks and page specific stylesheets
function renderScripts($timber_post) {
  $data = array();
  $js_optimized = is_js_optimized();

  // Get block specific stylesheet
  $block_scripts = array();
  if ( has_blocks( $timber_post->post_content ) ) {
      $blocks = parse_blocks( $timber_post->post_content );

      foreach($blocks as $row ) {
          $row_scripts = renderInnerBlockScripts($row, $js_optimized);
          foreach($row_scripts as $row_script) {
            if (!in_array($row_script, $block_scripts)) {
              array_push($block_scripts, $row_script);
            }
          }
      }

  }

  $post_id = $timber_post->id;


  
  if ($js_optimized) {
      $file_name = $timber_post->post_name . '-block';
      $jsURL = minifyJS($block_scripts, $file_name);


      if (strpos($jsURL, '.js') != false) {
          $data['block_scripts'] = array($jsURL);
      }
  } else {
    $data['block_scripts'] =  $block_scripts;
  }


  if ($js_optimized) {
      $scripts = get_post_meta($post_id, 'page_meta_scripts', true);

      if (!$scripts) {
        $scripts = array();
      }
  
      
       if (get_post_meta( $post_id, 'animation_support', true ) === 'on') {
        array_push($scripts, '/static/scripts/vendor/gsap.min.js');
        array_push($scripts, '/static/scripts/vendor/scroll-trigger.min.js');
        array_push($scripts, '/static/scripts/anim-lib.js');
      }

      $file_name = $timber_post->post_name . '-page';

      $jsURL = minifyJS($scripts, $file_name);
      if (strpos($jsURL, '.js') !== false) {
          $data['page_specific_scripts'] = array($jsURL);
      }
  } 

  return $data;
}