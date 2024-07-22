<?php

//* Remove WordPress's default image sizes
function remove_default_image_sizes( $sizes) {
    unset( $sizes['large']);
    unset( $sizes['thumbnail']);
    unset( $sizes['medium']);
    unset( $sizes['medium_large']);
    unset( $sizes['1536x1536']);
    unset( $sizes['2048x2048']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'remove_default_image_sizes');


function display_all_categories_with_empty() {
    $args = array(
        'hide_empty' => false, // Hiển thị cả những chuyên mục không có bài viết
    );
    $categories = get_categories( $args );
    
    if ( ! empty( $categories ) ) {
        $output = '<ul>';
        
        foreach ( $categories as $category ) {
            $output .= '<li><a title="' . $category->name . '" href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a></li>';
        }
        
        $output .= '</ul>';
        
        return $output;
    } else {
        return 'Không có chuyên mục nào được tìm thấy.';
    }
}
add_shortcode( 'danhsachchuyenmuc', 'display_all_categories_with_empty' );
// Hàm hiển thị nội dung trang tùy chỉnh
function custom_admin_page_content() {
    global $wpdb;
    $args = array(
        'hide_empty' => false, // Hiển thị cả những chuyên mục không có bài viết
    );
    $categories = get_categories($args); // Lấy tất cả danh mục bài viết
    ?>
    <div class="wrap">
        <h1>Hướng dẫn theme - LH 0911202404 (Truyện)</h1>
        <h2>⭐ Sửa danh sách bài viết muốn hiển thị ở trang chủ</h2>
<p>
Lấy ID danh mục tương ứng để điền vào shortcode chuyên mục muốn hiển thị:<br>
<img src="https://i.imgur.com/Lu4CJKr.png" width="700px"/>
		</p>
<p>
	Ví dụ:  Shortcode [chuyenmuc-wide cat_id="12" offset="4" posts_per_page="3"] thì trong đó cat_id="12" là id của chuyên mục , offset="4" là số bài viết không muốn hiển thị ( tính thụt lùi ), posts_per_page="3" là số lượng bài muốn hiển thị
		</p>
        <table class="widefat">
            <thead>
                <tr>
                    <th>Tên danh mục</th>
                    <th>ID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) : ?>
                    <tr>
                        <td><?php echo $category->name; ?></td> <!-- Hiển thị tên của danh mục -->
                        <td><?php echo $category->cat_ID; ?></td> <!-- Hiển thị ID của danh mục -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}



function latest_posts_shortcode($atts) {
    // Thiết lập các tham số mặc định và ghi đè bằng các tham số được truyền vào
    $atts = shortcode_atts(array(
        'offset' => 0,
    ), $atts, 'latest_posts');

    // Truy vấn các bài viết mới nhất với offset được chỉ định
    $args = array(
        'posts_per_page' => 7,
        'offset'         => $atts['offset'],
    );
    $query = new WP_Query($args);

    ob_start(); // Bắt đầu bộ đệm đầu ra
    ?>
    <ul class="baimoi">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <?php
            // Lấy tiêu đề và liên kết của bài viết
            $title = get_the_title();
            $link = get_permalink();
            ?>
            <li><a title="<?php echo esc_html($title); ?>" href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></li>
        <?php endwhile; ?>
    </ul>
<style>
ul.baimoi li {
    border-bottom: 1px dashed #ccc;
    padding: 0;
    margin: auto 15px;
}
</style>
    <?php
    $output = ob_get_clean(); // Lấy nội dung từ bộ đệm đầu ra và xóa bộ đệm

    // Phục hồi các truy vấn
    wp_reset_postdata();

    return $output;
}
add_shortcode('baimoinhat', 'latest_posts_shortcode');




function chuyenmuc_shortcode($atts) {
    // Thiết lập các tham số mặc định và ghi đè bằng các tham số được truyền vào
    $atts = shortcode_atts(array(
        'id' => 0,
        'offset' => -1,
        'number-post' => 10,
    ), $atts, 'chuyenmuc');

    // Truy vấn các bài viết trong chuyên mục được chỉ định
    $args = array(
        'posts_per_page' => $atts['number-post'],
        'offset'         => $atts['offset'],
        'category'       => $atts['id'],
    );
    $query = new WP_Query($args);

    ob_start(); // Bắt đầu bộ đệm đầu ra
    ?>
    <ul class="chuyenmuc">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <li>
                <div class="chuyenmuc-post-thumbnail">
                    <?php if (has_post_thumbnail()): ?>
                        <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                    <?php endif; ?>
                </div>
                <div class="chuyenmuc-post-content">
                    <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
<p><?php $post_date = get_the_date();
	echo $post_date; ?></p>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
    <style>
        ul.chuyenmuc li {
            display: flex;
            border-bottom: 1px dashed #ccc;
            padding: 6px;
            margin: 0 0 5px;
        }
        .chuyenmuc-post-thumbnail {
            flex: 0 0 25%;
            margin-right: 15px;
        }
.chuyenmuc-post-content a {
    font-weight: bold;
}
.chuyenmuc-post-content p {
    margin-bottom: 0;font-size:80%;color:#555;
}
        .chuyenmuc-post-thumbnail img {
            width: 100%;
			max-height:55px;
            height: auto;
			border-radius:5px;
        }
        .chuyenmuc-post-content {
            flex: 1;
        }
    </style>
    <?php
    $output = ob_get_clean(); // Lấy nội dung từ bộ đệm đầu ra và xóa bộ đệm

    // Phục hồi các truy vấn
    wp_reset_postdata();

    return $output;
}
add_shortcode('chuyenmuc', 'chuyenmuc_shortcode');




