<?php
use Timber\Timber;
$context = Timber::context();

$project_args = array(
	'post_type' => array( 'projects' )
);
$context['posts'] = Timber::get_posts($project_args);
$context['projects_tag'] = Timber::get_terms('projects_tag');

Timber::render( array( 'archive-projects.twig', 'index.twig' ), $context );
