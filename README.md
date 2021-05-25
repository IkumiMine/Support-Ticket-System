# Support-Ticket-System

## Description
This is a ticket support system application. A logged-in user as staff can see a list of all tickets and detailed each ticket, and reply to them. A logged-in user as a client can create a new ticket, see a list of their tickets and detailed each ticket, and reply to the message from the staff.

Used: HTML, CSS, bootstrap, PHP, XML

## Challenges
It was my first time adding a login function to the application using a session with PHP. I had difficulty assigning value to a session. I was able to create the session and see the session value. However, when I refreshed the page or visited the other pages, I couldn't get the session values.

## How I solved the challenges
With the professor's assistance, I was able to solve the problem. First, I assigned the value by typing the value directly to make sure that the session was created properly. After I made sure that, check what kind of data from XML I was trying to assign to the session. It turned out that the data I retrieved from XML was an array, it wasn't a string. By using "strval", I was able to assign value properly. 

## Future updates
I would like to add a login form for a user to register the system.