document.addEventListener("DOMContentLoaded", function () {

    $("#owl-carousel").owlCarousel({
		//autoPlay : 6000,
		navigation : true,
		slideSpeed : 300,
		paginationSpeed : 400,
		singleItem : true,
		lazyLoad : true,
		autoHeight : false,
        navigationText : ['<i class="icon arrow-left1"></i>','<i class="icon arrow-right1"></i>']

	});


    $('#owl-carousel-partners').owlCarousel({
        items : 5,
		autoPlay: true,
		singleItem : false,
		navigation : true,
		pagination: false,
		lazyLoad : true,
        slideSpeed : 200,
        rewindNav : true,
        
        // stopOnHover : true,
        responsive: true,
		navigationText : ['<i class="icon arrow-left"></i>','<i class="icon arrow-right"></i>']
	});


    $('.menu li a').click(function(){
        $('.header .menu li ul').stop().slideUp();
        if ( $(this).next().is('ul') ) {
            this.classList.toggle('down');
            $(this).next('ul').stop().slideToggle();
        }
    });

    $('.menuMobile > li > a').click(function(){
        $('.menuMobile li ul').stop().slideUp();
        if ( $(this).next().is('ul') ) {
            this.classList.toggle('down');
            $(this).next('ul').stop().slideToggle();
        }
    });


    $(document).on('click touchstart', function(e) {
			
        if (!$.contains($(".header .menu").get(0), e.target)  ) {
            $(".header .menu > li > ul").stop().slideUp();
        }

        if (!$.contains($(".header .menuMobile").get(0), e.target)  ) {
            $(".header .menuMobile > li > ul").stop().slideUp();
        }
    });

    $('input.styler, select').styler();


    $('.searchText').click(function(){
        $('.searchBoxOuter').toggle();
    });

    $('.searchBox .close').click(function(){
        $('.searchBoxOuter').hide();
    });

    $(document).on('click touchstart', function(e) {
			
        if (!$.contains($(".search").get(0), e.target)  ) {
            $(".searchBoxOuter").hide();
        }
       
    });

    $('article img').each(function (i, e) {
        console.log(i, e, e.style.float);
        if (e.style.float === 'left') {
            $(e).addClass('align-left');
        }
    });
    
});