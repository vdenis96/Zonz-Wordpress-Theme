<?php
/*
Template Name: Sitemap Page
*/
?>
<?php get_header(); ?>
<div id="site-map">
<div class="site_map1">
<h2 id="pages">Pagina's</h2>
<?php $pages = get_pages(); foreach ( $pages as $page ) { $option = '<ul><li> <a href="' . get_page_link( $page->ID ) . '" alt="'; $option .= $page->post_title; $option .='">'; $option .= $page->post_title;  $option .= '</a></li></ul>'; echo $option; } ?>


</div>
<div class="site_map1">
<h2 id="posts">Nieuwsberichten</h2>
<ul>
<?php
// Add categories you'd like to exclude in the exclude here
$cats = get_categories('exclude=');
foreach ($cats as $cat) {
  //echo "<li><h3>".$cat->cat_name."</h3>";
  echo "<ul>";
  query_posts('posts_per_page=-1&cat='.$cat->cat_ID);
  while(have_posts()) {
    the_post();
    $category = get_the_category();
    // Only display a post link once, even if it's in multiple categories
    if ($category[0]->cat_ID == $cat->cat_ID) {
      echo '<li><a href="'.get_permalink().'" alt="'.get_the_title().'">'.get_the_title().'</a></li>';
    }
  }
  echo "</ul>";
  echo "</li>";
}
?>
</ul>
</div>
<?php
foreach( get_post_types( array('public' => true) ) as $post_type ) {
  if ( in_array( $post_type, array('post','page','attachment') ) )
    continue;

  $pt = get_post_type_object( $post_type );
	echo '<div class="sit_sec">';
  echo '<h2 class="pre-sitemap">'.$pt->labels->name.'</h2>';
  echo '<ul>';

  query_posts('post_type='.$post_type.'&posts_per_page=-1');
  while( have_posts() ) {
    the_post();
    echo '<li><a href="'.get_permalink().'" alt="'.get_the_title().'">'.get_the_title().'</a></li>';
  }

  echo '</ul>';
  echo '</div>';
}
?>
</div>
<?php get_footer(); ?>