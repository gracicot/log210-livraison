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
     * @param token A valid API token
     * @param callback A function called once the request is complete. Parameters passed are (commande, error)
	 */
	createCommande: function (commande, token, callback) {
		$.ajax('/api/commandes', {
			contentType: 'application/json',
			data: JSON.stringify(commande),
			error: function(jqHXR, textStatus, errorThrown) {
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

/**
 *L'objet docCookies est pris du site web https://developer.mozilla.org/en-US/docs/Web/API/Document/cookie
 */
var docCookies = {
	getItem: function (sKey) {
		if (!sKey) { return null; }
		return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
	},
	setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
		if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return false; }
		var sExpires = "";
		if (vEnd) {
			switch (vEnd.constructor) {
				case Number:
					sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
					break;
				case String:
					sExpires = "; expires=" + vEnd;
					break;
				case Date:
					sExpires = "; expires=" + vEnd.toUTCString();
					break;
			}
		}
		document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
		return true;
	},
	removeItem: function (sKey, sPath, sDomain) {
		if (!this.hasItem(sKey)) { return false; }
		document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
		return true;
	},
	hasItem: function (sKey) {
		if (!sKey) { return false; }
		return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
	},
	keys: function () {
		var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
		for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) { aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]); }
		return aKeys;
	}
};
