<div align="center">
    <img width="75" vspace="" hspace="25" src="https://qvieo.com/githubimg/mlogo_md.svg">
  <p>Simplifies Mensa<p>
</div>

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

### Installing all dependencies:

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
** Resolve any conflicts **
git checkout master
git merge development
```