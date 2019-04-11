# Instalation
- `git clone https://github.com/RomainDW/Snowtricks.git`
- `composer install`
- configure the `.env` file with your database configuration : `DATABASE_URL=mysql://user:password@domain/snowtricks`
- `php bin/console d:d:c` (creation of the database)
- `php bin/console d:m:m` (make migrations)
- `php bin/console d:f:l` (load fixtures)
- `yarn install` (installing webpack encore)
- `yarn run encore dev` (compiled assets)

And now, the project is installed :)