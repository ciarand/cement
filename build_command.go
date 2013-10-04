package main

import (
	"errors"
	"log"
	"os"
)

func buildCommand() (ret error) {
	// Make an "exit code" channel
	exit := make(chan bool, 1)

	cwd, err := os.Getwd()
	if err != nil {
		return errors.New("Could not get current directory: " + err.Error())
	}

	go Build{Cwd: cwd, ConfigFile: ".cement"}.start(exit)
	if ret := <-exit; ret {
		log.Println("Build succeeded.")
	} else {
		log.Println("Build failed.")
	}
	close(exit)

	return nil
}
