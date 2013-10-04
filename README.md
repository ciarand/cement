Cement - a multipurpose build server / cli written in Go
========================================================
Example
-------
```
❯ cement help
Cement -- A multipurpose build server / CLI written in Go

Commands:
    build               Builds the current project
    init                Generates a .cement file for the current project
```
```
❯ cement init
Buildfile initialized.
```
```
❯ cement build

2013/10/04 10:50:00 Opened .cement file, reading commands in…
2013/10/04 10:50:00 Running command: `echo "I'm a buildfile"`
"I'm a buildfile"
2013/10/04 10:50:00 Build succeeded.
```
Status
------
*Very alpha.*
