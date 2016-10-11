
	$(function() {
		$( "#_filtersearch_precio_range" ).slider({
			range: true,
			min: 0,
			max: 50000,
			step:1000,
			values: [ 0, 50000 ],
			slide: function( event, ui ) {
				var val1=ui.values[ 0 ];
				var val2=ui.values[ 1 ];
				val1=val1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				val2=val2.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				$( ".filter_precio>label" ).find(".value").text( "$" + val1 + " - $" + val2 );
			},
			stop:function(event, ui){
				searcher.applyFilter({name:'precio1',value:ui.values[ 0 ] + "-" + ui.values[ 1 ]},mapa);
			}
		});
		
				var val1=$( "#_filtersearch_precio_range" ).slider( "values", 0 );
				var val2=$( "#_filtersearch_precio_range" ).slider( "values", 1 );
				val1=val1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				val2=val2.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
		$( ".filter_precio>label" ).find(".value").text( "$" + val1 +" - $" + val2 );
		
			
	});
	
	
	
	$(function() {
		$( "#_filtersearch_banos_range" ).slider({
			range: true,
			min: 0,
			max: 10,
			step:1,
			values: [ 0, 10 ],
			slide: function( event, ui ) {
				$( ".filter_banos>label" ).find(".value").text( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			},
			stop:function(event, ui){
				searcher.applyFilter({name:'banos1',value:ui.values[ 0 ] + "-" + ui.values[ 1 ]},mapa);
			}
		});
		
		$( ".filter_banos>label" ).find(".value").text( "" + $( "#_filtersearch_banos_range" ).slider( "values", 0 ) +
			" - " + $( "#_filtersearch_banos_range" ).slider( "values", 1 ) );
			
	});
	
	
	
	
	
	
	$(function() {
		$( "#_filtersearch_habitaciones_range" ).slider({
			range: true,
			min: 0,
			max: 10,
			step:1,
			values: [ 0, 10 ],
			slide: function( event, ui ) {
				$( ".filter_habitaciones>label" ).find(".value").text( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			},
			stop:function(event, ui){
				searcher.applyFilter({name:'habitaciones1',value:ui.values[ 0 ] + "-" + ui.values[ 1 ]},mapa);
			}
		});
		
		$( ".filter_habitaciones>label" ).find(".value").text( "" + $( "#_filtersearch_habitaciones_range" ).slider( "values", 0 ) +
			" - " + $( "#_filtersearch_habitaciones_range" ).slider( "values", 1 ) );
			
	});
	
	
	
	
	
	$(function() {
		$( "#_filtersearch_m2_range" ).slider({
			range: true,
			min: 0,
			max: 1000,
			step:1,
			values: [ 0, 1000 ],
			slide: function( event, ui ) {
				var val1=ui.values[ 0 ];
				var val2=ui.values[ 1 ];
				val1=val1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				val2=val2.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				$( ".filter_m2>label" ).find(".value").text( "" + val1 + " - " + val2 );
			},
			stop:function(event, ui){
				searcher.applyFilter({name:'m21',value:ui.values[ 0 ] + "-" + ui.values[ 1 ]},mapa);
			}
		});
		
				var val1=$( "#_filtersearch_m2_range" ).slider( "values", 0 );
				var val2=$( "#_filtersearch_m2_range" ).slider( "values", 1 );
				val1=val1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				val2=val2.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				
		$( ".filter_m2>label" ).find(".value").text( "" + val1 +" - " + val2 );
			
	});
	
	
	
	
	$(function() {
		$( "#_filtersearch_precio_m2_range" ).slider({
			range: true,
			min: 0,
			max: 1000000,
			step:1000,
			values: [ 0, 1000000 ],
			slide: function( event, ui ) {
				var val1=ui.values[ 0 ];
				var val2=ui.values[ 1 ];
				val1=val1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				val2=val2.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				$( ".filter_precio_m2>label" ).find(".value").text( "$" + val1 + " - $" + val2 );
			},
			stop:function(event, ui){
				searcher.applyFilter({name:'precio_m21',value:ui.values[ 0 ] + "-" + ui.values[ 1 ]},mapa);
			}
		});
		
				var val1=$( "#_filtersearch_precio_m2_range" ).slider( "values", 0 );
				var val2=$( "#_filtersearch_precio_m2_range" ).slider( "values", 1 );
				val1=val1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				val2=val2.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
		$( ".filter_precio_m2>label" ).find(".value").text( "$" + val1 +" - $" + val2 );
			
	});
	
	
	
	
	
	
	
	$(function() {
		$( "#_filtersearch_superficie_range" ).slider({
			range: true,
			min: 0,
			max: 10000,
			step:1,
			values: [ 0, 10000 ],
			slide: function( event, ui ) {
				var val1=ui.values[ 0 ];
				var val2=ui.values[ 1 ];
				val1=val1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				val2=val2.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				$( ".filter_superficie>label" ).find(".value").text( "" + val1 + " - " +val2 );
			},
			stop:function(event, ui){
				searcher.applyFilter({name:'superficie1',value:ui.values[ 0 ] + "-" + ui.values[ 1 ]},mapa);
			}
		});
		
		
				var val1=$( "#_filtersearch_superficie_range" ).slider( "values", 0 );
				var val2=$( "#_filtersearch_superficie_range" ).slider( "values", 1 );
				val1=val1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				val2=val2.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
				
		$( ".filter_superficie>label" ).find(".value").text( "" + val1 +" - " + val2);
			
	});
	
	
	
	
	
	
	
	
	
	
	$(function() {
		$( "#_filtersearch_anyo_range" ).slider({
			range: true,
			min: 1900,
			max: 2012,
			step:10,
			values: [ 1900, 2012 ],
			slide: function( event, ui ) {
				$( ".filter_anyo>label" ).find(".value").text( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			},
			stop:function(event, ui){
				searcher.applyFilter({name:'anyo1',value:ui.values[ 0 ] + "-" + ui.values[ 1 ]},mapa);
			}
		});
		
		$( ".filter_anyo>label" ).find(".value").text( "" + $( "#_filtersearch_anyo_range" ).slider( "values", 0 ) +
			" - " + $( "#_filtersearch_anyo_range" ).slider( "values", 1 ) );
			
	});
	
	
	
	
	
	
	
	
	$(function() {
		$( "#_filtersearch_estacionamiento_range" ).slider({
			range: true,
			min: 0,
			max: 10,
			step:1,
			values: [ 0, 10 ],
			slide: function( event, ui ) {
				$( ".filter_estacionamiento>label" ).find(".value").text( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			},
			stop:function(event, ui){
				searcher.applyFilter({name:'estacionamiento1',value:ui.values[ 0 ] + "-" + ui.values[ 1 ]},mapa);
			}
		});
		
		$( ".filter_estacionamiento>label" ).find(".value").text( "" + $( "#_filtersearch_estacionamiento_range" ).slider( "values", 0 ) +
			" - " + $( "#_filtersearch_estacionamiento_range" ).slider( "values", 1 ) );
			
	});
	
	
	