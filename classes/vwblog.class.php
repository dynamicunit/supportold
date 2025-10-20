<?php
class vwblog
{
    public function get_blogs($data)
    {
        $random_posts_html = '';

        foreach ($data as $post) {
            // Check for required fields
            $title = isset($post['blog_title']) ? htmlspecialchars($post['blog_title']) : 'Untitled';
            $image = isset($post['image']) ? htmlspecialchars($post['image']) : 'img/default-image.jpg';
            $slug = isset($post['slug']) ? htmlspecialchars($post['slug']) : '#';
            $description = isset($post['meta_desc']) ? htmlspecialchars($post['meta_desc']) : 'No description available';
            $category = isset($post['description']) ? htmlspecialchars($post['description']) : 'General';
            $createdAt = isset($post['created_at']) ? htmlspecialchars($post['created_at']) : 'Unknown date';

            // Build the post URL
            $postUrl = config::get('website/website_url') . '/blog-post/' . urlencode($slug);

            // Generate the HTML
            $random_posts_html .= <<<HTML
    
    <div class="row mb-4 align-items-center">
        <div class="col-2">
            <figure class="mb-0">
                <a href="$postUrl">
                    <img src="$image" alt="" class="img-fluid rounded">
                </a>
            </figure>
        </div>
        <div class="col-10">
            <div class="post_info">
                             <h3 class="mb-1"><a href="$postUrl" class="text-dark text-decoration-none">$title</a></h3>
                <small class="text-muted d-block mb-2">$category - $createdAt</small>
                <p class="text-muted mb-0">$description</p>
            </div>
        </div>
    </div>
HTML;


        }

        return $random_posts_html;
    }
}
