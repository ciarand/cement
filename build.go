package main

import (
	"log"
	"os"
	"os/exec"
	"strings"
)

func (b Build) start(exit chan bool) {
	ch := make(chan BuildStep)
	go b.readConfig(ch)

	for step := range ch {
		step.Execute(exit)
	}

	// If no one else has said otherwise, the build succeeded
	exit <- true
}

func (b BuildStep) Execute(exit chan bool) {
	// Print the execution part
	log.Printf("Running command: `%s`", b.String())

	// Create the command and adjust the out / err pipes
	cmd := exec.Command(b.Command, b.Args...)
	cmd.Stdout = os.Stdout
	cmd.Stderr = os.Stderr

	// Start the command, check for errors
	err := cmd.Run()
	if err != nil {
		log.Println("Error: ", err.Error())
		exit <- false
	}
}

func (b BuildStep) String() string {
	return strings.Join(append([]string{b.Command}, b.Args...), " ")
}