function my_category_shortcode($atts) {

  // Thiết lập các tham số mặc định và ghi đè bằng các tham số được truyền vào
  $atts = shortcode_atts(array(
    'cat_id' => 0,
    'offset' => -1, 
    'posts_per_page' => 10
  ), $atts, 'mycategory');

  // Truy vấn các bài viết trong chuyên mục được chỉ định
  $args = array(
    'posts_per_page' => $atts['posts_per_page'],
    'offset' => $atts['offset'],
    'category' => $atts['cat_id']
  );

  $query = new WP_Query($args);

  ob_start();

  ?>

  <ul class="my-category">

  <?php while ($query->have_posts()) : $query->the_post(); ?>

    <li>
      <div class="cat-post-thumbnail">
        <?php if (has_post_thumbnail()): ?>
          <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
        <?php endif; ?>
      </div>

      <div class="cat-post-content">
		  <?php $post_date = get_the_date();
	echo $post_date; ?>
       <h3>
		   
		 <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>  </h3>
		<?php 
        $excerpt = get_the_excerpt();
        if ( wp_is_mobile() ) {
            echo ''; // Hiển thị chỉ 5 từ trên mobile
        } else {
            echo wp_trim_words( $excerpt, 50 ); // Hiển thị chỉ 50 từ trên desktop
echo '  <p><a class="xemthem" href="' . get_permalink() . '">Xem thêm</a></p>';
        }
    ?>
	
      </div>
    </li>

  <?php endwhile; ?>

  </ul>

<style>

ul.my-category li {
  display: flex; 
  border-bottom: 1px dashed #ccc;
  padding: 10px;
  margin: 0 0 5px;  
}
	.my-category a {font-weight:bold;font-size: 120%;}
.cat-post-thumbnail {
  flex: 0 0 25%;
  margin-right: 15px;  
}

.cat-post-thumbnail img {
  width: 100%;
  max-height: 115px;
  height: auto;
  border-radius: 5px;
}

.cat-post-content {
  flex: 1; font-size:90%;
}
a.xemthem, a.button.is-gloss.is-small.mb-0 {
    background: var(--fs-color-primary);
    padding: 5px 10px!important;
    border-radius: 5px;
    color: white;
    font-weight: normal;
    text-transform: capitalize;
    font-size: 13px;
    line-height: 1.4em;
}
</style>
  <?php

  $output = ob_get_clean();

  wp_reset_postdata();

  return $output;

}

add_shortcode('chuyenmuc-wide', 'my_category_shortcode');



// Function to retrieve latest posts
function breaknews_shortcode_function( $atts ) {
    // Default attributes
    $atts = shortcode_atts( array(
        'num_posts' => 5,
    ), $atts, 'breaknews' );

    // Get latest posts
    $latest_posts = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => $atts['num_posts'],
    ) );

    // Output HTML
    $output = '<div class="breaknews">';
    if ( $latest_posts->have_posts() ) {
        $output .= '<ul class="breaknews-list">';
        while ( $latest_posts->have_posts() ) {
            $latest_posts->the_post();
            $output .= '<li>⭐ <a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        }
        $output .= '</ul>';
    } else {
        $output .= '<p>No posts found</p>';
    }
    $output .= '</div><style>
.breaknews {
    overflow: hidden;
    max-width: 400px;
    margin: auto;
    border-radius: 99px;
    z-index: 999;
    padding: 10px;
    background: #293642;
    border-right: 3px solid var(--fs-color-primary);
}
.breaknews-list a {color:white;}
.breaknews-list a:hover {color:var(--fs-color-primary);}
.breaknews-list {
    list-style: none;
    padding: 5px;
    margin: 0;
    white-space: nowrap;
    animation: marquee 10s linear infinite;
}

.breaknews-list:hover {
    animation-play-state: paused;
}

.breaknews-list li {
    display: inline;
    margin-right: 20px;
}

@keyframes marquee {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}

@media only screen and (max-width: 600px) {
    .breaknews {
        max-width: 100%; /* Giữ khung trong phạm vi màn hình */
        margin: 0 10px; /* Khoảng cách để tránh tràn ra ngoài */
    }

    .breaknews-list {
        animation: none; /* Không chạy marquee khi màn hình nhỏ hơn 600px */
        white-space: normal; /* Trả về không gian trắng bình thường */
    }
}

</style>';

    // Restore global post data
    wp_reset_postdata();

    return $output;
}
add_shortcode( 'breaknews', 'breaknews_shortcode_function' );




