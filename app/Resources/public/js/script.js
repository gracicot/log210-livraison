$(document).ready(function () {
	$(document).initial();
    verifRestaurateur();
});

function verifRestaurateur()
{
    if($('form[name="log210_livraisonbundle_restaurant"]')) {
        console.log('patate');
        $('form[name="log210_livraisonbundle_restaurant"]').on('submit',function () {
            console.log('patate2');
            var choix = $(".selectRestaurateur option:selected").text();
            if (choix == "") {
                return alert("Aucun restaurateur choisit!");
            }
        });
    }
}
