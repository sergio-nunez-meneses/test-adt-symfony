# Symfony cheat sheet

## Creating database

Edit database information in ```.env``` file:

```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:db_port/db_name?serverVersion=5.7"

# to use mariadb:
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:db_port/db_name?serverVersion=mariadb-10.5.8"

# to use sqlite:
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/app.db"

# to use postgresql:
# DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:db_port/db_name?serverVersion=11&charset=utf8"
```

Run the following line:

```
php bin/console doctrine:database:create
```

## Creating tables

Run the following line and follow the instructions:
```
php bin/console make:entity
```

Update database without dropping existing data:

```
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrations
```

## Create controller

```
php bin/console make:controller controller_name
```
