/*
 * Función para obtener el color complementario
 */
function invertColor(hex, bw) {
  if (hex.indexOf('#') === 0) {
      hex = hex.slice(1);
  }
  // convert 3-digit hex to 6-digits.
  if (hex.length === 3) {
      hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
  }
  if (hex.length !== 6) {
      throw new Error('Invalid HEX color.');
  }
  let r = parseInt(hex.slice(0, 2), 16),
      g = parseInt(hex.slice(2, 4), 16),
      b = parseInt(hex.slice(4, 6), 16);
  if (bw) {
      return (r * 0.299 + g * 0.587 + b * 0.114) > 186
          ? '#000000'
          : '#FFFFFF';
  }
  // invert color components
  r = (255 - r).toString(16);
  g = (255 - g).toString(16);
  b = (255 - b).toString(16);
  // pad each with zeros and return
  return "#" + padZero(r) + padZero(g) + padZero(b);
}

/*
 * Función para rellenar con ceros una cadena
 */
function padZero(str, len) {
  len = len || 2;
  let zeros = new Array(len).join('0');
  return (zeros + str).slice(-len);
}

/*
 * Función para renderizar plantillas
 */
function template(id,data){
  var obj = document.getElementById(id).innerHTML;
  var temp = '';

  for (var ind in data){
    temp = '{{'+ind+'}}';
    obj = obj.replace(new RegExp(temp,"g") ,data[ind]);
  }

  return obj;
}

/*
 * Función para crear el slug de un texto
 */
function slugify(str){
  return str.toString().toLowerCase()
    .replace(/ñ/,'n')
    .replace(/\s+/g, '-')           // Replace spaces with -
    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
    .replace(/^-+/, '')             // Trim - from start of text
    .replace(/-+$/, '');            // Trim - from end of text
}

/*
 * Función equivalente al urlencode de php
 */
function urlencode(str){
  if (!str){ return ''; }
  return encodeURIComponent( str ).replace( /\%20/g, '+' ).replace( /!/g, '%21' ).replace( /'/g, '%27' ).replace( /\(/g, '%28' ).replace( /\)/g, '%29' ).replace( /\*/g, '%2A' ).replace( /\~/g, '%7E' );
}

/*
 * Función equivalente al urldecode de php
 */
function urldecode(str){
  if (!str){ return ''; }
  return decodeURIComponent( str.replace( /\+/g, '%20' ).replace( /\%21/g, '!' ).replace( /\%27/g, "'" ).replace( /\%28/g, '(' ).replace( /\%29/g, ')' ).replace( /\%2A/g, '*' ).replace( /\%7E/g, '~' ) );
}

/*
 * Función equivalente al ucfirst de php
 */
function ucfirst(str){
  return str.charAt(0).toUpperCase() + str.slice(1);
}

/*
 * Función para guardar en localstorage
 */
function setLocalStorageData(key,data){
  localStorage.setItem(key, JSON.stringify(data));
}

/*
 * Función para leer de localstorage y callback de error si no existe
 */
function getLocalStorageData(key,callback){
  var chk = localStorage.getItem(key);

  if (chk){
    return JSON.parse(chk);
  }
  else{
    return callback();
  }
}