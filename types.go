package main

// A build represents a run-through of a project's configuration file
// and all associated commands.
type Build struct {
	// The working directory of the project
	Cwd string
	// The config file to use for the build
	ConfigFile string
	// An array of build steps to execute
	Steps []BuildStep
}

// A build step represents an individual step in a build
// Each of the steps will be executed in order
type BuildStep struct {
	Command string
	Args    []string
}
