SHELL := /bin/bash

build:
	go build

build-all:
	GOOS=windows GOARCH=386 go build -o bin/windows_386.exe
	GOOS=linux GOARCH=386 go build -o bin/linux_386
	go build -o bin/macos
