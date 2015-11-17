##PHP-Paginator-Class

PHP Paginator Class is designed to calculate the pages you will need in your pagination bar.
PHP Paginator does not create links but rather calculates for you the pages you need so that your pagination bar will be more effective.

##Need to know

####Core
Refers to current page and the surrownding pages. If the current page is 10 and core size is 3 then the core will be pages: 9, 10, 11. If the core size is 5, the pages will be 8,9,10,11,12.
######By default the core size is 5

####Spreads/Wings
The spreads or wings are pages that will fall between the beginning (page#10) and the core; or the core and the end(last page). They should help for quick movement between records.
######By default the spreads/wings size is 3


##Examples

```php
# param #1 => current page
# param #2 => total number of records to be paginated
# param #3 => records per page
# param #4 => core size
# param #5 => number of spreads

//Current page is 1
$p = new Paginator(1,1000,10,5,3);
$pg = $p->getPages();

Result($pg):
  0 => int 1
  1 => int 2
  2 => int 3
  3 => int 33
  4 => int 66
  5 => int 99

//Current page is 10
$p = new Paginator(10,1000,10,5,3);
$pg = $p->getPages();

Result($pg):
  0 => int 1
  1 => int 4
  2 => int 7
  3 => int 8
  4 => int 9
  5 => int 10
  6 => int 11
  7 => int 12
  8 => int 39
  9 => int 69
  10 => int 99
```
#####If the difference in the size of the array is unacceptable you can use the method fixedPages([NUMBER]) to force a fixed size. If the method is unabled to produce the desired result (There are cases which are unsavable), it will return the regular page list.

```php
//Current page is 1
$p = new Paginator(1,1000,10,5,3);
$pg = $p->fixedPages(10);

Result($pg):
  0 => int 1
  1 => int 2
  2 => int 3
  3 => int 14
  4 => int 28
  5 => int 42
  6 => int 56
  7 => int 70
  8 => int 84
  9 => int 98

//Current page is 10
$p = new Paginator(10,1000,10,5,3);
$pg = $p->fixedPages(10);
  0 => int 1
  1 => int 4
  2 => int 7
  3 => int 8
  4 => int 9
  5 => int 10
  6 => int 11
  7 => int 12
  8 => int 39
  9 => int 69
```





##License
MIT