package main

import (
	"errors"
	"fmt"
	"os"
)

func initCommand() (ret error) {
	_, err := os.Stat(".cement")
	if err == nil {
		return errors.New("Buildfile already exists.")
	}

	file, err := os.Create(".cement")
	if err != nil {
		return err
	}

	_, err = file.WriteString("echo \"I'm a buildfile\"")
	if err != nil {
		fmt.Println("Error writing command: " + err.Error())
	}
	fmt.Println("Buildfile initialized.")
	return nil
}
