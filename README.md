CheTheatre 
===
 [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/geekhub-php/CheTheatre/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/geekhub-php/CheTheatre/?branch=master)
 [![Build Status](https://travis-ci.org/geekhub-php/CheTheatre.svg?branch=develop)](https://travis-ci.org/geekhub-php/CheTheatre)

### Charity project for Cherkassy Drama Theatre 
Theirs new outstanding website!

Repertoire of Cherkassy Ukrainian Music and Drama Theatre
can satisfy the most demanding audience for theatre 
works in different genres. 

Over the years the Theatre has performed over 
450 different performances, 
including most of them (300) being a work of 
modern domestic and foreign drama, 
about 75 performances being based on the works 
by Ukrainian classics and over 50 performances - 
on the works by foreign classics.

### Installation
Before installation be sure that you have Symfony2 installed and configured. If not, follow the instructions:
http://symfony.com/doc/current/book/installation.html

If you want to install the project, 
you have to follow next steps:

1. Install dependencies:
```bash
composer install
```

This command requires you to have Composer installed globally, 
as explained in the 
[installation chapter](https://getcomposer.org/doc/00-intro.md) 
of the Composer documentation.

2. Copy .env to .env.local and change it according to your env

3. Import database

```bash
mysql -u root -p db_name < 2020-01-30-theatre-dump.sql
```

4. Run server
```bash
symfony server:run
```
Use [SymfonyCLI](https://symfony.com/download) for that
Congratulation! You've done it successfully!

### Bug tracking

CheTheatre uses [GitHub issues](https://github.com/geekhub-php/CheTheatre/issues).
If you have found bug, please create an issue.

### Authors

CheTheatre was originally created by [Geekhub Project Team](http://geekhub.ck.ua).

[1]:  http://geekhub.ck.ua/

