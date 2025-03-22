
# 🛫 Welcome to Sky Hub 🌐
**Sky Hub** ✈️ is a comprehensive web platform for managing flights, reservations, and airplanes efficiently. 

**Users** can 🔐 authenticate (**Google** and **GitHub** included), 🔎 browse available flights using an advanced filtering system, and seamlessly 📅 book, ❌ cancel, or 📂 review their reservations. 

**Administrators** have access to a detailed view of all 🛩️ airplanes, including their 🗂️ associated flights and the 👥 users who have booked each one.

In addition to its web interface, Sky Hub provides a 🚀 **powerful API** for managing flights, airplanes, and reservations programmatically. 

The API is secured with 🛡️ **Laravel Passport** for robust authentication and authorization, and it is fully documented using 📖 **Swagger**, making integration and development easier. 

Whether through the platform or the API, Sky Hub offers a seamless and efficient solution for flight management.

## 👀🖥 Overview
The platform consists of the following views:

- **Home View**

  This page works as a gateway to the website

  ![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742583946/imagen_2025-03-21_200545090_xprend.png)

- **Flight View**

  This page works as a list of flights which can be booked or cancelled

  It has 3 types of filters:

    - **If active or not**
    - **If empty or not**
    - **If date has passed or not**

  ![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742584145/imagen_2025-03-21_200904355_ffe9wo.png)

- **Booking View**

  This page works as a list of the reservations that the authenticated user has, which can be cancelled

  ![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742584467/imagen_2025-03-21_201425335_ttyqrj.png)

- **Airplane View**

  This page functions as a list of airplanes with their assigned flights, with the users who have booked each flight, which can only be viewed by the authenticated admin

  ![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742584761/imagen_2025-03-21_201920125_tpqt3k.png)

- **Register View**

  This page works as a register to the website

  ![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742661031/imagen_2025-03-22_173028839_wyz6c3.png)

- **Login View**

  This page works as a login to the website

  ![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742661095/imagen_2025-03-22_173134574_ncm0fo.png)

- **Profile View**

  This page works as a user/admin profile edition. 
  If the user has registered via **Google** or **Github**, he will be given the option to create a password for his account.

  ![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742585218/imagen_2025-03-21_202656584_q68kiq.png)

- **Forgot Password View**

  This page works as a password recovery

  ![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742585380/imagen_2025-03-21_202938517_t7dqyi.png)

- **Reset Password View**

  This page works as a password reset

  ![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742585507/imagen_2025-03-21_203145736_lsdfiq.png)

- **Resend Verify Email View**

  This page works as a forwarding of the confirmation email.

  ![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742585927/imagen_2025-03-21_203825428_ck5hcg.png)

## 🛠️🚀 Tech Stack
- 🖥️ **Frameworks:** Laravel
- 🌐 **Server & Runtime:** XAMPP, Apache, Nodejs, Docker
- 📂 **Database:** MySQL
- 🛡️ **Authentication:** Laravel Passport (API), Session-based Authentication (Web), Laravel Socialite (Social Login)
- 📖 **API Documentation:** Swagger
- 🧪 **Testing:** PHPUnit
- 🔧 **Tools & Others:** Composer, Postman, JIRA

## 📊📁 DB Diagram
Below is a diagram of the database:
- **airplane - journey:** Many to many relationship. A journey can have many airplanes, and an airplane can have many journeys. This relationship is represented by **flight** pivot table.

- **users - flight:** Many to many relationship. A flight can have many users, and a user can have many flights. This relationship is represented by **booking** pivot table.

- **users - role_user:** One to many relationship. A role_user can have many users, but each user belongs to only one role_user.

- **users - auth_provider:** One to many relationship. A auth_provider belongs to a user, but each user have many auth_provider.

- **journey - destination:** One to many relationship. A destination can have many journey, but each journey belongs to only one destination with two differents departure points, by **departure_id** (departure) and **arrival_id** (arrival).

![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742661654/imagen_2025-03-22_174052714_erszht.png)

## 🔧⚙️ Installation

- Clone repository
  ```
  git clone https://github.com/AntonioGuillen123/SkyHub
  ```

