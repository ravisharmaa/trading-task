# Challenge By Trading

## Description
This was challenge task from trading.com.

## Dependencies
The following dependencies are required to successfully run this project:
- Docker
- Make (Optional)


## Setup Instructions
Assuming that docker & make is already available in the machine
1. For make Run `make install`
2. For docker run `docker compose up -d` or `docker-compose up -d`
3. Additionally, a docker-compose.override.yml can also be used to smoothen the development environemnt
```yaml
version: '3.4'

services:
  app:
    build:
      context: ./docker/php
      dockerfile: Dockerfile
      target: debug
      args:
        - UID=${UID:-1000}
        - GID=${GID:-1000}
    container_name: trading-point-app
    restart: unless-stopped
    tty: true
    working_dir: /var/www/
    depends_on:
      - composer
      - mysql
      - mailer
    environment:
      - PHP_IDE_CONFIG=serverName=api.app.docker
    volumes:
      - ./:/var/www
      - ./docker/php/xdebug.ini:/usr/local/etc/php/conf.d/ext-xdebug.ini

  mailer:
    image: maildev/maildev
    container_name: mailer
    command: maildev --smtp 25
    ports:
      - "1080:1080"

```
The compose file adds a mail server as well and also enabled debug mode for php. As the base docker-compose file comes up with Traefik proxy installed which should serve the applcation in `http://trading.localhost` domain after running the up commands.
## Libraries Used
1. Vue 3
2. Vite
3. Tailwind CSS
4. Vue MultiSelect
5. Vue Validate
6. Vue Datepicker
7. Apex Charts

## Solution Formulation
Steps I thought of when I was trying to work on this task.
1. Creating a Laravel 10 + Vue 3 app to address the requirements..
2. Adding proper docker-compose and docker file which address the easy browsing procedure.
3. Installing Vite + Vue + Tailwind
   - Installation of Frontend client Vue was acutally a cumbersome task but however I wanted to experiment with Vite and Vue3.
4. Storing the company symbols
    - As the company symbols were in a link which threw some json.I thought it would be nice to store them into the database which would help me afterwards while I search for the company symbol as there were a lot of them.
    - Because the company symbols fetching was a one time work I added a laravel command which can be run whenever we need to update the symbols. 
5. Showing the data in tabular form and Chart form

## Decisions Tradeoffs & Constraints
While working on the solution I made some conscious decisions which are:
1. Storing the company symbols:
   - Storing company symbols in database might be questionable but I wanted to reduce the number of http requests.
   - Searching on the json file is also not a problem but to do a wild card search while searching for symbols would help in giving the results faster.
2. Queue Driver as Database
    - I consciously chose database as the queue connection but at places it can be a good choice to use other mechanisms.

## Further Improvements
As developers, we need to find some balance but if I were to improve this I would have worked on these steps:
- Implement caching on the company symbols response.
- Implement proper queue mechanism( SQS, BEANKSTALKD).
- Implement a better chart and improve the overall UI.

