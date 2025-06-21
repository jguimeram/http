# PHP Minimal HTTP Router Framework – Code Explanation

This document explains the purpose and functionality of the three main files in your PHP HTTP routing framework: `Request.php`, `Response.php`, and `NewRouter.php`.

---

## 1. Request.php

**Purpose:**  
Represents the incoming HTTP request. It provides methods to access request data such as query parameters, POST data, headers, and route parameters.

**Key Features:**

- **Properties:**

  - `$params`: Stores route parameters (e.g., `/users/{id}` → `['id' => 123]`).
  - `$query`: Stores GET parameters (`$_GET`).
  - `$post`: Stores POST parameters (`$_POST`).
  - `$headers`: Stores HTTP headers, normalized to lowercase and hyphenated.
  - `$method`: HTTP method (GET, POST, etc.).
  - `$path`: The request path (e.g., `/users/123`).

- **Constructor:**  
  Initializes the above properties from PHP superglobals.

- **Methods:**
  - `getMethod()`: Returns the HTTP method.
  - `getPath()`: Returns the request path.
  - `getQuery(?string $key = null, $default = null)`: Gets a query parameter or all query parameters.
  - `getPost(?string $key = null, $default = null)`: Gets a POST parameter or all POST parameters.
  - `getHeader(string $name, $default = null)`: Gets a specific HTTP header.
  - `setParam(string $key, $value)`: Sets a route parameter (used by the router).
  - `getParam(string $key, $default = null)`: Gets a route parameter.
  - `getParams()`: Gets all route parameters.
  - `getAllHeaders()`: Extracts all HTTP headers from `$_SERVER` and normalizes them.

**How and Where Methods Are Used:**

- The constructor is called when a new `Request` object is created (by the router).
- `getMethod()` and `getPath()` are used by the router to determine which route matches the request.
- `setParam()` is used by the router to assign route parameters (like `{id}` in `/users/{id}`).
- `getParam()`, `getQuery()`, `getPost()`, and `getHeader()` are used in your route handlers/controllers to access request data.

---

## 2. Response.php

**Purpose:**  
Represents the HTTP response to be sent back to the client. It allows you to set status codes, headers, and body content in various formats.

**Key Features:**

- **Properties:**

  - `$statusCode`: HTTP status code (default 200).
  - `$headers`: Associative array of headers to send.
  - `$body`: The response body.

- **Methods:**
  - `setStatusCode(int $code)`: Sets the HTTP status code.
  - `setHeader(string $name, string $value)`: Sets a header.
  - `setBody(string $body)`: Sets the response body.
  - `json(array $data)`: Sets the response as JSON.
  - `html(string $html)`: Sets the response as HTML.
  - `text(string $text)`: Sets the response as plain text.
  - `send()`: Sends the status code, headers, and body to the client.
  - `getStatusCode()`, `getHeaders()`, `getBody()`: Getters for the response properties.

**How and Where Methods Are Used:**

- In your route handlers/controllers, you use methods like `setStatusCode()`, `setHeader()`, `setBody()`, `json()`, `html()`, and `text()` to build the response.
- The router calls `send()` after the handler returns, to actually send the response to the client.
- Getters are useful for testing or debugging.

---

## 3. Router.php

**Purpose:**  
Implements a simple routing system that maps HTTP methods and paths to handler functions or methods. It dispatches incoming requests to the appropriate handler.

**Key Features:**

- **Properties:**

  - `$routes`: Stores all registered routes, organized by HTTP method and path.

- **Route Registration Methods:**

  - `get()`, `post()`, `put()`, `delete()`: Register routes for each HTTP method.
  - `route()`: Register a route for any HTTP method.
  - `addRoute()`: Internal method to add a route.

- **Request Handling:**

  - `run()`:
    - Creates `Request` and `Response` objects.
    - Checks for an exact route match.
    - If not found, checks for parameterized routes (e.g., `/users/{id}`).
    - If a match is found, extracts route parameters and calls the handler.
    - If no match, returns a 404 response.

- **Route Matching:**

  - `matchRoute()`:
    - Converts route patterns with `{param}` to regex.
    - If the request path matches, extracts parameters and sets them in the `Request` object.

- **Handler Execution:**
  - `executeHandler()`:
    - Calls the handler with the `Request` and `Response`.
    - Handles different return types (Response, array, string, etc.).
    - Catches exceptions and returns a 500 error if needed.

**How and Where Methods Are Used:**

- You register routes using `get()`, `post()`, etc., in your application setup.
- The main entry point is `run()`, which is called to process each HTTP request.
- `matchRoute()` is used internally by `run()` to match parameterized routes and extract parameters.
- `executeHandler()` is used by `run()` to call the matched route handler and process its return value.

---

## How Everything Works Together

1. **Route Registration:**

   - You define routes using `get()`, `post()`, etc., and provide handler functions or methods.

2. **Handling a Request:**

   - When a request comes in, you call `Router::run()`.
   - This creates a `Request` (parsing method, path, params, etc.) and a `Response` (to build the reply).

3. **Route Matching:**

   - The router checks for an exact match.
   - If not found, it checks for parameterized routes using `matchRoute()`.
   - If a parameterized route matches, it sets the parameters in the `Request`.

4. **Handler Execution:**

   - The matched handler is called with the `Request` and `Response`.
   - The handler can return a string, array, or a `Response` object, or modify the `Response` directly.
   - `executeHandler()` processes the return value and sends the response.

5. **Response:**
   - The `Response` object is used to set status, headers, and body, and finally sent to the client.

---

## Which Methods Are Used in Controllers/Handlers

When writing your own controllers or route handlers, you will typically use:

**From the `Request` object:**

- `getQuery()`, `getPost()`, `getHeader()`: To access query parameters, POST data, and headers.
- `getParam()`, `getParams()`: To access route parameters.

**From the `Response` object:**

- `setStatusCode()`, `setHeader()`, `setBody()`: To customize the response.
- `json()`, `html()`, `text()`: To send data in different formats.

You do **not** need to call `send()`; the router does this for you after your handler returns.

---

## Summary

- **Request.php**: Encapsulates and provides access to all incoming request data.
- **Response.php**: Encapsulates and manages the outgoing response.
- **NewRouter.php**: Registers routes and dispatches requests to the correct handler, using the Request and Response classes.

Together, these files form a minimal but functional HTTP routing framework. By understanding and experimenting with these classes, you gain practical knowledge of HTTP request/response cycles, routing, and web application structure
