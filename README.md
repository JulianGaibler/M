<div align="center">
    <img width="75" vspace="" hspace="25" src="https://qvieo.com/githubimg/mlogo_md.svg">
  <p>Simplifies Mensa<p>
</div>

## Client

### Installing all dependencies:

```bash
npm install
```

Also check out `/mclient/webpack.dev.js` and API_URL so it fits for you!

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
php -S localhost:8000 -t public
```
