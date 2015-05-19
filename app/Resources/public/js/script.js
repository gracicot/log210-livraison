$(document).ready(function () {
	$(document).initial();
});

function verifRestaurateur()
{
	var choix=$( ".selectRestaurateur option:selected" ).text();
	if(choix=="")
	{
		return alert("Aucun restaurateur choisit!");	
	}	
}