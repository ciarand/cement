The concept
===========
Projects have needs. Things need to be done. Oftentimes, it would be nice if
those things were done automatically, asynchronously, and quickly.

The need
--------
Examples of project needs that should be automatically handled:

* Unit tests
* Integration tests
* Binary compilation
* Deployment
* Asset compilation / minification / cachebusting

Does this mean you're trying to build another CI server?
--------------------------------------------------------
Yes.

The features
------------
While automatically and asynchronously are very important, it also means that
the tools for checking on the status need to clean and comprehensive. An
interested developer should be able to check the status of the "build" at any
point. This includes:

* Partial updating of command responses (e.g. current number of unit tests run
  vs. number to go)

* Subscription options, in the form of a variety of protocols:

    * HipChat
    * CampFire
    * Pushover
    * Email
    * SMS (?)

* Events, and fine grained options on the notifications produced by each

The roadmap
-----------
| Version | Feature Set            |
| ------- | ---------------------- |
| v0.1.0  | Ugly Makefile clone    |
| v0.2.0  | Log persistence        |
| v0.3.0  | Multiple projects      |
| v0.4.0  | Build queue            |
| v0.5.0  | Concurrent builds      |
| v0.6.0  | Simple web interface   |
| v0.7.0  | CRUD on web interface  |
| v0.8.0  | Code cleanup           |
| v0.9.0  | Distributable binaries |
| v1.0.0  | Code cleanup           |

