/**
 * jQuery UI Widget 1.8.18
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Widget
 */
(function(a,b){if(a.cleanData){var c=a.cleanData;a.cleanData=function(b){for(var d=0,e;(e=b[d])!=null;d++)try{a(e).triggerHandler("remove")}catch(f){}c(b)}}else{var d=a.fn.remove;a.fn.remove=function(b,c){return this.each(function(){c||(!b||a.filter(b,[this]).length)&&a("*",this).add([this]).each(function(){try{a(this).triggerHandler("remove")}catch(b){}});return d.call(a(this),b,c)})}}a.widget=function(b,c,d){var e=b.split(".")[0],f;b=b.split(".")[1],f=e+"-"+b,d||(d=c,c=a.Widget),a.expr[":"][f]=function(c){return!!a.data(c,b)},a[e]=a[e]||{},a[e][b]=function(a,b){arguments.length&&this._createWidget(a,b)};var g=new c;g.options=a.extend(!0,{},g.options),a[e][b].prototype=a.extend(!0,g,{namespace:e,widgetName:b,widgetEventPrefix:a[e][b].prototype.widgetEventPrefix||b,widgetBaseClass:f},d),a.widget.bridge(b,a[e][b])},a.widget.bridge=function(c,d){a.fn[c]=function(e){var f=typeof e=="string",g=Array.prototype.slice.call(arguments,1),h=this;e=!f&&g.length?a.extend.apply(null,[!0,e].concat(g)):e;if(f&&e.charAt(0)==="_")return h;f?this.each(function(){var d=a.data(this,c),f=d&&a.isFunction(d[e])?d[e].apply(d,g):d;if(f!==d&&f!==b){h=f;return!1}}):this.each(function(){var b=a.data(this,c);b?b.option(e||{})._init():a.data(this,c,new d(e,this))});return h}},a.Widget=function(a,b){arguments.length&&this._createWidget(a,b)},a.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",options:{disabled:!1},_createWidget:function(b,c){a.data(c,this.widgetName,this),this.element=a(c),this.options=a.extend(!0,{},this.options,this._getCreateOptions(),b);var d=this;this.element.bind("remove."+this.widgetName,function(){d.destroy()}),this._create(),this._trigger("create"),this._init()},_getCreateOptions:function(){return a.metadata&&a.metadata.get(this.element[0])[this.widgetName]},_create:function(){},_init:function(){},destroy:function(){this.element.unbind("."+this.widgetName).removeData(this.widgetName),this.widget().unbind("."+this.widgetName).removeAttr("aria-disabled").removeClass(this.widgetBaseClass+"-disabled "+"ui-state-disabled")},widget:function(){return this.element},option:function(c,d){var e=c;if(arguments.length===0)return a.extend({},this.options);if(typeof c=="string"){if(d===b)return this.options[c];e={},e[c]=d}this._setOptions(e);return this},_setOptions:function(b){var c=this;a.each(b,function(a,b){c._setOption(a,b)});return this},_setOption:function(a,b){this.options[a]=b,a==="disabled"&&this.widget()[b?"addClass":"removeClass"](this.widgetBaseClass+"-disabled"+" "+"ui-state-disabled").attr("aria-disabled",b);return this},enable:function(){return this._setOption("disabled",!1)},disable:function(){return this._setOption("disabled",!0)},_trigger:function(b,c,d){var e,f,g=this.options[b];d=d||{},c=a.Event(c),c.type=(b===this.widgetEventPrefix?b:this.widgetEventPrefix+b).toLowerCase(),c.target=this.element[0],f=c.originalEvent;if(f)for(e in f)e in c||(c[e]=f[e]);this.element.trigger(c,d);return!(a.isFunction(g)&&g.call(this.element[0],c,d)===!1||c.isDefaultPrevented())}}})(jQuery);

/**
 * Webfonts loader
 */
WebFontConfig = {
  google: { families: [ 'Chivo:900:latin' ] }
};
(function() {
  var wf = document.createElement('script');
  wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
  wf.type = 'text/javascript';
  wf.async = 'true';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(wf, s);
})();

/**
 * Rexmac Binary Life
 */
(function($) {
  if(!$.Rexmac) {
    $.Rexmac = {};
  }

  $.Rexmac.charMetrics = function(el) {
    var h=0, w=0,
    styles = ['border', 'font-size', 'font-style', 'font-weight', 'font-family', 'line-height', 'text-transform', 'letter-spacing', 'margin', 'padding'],
    d = $('<pre style="display:none;padding:0;margin:0">0</pre>').appendTo(document.body);
    $(styles).each(function() {
      var s = this.toString();
      //console.log(s, el.css(s));
      d.css(s, el.css(s));
    });
    //h = d.outerHeight();
    //w = d.outerWidth();
    h = d.height();
    w = d.width();
    d.remove();
    return { height: h, width: w };
  };

  $.Rexmac.BinaryLife = function(el, s) {
    this.init(el, s);
  };
  $.Rexmac.BinaryLife.prototype = {
    el: null,
    charMetrics: null,
    init: function(el, s) {
      var self = this, w;
      //console.log('el: ' + el.outerWidth() + ' x ' + el.outerHeight());
      this.el = el;
      this.charMetrics = $.Rexmac.charMetrics(this.el);
      //console.log('charMetrics: ' + this.charMetrics.width + 'x' + this.charMetrics.height);
      this.numberOfCharsAcross = Math.floor(this.el.outerWidth() / ( this.charMetrics.width * 2 ));
      this.numberOfCharsDown = Math.floor(this.el.outerHeight() / this.charMetrics.height);
      //console.log('chars', this.numberOfCharsAcross, this.numberOfCharsDown);
      w = 2 * this.charMetrics.width * this.numberOfCharsAcross - this.charMetrics.width;
      //console.log('so width should be?', w);
      //console.log('and left pos should be?', Math.floor((el.outerWidth() - w) / 2));
      this.el.css({
        'width': w,
        'left': Math.floor((el.outerWidth() - w) / 2)
      });

      if(s && s.length) {
        //console.log('s', s);
        while(s.length < (this.numberOfCharsAcross * this.numberOfCharsDown)) {
          s += s;
        }

        this.s = s.substr(0, this.numberOfCharsAcross * this.numberOfCharsDown);
        //console.log('final s', s);
      }

      $(window).resize(function() {
        //console.log('window resized', this);
        if(this.resizing) clearTimeout(this.resizing);
        this.resizing = setTimeout(function() {
          $(this).trigger('resizeEnd');
        }, 500);
      });

      $(window).on('resizeEnd', function() {
        var s = self.intervalId;
        //console.log('resize end');
        if(s) self.stop();

        self.el.css({
          width: $(window).width(),
          height: $(window).height()
        });
        self.init(self.el, self.s);

        if(s) self.start();
      });

      this.reset();
    },
    reset: function() {
      var c = 0, r = 0;
      this.cells = [];
      this.nextCells = [];
      this.prevCells = [];
      for(; r < this.numberOfCharsDown; ++r) {
        if(this.s && this.s.length) {
          this.cells[r] = this.s.substr(r * this.numberOfCharsAcross, this.numberOfCharsAcross);
        } else {
          this.cells[r] = [];
        }
        this.nextCells[r] = [];
        this.prevCells[r] = [];
        for(c = 0; c < this.numberOfCharsAcross; ++c) {
          if(!(this.s && this.s.length)) {
            this.cells[r][c] = 2*Math.random() | 0;
          }
          this.nextCells[r][c] = null;
          this.prevCells[r][c] = null;
        }
      }
      this.el.trigger('iterated', null, null);
    },
    start: function() {
      var me = this;
      this.intervalId = window.setInterval(function() { me.step.apply(me); }, 1000);
    },
    step: function() {
      var n = 0, col = 0, row = 0,
      prevCol = 0, prevRow = 0, nextCol = 0, nextRow = 0,
      nRows = this.cells.length,
      nCols = this.cells[0].length,
      changed = false,
      repeat = true;
      this.nextCells = $.extend(true, [], this.cells);
      for(; row < nRows; ++row) {
        prevRow = (row === 0 ? nRows : row) - 1;
        nextRow = (row === nRows - 1 ? 0 : row + 1);
        for(col = 0; col < nCols; ++col) {
          prevCol = (col === 0 ? nCols : col) - 1;
          nextCol = (col === nCols - 1 ? 0 : col + 1);
          // Count living neighbors
          n = this.cells[row][prevCol] +
            this.cells[prevRow][prevCol] +
            this.cells[prevRow][col] +
            this.cells[prevRow][nextCol] +
            this.cells[row][nextCol] +
            this.cells[nextRow][nextCol] +
            this.cells[nextRow][col] +
            this.cells[nextRow][prevCol];
          // Apply rules of life
          if(this.cells[row][col] && (n < 2 || n > 3)) { this.nextCells[row][col] = 0; }
          else if(!this.cells[row][col] && n === 3) { this.nextCells[row][col] = 1; }
          changed = changed || this.cells[row][col] !== this.nextCells[row][col];
          repeat = repeat && this.prevCells[row][col] === this.nextCells[row][col];
          if(this.prevCells[row][col] !== this.cells[row][col]) {
            this.prevCells[row][col] = this.cells[row][col];
          }
        }
      }
      if(!changed || repeat) {
        if(this.intervalId) {
          this.stop();
          this.reset();
          this.start();
        } else {
          this.reset();
        }
      } else {
        for(row = 0; row < nRows; ++row) {
          for(col = 0; col < nCols; ++col) {
            if(this.cells[row][col] !== this.nextCells[row][col]) {
              this.cells[row][col] = this.nextCells[row][col];
            }
          }
        }
        this.el.trigger('iterated', null, null);
      }
    },
    stop: function() {
      window.clearInterval(this.intervalId);
      this.intervalId = null;
    },
    toggle: function() {
      if(this.intervalId) this.stop();
      else this.start();
    },
    toString: function() {
      var row = 0, col = 0, s = '', numRows = this.cells.length, numCols = this.cells[0].length;
      for(; row < numRows; ++row) {
        for(col = 0; col < numCols; ++col) {
          s += this.cells[row][col] + (col+1 === numCols ? '' : ' ');
        }
        s += "\n";
      }
      return s;
    }
  };

  $.widget('Rexmac.binaryLife', {
    options: {
      backgroundColor: '#1d1d1d',
      color: '#333',
      text: null
    },
    _create: function() {
      var self = this,
          o = self.options,
          el = self.element;
      this.binaryLife = new $.Rexmac.BinaryLife(el, o.text);
      this.printBoard();
      el.bind('iterated', function(e){ self.printBoard(); });
    },
    _setOption: function(key, value) {
      var el = this.element;
      switch(key) {
        case 'backgroundColor':
        case 'color':
          el.css(key, value);
          break;
      }
      $.Widget.prototype._setOption.apply(this, arguments);
    },
    destroy: function() {
      this.element.text('');
      delete this.binaryLife;
      $.Widget.ptototype.destroy.call(this);
    },
    printBoard: function() {
      this.element.text(this.binaryLife.toString());
    },
    toggle: function() {
      this.binaryLife.toggle();
    }
  });
})(jQuery);

(function(jQuery, window) {
  var RM = window.RM = {};

  RM.onAjaxError = function(jqXhr) {
    var context = $(this),
        data    = $.parseJSON(jqXhr.responseText),
        errors  = [];

    if(!data) return;

    // If $.ajax({...}) did not set context, then use main content div
    if(typeof context[0].xhr === 'function') {
      context = $('div.main div.content');
    }

    if(data.message) {
      errors.push(a.message);
    } else if(typeof data.messages === 'object') {
      $.each(data.messages.error, function(k, v) {
        if(typeof v === 'object') {
          $.each(v, function(fieldName, error) {
            var ul = '';
            $.each(error, function(type, message) {
              ul += '<li>' + message + '</li>';
            });
            errors.push(fieldName + '<ul>' + ul + '</ul>');
          });
        } else if(typeof v === 'string') {
          errors.push(v);
        }
      });
    } else {
      errors.push('Unknown error');
    }

    Notifier.error(errors.join(''));
  };

  RM.zeroPad = function(n) {
    return(n < 10 ? '0' : '') + n;
  };
}(jQuery, window));

$(function() {
  $.ajaxSetup({
    async: false,
    dataType: 'json',
    error: RM.onAjaxError,
    type: 'POST'
  });

  $.validator.setDefaults({
    errorPlacement: function(error, element) {
      element.parents('.controls').append(error)
    },
    errorElement: 'span',
    errorClass: 'help-inline',
    highlight: function(element) {
      $(element).parents('.control-group').addClass('error');
    },
    unhighlight: function(element) {
      $(element).parents('.control-group').removeClass('error');
    }
  });


  /**
   * Get off my lawn, you damned self-referencing anchors!
   */
  //$('a[href="#"]').attr('href', window.location.href);


  /**
   * Dude, where are we?
   */
  $('.navbar-inner').find('ul.nav').first().children().each(function() {
    var path = window.location.pathname;
    if(0 === window.location.host.toUpperCase().indexOf('BLOG.')) {
      path = '/blog';
    }
    if(path === '/') {
      path = '/home';
    }
    if(0 === path.replace(/^\//, '').toUpperCase().indexOf($(this).text().replace(/^ /, '').toUpperCase())) {
      $(this).addClass('active');
    }
  });
  //if($('.navbar-inner').find('ul.nav').first().children('li.active').length === 0) {
  //  $('ul.nav > li').first().addClass('active');
  //}


  /**
   * Who, me?
   */
  $('.contactme').attr('href', 'mailto:' + ('erk' + String.fromCharCode(Math.pow(2, 7) / 2) + 'erkznp' + String.fromCharCode(Math.pow(7, 2) - 3) + 'pbz').replace(/[a-z]/g, function(c) {
    return String.fromCharCode(122 >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
  }));


  /**
   * Lights off!
   */
  if($.cookie('lightsoff')) {
    $('html').addClass('lightsoff');
  }
  $('#light-switch').click(function(e) {
    var $html = $('html');
    e.preventDefault();
    if($html.hasClass('lightsoff')) {
      $html.removeClass('lightsoff');
      $.removeCookie('lightsoff', {domain: 'rexmac.com', path: '/'});
    } else {
      $html.addClass('lightsoff')
      $.cookie('lightsoff', false, {expires:30, domain: 'rexmac.com', path:'/'});
    }
  });


  /**
   * Tips of tools?
   */
  $('a[rel=tooltip]').tooltip();


  /**
   * Binary Life!
   */
  //console.log('window: ' + $(window).width() + ' x ' + $(window).height());
  //console.log('document: ' + $(document).width() + ' x ' + $(document).height());
  //console.log('body: ' + $(document.body).width() + ' x ' + $(document.body).height());
  //var b = $('<pre id="binary-life"/>').prepend(document.body), bgText = '';
  var b = $('#binary-life'), bgText = '';
  b.css({
    width: $(window).width(),
    height: $(window).height()
  });
  if(Modernizr.mq('(min-width: 1070px)')) {
    b.binaryLife();
    $('#glider-tag').click(function() {
      b.binaryLife('toggle');
    });
    b.binaryLife('toggle');
  } else {
    //console.log('not wide enough for life');
    bgText = '01100111011001010111010000100000011000010010000001101100011010010110011001100101';
    b.binaryLife({text: bgText});
  }

  /**
   * Shhh! It'a a secret!
   */
  var kkeys = [], konami = '38,38,40,40,37,39,37,39,66,65';
  $(document).keydown(function(e) {
    kkeys.push(e.which);
    if(kkeys.toString().indexOf(konami) >= 0) {
      $(document).off('keydown', arguments.callee);
      $.getScript('/js/leppy.js', function() {
        leppyAdd();
        $(document).keydown(leppyAdd);
      });
      //$.getScript('http://rexmac.com/js/leppy.js')
      //.done(function(){console.log('done')})
      //.fail(function(jqXhr, settings, exception){console.log('fail', exception)});
    }
  });

  fayer.on('page-about-me', function() {
    $('#skills-treemap').treemap({
      color: ['#93c', '#f00'],
      legend: {
        labels: {
          low: 'Weak',
          high: 'Strong'
        },
        position: 'top'
      }
    });
  });
});
