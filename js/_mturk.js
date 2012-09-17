$(function() {

  // Empty input elements appear 1 piel higher than they should when using Bootstrap 2
  // May be limited to form-horizontal
  //$('input:text').val('0');
  //window.setTimeout(function() { $('input:text').val(''); }, 2050);

  // Select all text in textarea when focused
  $('#qualtricsHeaderCode').focus(function() {
    var $this = $(this);
    $this.select().mouseup(function(e) {
      e.off('mouseup');
      e.preventDefault();
    });
  }).keyup(function(e) {
    if(e.which === 9) {
      this.select();
    }
  });

  $('#reward').change(function() {
    $(this).val(sprintf('%0.2f', parseFloat($(this).val(), 10)));
  });

  $('#country').fcbkcomplete({
    json_url: 'iso.json',
    addontab: true,
    height: 5,
    cache: true,
    //newel: true
    firstselected: true
  });

  $('.controls').find('input, select, textarea').tooltip({
    placement: 'right',
    trigger: 'focus'
  });

  $('#mturkQualtricsHelperForm').validate({
    rules: {
      /*accessKeyId: 'required',
      accessKey: 'required',
      title: 'required',
      surveyUrl: {
        required: true,
        url: true
      },
      description: 'required',
      frameHeight: {
        required: true,
        number: true
      },
      max: {
        required: true,
        number: true
      },
      reward: {
        required: true,
        number: true
      },
      'duration[time]': {
        required: true,
        number: true
      },
      'autoApprovalDelay[time]': {
        required: true,
        number: true
      },
      'lifetime[time]': {
        required: true,
        number: true
      }*/
    },
    /*errorPlacement: function(error, element) {
      element.parents('.controls').append(error);
    },
    errorElement: 'span',
    errorClass: 'help-inline',
    highlight: function(element, errorClass, validClass) {
      $(element).parents('.control-group').addClass('error');
    },
    unhighlight: function(element, errorClass, validClass) {
      $(element).parents('.control-group').removeClass('error');
    },*/
    submitHandler: function(form) {
      console.log('form submit');
      var d = new Date(),
          timestamp, signature;

      timestamp = sprintf('%4d-%02d-%02dT%02d:%02d:%02d+00:00',
        d.getUTCFullYear(),
        (d.getUTCMonth() + 1),
        d.getUTCDate(),
        d.getUTCHours(),
        d.getUTCMinutes(),
        d.getUTCSeconds()
      );

      signature = CryptoJS.HmacSHA1(('AWSMechanicalTurkRequesterCreateHIT' + timestamp), $('#accessKey').val()).toString(CryptoJS.enc.Base64);
      console.log('signature', signature);

      $('#signature').val(signature);
      $('#timestamp').val(timestamp);
      $('#accessKey').val('');

      $.ajax({
        beforeSend: function() {
          $('#step2').find('.alert-success, .alert-error').remove();
          $(document.body).showLoading();
        },
        cache: false,
        complete: function() {
          $('html, body, #step2').animate({scrollTop: 0}, 'slow', function() {
            $(document.body).hideLoading();
          });
        },
        data: $(form).serialize(),
        success: function(data) {
          var msg = data.messages.success[0];
          console.log('msg', msg);
          //msg += sprintf(' You can <a href="%s" target="_blank" title="Manage HIT (Requester)">manage<span class="ui-icon ui-icon-newwin"></span></a> and <a href="%s" target="_blank" title="Preview HIT as a Worker">preview<span class="ui-icon ui-icon-newwin"></span></a> your HIT on Amazon Mechanical Turk.',
          //  data.manageUrl,
          //  data.previewUrl
          //);
          //$('#step2').showMessage({
          //  thisMessage: data.messages.success,
          //  className: 'success'
          //});
          //$('#step2').append('<div class="alert alert-success">' + data.messages.success + '</div>');
          Notifier.success(msg);
        },
        //url: 'http://rexmac.com/mturk/createExternalHit'
        url: 'http://rexmac.com/mturk/createExternalHit'
        //url: 'http://rexmac.com/mturk/createexternalhit'
      });
    }
  });

});
