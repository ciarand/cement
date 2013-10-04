package main

import (
	"os"
	"testing"
)

// Tests the output of the init command when a build file doesn't exist
func ExampleInitCommand_NoBuildFile() {

}

// Tests the output of the init command when a build file already exists
func ExampleInitCommand_WithBuildFile() {

}

// Tests the functionality of init when a build doesn't exist
func TestInitCommand(t *testing.T) {
	buildFile := ".cement"
	moveToFixturesDir(t)
	// We're going to ignore the error here
	_ = os.Remove(buildFile)
	initCommand()

	// Now we need to check that there is a build file
	curr, err := os.Stat(buildFile)
	if err != nil {
		t.Fatalf("Did not generate buildfile: %s", err)
	}

	err = os.Truncate(buildFile, 0)
	if err != nil {
		t.Fatalf("Could not modify created buildFile: %s", err)
	}

	// Now, we need to make sure the build file doesn't get overwritten
	initCommand()
	file, err := os.Stat(buildFile)
	if curr.Size() == file.Size() {
		t.Errorf("initCommand modified old build file")
	}
}

func moveToFixturesDir(t *testing.T) {
	// First, change the directory to "fixtures"
	err := os.Chdir("fixtures")
	if err != nil {
		t.Fatalf("Could not change dir: %s", err)
	}
}
