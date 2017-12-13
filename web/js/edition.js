
  $(document).ready(function() {
    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#trenndal_snowtricksbundle_edittrick_images');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find(':input').length;

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_image').click(function(e) {

      addCategory($container);

      e.preventDefault(); // évite qu'un # apparaisse dans l'URL
      return false;
    });


    $('.form-group').change(function(e) { 
      if($(this).children('input:file')){ 
        var inp = $(this).children('input')[0];
        if (inp.files && inp.files[0]) { 
          var reader = new FileReader();
		  var imgTarget = $(this).parent().parent().children('img');
          reader.onload = function(e) { 
		  imgTarget.attr('src', reader.result); };
		  reader.readAsDataURL(inp.files[0]);
        }
      } 
      return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
      addCategory($container);
    } else {
      // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
      $container.children('div').each(function() {
        addDeleteLink($(this));
      });
    }
	/*
    if (index == 0) {
      addHpic($container);
      addCategory($container);
    } else if (index == 1) {
      addCategory($container);
    } else {
      // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
      $container.children('div').each(function() {
        addDeleteLink($(this));
      });
    }*/

    // La fonction qui ajoute un formulaire CategoryType
    function addCategory($container) {
      // Dans le contenu de l'attribut « data-prototype », on remplace :
      // - le texte "__name__label__" qu'il contient par le label du champ
      // - le texte "__name__" qu'il contient par le numéro du champ
      var template = $container.attr('data-prototype')
        .replace(/__name__label__/g, 'Image n°' + (index+1))
        .replace(/__name__/g,        index)
      ;

      // On crée un objet jquery qui contient ce template
      var $prototype = $(template);
	  

      // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
      addDeleteLink($prototype);

      // On ajoute le prototype modifié à la fin de la balise <div>
      $container.append($prototype);

      // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
      index++;
    }
	
    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {
      // Création du lien
      var $imgPreview = $('<img src=" " class="card-img img-fluid w-100 mini-pic" alt="Preview" />');
      var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');

      // Ajout du lien
      $prototype.append($imgPreview);
      $prototype.append($deleteLink);
	  $prototype.attr('style','background-color:gray;');

      // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
      $deleteLink.click(function(e) {
        $prototype.remove();

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
      });
    }
  });
