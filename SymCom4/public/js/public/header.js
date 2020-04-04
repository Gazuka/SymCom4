//Le petit header est affiché par défaut dans la page

$(window).scroll(function (event) 
{
    var scroll = $(window).scrollTop();
    if(scroll != 0)
    {
        //Si on scroll la page, on met le mini header
        $( "#header_body_MD" ).addClass("sc4-header-mini");
        $( "#header_body_MD" ).removeClass("sc4-header-small");
        //On masque également une partie si on est sur telephone portable
        $( "#header_contact_XS" ).addClass("sc4-header-invisible");
    }
    else
    {
        //Si on reviens en haut de l'écran, on remet le petit header
        $( "#header_body_MD" ).addClass("sc4-header-small");
        $( "#header_body_MD" ).removeClass("sc4-header-mini");
        //Et on réaffiche le header complet sur telephone portable
        $( "#header_contact_XS" ).removeClass("sc4-header-invisible");
    }
});