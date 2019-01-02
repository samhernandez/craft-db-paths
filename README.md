# Craft DB Paths Module

Skip to [the usage instructions](##usage) if you already know the story.

## Background

Craft relies by default on the `mysqldump` and `mysql` executables to backup and restore databases. The default backup command looks like this:

```
mysqldump […options…] {database} > {file}'
```

 The problem is that, while `mysqldump` might be available in the system, PHP running under a web request doesn’t know where to find it because it’s not in any directories specified in the `PATH` environmental variable. This is true when running locally with MAMP. It’s also true sometimes on EC2 instances when running MariaDB on RDS.

For this reason, Craft provides two configuration variables for you: [`backupCommand`](https://docs.craftcms.com/v3/config/config-settings.html#backupcommand) and [`restoreCommand`](https://docs.craftcms.com/v3/config/config-settings.html#restorecommand). You can write your own command with the full path to the `mysqldump` executable. For MAMP it might be something like this:

 ```
/Applications/MAMP/Library/bin/mysqldump […options…] {database} > {file}
 ```

The thing is, Craft’s default dump command is really nice. It excludes unnecessary session, cache, and asset data. You can see how it’s built for MySQL in the [Schema::defaultBackupCommand()](https://github.com/craftcms/cms/blob/master/src/db/mysql/Schema.php#L146-L190) method. What if you could just replace `mysqldump` with its full path and not have to roll your own command? That's what this module is for.

## Usage

### Installation

This is a module for Craft CMS, not a plugin available in Craft's Plugin Store. It is installed with composer on the command line.

Run the following command in the root of your project.

```bash
composer require samhernandez/craft-db-paths
```

Open `/config/app.php`, add the `CraftDbPaths` class as a module, and bootstrap it. Example:

```php
return [
    'modules' => [
        'craft-db-paths' => \samhernandez\craftdbpaths\CraftDbPaths::class,
    ],
    'bootstrap' => ['craft-db-paths'],
];
```

### Configuration

Add any of the following env vars to your `.env` file. Here is an example for MAMP on a Mac with MySQL.

```
PATH_TO_MYSQL=/Applications/MAMP/Library/bin/mysql
PATH_TO_MYSQLDUMP=/Applications/MAMP/Library/bin/mysqldump
```

If necessary, you can add paths for Postgres too.

```
PATH_TO_PSQL=/usr/local/bin/psql
PATH_TO_PGDUMP=/usr/local/bin/pg_dump
```

No configuration file is necesary, just env variables set in your `.env` or otherwise.
