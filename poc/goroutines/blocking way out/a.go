//code inspiration and more info from: http://golang.org/doc/articles/concurrency_patterns.html
//relevant ML thread: https://groups.google.com/d/msg/golang-nuts/vvUkAk27jHg/4PtL-2AQfLgJ

package main

import "time"

var closed bool = false

//this is blocking, so goal is: need a way to quit it...
func some() int {
	//for i := 1; i < 5; i++ {
	for { //blocked on some read, let's say
		print(".")
		time.Sleep(1 * time.Second)
		//should still have some way to exit though
		if closed {
			break
		}
	}
	return 10
}

func main() {
	timeout := make(chan bool, 1)
	go func() {
		time.Sleep(2 * time.Second)
		timeout <- true
	}()

	ch := make(chan int) //, 1)
	go func() {
		for {
			ch <- some()
			println("done goroutine")
		}
	}()

F:
	for {
		select {
		case x := <-ch:
			// a read from ch has occurred
			println("ch:", x)
		case <-timeout:
			// the read from ch has timed out
			println("timeout")
			break F
		}
	}

	println("waiting 10 sec before next step towards exit")
	time.Sleep(10 * time.Second)
	closed = true
	//go routine is still alive because it's blocked on ch send, unless ch has buffer 1
	//run it with: go run -race a.go
	println("waiting 5 sec before exit")
	time.Sleep(10 * time.Second)
}