- Install Composer dependencies (If you use XAMPP see [this](#run-locally) before do that )
  ```
  composer install
  ```

- Install Nodejs dependencies
  ```
  npm install
  ```

### 📝 .Env File
- Duplicate .env.example file and rename to .env
- In this new .env, change the variables you need
  - 📂 **Database Connection**:
 
    Here we will set variables for the database connection, in which we will configure the connection driver, the host, the port, the database name, the user and the password.
    
    In **DB_CONNECTION** will come mysqlite, change it to the bd you use (in this case **mysql**)

    ```
    DB_CONNECTION=YOUR_DB_CONNECTION
    DB_HOST=YOUR_DB_HOST
    DB_PORT=YOUR_DB_PORT
    DB_DATABASE=skyhub
    DB_USERNAME=YOUR_DB_USERNAME
    DB_PASSWORD=YOUR_DB_PASSWORD
    ```

  - 📧 **Mailer**:
 
    Here we will set variables for the email service, in which we will configure the host, encryption, email expiration, among others

    ```
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=YOUR_EMAIL
    MAIL_PASSWORD=YOUR_PASSWORD
    MAIL_FROM_NAME="${APP_NAME}"
    MAIL_FROM_ADDRESS=YOUR_EMAIL
    MAIL_HASH=sha256
    MAIL_EXPIRATION_MINUTES=30
    ```

  - 📖 **Swagger**:
 
    Here we will set variables for the swagger, in which we will configure the host, the title, if we want the configuration to reload itself with each change or if we want to have the dark mode always activated by default.

    ```
    L5_SWAGGER_CONST_HOST=YOUR_SWAGGER_HOST
    SWAGGER_API_TITLE="${APP_NAME} | API Documentation"
    L5_SWAGGER_GENERATE_ALWAYS=true 
    L5_SWAGGER_UI_DARK_MODE=true
    ```

  - 🛡️ **Passport**:
 
    Here we will set variables for API authentication such as the public and private RSA keys, the personal access client and id, and the name of the app along with the token expiration.

    - To generate RSA keys it is necessary to enter the following command (The --force parameter is used to regenerate them)

      ```
      php artisan passport:keys --force
      ```

      Once generated, two files will be created in the **/storage** folder, we copy and paste them in their respective variables of the **.env** file
    
    - To generate the data of the personal access client, it is necessary to enter the following command

      ```
      php artisan passport:client --personal
      ```
      
      Once generated, the data will be printed on the screen, you will only see them once, you must copy and paste them in their respective variables of the **.env** file **(Important to do this after running the migrations)**

    ```
    PASSPORT_PRIVATE_KEY=YOUR_PRIVATE_KEY
    PASSPORT_PUBLIC_KEY=YOUR_PUBLIC_KEY
    PASSPORT_PERSONAL_ACCESS_CLIENT_ID=YOUR_PERSONAL_ACCESS_CLIENT_ID
    PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=YOUR_PERSONAL_ACCESS_CLIENT_SECRET
    PASSPORT_PERSONAL_ACCESS_CLIENT_NAME=" - Personal Access Token | ${APP_NAME}"
    PASSPORT_PERSONAL_ACCESS_TOKEN_EXPIRATION=15
    ```
  
  - 🌐 **Socialite:**

    Here we set variables for authentication with services like **Github** or **Google**.

    **To use them it is necessary to create a client app in the respective service**

    - **GitHub**

      ```
      GITHUB_CLIENT_ID=YOUR_GITHUB_CLIENT_ID
      GITHUB_CLIENT_SECRET=YOUR_GITHUB_CLIENT_SECRET
      GITHUB_REDIRECT_URI=YOUR_GITHUB_CALLBACK_URI
      ```
  
    - **Google**

      ```
      GOOGLE_CLIENT_ID=YOUR_GOOGLE_CLIENT_ID
      GOOGLE_CLIENT_SECRET=YOUR_GOOGLE_CLIENT_SECRET
      GOOGLE_REDIRECT_URI=YOUR_GOOGLE_CALLBACK_URI
      ```

- Generate an App Key with this command 
```
php artisan key:generate 
```

- Execute migrations with seeders
```
php artisan migrate --seed
```

## ▶️⚡ Run
This project includes several ways to run the project. 
The following are the different ways to execute them

### 💻 Run Locally
Follow these steps to run the project locally.

  - If you use **XAMPP** to run it locally, you should know that passport uses an extension called **sodium** that must be enabled manually.
  
    To do this, we must go to the php.ini file and look for

    ```
    ;extension=sodium
    ```
    Just delete the ; save the file and restart xampp

  - How to run the Laravel server  
    ```
    php artisan serve
    ```

  - If you want to run all this in development environment run the following command  
    ```
    npm run dev
    ```

  - For production you should run the following command 
    ```
    npm run build
    ```

### 🐳 Run in Docker
Follow these steps to run the project inside a Docker container

  - We must change the **DB_HOST** variable in the **.env** file to the **mysql** database container

    ```
    DB_HOST=mysql
    ```

  - We will start the containers with **Docker Compose** (The **-d** parameter is to run the containers in the background and the **--build** parameter is **only necessary the first time** as it is used to build the containers correctly)
    ```
    docker compose -d --build
    ```

  - Once we have the services up, it is time to execute the migrations with their seeders, for this we will use the **web** container with the following command
    ```
    docker compose exec web php artisan migrate --seed
    ```

  - Once the migrations are done, it is time to create the personal access token inside the **web** container, to do this we execute the following command
    ```
    docker compose exec web php artisan passport:client --personal
    ```
  
    The result will be printed on the screen and we will have to substitute the respective environment variables with the generated data
  
  - To stop the services, we enter the following command
    ```
    docker compose down
    ```

## 🏃‍♂️🧪 Running Tests

To run test you should uncomment the following lines on the **phpunit.xml** file.

![image](https://res.cloudinary.com/dierpqujk/image/upload/v1733829455/imagen_2024-12-10_121742908_b3mfqm.png)


With the following command we run the tests and we will also generate a coverage report

```bash
  php artisan test --coverage-html=coverage-report
```

If everything is correct, everything should be OK.

![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742665229/imagen_2025-03-22_184027922_xyajmv.png)


A folder called coverage-report will also have been generated with **100%** coverage.
![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742664529/imagen_2025-03-22_182847353_poqymz.png)

## 📡🌐 Sky Hub API
This API has interactive documentation generated with **Swagger**. To view it and try out the different available routes, follow these steps:

- We must generate the documentation with the following command

  ```
  php artisan l5-swagger:generate
  ```

- Once the documentation has been generated, with the server started, we will go to the following path in the browser

  ```
  /api/documentation
  ```

## ⏳📝 Automated Tasks
This project includes **automated tasks** that run at scheduled intervals to handle background processes efficiently. 

One of the key features is the periodic check of a flight's date. If the flight date has passed, its status will automatically be updated to **"inactive."** 

Below are the different ways to execute these automated tasks:

### ⚙️ Running Tasks Locally
To test scheduled tasks locally, use Laravel's scheduler worker

```
php artisan schedule:work
```

This will keep running in the foreground

### 🖥 Running Tasks on Windows (Batch File)
For Windows users, a **batch file (.bat)** is provided in the **/tasks/windows/** directory. It automates the execution of scheduled tasks

**Setting Up Windows Task Scheduler:**
- **Open Task Scheduler:** Press `Win + R`, type `taskschd.msc`, and hit Enter

- **Create a New Task:** Click **"Create Basic Task"** and give it a meaningful name (e.g., **SkyHub Scheduler**)

- **Choose a Trigger:** Select **"Daily"**, **"Hourly"**, or another schedule based on your needs

- **Set the Action:** Choose **"Start a Program"** and browse to select the batch file

  ```
  C:\path-to-your-project\tasks\windows\execute_tasks.bat
  ```

- **Configure Additional Settings:**
  - Check **"Run whether user is logged on or not"**
  - Check **"Run with highest privileges"** if needed

- **Finish & Test:** Click **"Finish"**, then right-click the task and select **"Run"** to test it

### 🐳 Running Tasks in Docker
Scheduled tasks run **automatically** every **10 minutes** when containers are started using **Docker Compose**.


- The crontab configuration is located at

  ```
  C:\path-to-your-project\tasks\cron\crontab
  ```

- A **Dockerfile** is included to build the service and ensure the scheduler runs inside the container.

## ✍️🙍 Author
- **Antonio Guillén:**  [![GitHub](https://img.shields.io/badge/GitHub-Perfil-black?style=flat-square&logo=github)](https://github.com/AntonioGuillen123)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Perfil-blue?style=flat-square&logo=linkedin)](https://www.linkedin.com/in/antonio-guillen-garcia)
[![Correo](https://img.shields.io/badge/Email-Contacto-red?style=flat-square&logo=gmail)](mailto:antonioguillengarcia123@gmail.com)
