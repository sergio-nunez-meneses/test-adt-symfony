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

Class name of the entity to create or update (e.g FiercePizza):
> ClassName

created: src/Entity/ClassName.php
created: src/Repository/ClassNameRepository.php

Entity generated! Now let's add some fields!
You can always add more fields later manually or by re-running this command.

New property name (press <return> to stop adding fields):

New property name (press <return> to stop adding fields):
> name

Field type (enter ? to see all types) [string]:
> string

Field length [255]:
> 255

Can this field be null in the database (nullable) (yes/no) [no]:
> no

New property name (press <return> to stop adding fields):
> price

Field type (enter ? to see all types) [string]:
> integer

Can this field be null in the database (nullable) (yes/no) [no]:
> no

New property name (press <return> to stop adding fields):
>
(press enter again to finish)
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
