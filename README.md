# weldapp-api

## Use

To get started, make sure you have [Docker installed](https://docs.docker.com/) on your system and [Docker Compose](https://docs.docker.com/compose/install/), and then clone this repository.

1. Clone this project:

   ```sh
   git clone https://github.com/storyofsoft/laravel-vue-docker-boilerplate.git
   ```

2. Inside the root directory and Generate your own `.env` to docker compose with the next command:

   ```sh
   cp .env.example .env
   ```

3. Add following lines to /etc/hosts

   ```sh
   127.0.0.1 weldapp.localhost
   127.0.0.1 api.weldapp.localhost
   127.0.0.1 phpmyadmin.weldapp.localhost
   ```

4. Run the project whit the next commands:

   ```sh
   docker-compose up
   ```