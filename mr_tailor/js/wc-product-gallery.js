jQuery(document).ready(function($) {

	"use strict";

	var control = false;

	$(".featured_img_temp").hide();
	
	//owl	
	$("#product-images-carousel").owlCarousel({
		singleItem : true,
		autoHeight : true,
		transitionStyle:"fade",
		lazyLoad : true,
		afterMove: function(event){
			if ( control !== true ) {
				activate_slide(this.currentItem	);
			}
		}
	});
	
	//get carousel instance data and store it in variable owl
	var owl                                 = $("#product-images-carousel").data('owlCarousel');
	var $product_thumbnails                 = $('.product_thumbnails');
	var $product_thumbnails_cells 			= $product_thumbnails.find('.carousel-cell');
	var $product_thumbnails_height 			= $product_thumbnails.height();
	var $product_thumbnails_width 			= $product_thumbnails.width();


	$(".variations").on('change', 'select', function() {
	    owl.goTo(0);
	});

	function product_gallery_slider() {

		if ( $("#product-images-carousel").length ) {


			$product_thumbnails.on('click', '.carousel-cell', function(event) {
				var index = $(event.currentTarget).index();

				activate_slide(index);

				control = true;
				owl.goTo(index);
				control = false;
			});


			$(".variations").on('change', 'select', function() {
				setTimeout(function() {
					activate_slide(0);
				}, 500);
			});

		}

	}

	function activate_slide(index) {
		//get carousel instance data and store it in variable owl

		if ( $(".show-for-large-up").length ) {
			activate_horizontal_slide(index);
		}
		else {
			activate_vertical_slide(index);
		}

	}

	function activate_vertical_slide(index) {

		var $product_thumbnails_cells_height 	= $product_thumbnails_cells.eq(index).outerHeight();

		var $distanceTop = $product_thumbnails_cells.eq(index).position().top + $product_thumbnails.scrollTop();

		$product_thumbnails.find('.is-nav-selected').removeClass('is-nav-selected');
		
		var $selected_cell = $product_thumbnails_cells.eq(index).addClass('is-nav-selected');

		var $scrollY = ($distanceTop) - ( ($product_thumbnails_height - $product_thumbnails_cells_height) / 2) - 10;


		$product_thumbnails.animate({
			scrollTop: $scrollY
		}, 300);	

	}

	function activate_horizontal_slide(index) {

		$product_thumbnails.css("display", "flex");

		var $product_thumbnails_cells_width = $product_thumbnails_cells.eq(index).outerWidth();

		var $distanceLeft = $product_thumbnails_cells.eq(index).position().left + $product_thumbnails.scrollLeft();

		$product_thumbnails.find('.is-nav-selected').removeClass('is-nav-selected');
		
		var $selected_cell = $product_thumbnails_cells.eq(index).addClass('is-nav-selected');

		var $scrollX = ($distanceLeft) - ( ($product_thumbnails_width - $product_thumbnails_cells_width) / 2) - 10;


		$product_thumbnails.animate({
			scrollLeft: $scrollX
		}, 300);	

	}


	product_gallery_slider();

});