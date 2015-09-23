# phpcb (php code benchmark)

**phpcb** is a very simple and very lightweight tool for speed benchmarking of various little pieces of PHP code, written in PHP, of course.

### Why
**phpcb** is meant to be used in those situations when there are multiple ways of how to do something - and you *know* all will have the exact same result - but you ***just can't*** decide which would ultimately be the best (meaning "*fastest*") to use.

### Requirements
- *PHP 5.4*, obviously.
- *BCMath Arbitrary Precision Mathematics* library for PHP; shouldn't be a problem, since it is commonly shipped with PHP itself.

### Installation
Download **phpcb** and just put it anywhere where you can access it through your (primarily local) webserver.

### Structure
This package contains two main directories: An `/app/` directory and a `/benchmarks/` directory.
- `/app/` directory contains the actual **phpcb** - files and classes handling and running the speed benchmarks, displaying of the results, etc.
- `/benchmarks/` directory contains single files of single separate benchmarks. There should be at least two sample benchmarks included and it shouldn't be that hard to understand what's going on and how to write your own benchmarks.

### Usage
Since you have **phpcb** accessible through your webserver, simply navigate to its directory via the browser and launch any of the benchmarks you want. **phpcb** will run predefined benchmark and display a resulting webpage, showing more detailed info about how things went.

Have fun!
