package main

import (
	"flag"
	"fmt"
	"strings"
)

func main() {
	// Here's where we'd define the flags to be used
	flag.Parse()

	var err error
	switch strings.ToLower(flag.Arg(0)) {
	case "build":
		err = buildCommand()
	case "init":
		err = initCommand()
	default:
		err = helpCommand()
	}

	if err != nil {
		fmt.Println(err)
	}
}
