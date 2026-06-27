# منصة إدارة المشاريع والتعاونيات (التحضير، الإنجاز، التبليغ)
==========================

## Overview & Project Purpose

منصة إدارة المشاريع والتعاونيات (التحضير، الإنجاز، التبليغ) هي منصة إلكترونية مصممة لتعزيز إدارة المشاريع والتعاونيات بشكل فعال. تتيح المنصة للمشاريع والتعاونيات إدارة المشاريع من مرحلة التحضير إلى الإنجاز، وتقديم تقارير تفصيلية عن التقدم.

### Project Purpose

* إدارة المشاريع والتعاونيات بشكل فعال
* تقديم تقارير تفصيلية عن التقدم
* تعزيز التعاون بين الأفراد والمشاريع

## Project Structure Mapping


.
├── docker-compose.yml
├── .env
├── app
│   ├── config
│   │   └── database.php
│   ├── controllers
│   │   └── ProjectController.php
│   ├── models
│   │   └── Project.php
│   ├── routes
│   │   └── web.php
│   └── views
│       └── project.blade.php
├── database
│   ├── migrations
│   │   └── 2022_01_01_000000_create_projects_table.php
│   └── seeds
│       └── ProjectSeeder.php
├── public
│   └── index.html
└── tests
    └── ProjectTest.php


## Step-by-Step Instructions on Running the Environment using Docker-compose up

1. **Install Docker and Docker Compose**:
   - Install Docker Desktop from the official Docker website: <https://www.docker.com/products/docker-desktop>
   - Install Docker Compose from the official Docker website: <https://docs.docker.com/compose/install/>

2. **Clone the Repository**:
   - Clone the repository using Git: `git clone https://github.com/your-username/project-name.git`

3. **Create a .env File**:
   - Create a new file named `.env` in the root directory of the project
   - Add the following environment variables to the file:
     
     DB_HOST=localhost
     DB_PORT=3306
     DB_DATABASE=project_name
     DB_USERNAME=root
     DB_PASSWORD=password
     

4. **Run the Docker Containers**:
   - Navigate to the root directory of the project
   - Run the following command to start the Docker containers: `docker-compose up -d`

5. **Access the Application**:
   - Open a web browser and navigate to `http://localhost:8000`

## Listing of Modules, Tables, and Roles

### Modules

* **Project Management**: إدارة المشاريع من مرحلة التحضير إلى الإنجاز
* **Collaboration**: تعزيز التعاون بين الأفراد والمشاريع

### Tables

* **projects**: إدارة المشاريع
* **tasks**: إدارة المهام
* **users**: إدارة المستخدمين

### Roles

* **admin**: إدارة المشاريع والتعاونيات بشكل كامل
* **project_manager**: إدارة المشاريع بشكل جزئي
* **team_member**: إدارة المهام بشكل جزئي

## Contact Developer Details

* **Name**: [Your Name]
* **Email**: [your-email@example.com](mailto:your-email@example.com)
* **Phone**: [Your Phone Number]
* **GitHub**: [Your GitHub Profile](https://github.com/your-username)

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
