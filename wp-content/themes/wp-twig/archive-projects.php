<?php
$context = Timber::context();

$project_args = array(
	'post_type' => array( 'projects' )
);
$context['posts'] = new Timber\PostQuery($project_args);
$context['projects_tag'] = Timber::get_terms('projects_tag');

Timber::render( array( 'archive-projects.twig', 'index.twig' ), $context );
