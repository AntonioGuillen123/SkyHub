# 🛫 Welcome to Sky Hub 🌐
**Sky Hub** ✈️ is a comprehensive web platform for managing flights, reservations, and airplanes efficiently. 

**Users** can 🔐 authenticate, 🔎 browse available flights using an advanced filtering system, and seamlessly 📅 book, ❌ cancel, or 📂 review their reservations. 

**Administrators** have access to a detailed view of all 🛩️ airplanes, including their 🗂️ associated flights and the 👥 users who have booked each one.

In addition to its web interface, Sky Hub provides a 🚀 **powerful API** for managing flights, airplanes, and reservations programmatically. 

The API is secured with 🛡️ **Laravel Passport** for robust authentication and authorization, and it is fully documented using 📖 **Swagger**, making integration and development easier. 

Whether through the platform or the API, Sky Hub offers a seamless and efficient solution for flight management.

## 🛠️🚀 Tech Stack
- 🖥️ **Frameworks:** Laravel
- 🌐 **Server & Runtime:** Xampp, Apache, Nodejs, Docker
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

- Install Composer dependencies

```
composer install
```
- Install Nodejs dependencies

```
npm install
```
- Duplicate .env.example file and rename to .env
- In this new .env, change the variables you need, but it is very important to uncomment the database connection lines that are these:
 
In DB_CONNECTION will come mysqlite, change it to the bd you use (in this case MySQL)

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skyhub
DB_USERNAME=root
DB_PASSWORD=
```
 - Generate an App Key with this command 
```
php artisan key:generate 
```

- Execute migrations with seeders
```
php artisan migrate --seed
```

## ▶️💻 Run Locally
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
```
php artisan l5-swagger:generate
```

## ✍️🙍 Author
- **Antonio Guillén:**  [![GitHub](https://img.shields.io/badge/GitHub-Perfil-black?style=flat-square&logo=github)](https://github.com/AntonioGuillen123)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Perfil-blue?style=flat-square&logo=linkedin)](https://www.linkedin.com/in/antonio-guillen-garcia)
[![Correo](https://img.shields.io/badge/Email-Contacto-red?style=flat-square&logo=gmail)](mailto:antonioguillengarcia123@gmail.com)

