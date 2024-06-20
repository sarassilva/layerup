<?php get_header(); ?>

<main id="main-content">
    <h1>Filter by Category</h1>

    <div id="categories" class="categories">
        <h2><span>Categorias</span></h2>

        <ul class="cat-list">
            <?php
            $categories = get_categories();
            foreach ($categories as $category) {
                echo '<li><a href="#" class="category" data-id="' . $category->term_id . '">' . $category->name . '</a></li>';
            }
            ?>
        </ul>

        <div id="posts" class="posts">
            <h2><span>Posts</span></h2>
            <ul id="post-list">
                <?php
                $args = array(
                    'numberposts' => -1 
                );
                
                $posts = get_posts($args);

                foreach ($posts as $post) {
                    setup_postdata($post);
                    ?>
                    <li>
                        <div class="featured">
                            <?php echo get_the_post_thumbnail( $page->ID, 'thumbnail' ); ?>
                        </div>
                        <h3><?php echo get_the_title(); ?></h3>
                    </li>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </ul>
        </div>
    </div>
</main>


<?php get_footer(); ?>