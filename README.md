HTTP Foundations:

Request = Method + Path

GET /about
POST /users

Response = Status Code + Headers + Body

200 + Content-Type: text/plain + "Hello World"

Router's Job:

Match incoming requests to defined routes
Execute the corresponding handler
Return proper HTTP responses

Key HTTP Response Codes:

200 - Success, everything worked
201 - Created, new resource made
302 - Redirect, go to another URL
400 - Bad Request, client sent wrong data
401 - Unauthorized, need to login
403 - Forbidden, access denied
404 - Not Found, route doesn't exist
500 - Server Error, something broke

Essential Headers:

Content-Type - Tells browser what kind of data this is
Location - Where to redirect (used with 302/301)
Content-Disposition - Force file downloads

How It Works:

Router stores routes in an array: $routes['GET']['/path'] = handler
When request comes in, extract method and path
Look up handler in routes array
Execute handler (which sends response)
If no route found, send 404

This is the foundation - every web framework does essentially this, just with more features added on top. The core is always: match request → execute handler → send response with proper HTTP codes and headers.
