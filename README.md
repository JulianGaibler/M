<div align="center">
    <img width="50" vspace="" hspace="25" src="https://qvieo.com/githubimg/mlogo.svg">
  <h1>M</h1>
  <p>Simplifies Mensa<p>
</div>

##Client

###Installing all dependencies:

```bash
npm install
```

###Running local Server:

Host is per default `0.0.0.0` and therefore public in your local network. If you don't want this behavior change the host in the webpack.config.js to `localhost`.

```bash
npm run dev
```

##Server

###Installing all dependencies:

```bash
composer install
```

###Running local Server:

Replace `localhost` with `0.0.0.0` if you want the server to be public in your local network.

```bash
php -S localhost:8000 -t public
```
