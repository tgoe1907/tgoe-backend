
package org.tgoe.backendservices;

import jakarta.jws.WebService;

@WebService(endpointInterface = "org.tgoe.backendservices.HelloWorld")
public class HelloWorldImpl implements HelloWorld {

    public String sayHi(String text) {
        return "Hello " + text;
    }
}

