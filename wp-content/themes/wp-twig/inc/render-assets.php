<?php

function getBlockStyle($row, $block_stylesheets) {
  $blockName = $row['blockName'];


    $blockName = str_replace('lazyblock/', 'lazyblock-', $blockName);
    $sheet = '/blocks/' . $blockName . '/block.css';
    $stylesheet = get_stylesheet_directory_uri() . $sheet;

     // If blocks include lazyblock then push unique block list into an array
     if (!in_array($stylesheet, $block_stylesheets) &&  strpos($blockName, 'lazyblock') !== false && file_exists(get_stylesheet_directory() . $sheet)) {
      return $stylesheet;
    }

}

function getBlockJS($row, $block_scripts) {
  $blockName = $row['blockName'];


    $blockName = str_replace('lazyblock/', 'lazyblock-', $blockName);
    $sheet = '/blocks/' . $blockName . '/block.js';
    $stylesheet = get_stylesheet_directory_uri() . $sheet;

     // If blocks include lazyblock then push unique block list into an array
     if (!in_array($stylesheet, $block_scripts) &&  strpos($blockName, 'lazyblock') !== false && file_exists(get_stylesheet_directory() . $sheet)) {
      return $stylesheet;
    }

}

function renderInnerBlockStyles($row) {
  $block_stylesheets = array();
  foreach($row['innerBlocks'] as $inner_blocks ) {
      $stylesheet = getBlockStyle($inner_blocks, $block_stylesheets);
      if ($stylesheet) {
          array_push($block_stylesheets, $stylesheet);
      }

      foreach($inner_blocks['innerBlocks'] as $inner_row ) {
          $innerSheets = renderInnerBlockStyles($inner_row);
          foreach($innerSheets as $inner_sheet ) {
              array_push($block_stylesheets, $inner_sheet);
          }
      }
  }

  $stylesheet = getBlockStyle($row, $block_stylesheets);
  if ($stylesheet) {
      array_push($block_stylesheets, $stylesheet);
  }


  return $block_stylesheets;
}

function renderInnerBlockScripts($row) {
  $block_scripts = array();
  foreach($row['innerBlocks'] as $inner_blocks ) {
      $stylesheet = getBlockJS($inner_blocks, $block_scripts);
      if ($stylesheet) {
          array_push($block_scripts, $stylesheet);
      }

      foreach($inner_blocks['innerBlocks'] as $inner_row ) {
          $innerSheets = renderInnerBlockScripts($inner_row);
          foreach($innerSheets as $inner_sheet ) {
              array_push($block_scripts, $inner_sheet);
          }
      }
  }

  $stylesheet = getBlockJS($row, $block_scripts);

  if ($stylesheet) {
      array_push($block_scripts, $stylesheet);
  }

  return $block_scripts;
}

// Render blocks and page specific stylesheets
function renderStyleSheets($timber_post) {
  $data = array();

  // Get block specific stylesheet
  $block_stylesheets = array();
  
  if ( has_blocks( $timber_post->post_content ) ) {
      $blocks = parse_blocks( $timber_post->post_content );

      foreach($blocks as $row ) {
          $row_sheets = renderInnerBlockStyles($row);
          foreach($row_sheets as $row_sheet) {
            if (!in_array($row_sheet, $block_stylesheets)) {
              array_push($block_stylesheets, $row_sheet);
            }
          }
      }

  }

  $data['block_style_sheets'] =  $block_stylesheets;
  return $data;
}

// Render blocks and page specific stylesheets
function renderScripts($timber_post) {
  $data = array();

  // Get block specific stylesheet
  $block_scripts = array();
  if ( has_blocks( $timber_post->post_content ) ) {
      $blocks = parse_blocks( $timber_post->post_content );

      foreach($blocks as $row ) {
          $row_scripts = renderInnerBlockScripts($row);
          foreach($row_scripts as $row_script) {
            if (!in_array($row_script, $block_scripts)) {
              array_push($block_scripts, $row_script);
            }
          }
      }

  }

  $post_id = $timber_post->id;
  $data['block_scripts'] =  $block_scripts;

  return $data;
}