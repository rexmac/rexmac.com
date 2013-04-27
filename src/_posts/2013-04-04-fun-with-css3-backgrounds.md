---
layout: post
title: "Fun with CSS3 Backgrounds"
date: 2013-04-04 13:22:36
category: "Web Development"
tags:
  - background
  - bootstrap
  - css
  - css3
  - gradient
css:
  - /css/fun.css
js:
  - http://codepen.io/assets/embed/ei.js
---

I decided to have some fun with different background effects using CSS3.

## Notebook Paper

The first effect I wanted to achieve was a background resembling a sheet of notebook paper. I found a "[Lined paper](http://lea.verou.me/css3patterns/#lined-paper)" pattern in [Lea Verou's CSS3 Patterns Gallery](http://lea.verou.me/css3patterns), but the vertical line wasn't working 100% when I tested it. It looks fine in Firefox, but it only shows up at certain window widths in Chrome 26, and when the line does show, it is far too wide (by a factor of 2x or 3x) -- I wrote a separate [blog article](#) about this particular "feature" of Chrome. I was also accustomed to a reddish pink vertical line and soft blue horizontal lines (qualities of only U.S. notebook paper?), and this pattern had a blue vertical line and grey horizontal lines.

### First Attempt

Here is my first attempt. I prefer this approach as it only requires styling a single element. Unfortunately, it suffers from the same bug/"feature" in Google Chrome as the original pattern, i.e., the vertical line appears intermittently depending on the width of the container. All other browsers I tested this in (Firefox, Opera, IE 10), did not have this issue.

<pre class="codepen" data-height="300" data-type="result" data-href="tgGjv" data-user="rexmac" data-safe="true"><code></code><a href="http://codepen.io/rexmac/pen/kcuKl">Check out this Pen!</a></pre>

### Second Attempt

Here is my second attempt, which, unfortunately, requires styling two elements to fully pull off the effect. However, it does properly render in every browser I tested.

<pre class="codepen" data-height="300" data-type="result" data-href="kcuKl" data-user="rexmac" data-safe="true"><code></code><a href="http://codepen.io/rexmac/pen/kcuKl">Check out this Pen!</a></pre>

### Future Plans

A lot of notebook paper purchased in the U.S. has three holes on the left side to allow it to be placed within a 3-ring binder or folder. I'd really like to implement an additional class (e.g., `with-holes`) that would add the effect of holes in the pattern. These "holes" would probably just be small grey circles; however, it would be even better if the holes were transparent.

## Twitter Bootstrap Masthead Background

I thought this might be another interesting effect to achieve without images. Unfortunately, the end result still needs some work; it doesn't quite match the original image 100%, and it doesn't appear the same in all browsers...yet.

<pre class="codepen" data-height="400" data-type="result" data-href="BjEah" data-user="rexmac" data-safe="true"><code></code><a href="http://codepen.io/rexmac/pen/BjEah">Check out this Pen!</a></pre>

