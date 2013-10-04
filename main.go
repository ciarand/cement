package main

import (
	"flag"
	"strings"
)

func main() {
	// Here's where we'd define the flags to be used
	flag.Parse()

	switch strings.ToLower(flag.Arg(0)) {
	case "build":
		buildCommand()
	default:
		helpCommand()
	}
}
