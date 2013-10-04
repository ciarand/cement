package main

import (
	"fmt"
	"os"
)

func initCommand() {
	_, err := os.Stat(".cement")
	if err == nil {
		fmt.Println("Buildfile already exists.")
		os.Exit(1)
	}

	file, err := os.Create(".cement")
	if err != nil {
		fmt.Println("Error: ", err)
		os.Exit(1)
	}

	_, err = file.WriteString("echo \"I'm a buildfile\"")
	if err != nil {
		fmt.Println("Error writing command: ", err)
	}
	fmt.Println("Buildfile initialized.")
}
