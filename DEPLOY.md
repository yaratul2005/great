# Deployment Guide for great10.xyz

Follow these steps to deploy your application to your live server.

## 1. File Upload
1.  Connect to your hosting via FTP or File Manager.
2.  Upload the entire contents of `c:\xampp\htdocs\great\` to your server's public folder (usually `public_html`).
    - *Tip:* If you want the site to be at `great10.xyz` directly, upload contents to `public_html`.
    - *Tip:* If you want `great10.xyz/great`, keep them in a `great` folder.
3.  **Important:** Ensure the `.htaccess` file in `public/` is uploaded.

## 2. Database Setup
1.  Log in to your hosting Control Panel (cPanel, etc.).
2.  Go to **MySQL Databases** and create a new database.
3.  Create a new user and password, and **add the user to the database** with ALL PRIVILEGES.
4.  Go to **phpMyAdmin**, select your new database, and click **Import**.
5.  Select the `database_schema.sql` file included in the root folder and click **Go**.

## 3. Configuration
1.  Open `app/Config/Database.php` on the server (Edit mode).
2.  Update the credentials with your NEW live database details:
    ```php
    private $host = 'localhost'; // Usually localhost
    private $db_name = 'your_live_db_name';
    private $username = 'your_live_db_user';
    private $password = 'your_live_db_password';
    ```
3.  Save the file.

## 4. Permissions
Ensure the following folders are **writable** (Permissions 755 or 777 depending on hosting):
- `public/uploads/` (and all subfolders)

## 5. Security (Critical)
To prevent people from accessing your code directly:
1.  Ensure your `app` folder is protected. Since your `index.php` is in `public/`, strictly speaking, your document root should ideally point to `public_html/public`.
2.  **Recommended:** If you cannot change the Document Root, verify that navigating to `great10.xyz/app/Config/Database.php` gives a "Forbidden" (403) or blank page, NOT the code.
    - You can add a `.htaccess` file inside `app/` with this content:
      ```apache
      Deny from all
      ```

## 6. Testing
1.  Visit your site at `great10.xyz`.
2.  Login with **admin@example.com** / **admin123**.
3.  Go to Admin Dashboard and check settings.
4.  Test uploading a product and downloading it.

## Troubleshooting
- **404 Errors:** Ensure `public/.htaccess` exists and `ModRewrite` is enabled.
- **Database Error:** Check `app/Config/Database.php` credentials.
- **Images not loading:** Check base URL in `app/Config/App.php` (it tries to auto-detect, but you might need to hardcode it if it fails: `return "https://great10.xyz";`).
