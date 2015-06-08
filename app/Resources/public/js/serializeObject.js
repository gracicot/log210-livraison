// Ce code à été emprunté à SpYk3HH
// Le code original peut être retrouvé a cette adresse: http://stackoverflow.com/questions/17488660/difference-between-serialize-and-serializeobject-jquery

$.fn.serializeObject = function()
{
   var o = {};
   var a = this.serializeArray();
   $.each(a, function() {
       if (o[this.name]) {
           if (!o[this.name].push) {
               o[this.name] = [o[this.name]];
           }
           o[this.name].push(this.value || '');
       } else {
           o[this.name] = this.value || '';
       }
   });
   return o;
};

// Fin d'emprunt de code
