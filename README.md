# 🛫 Welcome to Sky Hub 🌐
**Sky Hub** ✈️ is a comprehensive web platform for managing flights, reservations, and airplanes efficiently. 

**Users** can 🔐 authenticate, 🔎 browse available flights using an advanced filtering system, and seamlessly 📅 book, ❌ cancel, or 📂 review their reservations. 

**Administrators** have access to a detailed view of all 🛩️ airplanes, including their 🗂️ associated flights and the 👥 users who have booked each one.

In addition to its web interface, Sky Hub provides a 🚀 **powerful API** for managing flights, airplanes, and reservations programmatically. 

The API is secured with 🛡️ **Laravel Passport** for robust authentication and authorization, and it is fully documented using 📖 **Swagger**, making integration and development easier. 

Whether through the platform or the API, Sky Hub offers a seamless and efficient solution for flight management.

## 🛠️🚀 Tech Stack
- 🖥️ **Frameworks:** Laravel
- 🌐 **Server & Runtime:** XAMPP, Apache, Nodejs, Docker
- 📂 **Database:** MySQL
- 🛡️ **Authentication:** Laravel Passport (API), Session-based Authentication (Web)
- 📖 **API Documentation:** Swagger
- 🧪 **Testing:** PHPUnit
- 🔧 **Tools & Others:** Composer, Postman, JIRA

## 📊📁 DB Diagram
Below is a diagram of the database:
- **airplane - journey:** Many to many relationship. A journey can have many airplanes, and an airplane can have many journeys. This relationship is represented by **flight** pivot table.

- **users - flight:** Many to many relationship. A flight can have many users, and a user can have many flights. This relationship is represented by **booking** pivot table.

- **users - role_user:** One to many relationship. A role_user can have many users, but each user belongs to only one role_user.

- **journey - destination:** One to many relationship. A destination can have many journey, but each journey belongs to only one destination with two differents departure points, by **departure_id** (departure) and **arrival_id** (arrival).

![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742509688/imagen_2025-03-20_232807017_pduee4.png)

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
 
    In DB_CONNECTION will come mysqlite, change it to the bd you use (in this case MySQL)

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=skyhub
    DB_USERNAME=root
    DB_PASSWORD=
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
    L5_SWAGGER_CONST_HOST=http://127.0.0.1:8000
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

- Generate an App Key with this command 
```
php artisan key:generate 
```

- Execute migrations with seeders
```
php artisan migrate --seed
```

## ▶️⚡ Run

### ▶️⚡ Run Locally

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

## 🏃‍♂️🧪 Running Tests

To run test you should uncomment the following lines on the phpunit.xml file.

![image](https://res.cloudinary.com/dierpqujk/image/upload/v1733829455/imagen_2024-12-10_121742908_b3mfqm.png)


With the following command we run the tests and we will also generate a coverage report

```bash
  php artisan test --coverage-html=coverage-report
```

If everything is correct, everything should be OK.

![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742515295/imagen_2025-03-21_010134316_uhwgig.png)


A folder called coverage-report will also have been generated with **100%** coverage.
![image](https://res.cloudinary.com/dierpqujk/image/upload/v1742515465/imagen_2025-03-21_010425371_p0ktvn.png)

## 📡🌐 Sky Hub API
This API has interactive documentation generated with **Swagger**. To view it and try out the different available routes, follow these steps:

### 🛠 Requirements
The first thing to do is to generate the documentation with the following command
```
php artisan l5-swagger:generate
```

## ✍️🙍 Author
- **Antonio Guillén:**  [![GitHub](https://img.shields.io/badge/GitHub-Perfil-black?style=flat-square&logo=github)](https://github.com/AntonioGuillen123)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Perfil-blue?style=flat-square&logo=linkedin)](https://www.linkedin.com/in/antonio-guillen-garcia)
[![Correo](https://img.shields.io/badge/Email-Contacto-red?style=flat-square&logo=gmail)](mailto:antonioguillengarcia123@gmail.com)

