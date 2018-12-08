ToDoList
========
[![Maintainability][1]][2]
[![Codacy Badge][3]][4]
[![Build Status][5]][6]

This project is a ToDoList application built with **Symfony 4.1**. This project was built during my [web development learning path][8] with OpenClassrooms, which aims to **improve an [existing project][9]**.

### Prerequisites
- PHP >=7.1.3
- MySQL 5.6
- PHPUnit 6.5
- [Composer][7] to install Symfony 4.1 and project's dependencies

### Installation
1. Clone the repository.
2. Run `composer install` to install dependencies.
3. Change the file `.env.dist` or override it with a new file named `.env`.
4. Set up the database by running :
    - `bin/console doctrine:database:create`  
    - `bin/console doctrine:schema:update --force`  
    - `bin/console doctrine:fixtures:load --append` 
    
### Documentations
In [`documents`][10] folder you can find:
- [A security documentation][11]
- [A quality and performance analyse][12] using [BlackFire][15]
- [The tests coverage][13]

### Contribution

To contribute to this project read [How to contribute instructions][14].


[1]: https://api.codeclimate.com/v1/badges/ecda728b944ae89446d7/maintainability
[2]: https://codeclimate.com/github/vlescot/TodoList/maintainability
[3]: https://api.codacy.com/project/badge/Grade/b0a3e5aa6c944c1c95dfaa18d0b5f1b4
[4]: https://www.codacy.com/app/vlescot/TodoList?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=vlescot/TodoList&amp;utm_campaign=Badge_Grade
[5]: https://travis-ci.org/vlescot/TodoList.svg?branch=master
[6]: https://travis-ci.org/vlescot/TodoList
[7]: https://getcomposer.org/
[8]: https://openclassrooms.com/paths/developpeur-se-d-application-php-symfony
[9]: https://github.com/saro0h/projet8-TodoList
[10]: https://github.com/vlescot/TodoList/tree/master/documents/
[11]: https://github.com/vlescot/TodoList/tree/master/documents/Security.pdf
[12]: https://github.com/vlescot/TodoList/tree/master/documents/Quality%20Analyse.pdf
[13]: https://github.com/vlescot/TodoList/tree/master/documents/code-coverage.png/
[14]: https://github.com/vlescot/TodoList/blob/master/CONTRIBUTING.md
[15]: https://blackfire.io/
