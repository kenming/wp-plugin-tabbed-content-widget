<?php

function show_recent_comments(){
    $comment_args = array(
        'status' => 'approve',
        'number'    => '10' //Number of comments
    );

    // The Query
    $comments_query = new WP_Comment_Query;
    $comments 		= $comments_query->query( $comment_args );

    $render_html = '<ul class="list-unstyled widget-recent-comments">';
    // Comment Loop
    if ( $comments ) {
        foreach ( $comments as $comment ) {
            $render_html .= '<li>';
            $render_html .= '<div class="author-meta">';
            $render_html .= get_avatar( $comment->comment_author_email, 48 );
            $render_html .=	'<p><a href="' . esc_url( $comment->comment_author_url ) . '">' . $comment->comment_author . '</a>';
            $render_html .= '<span>&nbsp;@&nbsp;</span>' ;
            $render_html .= '<a href="' . esc_url( get_permalink( $comment->comment_post_ID ) ) . '">' . $comment->post_title . '</a></p>';
            $render_html .= '</div>';
            $render_html .= '<div style="clear:both">';
            $render_html .= '<p class="comment-body">' . custom_excerpt($comment->comment_content, '48') . '</p>';
            $render_html .= '</div>';
            $render_html .= '</li>';
        }
        $render_html .= '</ul>';
    } else {

        $render_html .= __e( '<p>No comments found.</p>', 'tabbed-contents_widget' );
    }
    
    return $render_html; 
}

function show_rand_posts() { 

    $args = array(
        'post_type' => 'post',
        'orderby'   => 'rand',
        'posts_per_page' => 10, 
        );
     
    $the_query = new WP_Query( $args );
    
    $render_html = '<ul class="list-unstyled widget-rand-posts">';

    $default_img_src = 'http://images.kenming.idv.tw/default-img/thumbnail-default-image.png';
    if ( $the_query->have_posts() ) {    
        while ( $the_query->have_posts() ) {
            $the_query->the_post();

            $render_html .= '<li>';
            if ( has_post_thumbnail()) :
                $render_html .= '<img src=' . get_the_post_thumbnail_url() . '></img></a>';
            else :                            
                $render_html .= '<img src="' . $default_img_src . '"></img></a>';
            endif;
            $render_html .= '<a href="'. get_permalink() .'">'. '<span>' . get_the_title() . '</span></a>';
            $render_html .= '<div class="post-date">' . get_the_date('Y-m-d') . '&nbsp;&nbsp;' . get_the_time('H:i') . '</div>'; 
            $render_html .= '</li>';
        }
        $render_html .= '</ul>';
        /* Restore original Post Data */
        wp_reset_postdata();
    } else {     
        $render_html .= __e( '<p>No Post found.</p>', 'tabbed-contents_widget' );
    }
     
    return $render_html; 
}