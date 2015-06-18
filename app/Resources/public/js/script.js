$(document).ready(function () {
	$(document).initial();
    verifRestaurateur();
    verifRestaurant();
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
function verifRestaurant()
{
    if($('form[name="log210_livraisonbundle_restaurateur"]')) {
        $('form[name="log210_livraisonbundle_restaurateur"]').on('submit',function () {
            var choix = $(".selectRestaurant option:selected").text();
            if (choix == "") {
                return alert("Aucun restaurant choisit!");
            }
        });
    }
}