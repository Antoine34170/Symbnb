$('#add-image').click(function () { 
			
    // Je récupère le numéro des futus champs que je vais créer
    const index = +$('#widgets-counter').val();
    $('#widgets-counter').val(index +1);

    // Je récupère le prototype des entrés
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);
    // J'injecte ce code au sein de la div
    $('#ad_images').append(tmpl);
    // Je gère le boutton 
    
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
    const target = this.dataset.target ;
   
    $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#ad_images div.form-group').length;
    
    $('#widgets-counter').val(count );
}
updateCounter();
handleDeleteButtons();