function custom_breadcrumbs() {
    // Home page
    echo '<a href="' . home_url() . '">Trang chủ</a>';

    if (is_category() || is_single()) {
        // Category
        $category = get_the_category();
        if (!empty($category)) {
            echo '<span> / </span><a href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->name . '</a>';
        }

        // Single post
        if (is_single()) {
          
        }
    } elseif (is_page()) {
        // Page
        echo '<span> / </span>' . get_the_title();
    }
}

// ADD CUSTOM UX
function devvn_ux_builder_element(){
    add_ux_builder_shortcode('devvn_viewnumber', array(
        'name'      => __('Bai Viet Trang Chu UX'),
        'category'  => __('Content'),
        'priority'  => 1,
        'options' => array(
            'catId'    =>  array(
                'type' => 'scrubfield',
                'heading' => 'Categories ID',
                'default' => '4',
                'step' => '1',
                'unit' => '',
                'min'   =>  1,
                //'max'   => 2
            ),
        ),
    ));
}
add_action('ux_builder_setup', 'devvn_ux_builder_element');

// SHORTCODE

function devvn_viewnumber_func($atts){
    $atts = shortcode_atts(array(
        'cat_id' => '7',
        'offset' => -1, 
        'posts_per_page' => 4,
        'orderby' => 'rand'
      ), $atts );

	$args = array(
    'cat' => 7,               // Chỉ định ID của category
    'posts_per_page' => 4,    // Số lượng bài viết muốn lấy
    'post__not_in' => array(911),// Loại trừ bài viết có ID là 33
		 'orderby' => 'rand'
);
    
      $query = new WP_Query($args);
    
      echo '<div class="content-left">';
    //   while ($query->have_posts()) : $query->the_post(); 
    //   if ($query->current_post === 0) {
    //         echo '<div class="big-news ani-item on-show">
    //         <div class="box-news">
    //             <a class="link-load" href="#">
    //             <div class="date">08 <span>05 - 2024</span>
    //             </div>
    //             <div class="pic-news" style="background-image: url(&quot;https://www.ancuong.com/webadmin-v2/pictures/files/news/2024/creative-hub-brochure/web.jpg&quot;);">
    //                 <img src="https://www.ancuong.com/webadmin-v2/pictures/files/news/2024/creative-hub-brochure/web.jpg" alt="CREATIVE HUB - TRUNG TÂM SÁNG TẠO BY AN CƯỜNG" loading="lazy" class="load-start" data-was-processed="true">
    //             </div>
    //             <h3><span style = "color:#fff !important; font-weight:500;">CREATIVE HUB - TRUNG TÂM SÁNG TẠO BY LÂM HIỆP HƯNG</span>
    //             </h3>
    //             </a>
    //         </div>
    //     </div>';
    //   }else{
    //         echo '<div class="small-news ani-item on-show">
    //         <div class="box-news">
    //             <a class="link-load" href="#">
    //             <div class="date">12 <span>04 - 2024</span>
    //             </div>
    //             <h3 >CHÍNH THỨC RA MẮT KHÔNG GIAN SÁNG TẠO ĐẦY MÀU SẮC CREATIVE HUB BY LAM HIEP HUNG</h3>
    //             </a>
    //         </div>
    //     </div>';
    //   };


      while ($query->have_posts()) : $query->the_post(); 
      if ($query->current_post === 0) { 
            echo '<div class="big-news ani-item on-show">
            <div class="box-news">
              <a class="link-load" href="'.get_permalink().'"> 
                <div class="date">'.get_the_date('d').'<span>'.get_the_date('m').'-'.get_the_date('Y').'</span>
                </div>
                <div class="pic-news" >';
                $thumbnail_url = get_the_post_thumbnail_url();
                if ($thumbnail_url) {
                    echo '<img src="' . $thumbnail_url . '" alt="' . get_the_title() . '" loading="lazy" class="load-start" data-was-processed="true">';
                }else{
                    echo '<img src="http://aleecafe.com:81/wp-content/uploads/2024/05/placeholder-image.webp" loading="lazy" class="load-start" data-was-processed="true">';
                };
                echo '
                 </div>
                <h3>
                  <span style = "color:#fff !important; font-weight:500;" >'.get_the_title().'</span>
                </h3>
              </a>
            </div>
          </div>';
        } else {
            echo '<div class="small-news ani-item on-show">
            <div class="box-news">
              <a class="link-load" href="'.get_permalink().'">
                <div class="date">'.get_the_date('d').'<span>'.get_the_date('m').'-'.get_the_date('Y').'</span>
                </div>
                <h3>'.get_the_title().'</h3>
              </a>
            </div>
            
          </div>';
        };
      endwhile;
    wp_reset_postdata(); // Đặt lại dữ liệu bài viết
    echo '</div>';

  echo '  <style> 
            @media screen and (min-width: 1100px) {
                .pic-news, .pic-pro {
                    border: 0 solid #444;
                    transition: border-width .3s ease-in-out;
                }
            }
            @media screen and (min-width: 1100px) {
                .big-news.on-show, .player-vid.on-show, .small-news.on-show, .view-all.on-show {
                    animation-duration: 1s;
                    animation-fill-mode: forwards;
                }
            }
            @media screen and (min-width: 1100px) {
                .big-news.on-show, .small-news.on-show {
                    animation-name: goRight;
                    animation-delay: 0s;
                }
            }
            .big-news, .small-news {
                display: block;
                margin: 0;
            } 
            .big-news .box-news, .date {
                background-color: var(--color-normal);
            }
            .big-news h3 span, .box-news, .box-news a, .box-news h3, .date span {
                display: block;
            }
            .box-news {
                margin: 0 0 10px;
                text-align: left;
                background-color: var(--color-white-grey);
            }
            .big-news, .box-news, .pic-news, .small-news {
                width: 100%;
                height: auto;
                position: relative;
                opacity: 1;
            }
            @media screen and (min-width: 1100px) {
                .box-news, .date, .new-product h3 {
                    transition: background-color .3s ease-in-out;
                }
            } .big-news .date {
                top: 0;
                transform: translateY(0);
            }

            .big-news .box-news, .date {
                background-color: #ba181b;
            }
            .date {
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: auto;
                height: auto;
                padding: 5px 10px;
                font-weight: 100;
                font-size: 36px;
                color: #fff;
                line-height: 1;
                text-align: center;
                z-index: 1;
            } 
            .date span {
                font-weight: 500;
                font-size: 10px;
                color: #FFF;
            }

            .big-news .pic-news {
                height: 280px;
                max-height: none;
            }
            .pic-news {
                max-height: 255px;
                overflow: hidden;
                background-position: center center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            .big-news, .box-news, .pic-news, .small-news {
                width: 100%;
                height: auto;
                position: relative;
                opacity: 1;
            }
            .pic-news img {
                width: 100%;
                height: auto;
                display: block;
                opacity: 1;
            }
            .pic-news img {
                pointer-events: none;
            }
            .big-news h3, .view-all {
                padding: 10px 20px;
                font-size: 13px;
            }

            .big-news h3 {
                color: #fff;
                text-transform: uppercase;
            }
            .box-news h3 {
                font-size: 14px;
                line-height: 1.6;
                font-weight: 500;
                color: #111;
                position: relative;
                padding: 10px 20px 10px 70px;
                text-transform: uppercase;
            }

            .small-news.ani-item.on-show {
                margin-top: 20px;
            }
            </style>';
}
add_shortcode('devvn_viewnumber', 'devvn_viewnumber_func');

function enqueue_child_theme_styles() {
    // Enqueue parent theme styles first if needed
    // wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Enqueue Slick CSS
    wp_enqueue_style('slick-css', get_stylesheet_directory_uri() . '/assets/css/slick.min.css', array(), null);
}
add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles');

function add_custom_scripts() {
    // Đăng ký jQuery
    wp_enqueue_script('custom-jquery', get_stylesheet_directory_uri() . '/assets/js/jquery.min.js', array(), false, true);

    // Đăng ký Slick slider và các sự kiện của nó
    wp_enqueue_script('slick', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array('custom-jquery'), false, true);
    wp_enqueue_script('event-slick', get_stylesheet_directory_uri() . '/assets/js/event-slick.min.js', array('slick'), false, true);

    // Đăng ký các hàm JavaScript tùy chỉnh
    wp_enqueue_script('custom-functions', get_stylesheet_directory_uri() . '/assets/js/function.min.js', array('slick'), false, true);
}

add_action('wp_enqueue_scripts', 'add_custom_scripts');
