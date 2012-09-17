---
layout: post
title: "Jekyll Power"
date: 2012-09-01 12:03:01
category: Lyfebites
tags:
  - jekyll
  - ruby
---

Several months ago, when I decided to launch a new blog, I knew that I wanted a platform that would allow me to quickly and easily add new content (and manage existing content), while also spreading my developer wings and having some fun in the process.

#### [WordPress](http://wordpress.org/)

I've used it in the past for personal projects and also for a few clients, but it always felt...wrong. From a user's perspective, WP can be a pleasure, but it can also feel a bit daunting with it's myriad of options in the Admin panel. From a developer's perspective, WP is simply atrocious. I could rant for years on the faults of the WP code base, but I would rather get on with life. Suffice to say, using WP for my blog was never an option.

#### [Perch](http://grabaperch.com/)

I've heard great things about this little CMS, and I am a big fan of [the developers](http://www.edgeofmyseat.com/). I definitely plan on experimenting with Perch in the near future as I think it could be a great platform for clients that require easy editing of their site without feeling overwhelmed by unnecessary complexity. I came very close to purchasing a license and using Perch for this site, but then...

#### [Jekyll](http://jekyllrb.org)

I discovered Jekyll when I began looking into hosting a site using [Github Pages](http://pages.github.com/). I was, initially, slightly put off by the idea of a static site, but the more I thought about it, the more sense it made. Do I really need all the processing overhead of a full-blown PHP/Ruby/whatever stack behind each request for a simple blog? No. Do I really need a (bulky) front-end system to assist with managing the site and its content? No. A static site means there are no security holes introduced by a front-end management system, and any web server can serve up static files lickity split. Double win!

I also find the idea of writing blog posts in [Markdown](http://daringfireball.net/projects/markdown/) to be very appealing. It means that I can write/edit posts in my favorite editors ([VIM](http://www.vim.org/) and [Sublime Text 2](http://www.sublimetext.com/)) with a high factor of readability (compared to writing HTML) and without the unnecessary bloat and ~~potential~~ pitfalls of a WYSIWYG editor. Even more win!

And rather than storing all the content (and prior versions) in a bulky RDBMS, I can store every revision of the site's content (_and_ the source code) in a git repo. ZOMG, win covered in awesomesauce with more win on top!

The one major drawback to using Jekyll to generate my new blog is that, aside from some very minor hacking on [Redmine](http://redmine.org), I have no experience with [Ruby](en.wikipedia.org/wiki/Ruby_%28programming_language%29) programming (Oh, by the way, Jekyll is written in Ruby).

...???...mention other static site generators like MiddleMan and nanoc

It wasn't too long into this foray, that I built up quite a collection of very useful plugins for Jekyll. Unfortunately, Github Pages [does not allow plugins for security reasons](https://github.com/mojombo/jekyll/issues/325) (and rightly so). One solution would be to generate the site locally and then push the changes to github, but I'm actually happier hosting the site myself as I have more control. After modifying a few plugins that I gathered from the interwebs[^1], I have even managed to hack together some of my own Jekyll plugins (I hope to blog about those later).

So far, I am happy with the results. I wish I was a better designer, but I think the site looks fairly decent.

[^1]: I drew a lot of inspiration (read: borrowed a lot of code) from [BlackBulletIV](https://github.com/BlackBulletIV/blackbulletiv.github.com), [Jekyll-Bootstrap](http://jekyllbootstrap.com), and [Octopress](http://octopress.org).