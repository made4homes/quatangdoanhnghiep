
    $(document).on('ready', function() {  

      $('.slide-leadership').on('init reInit setPosition', function(event, slick) {
        // Thêm 'slick-active' và 'slick-current' cho tất cả các slides
        slick.$slides.addClass('slick-active slick-current');
    }).slick({
      slidesToShow: 6,
        slidesToScroll: 1,
        nextArrow: "<div class='next'><svg class='lnr lnr-arrow-right'><use xlink:href='#lnr-arrow-right'></use></svg></div>",
        prevArrow: "<div class='prev'><svg class='lnr lnr-arrow-left'><use xlink:href='#lnr-arrow-left'></use></svg></div>",
        responsive: [{
            breakpoint: 1500,
            settings: {
                slidesToShow: 5,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 1200,
            settings: {
                slidesToShow: 4,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 990,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 768,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 450,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
        // Các tùy chọn khác của Slick
    });

    $(".popup").click(function(o) {
        0 === $(o.target).parents(".popup").length && ($(this).fadeOut(),
        $(this).removeClass("active"),
        $("body,html").removeClass("overflow-hidden-ios"))
    }),
    $(".close-popup").click(function() {
        $(".popup").fadeOut(),
        $(".popup").removeClass("active"),
        $("body,html").removeClass("overflow-hidden-ios")
    }),
    
    $(".leadership .item").click(function() {
        let o = $(this);
        t(o, "data-name", ".render-fullname"),
        t(o, "data-position", ".render-position"),
        t(o, "data-description", ".render-description"),
        function(o) {
            let e = $(o).attr("data-image").split("||")
              , i = [];
            e.forEach(o=>{
                i.push(`<div><img src=${o} alt="#" width="100%"></div>`)
            }
            ),
            $(".render-img").html(`<div class="slide-prewiew">${i.join(" ")} </div>`)
        }(o),
        $(".slide-prewiew").slick({
            arrows: !0,
            nextArrow: "<div class='prev'><svg class='lnr lnr-chevron-right'><use xlink:href='#lnr-chevron-right'></use></svg></div>",
            prevArrow: "<div class='next'><svg class='lnr lnr-chevron-left'><use xlink:href='#lnr-chevron-left'></use></svg></div>"
        })
    }),
    $(".amination-dots").click(function() {
        $(this).parent().find(".name-dots").addClass("opacity-1"),
        $(this).parent().siblings().find(".name-dots").removeClass("opacity-1")
    });

});
