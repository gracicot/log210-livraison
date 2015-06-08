$(document).ready(function () {
	$(document).initial();
    verifRestaurateur();
});

function verifRestaurateur()
{
    if($('form[name="log210_livraisonbundle_restaurant"]')) {
        $('form[name="log210_livraisonbundle_restaurant"]').on('submit',function () {
            var choix = $(".selectRestaurateur option:selected").text();
            if (choix == "") {
                return alert("Aucun restaurateur choisit!");
            }
        });
    }
}
