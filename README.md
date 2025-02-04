# 🛫 Welcome to Sky Hub 🌐
Sky Hub is a platform designed to efficiently manage flights and airplanes.

The system offers a powerful API that allows operators to oversee flights, track airplane availability, and ensure seamless coordination of journeys.

With this platform, airlines and administrators can streamline flight scheduling, enhance operational efficiency, and reduce logistical challenges in air travel.

## 🛠️🚀 Tech Stack
- **Frameworks:** Laravel
- **Server:** Xampp, Apache, Nodejs
- **Database:** Mysql
- **Others:** Composer, Postman, JIRA

## 📊📁 DB Diagram
Below is a diagram of the database:
- **airplane - journey:** Many to many relationship. A journey can have many airplanes, and airplane can have many journeys. This relationship is represented by flight pivot table.

![image](https://res.cloudinary.com/dierpqujk/image/upload/v1738703842/imagen_2025-02-04_221723922_acc9a4.png)

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

![image](https://res.cloudinary.com/dierpqujk/image/upload/v1738703578/imagen_2025-02-04_221259065_km78tn.png)


A folder called coverage-report will also have been generated with **87.12%** coverage.
![image](https://res.cloudinary.com/dierpqujk/image/upload/v1738702394/imagen_2025-02-04_215315877_x46iff.png)

## 📡🌐 Sky Hub API
This API allows you to manage airplane and flight and provides CRUD (Create, Read, Update, Delete) operations for them.

### Airplane
#### 1 Get all airplane entries

```http
GET /api/airplane

```
### 🔹Request

#### Header:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`      | `string` | Must be **application/json**    |

### 🔹Response

- **Status Code:** 200
- **Content Type:** application/json

#

#### 2 Get an airplane by ID

```http
GET /api/airplane/{id}

```
### 🔹Request

#### Path Parameters:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `integer` | **Required**. Airplane Id     |

#### Header:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`      | `string` | Must be **application/json**    |

### 🔹Response

- **Status Code:** 200, 404
- **Content Type:** application/json

#

#### 3 Create a new airplane

```http
POST /api/airplane

```
### 🔹Request

#### Header:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`      | `string` | Must be **application/json**    |

#### Body: 

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name`    | `string` | **Required.** **Max: 255.** Airplane name    |
| `maximum_places`    | `integer` | **Required.** **Min: 0.** Airplane maximum places    |

### 🔹Response

- **Status Code:** 201, 422
- **Content Type:** application/json

#

#### 4 Update an existing airplane by ID

```http
PUT /api/airplane/{id}

```
### 🔹Request

#### Path Parameters:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `integer` | **Required**. Airplane Id     |

#### Header:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`      | `string` | Must be **application/json**    |

#### Body: 

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name`    | `string` | **Max: 255.** Airplane name    |
| `maximum_places`    | `integer` | **Min: 0.** Airplane maximum places    |

### 🔹Response

- **Status Code:** 200, 404, 422
- **Content Type:** application/json

#

#### 5 Delete an airplane by ID

```http
DELETE /api/airplane/{id}

```
### 🔹Request

#### Path Parameters:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `integer` | **Required**. Airplane Id     |

#### Header:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`      | `string` | Must be **application/json**    |

### 🔹Response

- **Status Code:** 204, 404
- **Content Type:** No-Content, application/json

#

### Flight
#### 1 Get all flight entries

```http
GET /api/flight

```
### 🔹Request

#### Header:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`      | `string` | Must be **application/json**    |

### 🔹Response

- **Status Code:** 200
- **Content Type:** application/json

#

#### 2 Get a flight by ID

```http
GET /api/flight/{id}

```
### 🔹Request

#### Path Parameters:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `integer` | **Required**. Flight Id     |

#### Header:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`      | `string` | Must be **application/json**    |

### 🔹Response

- **Status Code:** 200, 404
- **Content Type:** application/json

#

#### 3 Create a new flight

```http
POST /api/flight

```
### 🔹Request

#### Header:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`      | `string` | Must be **application/json**    |

#### Body: 

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `airplane_id`    | `integer` | **Required.** **Min: 0.** Airplane Id    |
| `journey_id`    | `integer` | **Required.** **Min: 0.** Journey Id    |
| `state`    | `boolean` | Flight state    |
| `remaining_places`    | `integer` | **Min: 0.** Flight state    |
| `flight_date`    | `date` | **date_format: Y-m-d H:i.** Flight date    |

### 🔹Response

- **Status Code:** 201, 404, 422
- **Content Type:** application/json

#

#### 4 Update an existing flight by ID

```http
PUT /api/flight/{id}

```
### 🔹Request

#### Path Parameters:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `integer` | **Required**. Flight Id     |

#### Header:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`      | `string` | Must be **application/json**    |

#### Body: 

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `airplane_id`    | `integer` | **Min: 0.** Airplane Id    |
| `journey_id`    | `integer` | **Min: 0.** Journey Id    |
| `state`    | `boolean` | Flight state    |
| `remaining_places`    | `integer` | **Min: 0.** Flight state    |
| `flight_date`    | `date` | **date_format: Y-m-d H:i.** Flight date    |

### 🔹Response

- **Status Code:** 200, 404, 422
- **Content Type:** application/json

#

#### 5 Delete a flight by ID

```http
DELETE /api/flight/{id}

```
### 🔹Request

#### Path Parameters:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `integer` | **Required**. Flight Id     |

#### Header:

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`      | `string` | Must be **application/json**    |

### 🔹Response

- **Status Code:** 204, 404
- **Content Type:** No-Content, application/json

## ✍️🙍 Author
- **Antonio Guillén:**  [![GitHub](https://img.shields.io/badge/GitHub-Perfil-black?style=flat-square&logo=github)](https://github.com/AntonioGuillen123)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Perfil-blue?style=flat-square&logo=linkedin)](https://www.linkedin.com/in/antonio-guillen-garcia)
[![Correo](https://img.shields.io/badge/Email-Contacto-red?style=flat-square&logo=gmail)](mailto:antonioguillengarcia123@gmail.com)

