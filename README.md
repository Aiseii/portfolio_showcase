# Portfolio Showcase – Mini Project 2

PHP + MySQL + Bootstrap Student Portfolio & FYP Showcase System.

## Requirements
- Laragon (Apache, MySQL, PHP 8.1+), php extensions `pdo_mysql`, `fileinfo`, `mbstring`, `zip`
- Internet for Bootstrap CDN (or download Bootstrap locally if offline)

## Run locally
1. Extract this folder as `C:\laragon\www\portfolio_showcase`.
2. Start **Apache** and **MySQL** in Laragon.
3. Open phpMyAdmin/HeidiSQL and import `database/assignment_system.sql` (creates `assignment_system`).
4. Check `includes/db.php` (default Laragon: `root` / blank password).
5. If folder is renamed, change `BASE_URL` in `includes/init.php`.
6. Visit `http://localhost/portfolio_showcase/`.
7. Register a student account. Roles cannot be chosen on the public registration page.
8. To make a lecturer an admin, register that account first, then run **in phpMyAdmin**:
   ```sql
   UPDATE users SET role='admin' WHERE email='lecturer@example.com';
   ```
   Replace the email with the lecturer's actual registered email, then sign in again.
9. Log in as admin to create categories and assignments, and inspect uploaded projects/assignment files.

## Main pages
- Public: `register.php`, `login.php`
- Student: `student/dashboard.php`, `student/submit.php`, `student/my_projects.php`, `student/edit_project.php`, `student/assignments.php`
- Admin: `admin/dashboard.php`, `admin/categories.php`, `admin/assignments.php`, `admin/submissions.php`
- AJAX: `api/projects.php` (live project search)
- File downloads: `download.php` (checks login and ownership for private assignment files)

## Database design
- `users`, `categories`, `projects` follow the portfolio ERD in Figure 11.
- `assignments` and `submissions` support the written *create assignment / many submissions per assignment* criteria.
- Student portfolio projects are separate from assignment submissions.
- Prepared statements, password hashing, protected routes, POST forms, CSRF tokens, input validation and upload checks are implemented.

## Git workflow (perform as a group, not fabricated history)
```bash
cd C:\laragon\www\portfolio_showcase
git init
git add .
git commit -m "Create database schema and reusable PHP layout"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
git push -u origin main
```
Have each member clone the repository, implement and test real work, commit their own changes with meaningful descriptions, and push. Capture actual commit history (not a manufactured screenshot) for the PDF report. **Do not commit real credentials or uploaded student documents.**

## Test checklist
- Register with malformed email / short password / duplicate email; verify errors.
- Login with wrong credentials; check session and redirect behavior.
- Visit admin page as student and student page as admin; verify access blocked.
- Admin create category and assignment; reject empty title and description.
- Student upload PDF/DOC/DOCX/TXT <5MB; reject other formats and oversized files.
- Student dashboard search by title, student, tech stack; no full-page refresh.
- Student edit/delete own project; confirm another student cannot edit it.
- Student submit an assignment and attempt duplicate; confirm it is rejected.
- Admin inspect joined submission lists and download files.
- Logout and try protected URLs again.

## Security & limitations
For coursework/local deployment. Before production: add HTTPS, stricter upload scanning, rate limits for login, environment-based secrets, pagination, and external hosting configuration. The `/uploads` folder must not execute scripts; included Apache rules help, but verify your server configuration.

## Screenshots for report
1. Register and login pages (Figures 1–2)
2. Student directory, live search with different keywords (Figures 3, 9–10)
3. New project submission, My Portfolio and edit/delete (Figures 4–5)
4. Admin dashboard, categories, submissions (Figures 6–8)
5. Admin create assignment and student assignment upload (written criteria)
6. phpMyAdmin database tables / relationship view (Figure 11)
7. GitHub real commit history (Figure 12)
8. Validation messages, blocked unauthorized routes and successful download

## Submission components
ZIP project folder, exported `.sql`, GitHub URL, README, PDF report with typed code and actual application screenshots. The ZIP includes a database schema; you can also export a fresh database from phpMyAdmin after testing.


## Included demonstration database
The file `database/assignment_system.sql` rebuilds the five application tables and inserts demonstration records. **Back up your current database first; importing it clears existing application data.** It includes downloadable sample TXT documents in `uploads/`. Demo logins: Afiq (student) `afiq@gmail.com` / `Afiq@12345`; Admin `admin@gmail.com` / `Admin@12345`. Change these temporary passwords before deployment.
