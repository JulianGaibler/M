<div align="center">
    <svg xmlns="http://www.w3.org/2000/svg" width="136" height="182" viewBox="0 0 136 182"><title>Artboard 1</title><path fill="#ff543c" d="M89.5,74.751,75.419,93.737l-11.177,1.53L47.4,74.751v50.374H19.533V21.009H38.672L70.061,60.665,98.845,21.009h18.527V125.125H89.5Z"/><path fill="#ff543c" d="M20.223,166.549H10.9V141.8h8.663a10.118,10.118,0,0,1,6.1,1.619q2.2,1.62,2.2,5.187,0,2.511-2.912,4.587a7.466,7.466,0,0,1,3.039,2.4,7.029,7.029,0,0,1-1.31,9.1A10.345,10.345,0,0,1,20.223,166.549Zm-1.165-21.33H14.982v6.916H18.4a7.641,7.641,0,0,0,3.785-.874,2.825,2.825,0,0,0,1.419-2.621,2.947,2.947,0,0,0-1.146-2.675A6.3,6.3,0,0,0,19.058,145.219Zm1.274,10.229H14.982v7.716h4.987a6.161,6.161,0,0,0,3.586-.928,3.173,3.173,0,0,0,1.329-2.785,3.669,3.669,0,0,0-1.22-2.984A5.034,5.034,0,0,0,20.332,155.447Z"/><path fill="#ff543c" d="M49.2,163.345l.619,3.2H33.327V141.8H47.669l.582,3.2H37.4v7.389H45.63l.619,3.2H37.4v7.752Z"/><path fill="#ff543c" d="M53.747,166.549V141.8h7.389a11.743,11.743,0,0,1,6.916,1.874,6.392,6.392,0,0,1,2.658,5.587,6.773,6.773,0,0,1-1.166,3.968,7.413,7.413,0,0,1-3.093,2.548L72.6,166.549H68.2l-5.387-9.755a14.18,14.18,0,0,1-2,.109H57.824v9.646Zm7.317-21.33h-3.24v8.335h3.167a7.047,7.047,0,0,0,3.932-.982,3.476,3.476,0,0,0,1.491-3.13,3.7,3.7,0,0,0-1.383-3.24A6.8,6.8,0,0,0,61.064,145.219Z"/><path fill="#ff543c" d="M80.246,163.345H91.275l.583,3.2H76.169V141.8h4.076Z"/><path fill="#ff543c" d="M99.5,141.8v24.752H95.425V141.8Z"/><path fill="#ff543c" d="M109.947,149v17.545h-4.076V141.8h3.858l12.158,17.436V141.8H126v24.752h-3.895Z"/></svg>
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