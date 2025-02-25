# Forum Project

A simple forum application built for a university project. This forum allows users to create, edit, and delete threads while maintaining user authentication.

## Installation

### **1. Prerequisites**
- A web server with PHP support (e.g., Apache with XAMPP or WAMP)
- MySQL or MariaDB for database management
- A web browser

### **2. Setup**
1. Clone this repository:
   ```sh
   git clone https://github.com/geoff-oco/forum-project.git
   cd forum-project
   ```

2. Import the database schema:
   - Open **phpMyAdmin** or your preferred database tool.
   - Create a new database.
   - Import `iwd_forum.sql`.

3. Configure database connection:
   - Open `db_connect.php`.
   - Update the database credentials.

4. Deploy the project:
   - Place the files in your server's root directory (e.g., `htdocs` for XAMPP).
   - Start your server.

## Usage

- **Login/Register** to create an account.
- **Create New Threads** to start discussions.
- **Edit/Delete Threads** if you have permission.
- **Reply to Threads** to join discussions.
- **Search for Threads** using keywords.
- **View User Profiles** to check participant details.
- **Moderate Logs** to track forum activity (if applicable).
- **Logout** to end the session.

## File Structure

- **Core Functionality**
  - `db_connect.php` → Handles database connections.
  - `login.php` → User authentication system.
  - `logout.php` → Ends user sessions.
  - `register.php / register_form.php` → Handles user registration.
  - `view_profile.php` → Displays user details.

- **Thread Management**
  - `list_threads.php` → Displays all threads in the forum.
  - `new_thread.php / new_thread_form.php` → Allows users to create new threads.
  - `edit_thread.php / edit_thread_form.php` → Allows users to modify posts.
  - `delete_thread.php` → Handles thread deletion.
  - `view_thread.php` → Displays a single thread and its replies.
  - `reply.php` → Enables users to respond to threads.
  - `search_threads.php` → Provides a search function for threads.

- **Admin & Moderation**
  - `change_access.php / change_access_level.php` → Manages user roles.
  - `view_logs.php` → Logs admin/moderation actions.

- **Styling**
  - `forum_stylesheet.css` → Controls forum UI styling.

## Contributing

If you'd like to contribute:
1. Fork the repository.
2. Create a new branch.
3. Make your changes and submit a pull request.

## Contact

For any questions or issues, reach out via GitHub: [geoff-oco](https://github.com/geoff-oco).