$(document).ready(function () {
	$(document).initial();
});

var apiClient = {
	/**
	 * This function creates an api token from a username and password combination
	 * @param username A username to use for authentication
	 * @param password A password to use for authentication
	 * @param callback A function called once the request is complete. Parameters passed are (token, error)
	 */
	createToken: function(username, password, callback) {
		$.ajax('/api/tokens', {
			contentType: 'application/json',
			data: JSON.stringify({
				username: username,
				password: password
			}),
			error: function (jqXHR, textStatus, errorThrown) {
				callback(null, errorThrown);
			},
			method: 'POST',
			success: function(data, textStatus, jqXHR) {
				callback(data);
			}
		});
	},
	/**
	 * This function refreshes an api token
	 * @param refresh_token A valid refresh token
	 * @param callback A function to be called once the request is complete. Parameters passed are (token, error)
	 */
	refreshToken: function(refresh_token, callback) {
		$.ajax('/api/tokens', {
			contentType: 'application/json',
			data: JSON.stringify({
				refresh_token: refresh_token
			}),
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(jqXHR);
				callback(null, errorThrown);
			},
			method: "POST",
			success: function(data, textStatus, jqXHR) {
				console.log(jqXHR);
				callback(data);
			}
		});
	},
	/**
	 * Creates a commande with the api
	 * @param commande A commande object:
	 * {
	 *  adresse: "An adress",
	 *  commande_plats: [
	 *   {
	 *    plat_id: 0, // The plat id
	 *    quantity: 0 // The quantity
	 *   }
	 *  ],
	 *  date_heure: "A date-time string representation (ex: '1970-01-01 23:59')",
	 *  restaurant_id: 0 // The id of the restaurant
	 * }
     * @param callback A function called once the request is complete. Parameters passed are (commande, error)
	 */
	createCommande: function (commande, callback) {
		$.ajax('/api/commandes', {
			contentType: 'application/json',
			data: JSON.stringify(commande),
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(jqXHR);
				callback(null, errorThrown);
			},
			success: function(data, textStatus, jqXHR) {
				console.log(jqXHR);
				callback(data);
			}
		});
	}
};
