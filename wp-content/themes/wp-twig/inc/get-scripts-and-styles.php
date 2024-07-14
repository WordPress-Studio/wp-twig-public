<?php

function getScriptsAndStyles($timber_post, $context)
{

  $post_id = get_the_ID();


  $sheets = renderStyleSheets($timber_post);

  if (isset($sheets['block_style_sheets'])) {
    $context['block_style_sheets'] = $sheets['block_style_sheets'];
  }

  $pageStyles = get_post_meta($post_id, 'page_meta_stylesheets', true);

  if (isset($pageStyles)) {
    $context['page_specific_style_sheets'] = $pageStyles;
  }

  $scripts = renderScripts($timber_post);

  if (isset($scripts['block_scripts'])) {
    $context['block_scripts'] = $scripts['block_scripts'];
  }

  
  $scripts = get_post_meta($post_id, 'page_meta_scripts', true);
  if (!$scripts) {
    $scripts = array();
  }


  if (get_post_meta($post_id, 'animation_support', true) === 'on') {
    array_push($scripts, '/static/scripts/vendor/gsap.min.js');
    array_push($scripts, '/static/scripts/vendor/scroll-trigger.min.js');
    array_push($scripts, '/static/scripts/anim-lib.js');
  }
  $context['page_specific_scripts'] = $scripts;

  return $context;
}

