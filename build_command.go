package main

import (
	"log"
	"os"
)

func buildCommand() {
	// Make an "exit code" channel
	exit := make(chan bool, 1)

	cwd, err := os.Getwd()
	if err != nil {
		log.Fatal("Could not get current directory: ", err.Error())
	}
	go Build{Cwd: cwd, ConfigFile: ".cement"}.start(exit)
	if ret := <-exit; ret {
		log.Println("Build succeeded.")
	} else {
		log.Println("Build failed.")
	}
	close(exit)
}
