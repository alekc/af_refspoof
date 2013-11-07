af_refspoof
===========

This plugin allow to fake referral on feed images. A lot of sites protect their images from hotlinking, but unfortunately it also breaks rss readers functionality (especialy the web ones). 

This plugin rewrite image path and pass it through proxy with fake referral (article link).

Installation
===========

Download and place login in folder named af_refspoof under Tiny Tiny Rss plugin directory. Go to backend and enable it, after that you will see a new panel under Preferences tab called "Plugin RefSpoof". Click on it, enable feeds which require faking of referral and you are done. 