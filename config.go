package main

import (
	"bufio"
	"log"
	"os"
	"strings"
)

func (b Build) readConfig(ch chan BuildStep) {
	// Here's where we'd figure out the project path / config file to use
	file, err := os.Open(b.ConfigFile)
	if err != nil {
		log.Fatal("Couldn't open %s: ", b.ConfigFile, err.Error())
	}

	log.Printf("Opened %s file, reading commands in…", b.ConfigFile)

	scanner := bufio.NewScanner(file)
	var line []string
	var length int

	for scanner.Scan() {
		line = strings.Fields(scanner.Text())
		// We only do anything if we have a command to run
		if length = len(line); length > 1 {
			ch <- BuildStep{line[0], line[1:]}
		} else if length == 1 {
			ch <- BuildStep{line[0], []string{""}}
		}
	}

	// Close the channel, we're done
	close(ch)
}
