---
layout: page
title: 'MTurk-Qualtrics-Helper'
description: 'The MTurk-Qualtrics Helper aims to be a simple web app to help create Amazon Mechanical Turk HITs for Qualtrics surveys.'
no_sidebar: true
credits: 'Browser icons courtesy of [Paul Irish](http://paulirish.com/2010/high-res-browser-icons/).'
js: ["/js/libs/hmac-sha1-b64.min.js", "/js/mturk.js"]
---

<div class="alert alert-warn">
  <p>MTurk-Qualtrics Helper is currently in probation (read: beta stage of development). If you think you've caught it misbehaving, please <a href="https://github.com/rexmac/MTurk-Qualtrics-Helper/issues/new" title="File a report on github">tell me</a> about it so that I can take appropriate disciplinary action. Thank you!
</div>

The MTurk-Qualtrics Helper aims to be a simple web app to help create [Amazon Mechanical Turk](https://www.mturk.com/mturk/welcome) [HIT](https://www.mturk.com/mturk/help?helpPage=overview#what_is_hit)s for [Qualtrics](http://www.qualtrics.com/) surveys. Just create your survey at Qualtrics.com, and then follow the steps below to publish it to Amazon Mechanical Turk.

Why? There does not appear to be a method of creating a HIT on Amazon Mechanical Turk using an external URL that also ensures that a Worker has completed the survey before clicking the "Submit" button. This "app" aims to fix that by meeting the following criteria:

* A survey created and hosted at Qualtrics.com is displayed through the AMT Worker interface.
* The "Submit HIT" button of the AMT Worker interface (that is visible to the AMT Worker throughout the survey) remains disabled until the survey has been completed.

The MTurk-Qualtrics Helper has been tested in the following browsers:
<a href="http://www.google.com/chrome/" title="Google Chrome"><i class="icon-browser icon-browser-chrome"></i></a> 11+
<a href="http://www.mozilla.com/firefox/" title="Mozilla Firefox"><i class="icon-browser icon-browser-firefox"></i></a> 4+
<a href="http://windows.microsoft.com/en-US/internet-explorer" title="Microsoft Internet Explorer"><i class="icon-browser icon-browser-ie"></i></a> 8+
<a href="http://www.apple.com/safari/" title="Apple Safari"><i class="icon-browser icon-browser-safari"></i></a> 5+
<a href="http://www.opera.com/" title="Opera Software - Opera Web Browser"><i class="icon-browser icon-browser-opera"></i></a> 11+

If you...

* ...have successfully used the MTurk-Qualtrics-Helper in a browser version not listed above...
* ...have any issues using the MTurk-Qualtrics-Helper...
* ...have any suggestions or feature requests...

...please [let me know](https://github.com/rexmac/MTurk-Qualtrics-Helper/issues/new "report it on github").

To learn more about this app and why it was created, please read [this blog post](http://blog.rexmac.com/mturk-qualtrics-helper).

<div class="accordion" id="accordion">
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#step1">Step 1: Copy &amp; Paste Javascript Code into Survey Header</a>
    </div>
    <div id="step1" class="accordion-body collapse">
      <div class="accordion-inner" markdown="1">

To prevent MTurk Workers from submitting the HIT before the survey has been completed, you must add some [Javascript](http://en.wikipedia.org/wiki/JavaScript) code to the header of your Qualtrics.com survey.

To add code to the header of your Qualtrics survey, navigate to your survey's "Edit Survey" page and click the "Look &amp; Feel" button on the far left of the page's menu.

<p><img class="thumbnail centered" src="/img/mturk-qualtrics-helper/qualtrics_lookandfeel.png" title="Qualtrics.com - Edit Survey - Look &amp; Feel button" width="378" height="128" /></p>

In the Look & Feel window, click on the "Advanced" tab, and then paste the copied code into the "Header" field. NOTE: Although it may not appear to be, the code is actually one very long line. Don't be surprised if it appears as such when you paste it into the field. Also, if you choose to click the nearby "Edit" link to open the larger editing window, please make sure to click the "Source" button before pasting the code.

<p><img class="thumbnail centered" src="/img/mturk-qualtrics-helper/qualtrics_advanced_header.png" title="Qualtrics.com - Edit Survey - Look &amp; Feel - Advanced - Header" width="400" height="150" /></p>

Save your changes and launch the survey using the "Launch Survey" button. If your survey was already launched, you must launch it again for the changes to take effect.

Copy and paste the following Javascript code into the header of your Qualtrics.com survey:

<textarea id="qualtricsHeaderCode" class="full-width resize-v" rows="5" readonly="readonly">&lt;input type=&quot;hidden&quot; id=&quot;AssignmentId&quot; name=&quot;ED~AssignmentId&quot; value=&quot;${e://Field/AssignmentId}&quot;&gt;&lt;input type=&quot;hidden&quot; id=&quot;HitId&quot; name=&quot;ED~HitId&quot; value=&quot;${e://Field/HitId}&quot;&gt;&lt;input type=&quot;hidden&quot; id=&quot;WorkerId&quot; name=&quot;ED~WorkerId&quot; value=&quot;${e://Field/WorkerId}&quot;&gt;&lt;input type=&quot;hidden&quot; id=&quot;Test&quot; name=&quot;ED~Test&quot; value=&quot;${e://Field/Test}&quot;&gt;&lt;style type=&quot;text/css&quot;&gt;.rmOverlay{width:100%;height:100%;position:fixed;background:#333;top:0;left:0;z-index:9999;-ms-filter:&quot;progid:DXImageTransform.Microsoft.Alpha(Opacity=50)&quot;;filter: alpha(opacity=50);-moz-opacity:0.5;-khtml-opacity:0.5;opacity:0.5;}&lt;/style&gt;&lt;script&gt;Event.observe(window,'load',function(){var url=window.location.toString(),qs=url.match(/\?(.+)$/),qsa=qs[1].split('&amp;')arams={},tmp={};for(var i=0,n=qsa.length;i&lt;n;++i){tmp=qsa[i].split('=');params[tmp[0]]=unescape(tmp[1]);}$('AssignmentId').value=params.assignmentId||'';$('HitId').value=params.hitId||'';$('WorkerId').value=params.workerId||'';$('Test').value=params.test||'';if(/mturk\/preview\?/.test(document.referrer)){$(document.body).insert({top:new Element('div',{'class':'rmOverlay'})});}});Qualtrics.SurveyEngine.OnEndOfSurvey=function(){if($('AssignmentId').value.length&amp;&amp;$('HitId').value.length&amp;&amp;$('WorkerId').value.length){var url='https://'+($('Test').value.length?'workersandbox':'www')+'.mturk.com/mturk/externalSubmit'+'?assignmentId='+$('AssignmentId').value+'&amp;hitId='+$('HitId').value+'&amp;workerId='+$('WorkerId').value+'&amp;test='+$('Test').value;var iframe=document.createElement('iframe');iframe.setAttribute('src',url);$(iframe).setStyle({display:'none'});document.body.appendChild(iframe);}}&lt;/script&gt;</textarea>

      </div>
    </div>
  </div>

  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#step2">Step 2: Create HIT on Amazon Mechanical Turk </a>
    </div>
    <div id="step2" class="accordion-body collapse">
      <div class="accordion-inner">
        <form id="mturkQualtricsHelperForm" class="form-horizontal">
          <input type="hidden" name="signature" id="signature" />
          <input type="hidden" name="timestamp" id="timestamp" />
          <fieldset>
            <legend>Amazon Web Services Credentials</legend>
            <div class="alert alert-info">
              Please visit <a class="withIcon" href="http://aws.amazon.com/security-credentials" target="_blank" title="Amazon Web Services Security Credentials">http://aws.amazon.com/security-credentials<span class="ui-icon ui-icon-newwin" title="This link will open in a new window/tab"></span></a> to view your access keys.
              NOTE: Your AWS secret key is never sent to my server or otherwise transmitted from this page. It is only used by your browser to digitally sign the AWS request.
            </div>

            <div class="control-group">
              <label class="control-label" for="accessKeyId">Access Key:</label>
              <div class="controls">
                <input type="text" class="input-xxlarge" name="accessKeyId" id="accessKeyId" />
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="accessKey">Secret Key:</label>
              <div class="controls">
                <input type="text" class="input-xxlarge" name="accessKey" id="accessKey" title="Your secret access key will never be stored or transmitted. It is only used by your browser to generate your AWS signature." />
              </div>
            </div>

          </fieldset>

          <fieldset>
            <legend>HIT Details</legend>

            <div class="control-group">
              <label class="control-label" for="title">Title:</label>
              <div class="controls">
                <input type="text" class="input-xxlarge" name="title" id="title" placeholer="The name of your HIT as it will be seen in MTurk" title="The HIT's title. Will be displayed to public through the MTurk Worker interface." />
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="surveyUrl">Surey URL:</label>
              <div class="controls">
                <input type="text" class="input-xxlarge" name="surveyUrl" id="surveyUrl" placeholder="The URL of your Qualtrics survey" title="The public URL for your Qualtrics.com survey." />
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="description">Description:</label>
              <div class="controls">
                <textarea class="input-xxlarge" name="description" id="description" rows="2" cols="80" placeholder="Describe your survey..." title="Description of the HIT. Will be displayed to Workers through the MTurk Worker interface."></textarea>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="keywords">Keywords:<br />(Optional)</label>
              <div class="controls">
                <textarea class="input-xxlarge" name="keywords" id="keywords" placeholder="keyword-1, keyword-2, ... , keyword-n" title="A comma-delimited list of keywords describing the HIT. Although this field is optional, it is recommended as it can help Workers find your HIT when searching through the MTurk interface."></textarea>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="frameHeight">Height of survey frame:</label>
              <div class="controls">
                <input type="text" class="span1" name="frameHeight" id="frameHeight" placeholder="800" title="When viewed from within MTurk, your survey will be displayed within a frame of the Worker interface. The default height of this frame is 400 pixels which is rather small. It is really only adequate for surveys that display one question at a time. Larger values allow the user to see more of the survey page thereby lessening the amount of vertical scrolling required. The value required by individual surveys will vary depending on their contents. Please utilize the &ldquo;Test&rdquo; feature below to experiment with different height values." />
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="max">Max # of participants:</label>
              <div class="controls">
                <input type="text" class="span1" name="max" id="max" placeholder="1000" title="Maximum number of HIT completions. After this many HITs/surveys have been submitted, the HIT will be removed from the listing of available HITs on Mechanica  Turk." />
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="reward">Reward amount:</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on">$</span><input type="text" class="span1" name="reward" id="reward" placeholder="0.50" title="Amount to be paid to each Worker (in USD) for completion of the HIt/survey." />
                </div>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="duration">Duration:</label>
              <div class="controls">
                <input type="text" class="span1" name="duration[time]" id="duration-time" placeholder="1" title="Amount of time a Worker has to complete the HIT/survey after acceting it. If a Worker does not complete the assignment within the specified duration, the assignment is considered abandoned. If the HIT is still active (that is, its lifetime has not elapsed), the assignment becomes available for other users to find and accept." />
                <select class="auto-width" name="duration[units]" id="duration-units">
                  <option value="1" label="Minutes">Minutes</option>
                  <option value="2" label="Hours" selected="selected">Hours</option>
                  <option value="3" label="Days">Days</option>
                  <option value="4" label="Weeks">Weeks</option>
                  <option value="5" label="Months">Months</option>
                </select>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="autoApprovalDelay">Auto-approval delay:</label>
              <div class="controls">
                <input type="text" class="span1" name="autoApprovalDelay[time]" id="autoApprovalDelay-time" placeholder="1" title="The amount of time after a completed HIT has been submitted, after which the HIT is automatically approved for payment unless you explicitly reject it." />
                <select class="auto-width" name="autoApprovalDelay[units]" id="autoApprovalDelay-units">
                  <option value="1" label="Minutes">Minutes</option>
                  <option value="2" label="Hours" selected="selected">Hours</option>
                  <option value="3" label="Days">Days</option>
                  <option value="4" label="Weeks">Weeks</option>
                  <option value="5" label="Months">Months</option>
                </select>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="lifetime">Lifetime:</label>
              <div class="controls">
                <input type="text" class="span1" name="lifetime[time]" id="lifetime-time" placeholder="30" title="Amount of time after which the HIT is no longer available for Workers to accept. After the lifetime of the HIT elapses, the HIT no longer appears in HIT searches, even if not all of the assignments for the HIT have been accepted." />
                <select class="auto-width" name="lifetime[units]" id="lifetime-units">
                  <option value="1" label="Minutes">Minutes</option>
                  <option value="2" label="Hours">Hours</option>
                  <option value="3" label="Days" selected="selected">Days</option>
                  <option value="4" label="Weeks">Weeks</option>
                  <option value="5" label="Months">Months</option>
                </select>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="country">Country:<br />(Optional)</label>
              <div class="controls">
                <select class="auto-width" name="countryStatus" id="countryStatus">
                  <option value="include" label="Include">Include</option>
                  <option value="exclude" label="Exclude">Exclude</option>
                </select>
                <select multiple="multiple" class="auto-width" name="country[]" id="country" title="The list of countries in which Workers must reside (or not reside) in order to access your HIt/survey."></select>
                <p class="help-block">The list of countries in which Workers must reside (or not reside) in order to access your HIt/survey.</p>
              </div>
            </div>

          </fieldset>

          <div class="control-group">
            <div class="controls">
              <label class="checkbox" for"sandbox">
                <input type="checkbox" name="sandbox" id="sandbox" checked="checked" />
                Test
              </label>
            </div>
          </div>

          <p><strong>NOTE:</strong> By checking &ldquo;Test&rdquo; above, the HIT is created in the MTurk <a href="https://workersandbox.mturk.com/">Developer Sandbox</a>. This allows you the opportunity to preview and complete your HIT (i.e., take your own survey throught the MTurk Worker interface). While in the developer sandbox, your HIT will appear just as it will to AMT Workers when the HIT is made public. It is tringly suggested that you utilize this feature, take the time to ensure that your HIT is working properly, and that proper credit is awarded after the completion of the survey. (After completing the survey, a &ldquo;success&rdquo; message should be displayed in the MTurk interface). Also ensure that the number of completed assignments has increased by 1 in the <a href="https://requestersandbox.mturk.com/">Requester Sandbox</a> interface. Taking the time to test your HIT ensures accurate results and avoids unnecessary payments for incomplete assignments once the HIT is public. Once you have tested and verified that everything is working as intended, uncheck the &ldquo;Test&rdquo; box above, and click the &ldquo;Create&rdquo; button below to publish the HIT on MTurk.</p>

          <div class="control-group">
            <div class="controls">
              <label class="checbox" for"confirmation">
                <input type="checkbox" name="confirmation" id="confirmation" checked="checked" />
                I have read and understand the above statement regarding the importance of testing HITs in the MTurk sandbox.
              </label>
              <button type="submit" class="btn btn-primary">Create HIT</button>
            </div>
          </div>

        </form>


      </div>
    </div>
  </div>
</div>

<h3>Changelog</h3>
<ul class="changelog">
  <li><strong>Version 0.06</strong> - <em>2012-08-26</em>
    <ul>
      <li>UI facelift using Bootstrap</li>
    </ul>
  </li>
  <li><strong>Version 0.05</strong> - <em>2011-08-09</em>
    <ul>
      <li>Moved from alpha stage of development to beta</li>
      <li>Added support for country QualificationRequirement</li>
    </ul>
  </li>
  <li><strong>Version 0.04</strong> - <em>2011-05-18</em>
    <ul>
      <li>Implemented frameHeight on back-end</li>
      <li>Using new method to detect AMT preview page</li>
    </ul>
  </li>
  <li><strong>Version 0.03</strong> - <em>2011-05-16</em>
    <ul>
      <li>Added frameHeight input</li>
      <li>Added placeholder text to all fields of &ldquo;HIT Details&rdquo; fieldset</li>
      <li>Added more information to the section regarding editing the survey header</li>
    </ul>
  </li>
  <li><strong>Version 0.02</strong> - <em>2011-05-11</em>
    <ul>
      <li>Added overlay to HIT preview frame to prevent AMT workers from interacting with survey prior to accepting the HIT</li>
      <li>Now scrolls to top of page (and top of &ldquo;Step 2&rdquo; container) upon form submission to ensure the AJAX response message is fully visible</li>
    </ul>
  </li>
</ul>
