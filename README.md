# Internations Coding Challenge


### By: Victor Salas Padilha

## First steps
This project uses composer for library management for PHP.

To minimize problems, there is a Docker solution to run everything.
This requires [Docker Compose](https://docs.docker.com/compose) to be installed.

Once you have Docker Compose installed and set up, run this command to build the image.

```shell
docker-compose build
```

Then proceed to install the dependencies required by this project
```shell
docker-compose run app composer install
```

## Starting docker

---
To start the docker image with the web server, execute the following command:

```shell
docker-compose up -d
```

This will start the docker services and the API will be available on `http://localhost:8005`

## Inscomnia file

---
There's an Insomnia Collection file (`Insomnia_collection.yml`) available with examples to all API routes of the project. 

## Database Schema

---

Here's the database model used for this project
![alt text](https://drive.google.com/uc?id=1uhW7i6bFTtS2X5dZYE0j51xW2gbnCINo)
