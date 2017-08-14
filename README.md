# Board Package
Ths is a system built for the Tanyard Springs Homeowners Association to handle electronic votes. This is based off of the phpclicks.com ajax login form using php mysql and pdo

----------------------
## Requirements
----------------------
- LAMP Stack
- Mail Server
 
 ----------------------
 ### Additional Notes
 ----------------------
 MariaDB can be substituted for MySQL if desired
 

----------------------
## Core Features
----------------------
- Ability to add a motion
- Ability to defer a motion
- Ability to revoke a motion
- Ability to amend a motion
- Ability to add discussion/comments for each motion
- Generate reports for each motion
- Automatically e-mail Management when motion passes to add to agenda to ratify
- Automatically e-mail Management when motion fails to add to agenda as a motion to vote on
- Automatically e-mail Management when motion is deferred to add to agenda as a motion to vote on
- Automatically e-mail to board members when motino was amended to verify their vote is still accurate
- Ability to add a vote
- Ability to modify a vote

----------------------
## Other Features
----------------------
- Abilty to modify e-mail address
- Ability to modify password
- Utilizes timing of local time zone instead of GMT


----------------------
Future Features
----------------------
- Ability to modify user accesses in the GUI instead of database
- Ability to disable user in GUI
- Ability to add user in GUI
- Force user to log out when they do a password change
- Fully implement Forgot Password Functionality
- Ability to be able to add formatting in description (tables, colors, line breaks, etc) in motion description
- Authenticate via LDAP
