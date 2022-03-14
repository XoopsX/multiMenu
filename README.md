[![Project Status: Active – The project has reached a stable, usable state and is being actively developed.](https://www.repostatus.org/badges/2.0.0/active.svg)](https://github.com/xoopscube/xcl)
![License GPL](https://img.shields.io/badge/License-GPL-green)
![X-Updare Store](https://img.shields.io/badge/X--Update%20Store-Pending-red)

## ///// — MultiMenu :: Custom Navigation Blocks

![alt text](https://repository-images.githubusercontent.com/461634439/038a5641-d653-4d78-ba55-898667ae7d)


MODULE | MultiMenu
------------ | -------------
Description  | MultiMenu module allows you to display customized menus.
Render Engine| Smarty v2 and XCube Layout
Version      | 2.3.1
Author       | Tom Hayakawa
Author       | Update by @gigamaster Nuno Luciano (XCL7)
Copyright    | 2005-2022 Authors
License      | GPL


##### :computer: The Minimum Requirements



          Apache, Nginx, etc. PHP 7.2.x
          MySQL 5.6, MariaDB  InnoDB utf8 / utf8mb4
          XCL version 2.3.+



-----

## Module MultiMenu v.2.3.+

Refactor code to XCL - PHP7 and MySQL ENGINE=InnoDB 

### Description

multiMenu module allows you to display customized menus.  
Original module by Solo, luinithil, Wolf and contributions by Domifara, Naoki Sawada, Naoki Okino, Nobunobu, Yoshi Sakai, Toshihiro Takehara (aka nouphet).

### Setting matching methods

The following methods describe the logic of MultiMenu links management on the UI screen.

#### Specify URL with full path as usual

**Example**: https://github.com/xoopscube/xcl 

#### Specification by module name  
[Module_name]  
[Module_name]xxxx.php?xxxx=xxxx

**Example:**  

Link ==>[news]  
Title ==> News  
↓  
Display: News  

**Example:**  

Link ==> [news]article.php?storyid=11  
Title ==> Important news  
↓   
Display: Important news   


#### Always show submenu  

+[module_name]  
+[module_name]xxxx.php?xxxx=xxxx  

**Example:**  
  
Link ==> +[news]  
Title ==> News  
↓  
Display: (Always displayed)  
  News  
    News post  
    Archive  

**Example:**  
Link ==> @[news]article.php?storyid=11  
Title ==> Important news  
↓  
Display: (Always displayed)  
Important news  
News post  
Archive  

#### Display the submenu only with module  
when the corresponding module is displayed (same operation as the main menu)

@[Module_name]  
@[Module_name]xxxx.php?xxxx=xxxx  

**Example:** 
Link ==> @[news]  
Title ==> News  
↓  
Display: (Normal)  
News  
↓  
Display: (when the corresponding module is displayed)  
News  
News post  
Archive  

**Example:**  
Link ==> @[news]article.php?storyid=11  
Title ==> Important news  
↓  
Display: (Normal)  
Important news  
↓  
Display: (when the corresponding module is displayed)  
Important news  
News post  
Archive  

#### Show as custom submenu  
-[Module_name]  
-[Module_name]xxxx.php?xxxx=xxxx  
-https://github.com/xoopscube/xcl  

Links that start with a sign are added to submenus of menus that have links that do not start with a-sign.
The way the sub menu is displayed changes depending on the attributes of the parent menu, as shown in the examples.
If displayed at the same time as the submenu, a custom submenu will be added to the bottom of the module submenu.

### Changelog

[refactor code]
v.2.30 PHP7 and MySQL ENGINE=InnoDB for XCL

[Update]
v1.20
 * Rewritten exclusively for XOOPS Cube Legacy 2.1.x
 * Improvement of security (POST foreach deployment is abolished)
 * Supports PHP5 only
 * Fixed to work without system module
 * HTTP_POST_VARS deprecated
 * Change block template file name
 * Fixed block management not working
