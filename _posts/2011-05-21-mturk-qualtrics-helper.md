---
layout: post
title: "MTurk-Qualtrics Helper"
date: 2011-05-21 04:07:32
category: Projects
tags:
  - mturk
  - qualtrics
  - amazon
---

#### Background

Recently, my fiancée was investigating the use of [Amazon Mechanical
Turk](http://www.mturk.com/) (AMT) as a means of paying people for
participating in her latest empirical research. The study involves taking an
online survey that she created at [Qualtrics.com](http://www.qualtrics.com/)
(her University has an account).

#### The Problem

AMT has a built-in feature for creating surveys. Unfortunately, it is rather
limited in its implementation:

* There are no options for customizing the structure of surveys (e.g. multiple
  pages, order of presentation, etc.)
* Customizing the look and feel requires knowledge of HTML and CSS. Skills
  that my fiancée and many others in her position do not possess.

If you are creating a very simple survey with no more than a few questions,
and if you are not terribly concerned about the survey's appearance, then the
built-in survey feature of AMT is probably the best option for you. But if you
want a more complex survey and more control over its presentation, then you
may be better off choosing from one of the various providers of web-based
survey software.

Unfortunately, AMT's web interface does not seem to allow for creating
[HIT](https://www.mturk.com/mturk/welcome?variant=worker)s that redirect the
user to an external website. It was at this point that my fiancée asked me to
look into the issue to see if there was a possible "programming" solution to
the problem.

#### Preparation

My first stop was the [AMT requester sandbox
site](https://requestersandbox.mturk.com/) where I created a developer
account. I then ent through the same procedure on the [AMT worker sandbox
site](https://workersandbox.mturk.com/). I now had access to the AMT sandbox
as both a requester and a worker, which allowed me to create and test my own
HITs.

#### Easy Solution?

First, I examined the "Design" section of the AMT requester site. I chose to
work with the "Blank Template", which proved to be the exact opposite of what
its name implies. After removing the contents of the "blank" template, my
first thought was to just insert an `<iframe>` and point it to the URL of my
fiancée's survey.

```<iframe width=”100%” height=”100%” src=”[YOUR_URL_HERE]“></iframe>```

Unfortunately, this solution has several major flaws. First, AMT's web
interface requires that your HIT template contain at least one question, i.e.
an `<input>` element with a name prefixed with “Q1″. Adding an `<input>`
element of type “hidden” satisfies this requirement, but it also leads to the
next problem. Now that the survey meets the requirement of having at least one
"question", AMT inserts a "Submit" button at the bottom of the frame. The idea
is that the user answers your "question" and then clicks the "Submit" button
to claim their reward. In our scenario, the "question" that we want the user
to answer is the survey in the `<iframe>`. However, there is no obvious way of
preventing the user from clicking the "Submit" button before completing the
survey. In other words, there is no way to prevent the user from getting paid
for doing nothing! _Author's note: If you know of a method to prevent this
from happening, please let me know_.

#### "Programming" Solution

I did some digging around in the [AMT developer
resources](https://requestersandbox.mturk.com/developer), and I discovered
that, through the MTurk API, it was possible to create an "External HIT",
which loads a specified URL as the contents of a HIT. Creating an external HIT
has a major advantage over the previous solution in that it causes the
"Submit" button to be disabled. Therefore, the user can not submit the HIT and
get credit for doing nothing! Of course, after the user has completed the
survey, they deserve to get paid, at which point we want to enable that
button. To do so, we must send a signal to AMT when the survey is over. When,
AMT loads the survey URL, there are three parameters appended to the URL's
query string: `assignmentId`, `hitdId`, and `workerId`. To signal AMT that the
survey has been completed and to allow the worker to claim their reward, we
must send a GET or POST request to
`https://www.mturk.com/mturk/externablSubmit` that contains the same three
parameters. Easy enough. Except, how do we determine when we're at the end of
the survey?

I started looking at Qualtrics.com next. I didn't have access to my fiancée's
university's account, so I just worked with a free trial account. I created a
survey with a few silly questions and got a feel for the Qualtrics.com web
interface. Next, I looked at the help section of their site, and I was pleased
to discover that they allow for embedding HTML code into the header or footer
of surveys. More importantly, you can embed Javascript! They also use a
version of the [Prototype](http://www.prototypejs.org/) library in their
survey pages which is great, but it took some getting used to as I have not
used it in quite some time. Knowing that I can insert Javascript into the
survey, I began looking for an event or a DOM element or attribute that I
could use to test if the survey was completed or not. Turns out, Qualtrics has
created just such an event. I decided to override their event handler,
`Qualtrics.SurveyEngine.OnEndOfSurvey`, with my own function that signals AMT
that the survey has been completed. I accomplished this by creating an
invisible `<iframe>` that loads AMT's external submit URL. In order to carry
over the three query string parameters from AMT all the way to the end of the
survey, I inserted some Javascript to extract each of the parameters' values
from the URL query string and insert them as hidden `<input>` elements. This
allowed me to extract the values at the end of the survey. It also has the
side benefit of including those values in the survey data allowing the
surveyor to compare them to the values reported by AMT.

After I was done testing, I created a simple little [web
application](http://rexmac.com/mturk) to assist in the process of making
external HITs on AMT for use with Qualtrics.com surveys. It provides the
JavaScript to be inserted into the survey along with some helpful guidance on
how to do just that, but the meat of the app is the form that allows for the
quick creation of external HITs.

It was at this point that I realized there was another problem. Prior to
accepting a HIT, an AMT worker sees a "preview" of the HIT, which in the case
of an external HIT, is exactly the same thing they will see for a non-preview.
In other words, AMT loads your survey URL on the preview page and does nothing
to prevent the user from interacting with it. While there is nothing wrong
with letting the user preview the actual survey, an inexperienced AMT worker
may begin answering the survey without realizing that they have not yet
accepted the HIT. Then, after completing the survey they will be unable to
submit. I decided to try and solve this by adding some more Javascript to the
survey header that would dim the frame and prevent clicking within the frame
when the survey was being viewed as a preview. So far, I have tried three
different methods of detecting when a survey is being previewed. All three
methods have worked for me and my free trial account survey, but none of the
methods have worked for my fiancée's surveys on a paid account. I have an idea
for a fourth method that should work in all conditions, but I have not yet
implemented it.

All code is on [github](http://github.com/rexmac/MTurk-Qualtrics-Helper/).
