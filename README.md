NEUOJ
====

### Function Enabled for v1.0
* [ ] Problem managment
 * [ ] Add Problem
 * [ ] Edit Problem
 * [ ] Delete Problem
 * [ ] Edit data
* [ ] Hold Contest
 * [ ] Three kind of access to contest: Private Register Public
 * [ ] Enable user register himself into the register Contest
 * [ ] Board display
 * [ ] Import student info from xls and xlsx etc.
* [ ] Root Admin Panel
 * [ ] + User Managment
* [ ] Auth module
 * [x] Register
 * [x] Login
 * [ ] Reset Password
 * [ ] (Future) SSO and Third party login
* [ ] Problem Browsing
 * [ ] Search problem by id
 * [x] View Page-splited problem list
 * [ ] Search problem by title
* [x] Problem Submit
* [x] Judge (Current Plan: use domjudge judgedaemon to judge)
 * [x] Read domjudge judge/ code and make the Request Graph
 * [x] Implement the RESTful API with laravel


### top level route
* /profile User profile page
* /dashboard User managment panel
* /problem Show Problem(s)
* /status Show status(es)
* /auth Authenticate interface (Register login and reset password)
* /contest Show or get in contest
* /discuss Route to subfunction of bbs

### Code Styling

Use [PSR-2](http://www.php-fig.org/psr/psr-2/) Code Standard
