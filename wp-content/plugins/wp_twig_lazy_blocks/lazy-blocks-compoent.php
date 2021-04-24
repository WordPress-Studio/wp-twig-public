<?php
/*
Plugin Name: Lazy blocks components
Description: Wordpress gutenberg component collection for lazy blocks
Author: Prosenjit Manna
Version: 0.1
*/
add_action('admin_menu', 'wp_twig_lazy_blocks');
require_once __DIR__ . '/lazy-blocks-tools.php';

function wp_twig_lazy_blocks(){
        add_menu_page( 'WP Blocks', 'WP Blocks', 'manage_options', 'test-plugin', 'wp_twig_lazy_blocks_init' );
}




function wp_twig_lazy_blocks_init(){
  $modal = '
<div class="modal" v-bind:class="{ fade : doc, show : doc, \'d-block\': doc }">
<div class="modal-dialog modal-dialog-centered modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Block installation instruction</h5>
      <button type="button" class="close"  v-on:click="closeDoc">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body" v-html="doc">
    </div>
  </div>
</div>
</div>
<div class="modal-backdrop " v-bind:class="{ fade : doc, show : doc, \'d-none\': !doc }"></div>

';
        echo "<div id='wp-test-app' class='container-fluid'>
          <div class='blocks row'>
            <div class='col-4 col-md-4' v-for='item in blocks' :key='item.title'>
              <h3>
                  {{item.title}}
              </h3>
              <img v-bind:src='item.image' class='img-fluid'>
              <button class='btn btn-primary mt-4' v-if='item.installed < 0' v-on:click='installComponent(item)'>Install</button>
              <button class='btn btn-primary mt-4' disabled v-if='item.installed >= 0'>Installed</button>
              <button class='btn btn-primary mt-4' v-on:click='openDocs(item.id)'>Docs</button>
            </div>
          </div>
          " .  $modal . "
          <div class='alert alert-info mt-4' v-if='message' v-html='message'></div>
        </div>
        ";
}

add_action( 'admin_enqueue_scripts', 'wp_twig_lazy_blocks_enquirer' );

function wp_twig_lazy_blocks_enquirer() {
  wp_enqueue_style( "bootstrap",  'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css', array() );

  wp_enqueue_script( "vue",  'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', array( ) );
  wp_enqueue_script( "markdown",  'https://cdnjs.cloudflare.com/ajax/libs/markdown-it/11.0.0/markdown-it.min.js', array( ) );
  wp_enqueue_script( "axios",  'https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js', array() );

  wp_enqueue_script( "main", plugin_dir_url( __FILE__ ) . '/main.js?v=' .time(), array('vue' ) );


  wp_enqueue_style('lazy-styles', plugin_dir_url( __FILE__ ) . '/main.css');

  wp_localize_script( 'main', 'wp_twig_lazy_blocks', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'nonce'   => wp_create_nonce( 'lzb-tools-import-nonce' ),
    'plugin_url' => plugins_url(),
  ) );


}

function wp_twig_lazy_blocks_ajax_process_request() {

    try {
      $tools = new WpTwigLazyBlockTools();
      $response = $tools -> import_json_ajax();
      echo $response;
    } catch(Exeception $e) {
      echo 'Message: ' .$e->getMessage();
    }
		die();
}
add_action('wp_ajax_import_lazy_block_action', 'wp_twig_lazy_blocks_ajax_process_request');


function wp_twig_get_blocks() {

  try {
    // WP_Query arguments
    $args = array(
      'post_type'              => array( 'lazyblocks' ),
    );
    $query = new WP_Query( $args );
    $json = json_encode($query->posts, true);
    echo $json;
    wp_reset_postdata();


  } catch(Exeception $e) {
    echo 'Message: ' .$e->getMessage();
  }
  die();
}
add_action('wp_ajax_get_blocks', 'wp_twig_get_blocks');

?>