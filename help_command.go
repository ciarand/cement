package main

import (
	"fmt"
)

func helpCommand() {
	fmt.Println(`
Cement -- A multipurpose build server / CLI written in Go

Commands:
    build               Builds the current project
    init                Generates a .cement file for the current project
	`)
}
