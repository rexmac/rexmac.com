// Inspired by http://www.cornify.com/
var leppyCount = 0;
leppyAdd = function() {
  var heightRandom = Math.random() * 0.75,
  windowHeight = $(window).height(),
  windowWidth = $(window).width(),
  div = $('<div/>')
    .css({
      position: 'fixed',
      zIndex: 10000,
      outline: 0
    })
    .click(leppyAdd)
    .hover(function() {
      var size = 1 + Math.floor(Math.random() * 10) / 100,
          angle = Math.floor(Math.random() * 20 - 10),
          result = 'rotate(' + angle + 'deg) scale(' + size + ',' + size + ')';
      $(this).css({
        '-webkit-transform': result,
        '-moz-transform': result,
        '-o-transform': result,
        '-ms-transform': result,
        'transform': result
      });
    }, function() {
      var size = 0.9 + Math.floor(Math.random() * 10) / 100,
          angle = Math.floor(Math.random() * 6 - 3),
          result = 'rotate(' + angle + 'deg) scale(' + size + ',' + size + ')';
      $(this).css({
        '-webkit-transform': result,
        '-moz-transform': result,
        '-o-transform': result,
        '-ms-transform': result,
        'transform': result
      });
    })
    .append($('<img/>').attr('src', '/img/leprechaun_' + (Math.floor(Math.random() * 9) + 1) + '.gif'));

  if(++leppyCount === 15) {
    div.css({
      top: Math.max(0, Math.round((windowHeight - 530) / 2)) + 'px',
      left: Math.round((windowWidth - 530) / 2) + 'px',
      zIndex: 100001
    });
  } else {
    div.css({
      top: Math.round(windowHeight * heightRandom) + 'px',
      left: Math.round(Math.random() * 90) + '%'
    });
  }

  $(document.body).append(div);

  if(leppyCount === 1) {
    var cssExisting = document.getElementById('__leppy_css');
    if(!cssExisting) {
      var head = document.getElementsByTagName('head')[0],
          css = document.createElement('link');
      css.id = '__leppy_css';
      css.type = 'text/css';
      css.rel = 'stylesheet';
      css.href = '/js/leppy.min.css';
      css.media = 'screen';
      head.appendChild(css);
    }
    leppyReplace();
  }
};

leppyReplace = function() {
  var hc = 6, hs, h, k,
  words = ['Happy','Evil','Delicious','Fun','Magical','Incredible','Yummy','Charming','Amazing','Wonderful'];
  while(hc >= 1) {
    hs = document.getElementsByTagName('h' + hc);
    for(k = 0; k < hs.length; ++k) {
      h = hs[k];
      if($(h).children().length) {
        h = $(h).children().last();
      } else {
        h = $(h);
      }
      h.html(words[Math.floor(Math.random() * words.length)] + ' ' + h.html());
    }
    --hc;
  }
};
