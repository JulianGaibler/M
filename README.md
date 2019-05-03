![M](http://qvieo.com/githubimg/m-banner.png)

## Notice

This project hasn't been updated in a very long time and I'm currently trying to get this huge pile of outdated dependencies in order and clean up other mistakes I made then.

## Client

### Installing all dependencies:

```bash
npm install
```

Also check out API_URL in `/mclient/webpack.dev.js` so it for your dev-environment.

If you don't need a local API-Server, just put the production-link there.

### Running local Server:

Host is per default `0.0.0.0` and therefore public in your local network. If you don't want this behavior change the host in the webpack.config.js to `localhost`.

```bash
npm run dev
```

## Server

Long term goal is to move away from PHP

### Prerequisites

You will need:
1. PHP 71
2. MongoDB driver for PHP
3. npm
4. Composer

```bash
	brew install php71
	brew install php71-mongodb
	echo 'extension="/usr/local/opt/php71-mongodb/mongodb.so"' >> /private/etc/php.ini
	brew install composer
```

Don't forget to run
```bash
	composer install
```

### Running local Server:

Replace `localhost` with `0.0.0.0` if you want the server to be public in your local network.

```bash
php -S 0.0.0.0:8000 -t public
```

## Merging into Production
```
git merge master
"Resolve any conflicts"
git checkout master
git merge development
```


## Commits

Using [gitmoji](https://gitmoji.carloscuesta.me/) for all new commits
