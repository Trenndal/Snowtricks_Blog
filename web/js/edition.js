
  $(document).ready(function() {
    var $container = $('div#trenndal_snowtricksbundle_edittrick_images');
    var $container2 = $('div#trenndal_snowtricksbundle_edittrick_videos');

    var index = $container.find(':input').length;
    var index2 = $container2.find(':input').length;


    $('#add_image').click(function(e) {

      addCategory($container);//redobfs();

      e.preventDefault(); 
      return false;
    });

    $('#add_video').click(function(e) {

      addCategory2($container2);

      e.preventDefault(); 
      return false;
    });


    $('.form-group').change(function(e) { 
      if($(this).children('DIV').length>0){ 
		var base = $(this).children('DIV').first();
		var imgTarget;var inp;
		var readers = [];
		for(var i= 1; i < base.children('.jqtarget').length+1; i++){ 
			imgTarget = base.children('.jqtarget:nth-child('+i+')');
			inp = imgTarget.children('DIV').first().children().first().children('input')[0];
			if (inp.files && inp.files[0]) { 
				readers.push(new FileReader());
				if(imgTarget.children('img').length==0){ imgTarget=imgTarget.children('video').first();}
				else{ imgTarget=imgTarget.children('img').first(); }
				
				(function (_picTarget) {
					readers[i-1].addEventListener("load", function assignImageSrc(event) {
						var picFile = event.target.result;
						_picTarget.attr('src', picFile);
						this.removeEventListener("load", assignImageSrc);
					}, false);
				})(imgTarget);
				readers[i-1].readAsDataURL(inp.files[0]);
			}
		}
      } 
    });
	

    if (index == 0) {
      addCategory($container);
    } else {
      $container.children('div').each(function() {
        addDeleteLink($(this),true);
      });
    }
    if (index2 == 0) {
      addCategory2($container2);
    } else {
      $container2.children('div').each(function() {
        addDeleteLink2($(this),true);
      });
    }

    function addCategory($container, old=false) {
      var template = $container.attr('data-prototype')
        .replace(/__name__label__/g, ' ')
        .replace(/__name__/g,        (index+1))
      ;

      var $prototype = $(template);

      addDeleteLink($prototype,old);

      $container.append($prototype);

      index++;
    }

    function addCategory2($container2) {
      var template = $container2.attr('data-prototype')
        .replace(/__name__label__/g, ' ')
        .replace(/__name__/g,        (index2+1))
      ;

      var $prototype = $(template);

      addDeleteLink2($prototype);

      $container2.append($prototype);

      index2++;
    }
	
	
    function addDeleteLink($prototype,old=false) {
		
		if(old){
			var $imgPreview = $('<img src="'+ $prototype.attr('img-data') +'" class="card-img img-fluid w-100 mini-pic" alt="Preview" />');
		}
		else{var $imgPreview = $('<img src="../img/bg.jpg" class="card-img img-fluid w-100 mini-pic" alt="Preview" />');}
		var $deleteLink = $('<div class="positioner"><a href="#" class="btn btn-danger pull-right" ><i class="icon-2x icon-trash"></i></a></div>');

		var upfile = $prototype.children('DIV').first().children('.form-group').first().children('input').first();
		$prototype.children('DIV').first().children('.form-group').first().attr('class','form-group positioner2');
		upfile.attr('class','filestyle');
		upfile.attr('data-input','false');
		upfile.attr('data-iconName','icon-2x icon-pencil');
		upfile.attr('data-buttonText',' ');
		if(old){ upfile.removeAttr('required'); }
	  
		$prototype.children('DIV').first().children('.form-group').first().children('label').first().attr('style','display:none;');
		$prototype.children('label').first().attr('style','display:none;');
		
		$prototype.append($deleteLink);
		$prototype.append($imgPreview);
		$prototype.attr('class','form-group jqtarget');

      $deleteLink.click(function(e) {
        $prototype.remove();

        e.preventDefault(); 
        return false;
      });
    }
	
    function addDeleteLink2($prototype,old=false) {
		
      var $imgPreview = $('<video src=" " class="" alt="Video Preview" width="320" height="240" controls></video>');
      var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');

      $prototype.append($imgPreview);
      $prototype.append($deleteLink);
	  $prototype.attr('style','background-color:gray;');
	  $prototype.attr('class','form-group jqtarget');

      $deleteLink.click(function(e) {
        $prototype.remove();

        e.preventDefault(); 
        return false;
      });
    }
	
  });
