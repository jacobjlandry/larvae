# TODO
List of todo items

## Project Startup Process
- composer global require laravel/installer
- edit .zshrc with path export `export PATH=~/.composer/vendor/bin:$PATH`
- laravel new project
- setup docker
-- copy docker-compose yaml
-- -- edit to have new site info
-- copy docker-compose folder
-- -- edit contents to have new site info
-- copy Dockerfile
-- copy crontab config (if needed)
-- copy supervisor conf (if needed)
- edit hosts file
- setup env
-- don't forget to add non-default vars like app_port and filesystem_driver (must have been dropped in the newest env? still required?)
- build docker
- verify deployment
- create migrations

