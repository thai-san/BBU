$(document).ready(function(){

	$('table.footable').footable();

	var $applications = $('.sortable');
	var $data = $applications.clone();
	var $sortedData;

	
  	$("label.filter").mousedown(function(){
  	
  		var $filteredData = $data.find('li');
	  	var $filterType = $(this).children("input").attr('id');
		$sortedData = $filteredData;
  		var $filteredData = $data.find('li');
		
  		if ($filterType == "SortField") {
  			$sortedData = $filteredData;
		}else if($filterType == "SortWordAt") {
			var $sortedData = $filteredData.sorted({
				reversed:true,
				by: function(v) {
					return $(v).find('.worked_at').text();
				}
			});
		} else if($filterType == "SortOperation") {
			var $sortedData = $filteredData.sorted({
				by: function(v) {
					 return $(v).find('.operation_name').text();
				}
			});
		}else if($filterType == "SortProgress") {
			var $sortedData = $filteredData.sorted({
				reversed:true,
				by: function(v) {
					 return parseInt($(v).find('.amount_percent_overall').text());
				}
			});
		}

		$applications.quicksand($sortedData, {
			duration: 800,
			easing: 'easeOutExpo'
		});
  	});
 
  	 $('.tooltip-demo').tooltip({
      selector: "[data-toggle=tooltip]"
    })
});

(function($) {
  $.fn.sorted = function(customOptions) {
    var options = {
      reversed: false,
      by: function(a) { return a.text(); }
    };
    $.extend(options, customOptions);
    $data = $(this);
    arr = $data.get();
    arr.sort(function(a, b) {
      var valA = options.by($(a));
      var valB = options.by($(b));
      if (options.reversed) {
        return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;				
      } else {		
        return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;	
      }
    });
    return $(arr);
  };
})(jQuery);

function readImage(file,holder) {

    var reader = new FileReader();
    var image  = new Image();

    reader.readAsDataURL(file);  
    reader.onload		= function(_file) {
        image.src   	= _file.target.result;              // url.createObjectURL(file);
        image.onload 	= function() {
            var width 	= this.width,
                height	= this.height,
                type 	= file.type,                           // ext only: // file.type.split('/')[1],
                name 	= file.name,
                size 	= ~~(file.size/1024) +'KB';
            $(holder).append('<img class="img-thumbnail" src="' + this.src + '" width="100%"> ' + width + 'x' + height + ' ' + size + ' ' + type + ' ' + name + '<br>');
        };

        image.onerror= function() {
            alert('Invalid file type: '+ file.type);
        };      
    };

}