<img src="public/images/barebones.svg" width="50" alt="Barebones logo" style="position: absolute; top: 3rem; right: 1rem">

# Barebones

PHP framework with basic MVC functionality for legacy servers and projects with simple business logic.

## Requirements
* PHP version 5.6.36 (or newer)
* Composer 1 (Using Composer 2 will cause a syntax error in the autoload file for PHP 5.6)
* Enabled PHP extensions:
  * `ext-json` (for proper JSON encoding/decoding)
  * `ext-simplexml` (for parsing data from/to XML)
  * `ext-pdo` (for handling database connection)

## License
This work is licensed under [CC BY-SA 4.0](http://creativecommons.org/licenses/by-sa/4.0/?ref=chooser-v1).

![CC BY-SA 4.0](https://mirrors.creativecommons.org/presskit/icons/cc.svg?ref=chooser-v1)
![CC BY-SA 4.0](https://mirrors.creativecommons.org/presskit/icons/by.svg?ref=chooser-v1)
![CC BY-SA 4.0](https://mirrors.creativecommons.org/presskit/icons/sa.svg?ref=chooser-v1)

This license requires that reusers give credit to the creator. It allows reusers to distribute, remix, adapt, and build upon the material in any medium or format, even for commercial purposes. If others remix, adapt, or build upon the material, they must license the modified material under identical terms.

### Legacy

Barebones started in 2019 as a "needed foundation" for one of my first PHP projects - a simple guestbook.

Although I was already familiar with Symfony 4 at that time, due to limitations of the server, which did not
meet the requirements for running Symfony, I was "forced" to write the app along with the framework from scratch.

When the app was up and running, I decided to strip the whole code to the "bare bones", therefore creating a separate
project containing only the framework.

**I am aware that this framework is far from perfect.** Nonetheless, during its development, it gave me a lot of experience
and knowledge which I later built upon, and **I will be more than happy if it helps anyone to accomplish their goal as well** :)

### Sources
* SVG icons used across the framework provided by SVG Repo: [Halloween Collection](https://www.svgrepo.com/collection/halloween-26/)
