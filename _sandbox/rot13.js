
function rot(s, n) {
  return s.replace(/[a-zA-Z]/g, function(c) {
    //return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
    return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + (n || 13)) ? c : c - 26);
  });
}

/*
console.log(rot('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
console.log(rot(rot('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')));
console.log('');
console.log(rot('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 5));
console.log('');

function xor(s, n) {
  return s.replace(/[a-z]/g, function(c) {
    //return String.fromCharCode(((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) ^ 6) ? c : c - 26));
    return String.fromCharCode( (c <= "Z" ? 90 : 122) >= (c.charCodeAt(0) ^ n) ?
        (c <= "Z" ? 65 : 97) <= (c.charCodeAt(0) ^ n) ?
            c.charCodeAt(0) ^ n : (c.charCodeAt(0) ^ n) + 26
            : (c.charCodeAt(0) ^ n) - 26);
  });
}

var a = 'abcdefghijklmnopqrstuvwxyz';

console.log('');
console.log(a);
console.log(xor(a, 6));
console.log(xor(xor(a, 6), 6));
*/



function rot13(s) {
  return s.replace(/[a-z]/g, function(c) {
    return String.fromCharCode(122 >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
  });
}

var e = 'erk' + String.fromCharCode(Math.pow(2, 7) / 2) + 'erkznp' + String.fromCharCode(Math.pow(7, 2) - 3) + 'pbz';
//console.log(rot13('rex@rexmac.com'));
//console.log(rot13(rot13('rex@rexmac.com')));
console.log(rot13(e));
